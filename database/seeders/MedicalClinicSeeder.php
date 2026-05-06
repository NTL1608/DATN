<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Clinic;
use App\Models\Specialty;
use App\Models\ClinicSpecialty;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class MedicalClinicSeeder extends Seeder
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
            [
                'name' => 'Khoa Nội tổng hợp',
                'email' => 'noitonghop@example.com',
                'phone' => '0123-456-789',
                'address' => 'Tầng 1, Tòa nhà A',
                'description' => 'Chuyên khám và điều trị các bệnh nội khoa tổng quát',
                'contents' => 'Khoa nội tổng hợp cung cấp các dịch vụ khám, chẩn đoán và điều trị các bệnh nội khoa cơ bản.'
            ],
            [
                'name' => 'Khoa Tim mạch',
                'email' => 'timmach@example.com',
                'phone' => '0123-456-790',
                'address' => 'Tầng 2, Tòa nhà A',
                'description' => 'Chuyên về tim mạch và các bệnh lý tim mạch',
                'contents' => 'Khoa tim mạch chuyên về khám, chẩn đoán và điều trị các bệnh lý tim mạch.'
            ],
            [
                'name' => 'Khoa Thần kinh',
                'email' => 'thankinh@example.com',
                'phone' => '0123-456-791',
                'address' => 'Tầng 3, Tòa nhà A',
                'description' => 'Chuyên về thần kinh và các bệnh lý thần kinh',
                'contents' => 'Khoa thần kinh chuyên về khám, chẩn đoán và điều trị các bệnh lý thần kinh.'
            ],
            [
                'name' => 'Khoa Cơ – Xương – Khớp',
                'email' => 'coxuongkhop@example.com',
                'phone' => '0123-456-792',
                'address' => 'Tầng 4, Tòa nhà A',
                'description' => 'Chuyên về cơ xương khớp và các bệnh lý cơ xương khớp',
                'contents' => 'Khoa cơ xương khớp chuyên về khám, chẩn đoán và điều trị các bệnh lý cơ xương khớp.'
            ],
            [
                'name' => 'Khoa Tiêu hóa',
                'email' => 'tieuhoa@example.com',
                'phone' => '0123-456-793',
                'address' => 'Tầng 5, Tòa nhà A',
                'description' => 'Chuyên về tiêu hóa và các bệnh lý tiêu hóa',
                'contents' => 'Khoa tiêu hóa chuyên về khám, chẩn đoán và điều trị các bệnh lý tiêu hóa.'
            ],
            [
                'name' => 'Khoa Thận – Tiết niệu',
                'email' => 'thantietnieu@example.com',
                'phone' => '0123-456-794',
                'address' => 'Tầng 6, Tòa nhà A',
                'description' => 'Chuyên về thận tiết niệu và các bệnh lý thận tiết niệu',
                'contents' => 'Khoa thận tiết niệu chuyên về khám, chẩn đoán và điều trị các bệnh lý thận tiết niệu.'
            ],
            [
                'name' => 'Khoa Phụ sản',
                'email' => 'phusan@example.com',
                'phone' => '0123-456-795',
                'address' => 'Tầng 7, Tòa nhà A',
                'description' => 'Chuyên về phụ sản và các bệnh lý phụ khoa',
                'contents' => 'Khoa phụ sản chuyên về khám, chẩn đoán và điều trị các bệnh lý phụ khoa.'
            ],
            [
                'name' => 'Khoa Nhi',
                'email' => 'nhi@example.com',
                'phone' => '0123-456-796',
                'address' => 'Tầng 8, Tòa nhà A',
                'description' => 'Chuyên về nhi khoa và các bệnh lý trẻ em',
                'contents' => 'Khoa nhi chuyên về khám, chẩn đoán và điều trị các bệnh lý trẻ em.'
            ],
            [
                'name' => 'Khoa Da liễu',
                'email' => 'dalieu@example.com',
                'phone' => '0123-456-797',
                'address' => 'Tầng 9, Tòa nhà A',
                'description' => 'Chuyên về da liễu và các bệnh lý da',
                'contents' => 'Khoa da liễu chuyên về khám, chẩn đoán và điều trị các bệnh lý da.'
            ],
            [
                'name' => 'Khoa Tai – Mũi – Họng',
                'email' => 'taimuihong@example.com',
                'phone' => '0123-456-798',
                'address' => 'Tầng 10, Tòa nhà A',
                'description' => 'Chuyên về tai mũi họng và các bệnh lý TMH',
                'contents' => 'Khoa tai mũi họng chuyên về khám, chẩn đoán và điều trị các bệnh lý TMH.'
            ]
        ];

        foreach ($clinics as $clinicData) {
            Clinic::firstOrCreate(['name' => $clinicData['name']], $clinicData);
        }

        // Tạo các dịch vụ nếu chưa có
        $specialties = [
            // Khoa Nội tổng hợp
            [
                'name' => 'Khám tăng/giảm huyết áp',
                'description' => 'Khám và điều trị tăng huyết áp, hạ huyết áp',
                'image' => 'tang-giam-huyet-ap.jpg'
            ],
            [
                'name' => 'Khám tiểu đường, mỡ máu, gan nhiễm mỡ',
                'description' => 'Khám và điều trị tiểu đường, rối loạn mỡ máu, gan nhiễm mỡ',
                'image' => 'tieu-duong-mo-mau.jpg'
            ],
            [
                'name' => 'Khám phát hiện sớm ung thư',
                'description' => 'Khám phát hiện sớm ung thư phổi, gan, tiêu hóa',
                'image' => 'phat-hien-ung-thu.jpg'
            ],
            
            // Khoa Tim mạch
            [
                'name' => 'Khám đau ngực, khó thở, hồi hộp',
                'description' => 'Khám và điều trị đau ngực, khó thở, hồi hộp',
                'image' => 'dau-nguc-kho-tho.jpg'
            ],
            [
                'name' => 'Đo điện tâm đồ, siêu âm tim',
                'description' => 'Đo điện tâm đồ và siêu âm tim',
                'image' => 'dien-tam-do-sieu-am-tim.jpg'
            ],
            [
                'name' => 'Khám tăng huyết áp, rối loạn nhịp tim',
                'description' => 'Khám và điều trị tăng huyết áp, rối loạn nhịp tim',
                'image' => 'tang-huyet-ap-roi-loan-nhip.jpg'
            ],
            
            // Khoa Thần kinh
            [
                'name' => 'Khám đau đầu, rối loạn tiền đình',
                'description' => 'Khám và điều trị đau đầu, rối loạn tiền đình',
                'image' => 'dau-dau-roi-loan-tien-dinh.jpg'
            ],
            [
                'name' => 'Khám sau tai biến, đột quỵ',
                'description' => 'Khám và điều trị sau tai biến, đột quỵ',
                'image' => 'sau-tai-bien-dot-quy.jpg'
            ],
            [
                'name' => 'Tư vấn rối loạn thần kinh thực vật',
                'description' => 'Tư vấn và điều trị rối loạn thần kinh thực vật',
                'image' => 'roi-loan-than-kinh-thuc-vat.jpg'
            ],
            
            // Khoa Cơ – Xương – Khớp
            [
                'name' => 'Khám đau vai gáy, thoái hóa khớp',
                'description' => 'Khám và điều trị đau vai gáy, thoái hóa khớp',
                'image' => 'dau-vai-gay-thoai-hoa-khop.jpg'
            ],
            [
                'name' => 'Khám cột sống, thoát vị đĩa đệm',
                'description' => 'Khám và điều trị cột sống, thoát vị đĩa đệm',
                'image' => 'cot-song-thoat-vi-dia-dem.jpg'
            ],
            [
                'name' => 'Tư vấn điều trị Gout, loãng xương',
                'description' => 'Tư vấn và điều trị Gout, loãng xương',
                'image' => 'gout-loang-xuong.jpg'
            ],
            
            // Khoa Tiêu hóa
            [
                'name' => 'Khám đau bụng, đầy hơi, khó tiêu',
                'description' => 'Khám và điều trị đau bụng, đầy hơi, khó tiêu',
                'image' => 'dau-bung-day-hoi-kho-tieu.jpg'
            ],
            [
                'name' => 'Khám viêm gan, xơ gan',
                'description' => 'Khám và điều trị viêm gan, xơ gan',
                'image' => 'viem-gan-xo-gan.jpg'
            ],
            [
                'name' => 'Tư vấn nội soi dạ dày, đại tràng',
                'description' => 'Tư vấn nội soi dạ dày, đại tràng',
                'image' => 'noi-soi-da-day-dai-trang.jpg'
            ],
            
            // Khoa Thận – Tiết niệu
            [
                'name' => 'Khám sỏi thận, bí tiểu, tiểu buốt',
                'description' => 'Khám và điều trị sỏi thận, bí tiểu, tiểu buốt',
                'image' => 'soi-than-bi-tieu-tieu-buot.jpg'
            ],
            [
                'name' => 'Siêu âm thận, bàng quang',
                'description' => 'Siêu âm thận, bàng quang',
                'image' => 'sieu-am-than-bang-quang.jpg'
            ],
            [
                'name' => 'Tư vấn viêm tuyến tiền liệt',
                'description' => 'Tư vấn và điều trị viêm tuyến tiền liệt',
                'image' => 'viem-tuyen-tien-liet.jpg'
            ],
            
            // Khoa Phụ sản
            [
                'name' => 'Khám thai định kỳ, siêu âm thai',
                'description' => 'Khám thai định kỳ và siêu âm thai',
                'image' => 'kham-thai-dinh-ky-sieu-am.jpg'
            ],
            [
                'name' => 'Khám viêm nhiễm phụ khoa',
                'description' => 'Khám và điều trị viêm nhiễm phụ khoa',
                'image' => 'viem-nhiem-phu-khoa.jpg'
            ],
            [
                'name' => 'Khám vô sinh, rối loạn kinh nguyệt',
                'description' => 'Khám và điều trị vô sinh, rối loạn kinh nguyệt',
                'image' => 'vo-sinh-roi-loan-kinh-nguyet.jpg'
            ],
            
            // Khoa Nhi
            [
                'name' => 'Khám ho, sốt, tiêu chảy ở trẻ',
                'description' => 'Khám và điều trị ho, sốt, tiêu chảy ở trẻ em',
                'image' => 'ho-sot-tieu-chay-tre.jpg'
            ],
            [
                'name' => 'Tư vấn dinh dưỡng, phát triển thể chất',
                'description' => 