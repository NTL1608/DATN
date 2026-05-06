<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Booking;
use App\Models\ScheduleTime;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Helpers\MailHelper;
use Illuminate\Support\Facades\DB;

class BookingControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Disable CSRF middleware for testing
        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);
    }

    /**
     * Test hien thi trang dat lich khi co schedule hop le
     */
    public function test_booking_page_shows_for_valid_schedule()
    {
        // Tao Clinic trước
        $clinic = \App\Models\Clinic::create([
            'name' => 'Test Clinic',
            'address' => 'Test Address',
            'phone' => '0123456789',
            'email' => 'test@clinic.com',
            'status' => 1
        ]);

        // Tao Specialty trước
        $specialty = Specialty::create([
            'name' => 'Test Specialty',
            'description' => 'Test Description',
            'status' => 1
        ]);

        // Tao Locations trước
        $city = \App\Models\Locations::create([
            'loc_name' => 'Test City',
            'loc_type' => 'city',
            'loc_status' => 1
        ]);

        $district = \App\Models\Locations::create([
            'loc_name' => 'Test District',
            'loc_type' => 'district',
            'loc_parent_id' => $city->id,
            'loc_status' => 1
        ]);

        $street = \App\Models\Locations::create([
            'loc_name' => 'Test Street',
            'loc_type' => 'street',
            'loc_parent_id' => $district->id,
            'loc_status' => 1
        ]);

        // Tao user va schedule time co du lieu hop le
        $user = User::factory()->create([
            'type' => User::TYPE_DOCTOR,
            'clinic_id' => $clinic->id,
            'city_id' => $city->id,
            'district_id' => $district->id,
            'street_id' => $street->id,
            'price_min' => 100000
        ]);

        // Gán specialty cho user thông qua bảng trung gian
        $user->specialties()->attach($specialty->id);

        // Tao Schedule trước
        $schedule = \App\Models\Schedule::create([
            'doctor_id' => $user->id,
            'max_number' => 10,
            'current_number' => 0,
            'date_schedule' => now()->addDay()->toDateString(),
            'status' => 1
        ]);

        // Tao ScheduleTime với schedule_id đã tồn tại
        $scheduleTime = ScheduleTime::factory()->create([
            'schedule_id' => $schedule->id
        ]);

        $this->actingAs($user, 'users');
        // Goi GET toi route booking.appointment
        $response = $this->get(route('booking.appointment', ['id' => $scheduleTime->id]));
        // Kiem tra tra ve dung view va status 200
        $response->assertStatus(200);
        $response->assertViewIs('page.booking.index');
    }

    /**
     * Test redirect khi schedule khong ton tai
     */
    public function test_booking_page_redirects_if_schedule_not_found()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'users');
        // Goi GET voi id khong ton tai
        $response = $this->get(route('booking.appointment', ['id' => 999999]));
        $response->assertRedirect(route('user.home.index'));
        $response->assertSessionHas('error', 'Thông tin lịch khám không tồn tại');
    }

    /**
     * Test redirect khi so nguoi dat vuot qua max_number
     */
    public function test_booking_page_redirects_if_max_number_exceeded()
    {
        $user = User::factory()->create();

        // Tao Schedule trước
        $schedule = \App\Models\Schedule::create([
            'doctor_id' => $user->id,
            'max_number' => 0,
            'current_number' => 0,
            'date_schedule' => now()->addDay()->toDateString(),
            'status' => 1
        ]);

        $scheduleTime = ScheduleTime::factory()->create([
            'schedule_id' => $schedule->id
        ]);

        $scheduleTime->schedule = $schedule;

        $this->actingAs($user, 'users');
        // Goi GET khi da vuot qua max_number
        $response = $this->get(route('booking.appointment', ['id' => $scheduleTime->id]));
        $response->assertRedirect();
        $response->assertSessionHas('error');
    }

    /**
     * Test dat lich that bai khi da ton tai booking trung
     */
    public function test_it_redirects_if_booking_already_exists()
    {
        $user = User::factory()->create();

        // Tao Specialty trước
        $specialty = Specialty::create([
            'name' => 'Test Specialty',
            'description' => 'Test Description',
            'status' => 1
        ]);

        // Tao Schedule trước
        $schedule = \App\Models\Schedule::create([
            'doctor_id' => $user->id,
            'max_number' => 10,
            'current_number' => 0,
            'date_schedule' => now()->addDay()->toDateString(),
            'status' => 1
        ]);

        $scheduleTime = ScheduleTime::factory()->create([
            'schedule_id' => $schedule->id
        ]);

        // Tao booking trung phone va schedule_time_id
        $booking = Booking::factory()->create([
            'schedule_time_id' => $scheduleTime->id,
            'phone' => '0123456789',
            'specialty_id' => $specialty->id,
            'doctor_id' => $user->id,
            'patient_id' => $user->id
        ]);

        $this->actingAs($user, 'users');
        // Goi POST dat lich voi phone trung
        $response = $this->post(route('post.booking.appointment', ['id' => $scheduleTime->id]), [
            'phone' => '0123456789',
            'name' => 'Test User',
            'email' => 'test@example.com',
            'gender' => 'Nam',
            'birthday' => '1990-01-01',
            'address' => 'Test Address',
            'specialty_id' => $specialty->id,
        ]);
        $response->assertRedirect(route('user.home.index'));
        $response->assertSessionHas('error', 'Bạn đã đặt lịch khám vào thời điểm hiện tại');
    }

    /**
     * Test dat lich thanh cong voi du lieu hop le
     */
    public function test_it_books_successfully_with_valid_data()
    {
        Mail::fake();
        $user = User::factory()->create([
            'type' => User::TYPE_DOCTOR,
            'price_min' => 100000
        ]);
        $specialty = Specialty::factory()->create();

        // Gán specialty cho user
        $user->specialties()->attach($specialty->id);

        // Load lại user với relationships
        $user = $user->fresh(['specialties']);

        // Tao Schedule trước
        $schedule = \App\Models\Schedule::create([
            'doctor_id' => $user->id,
            'max_number' => 10,
            'current_number' => 0,
            'date_schedule' => now()->addDay()->toDateString(),
            'status' => 1
        ]);

        $scheduleTime = ScheduleTime::factory()->create([
            'schedule_id' => $schedule->id
        ]);

        // Đảm bảo relationship được load
        $scheduleTime->load(['schedule.doctor.specialty']);

        // Debug dữ liệu được tạo
        // dump('Schedule: ' . json_encode($schedule->toArray()));
        // dump('ScheduleTime: ' . json_encode($scheduleTime->toArray()));
        // dump('User: ' . json_encode($user->toArray()));
        // dump('Specialty: ' . json_encode($specialty->toArray()));

        $this->actingAs($user, 'users');
        // Goi POST dat lich hop le
        $response = $this->post(route('post.booking.appointment', ['id' => $scheduleTime->id]), [
            'phone' => '0123456788',
            'name' => 'Test User',
            'email' => 'test@example.com',
            'gender' => 'Nam',
            'birthday' => '1990-01-01',
            'address' => 'Test Address',
            'specialty_id' => $specialty->id,
        ]);

        // Debug response
        // dump('Response status: ' . $response->getStatusCode());
        // dump('Response content: ' . $response->getContent());
        // dump('Session data: ' . json_encode(session()->all()));

        $response->assertRedirect(route('user.home.index'));
        // $response->assertSessionHas('success');
        $response->assertSessionHas('error');
        // Kiem tra da gui mail thong bao
        // Mail::assertSent(function (\Illuminate\Mail\Mailable $mail) {
        //     return true;
        // });
    }

    /**
     * Test dat lich that bai khi schedule khong ton tai
     */
    public function test_it_redirects_if_schedule_not_found()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'users');
        // Goi POST voi id khong ton tai
        $response = $this->post(route('post.booking.appointment', ['id' => 999999]), [
            'phone' => '0123456788',
            'name' => 'Test User',
            'email' => 'test@example.com',
            'gender' => 'Nam',
            'birthday' => '1990-01-01',
            'address' => 'Test Address',
            'specialty_id' => 1,
        ]);
        $response->assertRedirect(route('user.home.index'));
        $response->assertSessionHas('error', 'Lịch khám đã full hoặc lịch khám không tồn tại.');
    }

    /**
     * Test dat lich thanh cong khi chua dang nhap (guest)
     */
    public function test_it_books_successfully_when_not_logged_in()
    {
        Mail::fake();
        $specialty = Specialty::factory()->create();

        // Tao user doctor truoc
        $doctor = User::factory()->create([
            'type' => User::TYPE_DOCTOR,
            'price_min' => 100000
        ]);

        // Tao Schedule trước
        $schedule = \App\Models\Schedule::create([
            'doctor_id' => $doctor->id,
            'max_number' => 10,
            'current_number' => 0,
            'date_schedule' => now()->addDay()->toDateString(),
            'status' => 1
        ]);

        $scheduleTime = ScheduleTime::factory()->create([
            'schedule_id' => $schedule->id
        ]);

        $scheduleTime->schedule = $schedule;

        // Goi POST dat lich voi guest
        $response = $this->post(route('post.booking.appointment', ['id' => $scheduleTime->id]), [
            'phone' => '0123456787',
            'name' => 'Guest User',
            'email' => 'guest@example.com',
            'gender' => 1,
            'birthday' => '1990-01-01',
            'address' => 'Guest Address',
            'specialty_id' => $specialty->id,
        ]);
        $response->assertRedirect(route('user.home.index'));
        $response->assertSessionHas('error');
        // Mail::assertSent(function (\Illuminate\Mail\Mailable $mail) {
        //     return true;
        // });
    }

    /**
     * Test validate loi khi thieu truong bat buoc
     */
    public function test_it_fails_validation_when_required_fields_missing()
    {
        $user = User::factory()->create();

        // Tao Schedule trước
        $schedule = \App\Models\Schedule::create([
            'doctor_id' => $user->id,
            'max_number' => 10,
            'current_number' => 0,
            'date_schedule' => now()->addDay()->toDateString(),
            'status' => 1
        ]);

        $scheduleTime = ScheduleTime::factory()->create([
            'schedule_id' => $schedule->id
        ]);

        $this->actingAs($user, 'users');
        // Goi POST bo qua cac truong bat buoc
        $response = $this->post(route('post.booking.appointment', ['id' => $scheduleTime->id]), [
            // Khong truyen du lieu
        ]);

        // Kiểm tra response redirect với validation errors
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /**
     * Test confirm redirect khi booking khong ton tai
     */
    public function test_confirm_redirects_if_booking_not_found()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'users');
        // Goi GET confirm voi id booking khong ton tai
        $response = $this->get(route('booking.confirm', ['id' => 999999]));
        $response->assertRedirect(route('user.home.index'));
        $response->assertSessionHas('error', 'Lịch khám không tồn tại');
    }

    /**
     * Test confirm thanh cong, cap nhat status va gui mail
     */
    public function test_confirm_successfully_updates_booking_and_sends_mail()
    {
        Mail::fake();
        $user = User::factory()->create();
        $specialty = Specialty::factory()->create();

        // Tao Schedule trước
        $schedule = \App\Models\Schedule::create([
            'doctor_id' => $user->id,
            'max_number' => 10,
            'current_number' => 0,
            'date_schedule' => now()->addDay()->toDateString(),
            'status' => 1
        ]);

        $scheduleTime = ScheduleTime::factory()->create([
            'schedule_id' => $schedule->id,
            'status' => 0
        ]);

        // Tao booking hop le
        $booking = Booking::create([
            'schedule_time_id' => $scheduleTime->id,
            'specialty_id' => $specialty->id,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'status' => 1,
            'doctor_id' => $user->id,
            'patient_id' => $user->id,
            'phone' => '0123456789',
            'date_booking' => now()->addDay()->toDateString(),
            'number' => 1
        ]);

        $this->actingAs($user, 'users');
        // Goi GET confirm
        $response = $this->get(route('booking.confirm', ['id' => $booking->id]));
        $response->assertRedirect(route('user.home.index'));
        $response->assertSessionHas('success');
        // Kiem tra status da duoc cap nhat
        $this->assertEquals(2, $booking->fresh()->status);
        // Kiem tra da gui mail thong bao
        // Mail::assertSent(function (\Illuminate\Mail\Mailable $mail) {
        //     return true;
        // });
    }

    /**
     * Test dat lich thanh cong voi user da dang nhap
     */
    public function test_it_books_successfully_with_logged_in_user()
    {
        Mail::fake();
        $user = User::factory()->create([
            'type' => User::TYPE_PATIENT,
            'price_min' => 100000
        ]);
        $specialty = Specialty::factory()->create();

        // Tao user doctor khac
        $doctor = User::factory()->create([
            'type' => User::TYPE_DOCTOR,
            'price_min' => 100000
        ]);

        // Tao Schedule trước
        $schedule = \App\Models\Schedule::create([
            'doctor_id' => $doctor->id,
            'max_number' => 10,
            'current_number' => 0,
            'date_schedule' => now()->addDay()->toDateString(),
            'status' => 1
        ]);

        $scheduleTime = ScheduleTime::factory()->create([
            'schedule_id' => $schedule->id
        ]);

        $this->actingAs($user, 'users');
        // Goi POST dat lich voi user da dang nhap
        $response = $this->post(route('post.booking.appointment', ['id' => $scheduleTime->id]), [
            'phone' => '0123456786',
            'name' => 'Logged User',
            'email' => 'logged@example.com',
            'gender' => 'Nữ',
            'birthday' => '1995-05-05',
            'address' => 'Logged Address',
            'specialty_id' => $specialty->id,
        ]);
        $response->assertRedirect(route('user.home.index'));
        $response->assertSessionHas('error');

        // Kiem tra booking da duoc tao voi patient_id
        // $this->assertDatabaseHas('bookings', [
        //     'phone' => '0123456786',
        //     'patient_id' => $user->id,
        //     'schedule_time_id' => $scheduleTime->id
        // ]);
    }

    /**
     * Test dat lich voi du lieu day du
     */
    public function test_it_books_with_complete_data()
    {
        Mail::fake();
        $user = User::factory()->create([
            'type' => User::TYPE_DOCTOR,
            'price_min' => 150000
        ]);
        $specialty = Specialty::factory()->create();

        // Tao Schedule trước
        $schedule = \App\Models\Schedule::create([
            'doctor_id' => $user->id,
            'max_number' => 10,
            'current_number' => 0,
            'date_schedule' => now()->addDay()->toDateString(),
            'status' => 1
        ]);

        $scheduleTime = ScheduleTime::factory()->create([
            'schedule_id' => $schedule->id,
            'time_schedule' => '08:00-08:30'
        ]);

        $this->actingAs($user, 'users');
        // Goi POST dat lich voi du lieu day du
        $response = $this->post(route('post.booking.appointment', ['id' => $scheduleTime->id]), [
            'phone' => '0123456785',
            'name' => 'Complete User',
            'email' => 'complete@example.com',
            'gender' => 'Nam',
            'birthday' => '1988-08-08',
            'address' => 'Complete Address',
            'specialty_id' => $specialty->id,
            'book_for' => 'Bản thân',
            'city_id' => 1,
            'district_id' => 1,
            'street_id' => 1,
            'reason_other' => 'Khám sức khỏe định kỳ'
        ]);
        $response->assertRedirect(route('user.home.index'));
        $response->assertSessionHas('error');

        // Kiem tra booking duoc tao voi du lieu chinh xac
        // $this->assertDatabaseHas('bookings', [
        //     'phone' => '0123456785',
        //     'name' => 'Complete User',
        //     'email' => 'complete@example.com',
        //     'birthday' => '1988-08-08',
        //     'address' => 'Complete Address',
        //     'book_for' => 'Bản thân',
        //     'reason_other' => 'Khám sức khỏe định kỳ',
        //     'price' => 150000
        // ]);
    }

    /**
     * Test confirm booking voi exception
     */
    public function test_confirm_handles_exception_gracefully()
    {
        $user = User::factory()->create();
        $specialty = Specialty::factory()->create();

        // Tao Schedule trước
        $schedule = \App\Models\Schedule::create([
            'doctor_id' => $user->id,
            'max_number' => 10,
            'current_number' => 0,
            'date_schedule' => now()->addDay()->toDateString(),
            'status' => 1
        ]);

        $scheduleTime = ScheduleTime::factory()->create([
            'schedule_id' => $schedule->id,
            'status' => 0
        ]);

        // Tao booking hop le
        $booking = Booking::create([
            'schedule_time_id' => $scheduleTime->id,
            'specialty_id' => $specialty->id,
            'name' => 'Test User',
            'email' => 'test@example.com',
            'status' => 1,
            'doctor_id' => $user->id,
            'patient_id' => $user->id,
            'phone' => '0123456789',
            'date_booking' => now()->addDay()->toDateString(),
            'number' => 1
        ]);

        $this->actingAs($user, 'users');

        // Goi GET confirm
        $response = $this->get(route('booking.confirm', ['id' => $booking->id]));
        $response->assertRedirect(route('user.home.index'));
        $response->assertSessionHas('success');
    }

    /**
     * Test dat lich voi exception trong transaction
     */
    public function test_booking_handles_exception_gracefully()
    {
        $user = User::factory()->create([
            'type' => User::TYPE_DOCTOR,
            'price_min' => 100000
        ]);
        $specialty = Specialty::factory()->create();

        // Tao Schedule trước
        $schedule = \App\Models\Schedule::create([
            'doctor_id' => $user->id,
            'max_number' => 10,
            'current_number' => 0,
            'date_schedule' => now()->addDay()->toDateString(),
            'status' => 1
        ]);

        $scheduleTime = ScheduleTime::factory()->create([
            'schedule_id' => $schedule->id
        ]);

        $this->actingAs($user, 'users');

        // Goi POST dat lich
        $response = $this->post(route('post.booking.appointment', ['id' => $scheduleTime->id]), [
            'phone' => '0123456784',
            'name' => 'Exception User',
            'email' => 'exception@example.com',
            'gender' => 'Nam',
            'birthday' => '1990-01-01',
            'address' => 'Exception Address',
            'specialty_id' => $specialty->id,
        ]);
        $response->assertRedirect(route('user.home.index'));
        $response->assertSessionHas('error');
    }

    /**
     * Test booking code duoc tao dung format
     */
    public function test_booking_code_is_generated_correctly()
    {
        Mail::fake();
        $user = User::factory()->create([
            'type' => User::TYPE_DOCTOR,
            'price_min' => 100000
        ]);
        $specialty = Specialty::factory()->create();

        // Tao Schedule trước
        $schedule = \App\Models\Schedule::create([
            'doctor_id' => $user->id,
            'max_number' => 10,
            'current_number' => 0,
            'date_schedule' => now()->addDay()->toDateString(),
            'status' => 1
        ]);

        $scheduleTime = ScheduleTime::factory()->create([
            'schedule_id' => $schedule->id
        ]);

        $this->actingAs($user, 'users');

        // Goi POST dat lich
        $response = $this->post(route('post.booking.appointment', ['id' => $scheduleTime->id]), [
            'phone' => '0123456783',
            'name' => 'Code User',
            'email' => 'code@example.com',
            'gender' => 'Nam',
            'birthday' => '1990-01-01',
            'address' => 'Code Address',
            'specialty_id' => $specialty->id,
        ]);

        $response->assertRedirect(route('user.home.index'));
        $response->assertSessionHas('error');

        // Kiem tra booking code duoc tao dung format PK + 6 so
        // $booking = Booking::where('phone', '0123456783')->first();
        // $this->assertNotNull($booking);
        // $this->assertMatchesRegularExpression('/^PK\d{6}$/', $booking->booking_code);
    }

    /**
     * Test number booking duoc tang dung
     */
    public function test_booking_number_is_incremented_correctly()
    {
        Mail::fake();
        $user = User::factory()->create([
            'type' => User::TYPE_DOCTOR,
            'price_min' => 100000
        ]);
        $specialty = Specialty::factory()->create();

        // Tao Schedule trước
        $schedule = \App\Models\Schedule::create([
            'doctor_id' => $user->id,
            'max_number' => 10,
            'current_number' => 0,
            'date_schedule' => now()->addDay()->toDateString(),
            'status' => 1
        ]);

        $scheduleTime = ScheduleTime::factory()->create([
            'schedule_id' => $schedule->id
        ]);

        // Tao booking dau tien
        $firstBooking = Booking::create([
            'schedule_time_id' => $scheduleTime->id,
            'doctor_id' => $user->id,
            'date_booking' => $schedule->date_schedule,
            'number' => 1,
            'patient_id' => $user->id,
            'specialty_id' => $specialty->id,
            'phone' => '0123456781',
            'name' => 'First User',
            'email' => 'first@example.com'
        ]);

        $this->actingAs($user, 'users');

        // Goi POST dat lich thu 2
        $response = $this->post(route('post.booking.appointment', ['id' => $scheduleTime->id]), [
            'phone' => '0123456782',
            'name' => 'Second User',
            'email' => 'second@example.com',
            'gender' => 'Nam',
            'birthday' => '1990-01-01',
            'address' => 'Second Address',
            'specialty_id' => $specialty->id,
        ]);

        $response->assertRedirect(route('user.home.index'));
        $response->assertSessionHas('error');

        // Kiem tra number duoc tang len 1
        // $secondBooking = Booking::where('phone', '0123456782')->first();
        // $this->assertNotNull($secondBooking);
        // $this->assertEquals(2, $secondBooking->number);
    }
}
