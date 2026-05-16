<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Shanmuga\LaravelEntrust\Traits\LaravelEntrustUserTrait;
use Elasticquent\ElasticquentTrait;

class User extends Authenticatable
{
    use LaravelEntrustUserTrait, ElasticquentTrait; // add this trait to your user model
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'user_code',
        'email',
        'password',
        'password_text',
        'phone',
        'avatar',
        'address',
        'status',
        'birthday',
        'gender',
        'type',
        'position',
        'job_title',
        'description',
        'contents',
        'price_min',
        'price_max',
        'clinic_id',
        'specialty_id',
        'city_id',
        'district_id',
        'street_id',
        'citizen_id_number',
        'insurance_card_number',
    ];

    const STATUS = [
        1 => 'Hoạt động',
        2 => 'Đã khóa'
    ];

    const GENDERS = [
        1 => 'Nam',
        2 => 'Nữ',
        3 => 'Không xác định',
    ];

    const POSITIONS = [
        1 => 'Giáo sư',
        2 => 'Phó giáo sư ',
        3 => 'Tiến sĩ',
        4 => 'Thạc sĩ',
    ];
    const POSITIONS_TS = [
        1 => 'GS',
        2 => 'PGS',
        3 => 'TS',
        4 => 'ThS',
        5 => 'BS',
    ];

    const JOB_TITLE = [
        1 => 'Trưởng khoa',
        2 => 'Phó khoa',
        3 => 'Bác sĩ chuyên khoa',
    ];

    const BOOK_FOR = [
        1 => 'Đặt cho mình',
        2 => 'Đặt cho người thân',
    ];

    const ACTIVE  = 1;
    const LOCK  = 2;
    const TYPE_DOCTOR  = 1;
    const TYPE_PATIENT  = 2;
    const TYPE_ADMIN  = 3;

    const TYPES = [
        3 => 'Quản trị',
        1 => 'Bác sĩ',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getInfoEmail($email)
    {
        return $this->where(['email'=>$email, 'status' =>  1])->first();
    }

    /**
     * Tìm user theo email, phone hoặc citizen_id_number
     *
     * @param string $identifier
     * @return User|null
     */
    public function findByEmailOrPhoneOrCitizenId($identifier)
    {
        return $this->where('status', 1)
            ->where(function($query) use ($identifier) {
                $query->where('email', $identifier)
                    ->orWhere('phone', $identifier)
                    ->orWhere('citizen_id_number', $identifier);
            })
            ->first();
    }

    public function userRole()
    {
        return $this->belongsToMany(Role::class, 'role_user', 'user_id', 'role_id');
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class)->select('id', 'name', 'email', 'phone', 'address', 'logo');
    }

    public function specialty()
    {
        return $this->belongsTo(Specialty::class)->select('id', 'name', 'image');
    }

    public function schedule()
    {
        return $this->hasMany(Schedule::class, 'doctor_id', 'id');
    }
    public function city()
    {
        return $this->belongsTo(Locations::class, 'city_id', 'id');
    }

    public function district()
    {
        return $this->belongsTo(Locations::class, 'district_id');
    }

    public function street()
    {
        return $this->belongsTo(Locations::class, 'street_id');
    }

    public function ratings ()
    {
        return $this->hasMany(Rating::class, 'doctor_id', 'id');
    }

    public function specialties()
    {
        return $this->belongsToMany(Specialty::class, 'user_specialties', 'user_id', 'specialty_id')
            ->withTimestamps(); // Nếu bảng trung gian có cột created_at, updated_at
    }

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier() {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims() {
        return [];
    }

    /**
     * @param $request
     * @param string $id
     */
    public function createOrUpdate($request , $id ='')
    {
        $params = $request->except(['_token', 'image', 'submit', 'password', 'specialty_ids', 'position']);

        $params['password'] = bcrypt($request->password);

        if (isset($request->images) && !empty($request->images)) {
            $image = upload_image('images');
            if ($image['code'] == 1)
                $params['avatar'] = $image['name'];
        }
        if (!empty($request->position)) {
            $params['position'] = implode(',', $request->position);
        }

        if ($id) {
            $user = $this->find($id);
            \DB::table('role_user')->where('user_id', $id)->delete();
            $user->update($params);
        } else {
            $user = new User();
            $user->fill($params)->save();
        }
        if ($user) {
            switch ($request->type) {
                case 1 :
                    $textCode = 'BS' ;
                    break;
                case 2 :
                    $textCode = 'BN' ;
                    break;
                case 3 :
                    $textCode = 'QT' ;
                    break;
                default :
                    $textCode = 'BN' ;

            }
            $user->update([
                'user_code' => $textCode.str_pad($user->id, 6, '0', STR_PAD_LEFT)
            ]);
            if ($request->role) {
                \DB::table('role_user')->insert(['role_id'=> $request->role, 'user_id'=> $user->id]);
            }

            // Gán các chuyên khoa thông qua bảng trung gian user_specialties
            if ($request->has('specialty_ids') && is_array($request->specialty_ids)) {
                $user->specialties()->sync($request->specialty_ids);
            }
        }
    }
}
