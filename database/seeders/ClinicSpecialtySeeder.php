<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Clinic;
use App\Models\Specialty;
use App\Models\ClinicSpecialty;

class ClinicSpecialtySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Tạo các khoa nếu chưa có
        $clinics = [
            ['name' => 'Khoa Nội tổng hợp', 'email' => 'noitonghop@example.com', 'phone' => '0123-456-789', 'address' => 'Tầng 1, Tòa nhà A', 'description' => 'Chuyên khám và điều trị các bệnh nội khoa tổng quát', 'contents' => 'Khoa nội tổng hợp cung cấp các dịch vụ khám, chẩn đoán và điều trị các bệnh nội khoa cơ bản.'],
            ['name' => 'Khoa Tim mạch', 'email' => 'timmach@example.com', 'phone' => '0123-456-790', 'address' => 'Tầng 2, Tòa nhà A', 'description' => 'Chuyên về tim mạch và các bệnh lý tim mạch', 'contents' => 'Khoa tim mạch chuyên về khám, chẩn đoán và điều trị các bệnh lý tim mạch.'],
            ['name' => 'Khoa Thần kinh', 'email' => 'thankinh@example.com', 'phone' => '0123-456-791', 'address' => 'Tầng 3, Tòa nhà A', 'description' => 'Chuyên về thần kinh và các bệnh lý thần kinh', 'contents' => 'Khoa thần kinh chuyên về khám, chẩn đoán và điều trị các bệnh lý thần kinh.'],
            ['name' => 'Khoa Cơ – Xương – Khớp', 'email' => 'coxuongkhop@example.com', 'phone' => '0123-456-792', 'address' => 'Tầng 4, Tòa nhà A', 'description' => 'Chuyên về cơ xương khớp và các bệnh lý cơ xương khớp', 'contents' => 'Khoa cơ xương khớp chuyên về khám, chẩn đoán và điều trị các bệnh lý cơ xương khớp.'],
            ['name' => 'Khoa Tiêu hóa', 'email' => 'tieuhoa@example.com', 'phone' => '0123-456-793', 'address' => 'Tầng 5, Tòa nhà A', 'description' => 'Chuyên về tiêu hóa và các bệnh lý tiêu hóa', 'contents' => 'Khoa tiêu hóa chuyên về khám, chẩn đoán và điều trị các bệnh lý tiêu hóa.'],
            ['name' => 'Khoa Thận – Tiết niệu', 'email' => 'thantietnieu@example.com', 'phone' => '0123-456-794', 'address' => 'Tầng 6, Tòa nhà A', 'description' => 'Chuyên về thận tiết niệu và các bệnh lý thận tiết niệu', 'contents' => 'Khoa thận tiết niệu chuyên về khám, chẩn đoán và điều trị các bệnh lý thận tiết niệu.'],
            ['name' => 'Khoa Phụ sản', 'email' => 'phusan@example.com', 'phone' => '0123-456-795', 'address' => 'Tầng 7, Tòa nhà A', 'description' => 'Chuyên về phụ sản và các bệnh lý phụ khoa', 'contents' => 'Khoa phụ sản chuyên về khám, chẩn đoán và điều trị các bệnh lý phụ khoa.'],
            ['name' => 'Khoa Nhi', 'email' => 'nhi@example.com', 'phone' => '0123-456-796', 'address' => 'Tầng 8, Tòa nhà A', 'description' => 'Chuyên về nhi khoa và các bệnh lý trẻ em', 'contents' => 'Khoa nhi chuyên về khám, chẩn đoán và điều trị các bệnh lý trẻ em.'],
            ['name' => 'Khoa Da liễu', 'email' => 'dalieu@example.com', 'phone' => '0123-456-797', 'address' => 'Tầng 9, Tòa nhà A', 'description' => 'Chuyên về da liễu và các bệnh lý da', 'contents' => 'Khoa da liễu chuyên về khám, chẩn đoán và điều trị các bệnh lý da.'],
            ['name' => 'Khoa Tai – Mũi – Họng', 'email' => 'taimuihong@example.com', 'phone' => '0123-456-798', 'address' => 'Tầng 10, Tòa nhà A', 'description' => 'Chuyên về tai mũi họng và các bệnh lý TMH', 'contents' => 'Khoa tai mũi họng chuyên về khám, chẩn đoán và điều trị các bệnh lý TMH.']
        ];

        foreach ($clinics as $key => $clinicData) {
            if (Clinic::where('id', $key + 1)->exists()) {
                Clinic::where('id', $key + 1)->update($clinicData);
            } else {
                Clinic::create($clinicData);
            }
        }

        // Tạo các dịch vụ với giá ngẫu nhiên là số chẵn
        $specialties = [
            ['name' => 'Khám tăng/giảm huyết áp', 'description' => 'Khám và điều trị tăng huyết áp, hạ huyết áp', 'price' => floor(rand(300000, 500000) / 1000) * 1000],
            ['name' => 'Khám tiểu đường, mỡ máu, gan nhiễm mỡ', 'description' => 'Khám và điều trị tiểu đường, rối loạn mỡ máu, gan nhiễm mỡ', 'price' => floor(rand(300000, 500000) / 1000) * 1000],
            ['name' => 'Khám phát hiện sớm ung thư', 'description' => 'Khám phát hiện sớm ung thư phổi, gan, tiêu hóa', 'price' => floor(rand(300000, 500000) / 1000) * 1000],
            ['name' => 'Khám đau ngực, khó thở, hồi hộp', 'description' => 'Khám và điều trị đau ngực, khó thở, hồi hộp', 'price' => floor(rand(300000, 500000) / 1000) * 1000],
            ['name' => 'Đo điện tâm đồ, siêu âm tim', 'description' => 'Đo điện tâm đồ và siêu âm tim', 'price' => floor(rand(300000, 500000) / 1000) * 1000],
            ['name' => 'Khám tăng huyết áp, rối loạn nhịp tim', 'description' => 'Khám và điều trị tăng huyết áp, rối loạn nhịp tim', 'price' => floor(rand(300000, 500000) / 1000) * 1000],
            ['name' => 'Khám đau đầu, rối loạn tiền đình', 'description' => 'Khám và điều trị đau đầu, rối loạn tiền đình', 'price' => floor(rand(300000, 500000) / 1000) * 1000],
            ['name' => 'Khám sau tai biến, đột quỵ', 'description' => 'Khám và điều trị sau tai biến, đột quỵ', 'price' => floor(rand(300000, 500000) / 1000) * 1000],
            ['name' => 'Tư vấn rối loạn thần kinh thực vật', 'description' => 'Tư vấn và điều trị rối loạn thần kinh thực vật', 'price' => floor(rand(300000, 500000) / 1000) * 1000],
            ['name' => 'Khám đau vai gáy, thoái hóa khớp', 'description' => 'Khám và điều trị đau vai gáy, thoái hóa khớp', 'price' => floor(rand(300000, 500000) / 1000) * 1000],
            ['name' => 'Khám cột sống, thoát vị đĩa đệm', 'description' => 'Khám và điều trị cột sống, thoát vị đĩa đệm', 'price' => floor(rand(300000, 500000) / 1000) * 1000],
            ['name' => 'Tư vấn điều trị Gout, loãng xương', 'description' => 'Tư vấn và điều trị Gout, loãng xương', 'price' => floor(rand(300000, 500000) / 1000) * 1000],
            ['name' => 'Khám đau bụng, đầy hơi, khó tiêu', 'description' => 'Khám và điều trị đau bụng, đầy hơi, khó tiêu', 'price' => floor(rand(300000, 500000) / 1000) * 1000],
            ['name' => 'Khám viêm gan, xơ gan', 'description' => 'Khám và điều trị viêm gan, xơ gan', 'price' => floor(rand(300000, 500000) / 1000) * 1000],
            ['name' => 'Tư vấn nội soi dạ dày, đại tràng', 'description' => 'Tư vấn nội soi dạ dày, đại tràng', 'price' => floor(rand(300000, 500000) / 1000) * 1000],
            ['name' => 'Khám sỏi thận, bí tiểu, tiểu buốt', 'description' => 'Khám và điều trị sỏi thận, bí tiểu, tiểu buốt', 'price' => floor(rand(300000, 500000) / 1000) * 1000],
            ['name' => 'Siêu âm thận, bàng quang', 'description' => 'Siêu âm thận, bàng quang', 'price' => floor(rand(300000, 500000) / 1000) * 1000],
            ['name' => 'Tư vấn viêm tuyến tiền liệt', 'description' => 'Tư vấn và điều trị viêm tuyến tiền liệt', 'price' => floor(rand(300000, 500000) / 1000) * 1000],
            ['name' => 'Khám thai định kỳ, siêu âm thai', 'description' => 'Khám thai định kỳ và siêu âm thai', 'price' => floor(rand(300000, 500000) / 1000) * 1000],
            ['name' => 'Khám viêm nhiễm phụ khoa', 'description' => 'Khám và điều trị viêm nhiễm phụ khoa', 'price' => floor(rand(300000, 500000) / 1000) * 1000],
            ['name' => 'Khám vô sinh, rối loạn kinh nguyệt', 'description' => 'Khám và điều trị vô sinh, rối loạn kinh nguyệt', 'price' => floor(rand(300000, 500000) / 1000) * 1000],
            ['name' => 'Khám ho, sốt, tiêu chảy ở trẻ', 'description' => 'Khám và điều trị ho, sốt, tiêu chảy ở trẻ em', 'price' => floor(rand(300000, 500000) / 1000) * 1000],
            ['name' => 'Tư vấn dinh dưỡng, phát triển thể chất', 'description' => 'Tư vấn dinh dưỡng và phát triển thể chất cho trẻ', 'price' => floor(rand(300000, 500000) / 1000) * 1000],
            ['name' => 'Tiêm chủng dịch vụ', 'description' => 'Tiêm chủng dịch vụ cho trẻ em', 'price' => floor(rand(300000, 500000) / 1000) * 1000],
            ['name' => 'Khám mụn, viêm da, dị ứng', 'description' => 'Khám và điều trị mụn, viêm da, dị ứng', 'price' => floor(rand(300000, 500000) / 1000) * 1000],
            ['name' => 'Khám nấm, lang ben, zona', 'description' => 'Khám và điều trị nấm, lang ben, zona', 'price' => floor(rand(300000, 500000) / 1000) * 1000],
            ['name' => 'Tư vấn chăm sóc da thẩm mỹ', 'description' => 'Tư vấn chăm sóc da thẩm mỹ', 'price' => floor(rand(300000, 500000) / 1000) * 1000],
            ['name' => 'Khám viêm mũi xoang, đau họng, ù tai', 'description' => 'Khám và điều trị viêm mũi xoang, đau họng, ù tai', 'price' => floor(rand(300000, 500000) / 1000) * 1000],
            ['name' => 'Khám viêm tai giữa, viêm amidan', 'description' => 'Khám và điều trị viêm tai giữa, viêm amidan', 'price' => floor(rand(300000, 500000) / 1000) * 1000],
            ['name' => 'Tư vấn nội soi TMH', 'description' => 'Tư vấn nội soi tai mũi họng', 'price' => floor(rand(300000, 500000) / 1000) * 1000]
        ];

        foreach ($specialties as $key => $specialtyData) {
            if (Specialty::where('id', $key + 1)->exists()) {
                Specialty::where('id', $key + 1)->update($specialtyData);
            } else {
                Specialty::create($specialtyData);
            }
        }

        // Ánh xạ khoa với dịch vụ dựa trên tên
        $clinicSpecialtyMappings = [
            'Khoa Nội tổng hợp' => [
                'Khám tăng/giảm huyết áp',
                'Khám tiểu đường, mỡ máu, gan nhiễm mỡ',
                'Khám phát hiện sớm ung thư'
            ],
            'Khoa Tim mạch' => [
                'Khám đau ngực, khó thở, hồi hộp',
                'Đo điện tâm đồ, siêu âm tim',
                'Khám tăng huyết áp, rối loạn nhịp tim'
            ],
            'Khoa Thần kinh' => [
                'Khám đau đầu, rối loạn tiền đình',
                'Khám sau tai biến, đột quỵ',
                'Tư vấn rối loạn thần kinh thực vật'
            ],
            'Khoa Cơ – Xương – Khớp' => [
                'Khám đau vai gáy, thoái hóa khớp',
                'Khám cột sống, thoát vị đĩa đệm',
                'Tư vấn điều trị Gout, loãng xương'
            ],
            'Khoa Tiêu hóa' => [
                'Khám đau bụng, đầy hơi, khó tiêu',
                'Khám viêm gan, xơ gan',
                'Tư vấn nội soi dạ dày, đại tràng'
            ],
            'Khoa Thận – Tiết niệu' => [
                'Khám sỏi thận, bí tiểu, tiểu buốt',
                'Siêu âm thận, bàng quang',
                'Tư vấn viêm tuyến tiền liệt'
            ],
            'Khoa Phụ sản' => [
                'Khám thai định kỳ, siêu âm thai',
                'Khám viêm nhiễm phụ khoa',
                'Khám vô sinh, rối loạn kinh nguyệt'
            ],
            'Khoa Nhi' => [
                'Khám ho, sốt, tiêu chảy ở trẻ',
                'Tư vấn dinh dưỡng, phát triển thể chất',
                'Tiêm chủng dịch vụ'
            ],
            'Khoa Da liễu' => [
                'Khám mụn, viêm da, dị ứng',
                'Khám nấm, lang ben, zona',
                'Tư vấn chăm sóc da thẩm mỹ'
            ],
            'Khoa Tai – Mũi – Họng' => [
                'Khám viêm mũi xoang, đau họng, ù tai',
                'Khám viêm tai giữa, viêm amidan',
                'Tư vấn nội soi TMH'
            ]
        ];

        foreach ($clinicSpecialtyMappings as $clinicName => $specialtyNames) {
            $clinic = Clinic::where('name', $clinicName)->first();
            if ($clinic) {
                foreach ($specialtyNames as $specialtyName) {
                    $specialty = Specialty::where('name', $specialtyName)->first();
                    if ($specialty) {
                        ClinicSpecialty::firstOrCreate([
                            'clinic_id' => $clinic->id,
                            'specialty_id' => $specialty->id
                        ]);
                    }
                }
            }
        }
    }
}