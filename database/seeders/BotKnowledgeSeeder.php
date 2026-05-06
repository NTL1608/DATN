<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BotKnowledge;

class BotKnowledgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $qaPairs = [
            // Thông tin chung
            [
                'question' => 'phòng khám có làm việc vào chủ nhật không',
                'answer' => 'Phòng khám làm việc từ thứ 2 đến thứ 7, từ 8:00 đến 17:00. Chủ nhật nghỉ.',
                'confidence' => 1
            ],
            [
                'question' => 'địa chỉ phòng khám ở đâu',
                'answer' => 'Phòng khám tọa lạc tại số 123, đường ABC, quận XYZ, thành phố HCM.',
                'confidence' => 1
            ],
            [
                'question' => 'số điện thoại liên hệ',
                'answer' => 'Bạn có thể liên hệ qua số điện thoại: 0123-456-789 hoặc hotline: 0987-654-321.',
                'confidence' => 1
            ],
            [
                'question' => 'phòng khám có bãi đậu xe không',
                'answer' => 'Có, phòng khám có bãi đậu xe miễn phí cho khách hàng.',
                'confidence' => 1
            ],
            [
                'question' => 'có cần đặt lịch trước không',
                'answer' => 'Bạn nên đặt lịch trước để tránh phải chờ đợi. Có thể đặt lịch qua website hoặc gọi điện trực tiếp.',
                'confidence' => 1
            ],

            // Lịch khám bác sĩ
            [
                'question' => 'tôi muốn đặt lịch khám bác sĩ',
                'answer' => 'Bạn có thể đặt lịch khám bằng cách: 1) Gọi điện thoại: 0123-456-789, 2) Đặt lịch online qua website, 3) Đến trực tiếp phòng khám. Vui lòng cho biết tên bác sĩ và ngày khám bạn muốn.',
                'confidence' => 1
            ],
            [
                'question' => 'lịch khám còn trống',
                'answer' => 'Để kiểm tra lịch khám còn trống, vui lòng cho biết tên bác sĩ và ngày khám bạn muốn. Tôi sẽ kiểm tra và thông báo cho bạn.',
                'confidence' => 1
            ],
            [
                'question' => 'bác sĩ có làm việc không',
                'answer' => 'Để kiểm tra lịch làm việc của bác sĩ, vui lòng cho biết tên bác sĩ và thời gian bạn muốn khám.',
                'confidence' => 1
            ],
            [
                'question' => 'giá khám bác sĩ',
                'answer' => 'Giá khám bệnh dao động từ 200.000đ đến 500.000đ tùy theo chuyên khoa và bác sĩ. Vui lòng cho biết tên bác sĩ để tôi cung cấp thông tin chính xác.',
                'confidence' => 1
            ],
            [
                'question' => 'tìm bác sĩ',
                'answer' => 'Phòng khám có đội ngũ bác sĩ chuyên khoa giàu kinh nghiệm. Vui lòng cho biết chuyên khoa hoặc dịch vụ bạn cần để tôi giới thiệu bác sĩ phù hợp.',
                'confidence' => 1
            ],

            // Dịch vụ nha khoa
            [
                'question' => 'phòng khám có những dịch vụ gì',
                'answer' => 'Chúng tôi cung cấp đầy đủ các dịch vụ nha khoa: khám tổng quát, cạo vôi răng, trám răng, nhổ răng, niềng răng, bọc răng sứ, cấy ghép implant, tẩy trắng răng.',
                'confidence' => 1
            ],
            [
                'question' => 'giá khám tổng quát bao nhiêu',
                'answer' => 'Giá khám tổng quát là 200.000đ/lần, bao gồm khám và tư vấn.',
                'confidence' => 1
            ],
            [
                'question' => 'cạo vôi răng giá bao nhiêu',
                'answer' => 'Giá cạo vôi răng là 300.000đ/lần, thời gian thực hiện khoảng 30 phút.',
                'confidence' => 1
            ],
            [
                'question' => 'trám răng giá bao nhiêu',
                'answer' => 'Giá trám răng dao động từ 400.000đ - 800.000đ/răng tùy loại vật liệu trám.',
                'confidence' => 1
            ],
            [
                'question' => 'nhổ răng giá bao nhiêu',
                'answer' => 'Giá nhổ răng từ 500.000đ - 2.000.000đ/răng tùy độ khó của ca nhổ.',
                'confidence' => 1
            ],

            // Niềng răng
            [
                'question' => 'niềng răng giá bao nhiêu',
                'answer' => 'Giá niềng răng dao động từ 25-50 triệu đồng tùy loại mắc cài và thời gian điều trị.',
                'confidence' => 1
            ],
            [
                'question' => 'niềng răng mất bao lâu',
                'answer' => 'Thời gian niềng răng trung bình từ 18-24 tháng, tùy tình trạng răng của mỗi người.',
                'confidence' => 1
            ],
            [
                'question' => 'có những loại niềng răng nào',
                'answer' => 'Chúng tôi cung cấp các loại niềng răng: mắc cài kim loại, mắc cài sứ, mắc cài tự buộc, niềng răng trong suốt Invisalign.',
                'confidence' => 1
            ],
            [
                'question' => 'niềng răng có đau không',
                'answer' => 'Niềng răng có thể gây khó chịu nhẹ trong 1-2 tuần đầu, sau đó sẽ quen dần. Bác sĩ sẽ kê thuốc giảm đau nếu cần.',
                'confidence' => 1
            ],
            [
                'question' => 'niềng răng có ảnh hưởng đến ăn uống không',
                'answer' => 'Trong 1-2 tuần đầu có thể hơi khó ăn, sau đó bạn có thể ăn uống bình thường, chỉ cần tránh thức ăn cứng và dính.',
                'confidence' => 1
            ],

            // Bọc răng sứ
            [
                'question' => 'bọc răng sứ giá bao nhiêu',
                'answer' => 'Giá bọc răng sứ từ 3-10 triệu đồng/răng tùy loại sứ và kỹ thuật thực hiện.',
                'confidence' => 1
            ],
            [
                'question' => 'bọc răng sứ có đau không',
                'answer' => 'Bọc răng sứ được thực hiện dưới tác dụng của thuốc tê nên không đau. Sau khi hết thuốc tê có thể hơi ê nhẹ.',
                'confidence' => 1
            ],
            [
                'question' => 'bọc răng sứ có bền không',
                'answer' => 'Răng sứ có tuổi thọ trung bình 10-15 năm nếu chăm sóc tốt. Có thể kéo dài hơn tùy loại sứ và cách chăm sóc.',
                'confidence' => 1
            ],
            [
                'question' => 'có những loại răng sứ nào',
                'answer' => 'Chúng tôi cung cấp các loại răng sứ: sứ kim loại, sứ titan, sứ zirconia, sứ ceramill.',
                'confidence' => 1
            ],
            [
                'question' => 'bọc răng sứ mất bao lâu',
                'answer' => 'Thời gian bọc răng sứ khoảng 2-3 ngày, trong đó mài răng và lấy dấu 1 ngày, lắp răng sứ 1-2 ngày sau.',
                'confidence' => 1
            ],

            // Cấy ghép implant
            [
                'question' => 'cấy ghép implant giá bao nhiêu',
                'answer' => 'Giá cấy ghép implant từ 15-30 triệu đồng/răng tùy loại implant và kỹ thuật thực hiện.',
                'confidence' => 1
            ],
            [
                'question' => 'cấy ghép implant có đau không',
                'answer' => 'Cấy ghép implant được thực hiện dưới tác dụng của thuốc tê nên không đau. Sau phẫu thuật có thể ê nhẹ và sưng trong 2-3 ngày.',
                'confidence' => 1
            ],
            [
                'question' => 'cấy ghép implant mất bao lâu',
                'answer' => 'Thời gian hoàn thành cấy ghép implant khoảng 3-6 tháng, bao gồm thời gian chờ tích hợp xương.',
                'confidence' => 1
            ],
            [
                'question' => 'implant có bền không',
                'answer' => 'Implant có tuổi thọ trung bình 20-25 năm, thậm chí có thể dùng suốt đời nếu chăm sóc tốt.',
                'confidence' => 1
            ],
            [
                'question' => 'ai không nên cấy ghép implant',
                'answer' => 'Những người không nên cấy ghép implant: bệnh nhân tiểu đường không kiểm soát, bệnh tim mạch nặng, loãng xương nặng, đang điều trị ung thư.',
                'confidence' => 1
            ],

            // Tẩy trắng răng
            [
                'question' => 'tẩy trắng răng giá bao nhiêu',
                'answer' => 'Giá tẩy trắng răng từ 2-5 triệu đồng tùy phương pháp và tình trạng răng.',
                'confidence' => 1
            ],
            [
                'question' => 'tẩy trắng răng có hại không',
                'answer' => 'Tẩy trắng răng an toàn nếu thực hiện đúng kỹ thuật. Có thể gây ê nhẹ trong 1-2 ngày sau khi tẩy.',
                'confidence' => 1
            ],
            [
                'question' => 'tẩy trắng răng mất bao lâu',
                'answer' => 'Thời gian tẩy trắng răng khoảng 1-2 giờ tại phòng khám, kết quả có thể thấy ngay.',
                'confidence' => 1
            ],
            [
                'question' => 'tẩy trắng răng duy trì được bao lâu',
                'answer' => 'Kết quả tẩy trắng duy trì từ 6 tháng đến 2 năm tùy cách chăm sóc và thói quen ăn uống.',
                'confidence' => 1
            ],
            [
                'question' => 'có những phương pháp tẩy trắng nào',
                'answer' => 'Chúng tôi cung cấp các phương pháp tẩy trắng: tẩy trắng tại phòng khám, tẩy trắng tại nhà, kết hợp cả hai phương pháp.',
                'confidence' => 1
            ],

            // Chăm sóc răng miệng
            [
                'question' => 'nên đánh răng mấy lần một ngày',
                'answer' => 'Nên đánh răng 2-3 lần/ngày, sau bữa ăn 30 phút. Mỗi lần đánh 2-3 phút.',
                'confidence' => 1
            ],
            [
                'question' => 'nên dùng loại bàn chải nào',
                'answer' => 'Nên dùng bàn chải lông mềm, đầu nhỏ để dễ dàng làm sạch các kẽ răng.',
                'confidence' => 1
            ],
            [
                'question' => 'có nên dùng chỉ nha khoa không',
                'answer' => 'Có, nên dùng chỉ nha khoa ít nhất 1 lần/ngày để làm sạch kẽ răng, nơi bàn chải không thể làm sạch được.',
                'confidence' => 1
            ],
            [
                'question' => 'nước súc miệng có cần thiết không',
                'answer' => 'Nước súc miệng là bổ sung, không thay thế được việc đánh răng và dùng chỉ nha khoa. Nên dùng loại không chứa cồn.',
                'confidence' => 1
            ],
            [
                'question' => 'bao lâu nên đi khám răng một lần',
                'answer' => 'Nên đi khám răng định kỳ 6 tháng/lần để phát hiện và điều trị sớm các vấn đề về răng miệng.',
                'confidence' => 1
            ],

            // Bảo hiểm và thanh toán
            [
                'question' => 'có chấp nhận bảo hiểm y tế không',
                'answer' => 'Có, chúng tôi chấp nhận thanh toán qua bảo hiểm y tế cho các dịch vụ được bảo hiểm chi trả.',
                'confidence' => 1
            ],
            [
                'question' => 'có chấp nhận thẻ tín dụng không',
                'answer' => 'Có, chúng tôi chấp nhận thanh toán bằng thẻ tín dụng, thẻ ghi nợ và các hình thức thanh toán điện tử khác.',
                'confidence' => 1
            ],
            [
                'question' => 'có chính sách trả góp không',
                'answer' => 'Có, chúng tôi có chính sách trả góp 0% lãi suất cho các dịch vụ có giá trị từ 10 triệu đồng trở lên.',
                'confidence' => 1
            ],
            [
                'question' => 'có giảm giá cho khách hàng thân thiết không',
                'answer' => 'Có, khách hàng thân thiết sẽ được giảm 5-10% tùy dịch vụ và được ưu tiên đặt lịch.',
                'confidence' => 1
            ],
            [
                'question' => 'có bảo hành dịch vụ không',
                'answer' => 'Có, chúng tôi bảo hành tất cả các dịch vụ theo thời gian quy định. Ví dụ: bọc răng sứ bảo hành 5 năm, implant bảo hành trọn đời.',
                'confidence' => 1
            ],

            // Đội ngũ bác sĩ
            [
                'question' => 'bác sĩ có kinh nghiệm không',
                'answer' => 'Đội ngũ bác sĩ của chúng tôi đều tốt nghiệp từ các trường đại học y danh tiếng, có nhiều năm kinh nghiệm và thường xuyên cập nhật kiến thức mới.',
                'confidence' => 1
            ],
            [
                'question' => 'có bác sĩ nước ngoài không',
                'answer' => 'Có, chúng tôi có đội ngũ bác sĩ nước ngoài giàu kinh nghiệm, đặc biệt trong lĩnh vực implant và niềng răng.',
                'confidence' => 1
            ],
            [
                'question' => 'bác sĩ có chuyên môn gì',
                'answer' => 'Mỗi bác sĩ đều có chuyên môn riêng: nha khoa tổng quát, chỉnh nha, implant, phục hình răng, nha khoa trẻ em.',
                'confidence' => 1
            ],
            [
                'question' => 'có bác sĩ nữ không',
                'answer' => 'Có, chúng tôi có cả bác sĩ nam và nữ để phục vụ nhu cầu đa dạng của khách hàng.',
                'confidence' => 1
            ],
            [
                'question' => 'bác sĩ có nói tiếng Anh không',
                'answer' => 'Có, nhiều bác sĩ của chúng tôi có thể giao tiếp bằng tiếng Anh để phục vụ khách hàng nước ngoài.',
                'confidence' => 1
            ],

            // Thiết bị và công nghệ
            [
                'question' => 'phòng khám có máy móc hiện đại không',
                'answer' => 'Có, chúng tôi trang bị đầy đủ thiết bị hiện đại: máy chụp X-quang kỹ thuật số, máy quét răng 3D, máy tẩy trắng răng, phòng phẫu thuật vô trùng.',
                'confidence' => 1
            ],
            [
                'question' => 'có chụp X-quang răng không',
                'answer' => 'Có, chúng tôi có máy chụp X-quang kỹ thuật số hiện đại, cho kết quả nhanh và chính xác.',
                'confidence' => 1
            ],
            [
                'question' => 'có máy quét răng 3D không',
                'answer' => 'Có, chúng tôi sử dụng máy quét răng 3D để lấy dấu răng chính xác, giúp thiết kế răng sứ và niềng răng tốt hơn.',
                'confidence' => 1
            ],
            [
                'question' => 'có phòng phẫu thuật riêng không',
                'answer' => 'Có, chúng tôi có phòng phẫu thuật vô trùng riêng biệt, đạt tiêu chuẩn quốc tế.',
                'confidence' => 1
            ],
            [
                'question' => 'có sử dụng công nghệ CAD/CAM không',
                'answer' => 'Có, chúng tôi sử dụng công nghệ CAD/CAM để thiết kế và chế tạo răng sứ chính xác, nhanh chóng.',
                'confidence' => 1
            ],

            // Nha khoa trẻ em
            [
                'question' => 'có khám răng cho trẻ em không',
                'answer' => 'Có, chúng tôi có chuyên khoa nha khoa trẻ em với bác sĩ chuyên về răng trẻ em.',
                'confidence' => 1
            ],
            [
                'question' => 'trẻ mấy tuổi nên đi khám răng',
                'answer' => 'Nên cho trẻ đi khám răng từ 1 tuổi hoặc khi chiếc răng đầu tiên mọc lên.',
                'confidence' => 1
            ],
            [
                'question' => 'có niềng răng cho trẻ em không',
                'answer' => 'Có, chúng tôi cung cấp dịch vụ niềng răng cho trẻ em từ 7 tuổi trở lên.',
                'confidence' => 1
            ],
            [
                'question' => 'có trám răng sâu cho trẻ không',
                'answer' => 'Có, chúng tôi có dịch vụ trám răng sâu cho trẻ em, sử dụng vật liệu an toàn và không đau.',
                'confidence' => 1
            ],
            [
                'question' => 'có nhổ răng sữa không',
                'answer' => 'Có, chúng tôi có dịch vụ nhổ răng sữa nhẹ nhàng, không đau cho trẻ em.',
                'confidence' => 1
            ],

            // Dịch vụ khẩn cấp
            [
                'question' => 'có khám ngoài giờ không',
                'answer' => 'Có, chúng tôi có dịch vụ khám ngoài giờ cho các trường hợp khẩn cấp. Vui lòng gọi hotline để đặt lịch.',
                'confidence' => 1
            ],
            [
                'question' => 'có cấp cứu răng không',
                'answer' => 'Có, chúng tôi có dịch vụ cấp cứu răng 24/7 cho các trường hợp đau răng cấp tính, gãy răng, chấn thương răng.',
                'confidence' => 1
            ],
            [
                'question' => 'đau răng khẩn cấp phải làm sao',
                'answer' => 'Khi đau răng khẩn cấp, hãy gọi ngay hotline của chúng tôi. Bác sĩ sẽ tư vấn và sắp xếp lịch khám ngay lập tức.',
                'confidence' => 1
            ],
            [
                'question' => 'gãy răng phải làm sao',
                'answer' => 'Khi bị gãy răng, hãy giữ lại mảnh răng gãy (nếu có), rửa sạch bằng nước muối và đến phòng khám ngay. Gọi hotline để được hướng dẫn thêm.',
                'confidence' => 1
            ],
            [
                'question' => 'chảy máu chân răng phải làm sao',
                'answer' => 'Khi bị chảy máu chân răng, hãy cắn chặt miếng gạc sạch vào vị trí chảy máu và đến phòng khám ngay. Gọi hotline nếu cần hỗ trợ khẩn cấp.',
                'confidence' => 1
            ],

            // Dịch vụ bổ sung
            [
                'question' => 'có dịch vụ tư vấn online không',
                'answer' => 'Có, chúng tôi có dịch vụ tư vấn online qua chat, video call. Hoàn toàn miễn phí.',
                'confidence' => 1
            ],
            [
                'question' => 'có gửi kết quả qua email không',
                'answer' => 'Có, chúng tôi có thể gửi kết quả khám, phim X-quang qua email theo yêu cầu của khách hàng.',
                'confidence' => 1
            ],
            [
                'question' => 'có dịch vụ đón khách không',
                'answer' => 'Có, chúng tôi có dịch vụ đón khách tại sân bay hoặc khách sạn cho khách hàng ở xa.',
                'confidence' => 1
            ],
            [
                'question' => 'có phòng chờ cho người nhà không',
                'answer' => 'Có, chúng tôi có phòng chờ rộng rãi, thoáng mát với đầy đủ tiện nghi cho người nhà.',
                'confidence' => 1
            ],
            [
                'question' => 'có wifi miễn phí không',
                'answer' => 'Có, chúng tôi cung cấp wifi miễn phí tốc độ cao cho khách hàng trong thời gian chờ đợi.',
                'confidence' => 1
            ],

            // Chính sách và quy định
            [
                'question' => 'có chính sách hoàn tiền không',
                'answer' => 'Có, chúng tôi có chính sách hoàn tiền trong trường hợp dịch vụ không đạt yêu cầu hoặc khách hàng không hài lòng.',
                'confidence' => 1
            ],
            [
                'question' => 'có bảo mật thông tin không',
                'answer' => 'Có, chúng tôi cam kết bảo mật tuyệt đối thông tin cá nhân và hồ sơ bệnh án của khách hàng.',
                'confidence' => 1
            ],
            [
                'question' => 'có chính sách ưu đãi cho người cao tuổi không',
                'answer' => 'Có, người cao tuổi từ 60 tuổi trở lên được giảm 10% cho tất cả các dịch vụ.',
                'confidence' => 1
            ],
            [
                'question' => 'có chính sách ưu đãi cho học sinh sinh viên không',
                'answer' => 'Có, học sinh sinh viên được giảm 15% cho các dịch vụ nha khoa.',
                'confidence' => 1
            ],
            [
                'question' => 'có chính sách bảo hành không',
                'answer' => 'Có, chúng tôi có chính sách bảo hành cho tất cả các dịch vụ, thời gian bảo hành tùy thuộc vào từng dịch vụ.',
                'confidence' => 1
            ],

            // Dịch vụ đặc biệt
            [
                'question' => 'có dịch vụ nha khoa thẩm mỹ không',
                'answer' => 'Có, chúng tôi cung cấp các dịch vụ nha khoa thẩm mỹ: tẩy trắng răng, dán sứ veneer, chỉnh nha thẩm mỹ.',
                'confidence' => 1
            ],
            [
                'question' => 'có dịch vụ nha khoa cho người khuyết tật không',
                'answer' => 'Có, chúng tôi có phòng khám chuyên biệt và đội ngũ bác sĩ được đào tạo để phục vụ người khuyết tật.',
                'confidence' => 1
            ],
            [
                'question' => 'có dịch vụ nha khoa cho bà bầu không',
                'answer' => 'Có, chúng tôi có dịch vụ nha khoa an toàn cho bà bầu, sử dụng thuốc tê và vật liệu an toàn.',
                'confidence' => 1
            ],
            [
                'question' => 'có dịch vụ nha khoa cho người nước ngoài không',
                'answer' => 'Có, chúng tôi có đội ngũ bác sĩ nói tiếng Anh và các ngôn ngữ khác để phục vụ khách hàng nước ngoài.',
                'confidence' => 1
            ],
            [
                'question' => 'có dịch vụ nha khoa tại nhà không',
                'answer' => 'Có, chúng tôi cung cấp dịch vụ nha khoa tại nhà cho các trường hợp đặc biệt, người già, người khuyết tật.',
                'confidence' => 1
            ]
        ];

        foreach ($qaPairs as $pair) {
            BotKnowledge::create($pair);
        }
    }
}
