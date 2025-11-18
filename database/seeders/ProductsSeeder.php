<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductsSeeder extends Seeder
{
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // Xoá toàn bộ dữ liệu bảng products trước khi thêm lại
        DB::table('products')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $usedSlugs = [];

        $makeSlug = function (string $name) use (&$usedSlugs) {
            $base = Str::slug($name);
            $slug = $base;
            $i = 2;
            while (in_array($slug, $usedSlugs, true)) {
                $slug = $base.'-'.$i;
                $i++;
            }
            $usedSlugs[] = $slug;
            return $slug;
        };

        // ---------Casio nữ-----------------
        $category = Category::where('name', 'Nữ')->first();
        $brand  = Brand::where('name', 'Casio')->first();

            if (!$category || !$brand) {
                $this->command->error('Không tìm thấy categories "Nữ" hoặc brands "Casio"!');
                return;
            }

            $products = [
                ['Casio LTP-1274D-7ADF', 'Casio1.avif', 1242000, 'Đồng hồ Casio nữ 22mm LTP-1274D-7ADF có thiết kế hiện đại, sang trọng với mặt số tròn, kim chỉ và vạch số thanh mãnh nổi bật trên nền số màu trắng trang nhã, dây đeo kim loại màu bạc thời trang thanh lịch.'],
                ['Casio W-218HC-4A2VDF', 'Casio2.avif', 916000, 'Mẫu Casio W-218HC-4A2VDF phiên bản mới mặt số vuông điện tử hiện thị đa chức năng giúp người đeo dễ quan sát, thiết kế dây vỏ nhựa tạo nên vẻ năng động.'],
                ['Casio LTP-V007L-7E1UDF', 'Casio3.avif', 896000, 'Casio LTP-V007L-7E1UDF phong cách cổ điển với form vỏ hình chữ nhật mạ crom sáng bóng sang trọng. Dây đeo da khóa chốt tiêu chuẩn tăng thêm phần thanh lịch, kết hợp bộ máy quartz Nhật Bản chính xác ổn định theo thời gian.'],
                ['Casio LTP-E157M-7ADF', 'Casio4.avif', 2668000, 'Casio nữ 32mm LTP-E157M-7ADF dây đeo thép lưới ánh bạc cùng mặt số sunburst đơn giản. Phù hợp cho nhu cầu đeo đi làm, đi chơi hay lễ tiệc.'],
                ['Casio LTP-V005D-4B2UDF', 'Casio5.avif', 961000, 'Mẫu Casio LTP-V005D-4B2UDF thiết kế đơn giản trẻ trung 3 kim trên nền mặt số size 28mm được phối tone màu hồng, cùng với chi tiết các cọc vạch số tạo nét thanh mảnh nữ tính.'],
                ['Casio LTP-V002GL-7B2UDF', 'Casio6.avif', 9610000, 'Mẫu Casio LTP-V002GL-7B2UDF phiên bản mạ vàng sang trọng trên chi tiết vỏ máy, thiết kế trẻ trung thanh lịch với nền mặt số size 25mm kiểu dáng 3 kim phối cùng bộ dây da tạo hình vân.'],
                ['Casio LTP-V005D-2B3UDF', 'Casio7.avif', 961000, 'Mẫu Casio LTP-V005D-2B3UDF thiết kế giản dị 3 kim cùng chi tiết vạch số tạo nét thanh mảnh đầy nữ tính, máy pin phần vỏ kim loại mạ bạc kiểu dáng mỏng chỉ dày 7mm.'],
                ['Casio LTP-1129A-7ARDF', 'Casio8.avif', 1144000, 'Đồng hồ Casio LTP-1129A-7ARDF có mặt số tròn nhỏ nhắn, kim chỉ và vạch số thanh mãnh nổi bật trên nền số màu xám, dây đeo kim loại màu bạc đem lại thời trang quyến rũ.'],
                ['Casio LTP-1183A-7ADF', 'Casio9.avif', 1352000, 'Đồng hồ nữ Casio LTP-1183A-7ADF thanh lịch tinh tế, mặt đồng hồ có nền trắng cùng chữ số lớn, kiểu dáng 3 kim đơn giản cùng 1 lịch ngày.'],
                ['Casio LWS-1200H-7A2VDF', 'Casio10.avif', 1277000, 'Mẫu Casio LWS-1200H-7A2VDF dây vỏ nhựa phiên bản tone màu trắng thời trang, nền mặt số tròn điện tử hiện thị đa chức năng với vẻ ngoài năng động.'],
                ['Casio LTP-V009D-4EUDF', 'Casio11.avif', 1277000, 'Mẫu Casio LTP-V009D-4EUDF phiên bản mặt số hình chữ nhật phối tone màu hồng nhạt thời trang, cùng với thiết kế đơn giản chức năng 3 kim.'],
                ['Casio LTP-V007L-9BUDF', 'Casio12.avif', 896000, 'Mẫu Casio LTP-V007L-9BUDF thiết kế đơn giản chức năng 3 kim, phiên bản mặt số nhỏ chữ nhật hoài cổ kết hợp nền cọc số la mã phù hợp cho các phái đẹp có phần cổ tay nhỏ.'],
                ['Casio LW-204-4ADF', 'Casio13.avif', 1168000, 'Mẫu Casio LW-204-4ADF mặt số điện tử với nhiều chức năng mang lại nhiều tiện ích cho người dùng, phiên bản dây da trơn phối tone màu hồng nhạt thời trang.'],
                ['Casio LW-204-1ADF ', 'Casio14.avif', 1168000, 'Mẫu Casio LW-204-1ADF mặt số điện tử với nhiều chức năng mang lại nhiều tiện ích cho người dùng, phiên bản dây da trơn phối tone màu xanh thời trang.'],
                ['Casio LTP-VT02BL-2AUDF', 'Casio15.avif', 1490000, 'Mẫu Casio LTP-VT02BL-2AUDF dây da phối tone màu xanh phiên bản da trơn trẻ trung cùng với lối thiết kế đơn giản chức năng 3 kim trên nền mặt số kích thước 30mm.'],
                ['Casio LTP-V006L-4BUDF', 'Casio16.avif', 1005000, 'Mẫu Casio LTP-V006L-4BUDF mặt số tròn nhỏ nữ tính size 25mm thiết kế đơn giản trẻ trung chức năng 3 kim, phối cùng bộ dây da phiên bản da trơn phối tone màu hồng.'],
                ['Casio LTP-V005GL-9BUDF', 'Casio17.avif', 896000, 'Mẫu Casio LTP-V005GL-9BUDF phong cách cổ điển, trẻ trung với vỏ máy mạ vàng sang trọng. Sử dụng máy quartz Nhật Bản cho khả năng vận hành ổn định và chính xác cao.'],
                ['Casio SHE-4052PG-4AUDF', 'Casio18.avif', 4899000, 'Mẫu Casio SHE-4052PG-4AUDF phiên bản đính pha lê sang trọng trên phần vỏ viền đồng hồ, cọc vạch số tạo hình mỏng trên mặt số vàng hồng xà cừ thời trang.'],
                ['Casio LTP-1335D-4AVDF', 'Casio19.avif', 1773000, 'Mẫu Casio LTP-1335D-4AVDF kiểu dáng đơn giản 3 kim cùng nền cọc số tạo hình mỏng trẻ trung trên mặt số size 30mm, nổi bật với phần vỏ máy pin chỉ 8mm.'],
                ['Casio LTP-V300L-7A2UDF', 'Casio20.avif', 1893000, 'Mẫu Casio LTP-V300L-7A2UDF phiên bản mặt số trắng size 33mm cùng các chức năng lịch tạo nên kiểu dáng 6 kim thời trang cho phái đẹp phối cùng mẫu dây da trơn.'],
                ['Casio LRW-200H-4E3VDF', 'Casio21.avif', 1015000, 'Mẫu Casio LRW-200H-4E3VDF thiết kế dây vỏ nhựa tone màu trắng năng động, mặt số đơn giản size 34mm kiểu dáng 3 kim 1 lịch.'],
                ['Casio LTP-1183Q-7ADF', 'Casio22.avif', 1242000, 'Đồng hồ nữ Casio LTP-1183Q-7ADF có vỏ kim loại mạ vàng tinh tế bao quanh nền số màu trắng trang nhã. Sử dụng dây đeo da họa tiết vân bắt mắt thanh lịch. Kết hợp máy quartz siêu bền, độ chính xác cao.'],
                ['Casio LTP-1274G-7ADF', 'Casio23.avif', 1689000, 'Đồng hồ nữ Casio LTP-1274G-7ADF có vỏ kim loại bằng thép không gỉ được mạ vàng sang trọng, kim chỉ và vạch số được làm thanh mảnh nhẹ nhàng.'],
                ['Casio B640WC-5ADF', 'Casio24.avif', 2252000, 'Casio B640WC-5ADF là phiên bản dùng được cho cả nam lẫn nữ nhờ đặc trưng riêng dòng đồng hồ điện tử. Với ưu điểm máy quartz, nhiều tiện ích, phối màu vàng hồng trẻ trung đúng xu hướng đã giúp thiết kế chinh phục hàng triệu bạn trẻ đam mê thời trang – phong cách.'],
            ];
            foreach ($products as [$name, $image, $price, $description]) {
            DB::table('products')->insert([
                'name' => $name,
                'slug' => $makeSlug($name),
                'description' => $description,
                'price' => $price,
                'image' => $image,
                'images' => json_encode([$image, str_replace('.', '_2.', $image), str_replace('.', '_3.', $image), str_replace('.', '_4.', $image)]),
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'warranty_months' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }


        // -----------Rolex nữ----------
        $category = Category::where('name', 'Nữ')->first();
        $brand  = Brand::where('name', 'Rolex')->first();

        if (!$category || !$brand) {
        $this->command->error('Không tìm thấy categories "Nữ" hoặc brands "Rolex"!');
        return;
        }

        $products = [
            ['Rolex Lady-Datejust 28', 'Rolex1.jpg', 265000000, 'Đồng hồ Rolex Lady-Datejust 28 thiết kế thanh lịch với viền khía và dây Jubilee sang trọng.'],
            ['Rolex Oyster Perpetual 31', 'Rolex2.jpg', 195000000, 'Mẫu Rolex Oyster Perpetual 31 mang lại sự trẻ trung và tinh tế với mặt số bạc tinh khiết.'],
            ['Rolex Datejust 31 Rose Gold', 'Rolex3.jpg', 315000000, 'Datejust 31 vàng hồng phối dây Oyster tôn lên vẻ nữ tính và quyền lực.'],
            ['Rolex Pearlmaster 34', 'Rolex4.jpg', 680000000, 'Chiếc Pearlmaster 34 đính kim cương cao cấp, đỉnh cao của nghệ thuật chế tác Rolex.'],
            ['Rolex Lady-Datejust 31 Dark Rhodium', 'Rolex5.jpg', 238000000, 'Thiết kế cổ điển với mặt số xám đậm, Lady-Datejust 31 mang lại vẻ đẹp vượt thời gian.'],
            ['Rolex Datejust 31 Chocolate Dial', 'Rolex6.jpg', 325000000, 'Mặt số màu chocolate độc đáo kết hợp với dây Oyster nữ tính và sang trọng.'],
            ['Rolex Lady-Datejust 28 Champagne Dial', 'Rolex7.jpg', 255000000, 'Tông vàng champagne quyến rũ tạo điểm nhấn nổi bật cho phong cách của bạn.'],
            ['Rolex Oyster Perpetual 34 Silver Dial', 'Rolex8.jpg', 180000000, 'Phiên bản mặt số bạc đơn giản nhưng đầy tinh tế, phù hợp với mọi dịp.'],
            ['Rolex Datejust 36 Two-Tone', 'Rolex9.jpg', 298000000, 'Sự kết hợp giữa thép và vàng tạo nên chiếc Rolex cổ điển pha nét hiện đại.'],
            ['Rolex Lady-Datejust 31 Blue Dial', 'Rolex10.jpg', 245000000, 'Thiết kế mặt số xanh navy mang phong cách mạnh mẽ nhưng vẫn nữ tính.'],
            ['Rolex Datejust 31 Green Olive', 'Rolex11.jpg', 339000000, 'Mặt số xanh olive độc đáo tạo nên điểm nhấn khác biệt và đẳng cấp.'],
            ['Rolex Pearlmaster 39 Diamond', 'Rolex12.jpg', 760000000, 'Đồng hồ đính kim cương toàn bộ mang đậm phong cách hoàng gia và quý phái.'],
            ['Rolex Lady-Datejust 28 Everose Gold', 'Rolex13.jpg', 289000000, 'Thiết kế vàng Everose tôn vinh làn da và nét đẹp sang trọng cho phái nữ.'],
            ['Rolex Datejust 31 Sundust Dial', 'Rolex14.jpg', 279000000, 'Mặt số ánh hồng sunburst nhẹ nhàng cho những ai yêu sự dịu dàng và tinh tế.'],
            ['Rolex Oyster Perpetual 31 Turquoise Blue', 'Rolex15.jpg', 199000000, 'Màu xanh ngọc nổi bật kết hợp dây thép Oyster mạnh mẽ, trẻ trung.'],
            ['Rolex Datejust 31 Floral Motif', 'Rolex16.jpg', 348000000, 'Họa tiết hoa độc đáo dành riêng cho quý cô yêu vẻ đẹp nhẹ nhàng, nghệ thuật.'],
            ['Rolex Lady-Datejust 28 Pink Dial', 'Rolex17.jpg', 259000000, 'Mặt số hồng dịu dàng phù hợp với mọi cô gái yêu thích sự thanh lịch.'],
            ['Rolex Datejust 31 Roman Numerals', 'Rolex18.jpg', 305000000, 'Chữ số La Mã truyền thống tạo nên điểm nhấn cổ điển cho chiếc Datejust.'],
            ['Rolex Oyster Perpetual 36 Coral Red', 'Rolex19.jpg', 215000000, 'Màu đỏ san hô nổi bật mang đậm tính cá nhân và gu thời trang riêng biệt.'],
            ['Rolex Datejust 31 Silver Roman', 'Rolex20.jpg', 270000000, 'Tông bạc và chỉ số La Mã mang đến sự tinh tế, dễ phối cùng nhiều trang phục.'],
            ['Rolex Lady-Datejust 28 Jubilee', 'Rolex21.jpg', 250000000, 'Dây đeo Jubilee nổi tiếng với sự thoải mái và vẻ ngoài cực kỳ sang trọng.'],
            ['Rolex Datejust 36 Mother of Pearl', 'Rolex22.jpg', 388000000, 'Mặt số xà cừ tự nhiên phản chiếu ánh sáng tạo nên hiệu ứng độc đáo.'],
            ['Rolex Oyster Perpetual 31 Candy Pink', 'Rolex23.jpg', 195000000, 'Màu hồng kẹo ngọt dễ thương, phù hợp với phong cách trẻ trung.'],
            ['Rolex Datejust 31 Gold Diamond', 'Rolex24.jpg', 430000000, 'Vàng nguyên khối kết hợp kim cương thiên nhiên mang đến đỉnh cao sang trọng.'],
        ];
        foreach ($products as [$name, $image, $price, $description]) {
            DB::table('products')->insert([
                'name' => $name,
                'slug' => $makeSlug($name),
                'description' => $description,
                'price' => $price,
                'image' => $image,
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'warranty_months' => 12,
                'created_at' => now(),
                'updated_at' => now(),

            ]);
        }
        // -----------Citizen nữ----------
        $category = Category::where('name', 'Nữ')->first();
        $brand  = Brand::where('name', 'Citizen')->first();

        if (!$category || !$brand) {
        $this->command->error('Không tìm thấy categories "Nữ" hoặc brands "Citizen"!');
        return;
        }
        $products = [
            ['Citizen L EW5620-55N', 'Citizen1.avif', 15585000, 'Citizen L EW5620-55N thiết kế khung vỏ màu trắng bạc sang trọng, kết hợp cùng mặt số khảm xà cừ thiên nhiên độc bản. Thuộc bộ sưu tập Citizen L – tôn vinh vẻ đẹp sang trọng và nữ tính của phụ nữ.'],
            ['Citizen L EM1140-80D', 'Citizen2.avif', 12085000, 'Citizen L EM1140-80D gây ấn tượng với thiết kế vượt thời gian và công nghệ tiên tiến. Bộ máy năng lượng ánh sáng có thời gian sử dụng lên đến 10 năm mà không phải thay pin thường xuyên.'],
            ['Citizen FE1244-72A – Nữ – Eco-Drive', 'Citizen3.avif', 7685000, 'Citizen Eco-Drive FE1244-72A mặt màu vàng nhẹ nhàng, độ mỏng chỉ dày 8,3mm. Dây đeo demi phối màu bạc và vàng cao cấp. Bộ máy năng lượng ánh sáng cho thời gian sử dụng lên đến 10 năm không phải thay pin, dự trữ năng lượng lên đến 8 tháng.'],
            ['Citizen FE1241-71Z – Nữ – Eco-Drive', 'Citizen4.avif', 6585000, 'Citizen Eco-Drive FE1241-71Z mặt màu hồng pastel nữ tính, độ mỏng chỉ dày 8,3mm. Bộ máy năng lượng ánh sáng cho thời gian sử dụng lên đến 10 năm không phải thay pin. Dây đeo kim loại cao cấp, dự trữ năng lượng lên đến 8 tháng.'],
            ['Citizen L EM1060-87N – Nữ – Eco-Drive', 'Citizen5.avif', 16685000, 'Đồng hồ Citizen L EM1060-87N sở hữu hữu nét đẹp thanh lịch, dây đeo kim loại bền bỉ. Mặt kính Sapphire chống trầy, khảm xà cừ thiên nhiên. Bộ kim và núm vặn màu xanh dương độc đáo.'],
            ['Citizen L EW5624-54Y – Nữ – Eco-Drive', 'Citizen6.avif', 18185000, 'Citizen L EW5624-54Y thiết kế khung vỏ phối 2 màu hồng và bạc sang trọng, kết hợp cùng mặt số khảm xà cừ thiên nhiên độc bản. Thuộc bộ sưu tập Citizen L – tôn vinh vẻ đẹp sang trọng và nữ tính của phụ nữ.'],
            ['Citizen L EW5602-81D', 'Citizen7.avif', 14485000, 'Citizen L EW5602-81D thiết kế theo kiểu dáng Tank huyền thoại, khung viền mạ vàng PVD sang trọng. Khảm xà cừ thiên nhiên, họa tiết độc bản. Công nghệ Eco-Drive trứ danh, dự trữ năng lượng tới 7 tháng.'],
            ['Citizen L EM1123-89D', 'Citizen8.avif', 32785000, 'Đồng hồ Citizen L EM1123-89D trang bị 39 viên kim cương tự nhiên 1:1mm đính xung quanh khung viền, thể hiện đẳng cấp và vẻ đẹp thời thượng. Bộ máy Eco-Drive trứ danh, dự trữ năng lượng lên đến 7 tháng.'],
            ['Citizen L EW5622-09P', 'Citizen9.avif', 14285000, 'Đồng hồ Citizen L EW5622-09P sử dụng bộ máy Eco-Drive trứ danh, dự trữ năng lượng tới 7 tháng. Thiết kế khung viền mạ vàng cùng dây da nâu cổ điển, thuộc bộ sưu tập Citizen L – tôn vinh vẻ đẹp sang trọng và nữ tính của phụ nữ.'],
            ['Citizen EM0493-85P', 'Citizen10.avif', 7085000, 'Citizen Eco-Drive EM0493-85P sang trọng với thiết kế đính 2 viên pha lê tại vị trí 12 giờ trên nền mặt số vuông được phối tone màu trắng ngà. Dây đeo thép lưới thanh lịch, uyển chuyển trên cổ tay.'],
            ['Citizen L EM1153-88A', 'Citizen11.avif', 9885000, 'Citizen L EM1153-88A gây ấn tượng với thiết kế vượt thời gian và công nghệ tiên tiến. Bộ máy năng lượng ánh sáng có thời gian sử dụng lên đến 10 năm mà không phải thay pin thường xuyên.'],
            ['Citizen EM1070-83D', 'Citizen12.avif', 13685000, 'Đồng hồ Citizen EM1070-83D sở hữu hữu nét đẹp thanh lịch, dây đeo kim loại bền bỉ. Mặt kính Sapphire chống trầy, khảm xà cừ thiên nhiên. Bộ kim và núm vặn màu xanh dương độc đáo.'],
            ['Citizen FE1241-71X ', 'Citizen13.avif', 6585000, 'Citizen Eco-Drive FE1241-71X mặt màu xanh pastel nữ tính, độ mỏng chỉ dày 8,3mm. Bộ máy năng lượng ánh sáng cho thời gian sử dụng lên đến 10 năm không phải thay pin. Dây đeo kim loại cao cấp, dự trữ năng lượng lên đến 8 tháng.'],
            ['Citizen EM1074-82D', 'Citizen14.avif', 14785000, 'Đồng hồ Citizen EM1074-82D sở hữu hữu nét đẹp thanh lịch, dây đeo kim loại Đờ mi kết hợp tone màu kim loại và vàng hồng. Mặt kính Sapphire chống trầy, khảm xà cừ thiên nhiên. Bộ kim và núm vặn màu xanh dương độc đáo.'],
            ['Citizen EM0801-85N', 'Citizen15.avif', 9315000, 'Citizen Eco-Drive EM0801-85N khảm xà cừ thiên nhiên, với tone màu xanh dương pha lẫn xanh lá nổi bật. Độ mỏng chỉ 7,2mm, kích thước mặt số . Bộ máy năng lượng ánh sáng cho thời gian sử dụng lên đến 10 năm không phải thay pin. Dây đeo kim loại cao cấp, dự trữ năng lượng lên đến 6 tháng.'],
            ['Citizen EM1100-84D', 'Citizen16.avif', 13685000, 'Citizen Eco-Drive EM1100-84D khảm xà cừ cao cấp, độ mỏng chỉ dày 8mm. Bộ máy năng lượng ánh sáng cho thời gian sử dụng lên đến 10 năm không phải thay pin. Dây đeo kim loại cao cấp, dự trữ năng lượng lên đến 6 tháng.'],
            ['Citizen EM1103-86Y', 'Citizen17.avif', 15685000, 'Citizen Eco-Drive EM1103-86Y khảm xà cừ cao cấp, độ mỏng chỉ dày 8mm. Bộ máy năng lượng ánh sáng cho thời gian sử dụng lên đến 10 năm không phải thay pin. Dây kim loại mạ vàng PVD chính hãng, dự trữ năng lượng lên đến 6 tháng.'],
            ['Citizen EM0419-11D', 'Citizen18.avif', 8685000, 'Citizen Eco-Drive EM0419-11D thiết kế phần vỏ mạ vàng hồng PVD, độ mỏng chỉ dày 7mm. Bộ máy năng lượng ánh sáng cho thời gian sử dụng lên đến 10 năm không phải thay pin. Dây da vân cá sấu 2 lớp chống thấm chính hãng, dự trữ năng lượng lên đến 8 tháng.'],
            ['Citizen EL3106-59D', 'Citizen19.avif', 5885000, 'Đồng hồ nữ Citizen Quartz EL3106-59D là sự kết hợp giữa vẻ đẹp thanh lịch và độ chính xác của bộ máy Nhật Bản. Mặt số khảm xà cừ óng ánh phối cùng tông màu mạ vàng PVD sang trọng.'],
            ['Citizen L Eco-Drive EM1156-80X', 'Citizen20.avif', 9185000, 'Citizen L EM1156-80X gây ấn tượng với thiết kế vượt thời gian và công nghệ tiên tiến. Bộ máy năng lượng ánh sáng có thời gian sử dụng lên đến 10 năm mà không phải thay pin thường xuyên.'],
            ['Citizen L Eco-Drive EM1150-86D', 'Citizen21.avif', 8885000, 'Đồng hồ nữ Citizen L Eco-Drive EM1150-86D với kích thước 32.5mm, mặt số khảm xà cừ óng ánh đặc biệt. Bộ máy năng lượng ánh sáng có thời gian sử dụng lên đến 10 năm mà không phải thay pin thường xuyên.'],
            ['Citizen Eco-Drive EM0506-77A', 'Citizen22.avif', 7585000, 'Citizen Eco-Drive EM0506-77A phiên bản dây đeo tone màu vàng demi, nền mặt số xà cừ với họa tiết Guilloche thẩm mỹ, sử dụng năng lượng mặt trời có tuổi thọ dài giúp tiết kiệm chi phí, cực kỳ trang nhã và thanh lịch.'],
            ['Citizen L EG7112-59D', 'Citizen23.avif', 14385000, 'Citizen L EG7112-59D khảm xà cừ thiên nhiên, với tone màu trắng chuyển sắc nhẹ nhàng. Độ mỏng chỉ 6,5mm, kích thước dáng tank 14,8mm x 28,3mm. Bộ máy dự trữ năng lượng ánh sáng cho thời gian sử dụng lên đến 10 năm không cần thay pin. Dây đeo và khung vỏ mạ vàng PVD cao cấp, dự trữ năng lượng lên đến 8 tháng.'],
            ['Citizen L EG7114-53D', 'Citizen24.avif', 13585000, 'Citizen L EG7114-53D khảm xà cừ thiên nhiên, với tone màu xanh nhạt dịu dàng. Độ mỏng chỉ 6,5mm, kích thước dáng tank 14,8mm x 28,3mm. Bộ máy dự trữ năng lượng ánh sáng cho thời gian sử dụng lên đến 10 năm không cần thay pin. Dây đeo demi cao cấp, dự trữ năng lượng lên đến 8 tháng.'],
        ];
        foreach ($products as [$name, $image, $price, $description]) {
            DB::table('products')->insert([
                'name' => $name,
                'slug' => $makeSlug($name),
                'description' => $description,
                'price' => $price,
                'image' => $image,
                'images' => json_encode([$image, str_replace('.', '_2.', $image), str_replace('.', '_3.', $image)]),
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'warranty_months' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // -----------Rado nữ----------
        $category = Category::where('name', 'Nữ')->first();
        $brand = Brand::where('name', 'Rado')->first();

        if (!$category || !$brand) {
            $this->command->error('Không tìm thấy categories "Nữ" hoặc brands "Rado"!');
            return;
        }

        $products = [
            ['Rado Centrix Open Heart R30248012', 'Rado1.avif', 63030000, 'Đồng hồ nữ Rado Centrix Open Heart R30248012 tông màu vàng hồng quý phái, mặt số lộ cơ táo bạo. Bộ máy tự động Thụy Sỹ trữ cót 80 giờ.'],
            ['Rado R48871714', 'Rado2.avif', 33750000, 'Mẫu Rado R48871714 mặt số size 28mm phối tone màu đen tạo điểm nhấn nổi bật cho thiết kế sang trọng 4 viên kim cương được đính tại 4 vị trí 3,6,9 và vị trí múi 12 giờ.'],
            ['Rado Florence R48873734', 'Rado3.avif', 33750000, 'Mẫu Rado R48873734 phiên bản vàng hồng thời trang cho phái đẹp, mặt số size 28mm tạo nên điểm nhấn nổi bật đính 4 kim cương nổi bật trước mặt kính Sapphire.'],
            ['Rado DiaStar Original Automatic', 'Rado4.jpg', 33700000, 'Dây lưới bạc nhẹ tay, thiết kế thoáng khí, hiện đại.'],
            ['Rado DiaStar Original 60-Year Edition', 'Rado5.jpg', 11700000, 'Thiết kế kỷ niệm 60 năm, mặt số xám, 2 dây đeo cao cấp.'],
            ['Rado Centrix Automatic', 'Rado6.jpg', 46300000, 'Thiết kế lộ cơ lớn, đính kim cương, dây thép và ceramic sang trọng.'],
            ['Rado True Round Automatic Open Heart', 'Rado7.jpg', 18500000, 'Vỏ ceramic trắng bóng, mặt lộ cơ, phối viền vàng hồng.'],
            ['Rado True Square Automatic Open Heart', 'Rado8.jpg', 24900000, 'Vỏ vuông ceramic nguyên khối, thiết kế lộ cơ nghệ thuật.'],
            ['Rado Anatom Automatic', 'Rado9.jpg', 31200000, 'Kính sapphire vát, dây cao su đỏ, mặt số chuyển sắc độc đáo.'],
            ['Rado Captain Cook Over-Pole Limited Edition', 'Rado10.jpg', 40100000, 'Phiên bản giới hạn, máy lên cót tay, dây thép mạ vàng.'],
            ['Rado Florence Diamonds', 'Rado11.jpg', 12700000, 'Thiết kế thanh lịch, mặt đen đính kim cương, bộ máy quartz chính xác.'],
            ['Rado DiaStar X Tej Chauhan Special Edition', 'Rado12.jpg', 35800000, 'Thiết kế neon phá cách, dây cao su, máy tự động R764.'],
            ['Rado DiaStar Original Open Heart', 'Rado13.jpg', 19400000, 'Thiết kế lộ cơ, mặt guilloche, máy tự động dự trữ 80 giờ.'],
            ['Rado DiaStar Original Automatic', 'Rado14.jpg', 10100000, 'Viền Ceramos™ vàng, mặt đính zirconia xanh, dây thép mạ vàng.'],
            ['Rado DiaStar Original', 'Rado15.jpg', 27400000, 'Mặt xanh ngọc chải xước, kim phủ dạ quang, dây thép không gỉ.'],
            ['Rado Minimal Gold-Tone', 'Rado16.jpg', 49900000, 'Mặt số vàng chải xước, dây thép phối vàng, sang trọng.'],
            ['Rado LaCoupole Diamonds', 'Rado17.jpg', 13500000, 'Vỏ ceramic đen bóng, mặt khắc sóng, đính kim cương.'],
            ['Rado Centrix Diamonds', 'Rado18.jpg', 38800000, 'Mặt sơn mài đen đính kim cương, dây thép và ceramic.'],
            ['Rado True Square Skeleton', 'Rado19.jpg', 22800000, 'Thiết kế skeleton lộ cơ, dây cao su, vỏ ceramic đen nhám.'],
            ['Rado True Square Thinline', 'Rado20.jpg', 30900000, 'Thiết kế xanh bóng thanh lịch, máy quartz chính xác.'],
            ['Rado True Square Formafantasma', 'Rado21.jpg', 19200000, 'Thiết kế tối giản đặc biệt, phong cách vượt thời gian.'],
            ['Rado True Square Undigital', 'Rado22.jpg', 43300000, 'Lấy cảm hứng từ đồng hồ số, thiết kế hiện đại táo bạo.'],
            ['Rado Anatom Automatic', 'Rado23.jpg', 25300000, 'Dây ceramic phối thép mạ vàng, mặt đen sọc, máy R766.'],
            ['Rado Captain Cook x Marina Heartbeat', 'Rado24.jpg', 14700000, 'Phiên bản đặc biệt, mặt đính đá cầu vồng, dây đổi được.']
        ];

        foreach ($products as [$name, $image, $price, $description]) {
            DB::table('products')->insert([
                'name' => $name,
                'slug' => $makeSlug($name),
                'description' => $description,
                'price' => $price,
                'image' => $image,
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'warranty_months' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        // -----------Seiko nữ----------
        $category = Category::where('name', 'Nữ')->first();
        $brand = Brand::where('name', 'Seiko')->first();

        if (!$category || !$brand) {
        $this->command->error('Không tìm thấy categories "Nữ" hoặc brands "Seiko"!');
        return;
        }

        $products = [
            ['Seiko Presage Cocktail White Lady SRE010J1', 'Seiko1.avif', 18638000, 'Đồng hồ Seiko Presage Cocktail SRE010J1 dành cho quý cô thanh lịch với chất liệu đá pha lê lấp lánh, mặt số trang trí bằng những họa tiết độc đáo. Sử dụng bộ máy tự động Nhật Bản cao cấp.'],
            ['Seiko Presage Cocktail SRE009J1', 'Seiko2.avif', 17850000, 'Đồng hồ Seiko Presage Cocktail SRE009J1 dành cho quý cô thanh lịch với chất liệu kim cương thật, mặt số trang trí bằng những họa tiết độc đáo, mạ vàng hồng PVD. Sử dụng bộ máy tự động Nhật Bản cho khả năng hiển thị chính xác.'],
            ['Seiko Presage Cocktail SRE007J1', 'Seiko3.avif', 17063000, 'Đồng hồ Seiko Presage Cocktail Time Skydiving SRE007J1 dành cho nữ với màu mặt số xanh Ice Blue độc đáo. Chất liệu kim cương tự nhiên đính thành cọc số lấp lánh, xa hoa. Vận hành bởi bộ máy automatic sản xuất in-house chất lượng.'],
            ['Seiko SUR354P1', 'Seiko4.avif', 8000000, 'Mẫu Seiko SUR354P1 mặt số size 30mm với thiết kế kim chỉ cùng cọc vạch số được phủ dạ quang nổi bật lên vẻ thời trang sang trọng khi phối cùng mẫu dây đeo vàng demi.'],
            ['Seiko SUR332P1', 'Seiko5.avif', 9870000, 'Mẫu Seiko SUR332P1 kim chỉ xanh phối tone màu thời trang nổi bật trên nền mặt số trắng tròn nhỏ nữ tính size 29mm, phiên bản dây vỏ phối màu vàng hồng tạo nên vẻ ngoài thời trang sang trọng.'],
            ['Seiko SRWZ03P1', 'Seiko6.avif', 11820000, 'Mẫu Seiko SRWZ03P1 phiên bản nền cọc số la mã tạo hình mỏng cách điệu thời trang cho phái đẹp trên nền mặt số size 35mm được phối tone màu vàng nhạt.'],
            ['Seiko Lukia SUT387J1', 'Seiko7.avif', 14500000, 'Seiko SUT387J1 phiên bản Seiko Solar đặc biệt với bộ máy pin trang bị công nghệ Năng Lượng Ánh Sáng, kim chỉ đỏ tone màu thời trang nổi bật trên mặt số size 33mm.'],
            ['Seiko SXDH04P1', 'Seiko8.avif', 6420000, 'Mẫu Seiko SXDH04P1 thiết kế đơn giản 3 kim đầy sang trọng cùng chi tiết tiết kim chỉ vạch số mạ vàng nổi bật trên mặt số size 28mm, cùng với thiết kế mỏng trẻ trung phần vỏ máy pin chỉ 8mm.'],
            ['Seiko SKK886P1', 'Seiko9.avif', 11970000, 'Mẫu Seiko SKK886P1 thiết kế đính pha lê sang trọng trên nền mặt số trắng xà cừ tạo nên điểm nhấn nổi bật cho phái đẹp kết hợp với mẫu dây đeo demi mạ vàng.'],
            ['Seiko SRZ526P1', 'Seiko10.avif', 8400000, 'Mẫu Seiko SRZ526P1 vẻ ngoài sang trọng dành cho phái đẹp với phần dây vỏ kim loại mạ vàng nổi bật, phiên bản cọc số la mã được tạo nét thanh mảnh cùng với kim chỉ xanh tone màu thời trang.'],
            ['Seiko SRZ520P1', 'Seiko11.avif', 8060000, 'Phiên bản Seiko SRZ520P1 vẻ ngoài thời trang với thiết kế mỏng máy pin 7mm, sang trọng nổi bật dành cho phái đẹp với phần dây vỏ kim loại mạ vàng.'],
            ['Seiko SRWZ99P1', 'Seiko12.avif', 9610000, 'Mẫu Seiko SRWZ99P1 phiên bản nền cọc số la mã tạo nét cách điệu đầy nữ tính trên mặt số tone màu trắng kiểu dáng 6 kim kết hợp với phần dây vỏ kim loại mạ bạc thời trang cho phái đẹp.'],
            ['Seiko SUR233P1', 'Seiko13.avif', 5930000, 'Mẫu Seiko SUR233P1 mang đến một vẻ ngoài giản dị của một chiếc đồng hồ 3 kim đầy tinh tế và thanh lịch với thiết kế chi tiết vạch số tạo hình thanh mảnh, kết hợp cùng mẫu dây da tông màu đen.'],
            ['Seiko SUT022P1', 'Seiko14.avif', 7710000, 'Mẫu đồng hồ Seiko SUT022P1 nổi bật với mặt đồng hồ kiểu dáng tròn phần viền ngoài được mạ vàng tạo nên phụ kiện thời trang sang trọng, ấn tượng với đồng hồ sử dụng công nghệ hiện đại Seiko Solar (Năng Lượng Ánh Sáng).'],
            ['Seiko SRW818P1', 'Seiko15.avif', 11960000, 'Mẫu đồng hồ Seiko SRW818P1 thiết kế theo phong cách thời trang dành cho nữ, với chức năng 6 kim chỉ 1 lịch ngày cùng tính năng Chronograph đầy tiện ích cho người dùng, vỏ máy với viền vàng hồng.'],
            ['Seiko SRZ440P1', 'Seiko16.avif', 8880000, 'Mẫu đồng hồ Seiko SRZ440P1 thiết kế theo phong cách giản dị thời thượng với sự kết hợp giữa vỏ máy cùng dây đeo bằng thép không gỉ được mạ vàng sang trọng, nổi bật với kim chỉ xanh trẻ trung trên nền trắng.'],
            ['Seiko SRW831P1', 'Seiko17.avif', 10150000, 'Mẫu đồng hồ nữ Seiko SRW831P1 mặt đồng hồ tròn nhỏ nhắn nữ tính với chữ số thiết kế to với 3 tông màu đỏ xanh bạc nổi bật trẻ trung, kèm theo 3 ô phụ với tính năng Chronograph.'],
            ['Seiko SKY696P1', 'Seiko18.avif', 8320000, 'Đồng hồ Seiko SKY696P1 có mặt số tròn, viền màu đồng, kim chỉ và vạch số sắc nét nổi bật trên nền số màu nâu, dây đeo chất liệu da màu đen bóng đem đến vẻ quyến rũ, sang trọng cho phái nữ.'],
            ['Seiko SUT236P1', 'Seiko19.avif', 8290000, 'Đồng hồ Seiko SUT236P1 phiên bản Seiko Solar có vỏ và dây đeo kim loại bằng chất liệu thép không gỉ mạ vàng sáng bóng, kim chỉ và vạch số được dát mỏng tinh xảo nổi bật trên nền số màu vàng, đem lại phong cách sang trọng.'],
        ];

            foreach ($products as [$name, $image, $price, $description]) {
                DB::table('products')->insert([
                'name' => $name,
                'slug' => $makeSlug($name),
                'description' => $description,
                'price' => $price,
                'image' => $image,
                'images' => json_encode([$image, str_replace('.', '_2.', $image), str_replace('.', '_3.', $image),]),
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'warranty_months' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        //---------------------------Casio cặp--------------------------
        $category = Category::where('name', 'Cặp đôi')->first();
        $brand = Brand::where('name', 'Casio')->first();

        if (!$category || !$brand) {
            $this->command->error('Không tìm thấy categories "Cặp đôi" hoặc brands "Casio"!');
            return;
        }

        $products = [
            ['Casio Đôi MTP-1335D-7AVDF – LTP-1335D-7AVDF', 'CasioCouple1.avif', 3546000, 'Mẫu Casio đôi thiết kế đơn giản chức năng 3 kim, nổi bật với khả năng chịu nước lên đến 5atm.'],
            ['Casio Đôi MTP-V300G-1AUDF – LTP-V300G-1AUDF', 'CasioCouple2.avif', 5168000, 'Mẫu Casio đôi mạ vàng sang trọng trên phần dây vỏ đồng hồ, thiết kế độc đáo với các ô lịch tạo nên kiểu dáng 6 kim trên nền mặt số.'],
            ['Casio Đôi MTP-1183G-7ADF – LTP-1183G-7ADF', 'CasioCouple3.avif', 3663000, 'Mẫu Casio đôi dây đeo vàng demi phiên bản thời trang cùng với thiết kế mỏng vỏ máy pin chỉ dày 8mm.'],
            ['Casio Đôi MTP-1314L-8AVDF – LTP-1314L-8AVDF', 'CasioCouple4.avif', 2930000, 'Mẫu Casio đôi dây da tone đen phiên bản da trơn thời trang, vỏ máy mạ bạc sang trọng với thiết kế mỏng chỉ 8mm.'],
            ['Casio Đôi MTP-1303L-7BVDF – LTP-1303L-7BVDF', 'CasioCouple5.avif', 2684000, 'Đồng hồ đôi Casio với phong cách giản dị, vỏ máy màu bạc sáng bóng nổi bật với chữ số đen trên nền trắng trang nhã, kết hợp cùng dây đeo bằng da đem lại phong cách cổ điển cho các cặp đôi.'],
        ];

        foreach ($products as [$name, $image, $price, $description]) {
            DB::table('products')->insert([
                'name' => $name,
                'slug' => $makeSlug($name),
                'description' => $description,
                'price' => $price,
                'image' => $image,
                'images' => json_encode([$image, str_replace('.', '_2.', $image), str_replace('.', '_3.', $image)]),
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'warranty_months' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }



//---------------------------Rolex cặp--------------------------
        $category = Category::where('name', 'Cặp đôi')->first();
        $brand = Brand::where('name', 'Rolex')->first();

        if (!$category || !$brand) {
            $this->command->error('Không tìm thấy categories "Cặp đôi" hoặc brands "Rolex"!');
            return;
        }

        $products = [
            ['Rolex Couple Oyster Perpetual', 'RolexCouple1.jpg', 755000000, 'Thiết kế tối giản, dây thép không gỉ, mặt số xanh lam cho nam và trắng cho nữ – biểu tượng cho sự đồng điệu.'],
            ['Rolex Datejust Silver Duo', 'RolexCouple2.jpg', 628000000, 'Cặp đôi dây Jubilee cổ điển, mặt số bạc sang trọng – phù hợp với cặp đôi yêu sự thanh lịch vượt thời gian.'],
            ['Rolex Everose Gold Harmony', 'RolexCouple3.webp', 845000000, 'Màu vàng hồng Everose đặc trưng, phối dây kim loại demi – dành cho những cặp đôi đam mê sự sang trọng.'],
            ['Rolex Two-Tone Datejust Pair', 'RolexCouple4.jpg', 699000000, 'Cặp đôi phối vàng và thép không gỉ, mặt số viền khía sang trọng, đồng điệu nhưng vẫn cá tính riêng.'],
            ['Rolex Elegant Blue & Pink Set', 'RolexCouple5.webp', 705000000, 'Mặt số xanh cho nam và hồng cho nữ, kết hợp dây Oyster truyền thống – thể hiện sự khác biệt hài hòa.'],
        ];


        foreach ($products as [$name, $image, $price, $description]) {
            DB::table('products')->insert([
                'name' => $name,
                'slug' => $makeSlug($name),
                'description' => $description,
                'price' => $price,
                'image' => $image,
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'warranty_months' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }



//---------------------------Rado cặp--------------------------
        $category = Category::where('name', 'Cặp đôi')->first();
        $brand = Brand::where('name', 'Rado')->first();

        if (!$category || !$brand) {
            $this->command->error('Không tìm thấy categories "Cặp đôi" hoặc brands "Rado"!');
            return;
        }

        $products = [
            ['Rado Couple Centrix Black', 'RadoCouple1.webp', 47800000, 'Cặp đôi Centrix mặt đen sang trọng, dây ceramic – biểu tượng hiện đại và bền bỉ.'],
            ['Rado True Square Pair', 'RadoCouple2.jpg', 52400000, 'Thiết kế mặt vuông độc đáo với dây ceramic trắng, dành cho cặp đôi yêu sự phá cách tinh tế.'],
            ['Rado Florence Classic Duo', 'RadoCouple3.jpg', 45200000, 'Dòng Florence thanh lịch với mặt số tối giản, dây thép không gỉ, phù hợp phong cách trang nhã.'],
        ];


        foreach ($products as [$name, $image, $price, $description]) {
            DB::table('products')->insert([
                'name' => $name,
                'slug' => $makeSlug($name),
                'description' => $description,
                'price' => $price,
                'image' => $image,
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'warranty_months' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }




//---------------------------Citizen cặp--------------------------
        $category = Category::where('name', 'Cặp đôi')->first();
        $brand = Brand::where('name', 'Citizen')->first();

        if (!$category || !$brand) {
            $this->command->error('Không tìm thấy categories "Cặp đôi" hoặc brands "Citizen"!');
            return;
        }

        $products = [
            ['Đồng hồ đôi Citizen Eco-Drive BM7526-81A và EW2596-89A', 'Citizencap.webp', 8350000, 'Citizen Eco-Drive BM7526-81A và EW2596-89A có đườg kính 39.2mm - 29.2mm, mặt kính Sapphire, chất liệu vỏ và dây thép không gỉ.'],
            ];


        foreach ($products as [$name, $image, $price, $description]) {
            DB::table('products')->insert([
                'name' => $name,
                'slug' => $makeSlug($name),
                'description' => $description,
                'price' => $price,
                'image' => $image,
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'warranty_months' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }




//---------------------------Seiko cặp--------------------------
        $category = Category::where('name', 'Cặp đôi')->first();
        $brand = Brand::where('name', 'Seiko')->first();

        if (!$category || !$brand) {
            $this->command->error('Không tìm thấy categories "Cặp đôi" hoặc brands "Seiko"!');
            return;
        }

       $products = [
    ['Seiko Đôi – Automatic – Dây Da (SRPK75J1 – SRE014J1 )', 'Seikocap1.avif', 35283000, 'Mẫu Seiko đôi dây da phiên bản giới hạn Star Bar tái hiện hoàng hôn lãng mạn. Sử dụng bộ máy automatic bền bỉ do Seiko sản xuất cho giúp cho tình yêu đôi lứa thêm thăng hoa, bền chặt.'],
    ['Seiko Đôi – Solar (Năng Lượng Ánh Sáng) – Dây Da (SUP860P1 – SUP370P1)', 'Seikocap2.avif', 5020000, 'Mẫu Seiko Solar đôi nền cọc số học trò tạo hình cách điệu trẻ trung trên nền mặt số trắng, kiểu máy pin phiên bản Solar (Năng Lượng Ánh Sáng)'],
];

        foreach ($products as [$name, $image, $price, $description]) {
            DB::table('products')->insert([
                'name' => $name,
                'slug' => $makeSlug($name),
                'description' => $description,
                'price' => $price,
                'image' => $image,
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'warranty_months' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // ---------Casio nam-----------------
        $category = Category::where('name', 'Nam')->first();
        $brand  = Brand::where('name', 'Casio')->first();


        if (!$category || !$brand) {
            $this->command->error('Không tìm thấy categories "Nam" hoặc brands "Casio"!');
            return;
        }

        $products = [
            ['Casio Edifice EFV-550L-1AVUDF', 'Casionam1.avif', 3899000, 'Casio Edifice EFV-550L-1AVUDF mang đến cho phái mạnh vẻ ngoài lịch lãm nhưng cũng không kém phần trẻ trung đặc trưng thuộc dòng Edifice với kiểu dáng đồng hồ 6 kim đi kèm tính năng đo thời gian Chronograph.'],
            ['Casio MTP-V300L-1AUDF', 'Casionam2.avif', 1893000, 'Casio MTP-V300L-1AUDF thiết kế thể thao 6 kim, chức năng lịch ngày, lịch thứ và hiển thị giờ ngày đêm 24 giờ. Bộ kim phủ dạ quang cho phép đọc giờ trong bóng tối.'],
            ['Casio Edifice EFV-630L-1AVUDF', 'Casionam3.avif', 3899000, 'Casio Edifice EFV-630L-1AVUDF kiểu dáng thể thao năng động – Chức năng Chronograph, dạ quang, lịch ngày – Pin lên đến 3 năm với độ chính xác +/- 20 giây mỗi tháng.'],
            ['Casio MTP-1384D-1AVDF', 'Casionam4.avif', 2694000, 'Đồng hồ Casio MTP-1384D-1AVDF mặt số màu đen mạnh mẽ, kim chỉ và vạch chỉ giờ bằng số La Mã được làm mỏng tinh tế nổi bật trên nền số, dây đeo kim loại lịch lãm, đồng hồ chịu được độ sâu 10ATM phù hợp cho các hoạt động dưới nước trừ việc lặn.'],
            ['Casio EFR-526L-1AVUDF', 'Casionam5.avif', 3701000, 'Đồng hồ Casio Edifice EFR-526L-1AVUDF tạo ấn tượng nhờ tông màu đen chủ đạo của bộ dây đeo da chắc chắn và mặt số Chronograph thể thao. Sử dụng chất liệu chế tác cao cấp cho trải nghiệm đáng tiền trong nhiều dịp khác nhau.'],
            ['Casio Edifice EFV-630D-1AVUDF', 'Casionam6.avif', 3899000, 'Mẫu Casio EFV-630D-1AVUDF phiên bản Edifice kiểu dáng đồng hồ 6 kim với thiết kế 3 núm điều chỉnh các tính năng Chronograph (đo thời gian) hiện thị trên nền mặt số lớn kích thước 43mm.'],
            ['Casio Edifice EFR-S567D-1AVUDF', 'Casionam7.avif', 5709000, 'Casio Edifice EFR-S567D-1AVUDF thiết kế 3 núm vặn điều chỉnh chức năng Chronograph đầy vẻ nam tính, điểm nhấn nổi bật với phiên bản mặt kính Sapphire đảm bảo khả năng hiển thị trong suốt và chống trầy xước tuyệt vời.'],
            ['Casio Edifice EFV-570D-7AVUDF', 'Casionam8.avif', 3899000, 'Casio Edifice EFV-570D-7AVUDF nổi bật với kiểu dáng 6 kim kèm tính năng Chronograph đo thời gian vượt trội đặc trưng thuộc dòng Edifice dành cho các tín đồ yêu thích phong cách thể thao nhưng lại khoác trên mình vẻ ngoài lịch lãm.'],
            ['Casio MTP-1302D-1A1VDF', 'Casionam9.avif', 1595000, 'Đồng hồ Casio MTP-1302D-1A1VDF với mặt số tròn lớn nam tính, viền được tạo khía độc đáo, kim chỉ và vạch số được mạ bạc và phủ phản quang tinh tế nổi bật trên nền số màu đen mạnh mẽ.'],
            ['Casio EFV-540D-1A9VUDF', 'Casionam10.avif', 3899000, 'Casio Edifice EFV-540D-1A9VUDF kiểu thiết kế đồng hồ 6 kim chức năng Chronograph đại diện cho phái mạnh với phong cách thể thao, các chi tiết vạch số tạo hình dày dặn phô diễn ra vẻ ngoài nam tính.'],
            ['Casio F-91WM-3ADF', 'Casionam11.avif', 673000, 'Casio F-91WM-3ADF mẫu đồng hồ huyền thoại gắn liền bao thế hệ người dùng Việt bởi độ bền ấn tượng, độ chính xác của bộ máy Nhật Bản cùng mức giá bình dân.'],
            ['Casio EFR-552D-1AVUDF', 'Casionam12.avif', 3899000, 'Đồng hồ Casio Edifice EFR-552D-1AVUDF kiểu dáng thể thao sang trọng với dây đeo thép sáng bóng. Mặt số 47mm trang bị chức năng bấm giờ Chronograph tiện lợi cho nam giới.'],
            ['Casio MTP-1302D-7A1VDF', 'Casionam13.avif', 1595000, 'Đồng hồ Casio MTP-1302D-7A1VDF thanh lịch, sang trọng với tông màu trắng tinh tế. Trang trí viền bezel dạng khía đặc trưng giúp tăng hiệu ứng thị giác thêm phần bắt mắt. Sử dụng máy Nhật cho độ chính xác cao, ổn định.'],
            ['Casio A168WA-1WDF', 'Casionam14.avif', 1307000, 'Casio A168WA-1WDF mẫu đồng hồ huyền thoại gắn liền với bao thế hệ. Sở hữu kiểu dáng mặt vuông sang trọng và cổ điển. Chức năng bấm giờ cùng đèn Led tạo nên phong cách riêng cho phái mạnh.'],
            ['Casio Edifice EFR-575D-1ADF', 'Casionam15.avif', 5234000, 'Đồng hồ Casio Edifice EFR-575D-1ADF lấy cảm hứng từ màn hình lồi trên các dòng xe đua thể thao. Thiết kế dây kim loại sang trọng, bền bỉ và chống nước 10ATM. Chức năng Chronograph, lịch ngày có độ chính xác cao.'],
            ['Casio Edifice EFR-575L-7ADF', 'Casionam16.avif', 4949000, 'Đồng hồ Casio Edifice EFR-575L-7ADF lấy cảm hứng từ màn hình lồi trên các dòng xe đua thể thao. Kiểu dây da với các hệ thống thông hơi thoáng mát cho cảm giác đeo nhẹ nhàng, thoải mái.'],
            ['Casio MTP-1183A-7ADF', 'Casionam17.avif', 1352000 , 'Đồng hồ Casio MTP-1183A-7ADF sang trọng lịch lãm với tông màu trắng bạc bắt mắt. Bộ máy quartz Japan movt đảm bảo luôn hiển thị đúng giờ, ổn định và bền bỉ theo thời gian.'],
            ['Casio MTP-1375L-1AVDF', 'Casionam18.avif', 2452000, 'Đồng hồ Casio MTP-1375L-1AVDF kiểu dáng trẻ trung, năng động với kích thước mặt số 42mm, 6 kim hiển thị thông tin: giờ hiện hành, lịch ngày – tháng và 24 giờ. Trang bị dây da cổ điển, sang trọng cùng khả năng chống nước 5ATM.'],
            ['Casio MTP-1381L-1AVDF', 'Casionam19.avif', 1893000, 'Đồng hồ Casio MTP-1381L-1AVDF có vỏ kim loại bằng chất liệu thép không gỉ, nền số màu đen mạnh mẽ, kim chỉ và vạch số được phủ phản quang nổi bật, có thanh lịch thứ vị trí 12h và ô lịch ngày vị trí 6h.'],
            ['Casio Edifice ECB-950DB-2ADF', 'Casionam20.avif', 7612000, 'Casio Edifice ECB-950DB-2ADF sở hữu bộ máy được trang bị công nghệ Solar (Năng lượng ánh sáng), phiên bản Edifice nổi bật tính năng Bluetooth kết nối điện thoại thông minh.'],
            ['Casio CA-500WEGG-1BDF', 'Casionam21.avif', 3752000, 'Mẫu Casio CA-500WEGG-1BDF được làm lại dựa trên nguồn cảm hứng từ thập niên 1980 dành cho những khách hàng yêu thích giá trị cổ xưa và có mong muốn sưu tầm'],
            ['Casio A120WE-1ADF', 'Casionam22.avif', 2678000, 'Mẫu Casio A120WE-1ADF được làm lại dựa trên nguồn cảm hứng từ thập niên 1980 dành cho những khách hàng yêu thích giá trị cổ xưa và có mong muốn sưu tầm'],
            ['Casio PRX MTP-B145D-2A2VDF', 'Casionam23.avif', 2440000, 'Mẫu Casio PRX MTP-B145D-2A2VDF sở hữu kiểu dáng trẻ trung phối hợp với nét thể thao của kiểu dây đeo “tích hợp” nổi tiếng trên dòng đồng hồ cao cấp'],
            ['Casio PRX MTP-B145G-9AVDF', 'Casionam24.avif', 3049000, 'Mẫu Casio PRX MTP-B145G-9AVDF sở hữu kiểu dáng trẻ trung phối hợp với nét thể thao của kiểu dây đeo “tích hợp” nổi tiếng trên dòng đồng hồ cao cấp'],
        ];

        foreach ($products as [$name, $image, $price, $description]) {
            DB::table('products')->insert([
                'name' => $name,
                'slug' => $makeSlug($name),
                'description' => $description,
                'price' => $price,
                'image' => $image,
                'images' => json_encode([$image, str_replace('.', '_2.', $image), str_replace('.', '_3.', $image), str_replace('.', '_4.', $image)]),
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'warranty_months' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

 // ---------Citizen nam-----------------
        $category = Category::where('name', 'Nam')->first();
        $brand  = Brand::where('name', 'Citizen')->first();

        if (!$category || !$brand) {
            $this->command->error('Không tìm thấy categories "Nam" hoặc brands "Citizen"!');
            return;
        }

        $products = [
            ['Citizen Corso BM7334-58B', 'Citizennam1.avif', 9885000, 'Citizen Corso BM7334-58B sở hữu ngoại hình sang trọng, kết hợp giữa tông màu bạc kim loại cùng sắc vàng của công nghệ mạ PVD. Mặt số gồm bộ 3 kim máu trắng phủ dạ quang, có ô cửa sổ lịch ngày cùng khả năng chống nước 10ATM.'],
            ['Citizen AW0100-27E', 'Citizennam2.avif', 7115000, 'Mẫu đồng hồ nam Citizen Eco-Drive AW0100-27E tông màu full đen lịch lãm với các chức năng lịch ngày – thứ tiện lợi. Bộ máy năng lượng mặt trời độc quyền có độ chính xác cao, pin lên đến 10 năm.'],
            ['Citizen Eco-Drive AW1786-88A', 'Citizennam3.avif', 8485000, 'Citizen Eco-Drive AW1786-88A thừa hưởng di sản của thương hiệu đồng hồ Nhật Bản. Trang bị bộ máy năng lượng ánh sáng hàng đầu thế giới với thời gian sử dụng lên đến 10 năm mà không phải thay pin.'],
            ['Citizen Eco-Drive AW1780-84L', 'Citizennam4.avif', 7425000, 'Citizen Eco-Drive AW1780-84L thừa hưởng di sản của thương hiệu đồng hồ Nhật Bản. Trang bị bộ máy năng lượng ánh sáng hàng đầu thế giới với thời gian sử dụng lên đến 10 năm mà không phải thay pin.'],
            ['Citizen Zenshin NJ0180-80A', 'Citizennam5.avif', 22485000, 'Citizen Zenshin NJ0180-80A là mẫu đồng hồ cơ mới được đánh giá cao của thương hiệu Nhật Bản. Thể hiện triết lý lâu đời, sự đổi mới qua việc sử dụng chất liệu Super Titanium và công nghệ bộ máy.'],
            ['Citizen Zenshin NJ0180-80L', 'Citizennam6.avif', 22485000, 'Citizen Zenshin NJ0180-80L là mẫu đồng hồ cơ mới được đánh giá cao của thương hiệu Nhật Bản. Thể hiện triết lý lâu đời, sự đổi mới qua việc sử dụng chất liệu Super Titanium và công nghệ bộ máy.'],
            ['Citizen Tsuyosa Small Second NK5010-51X', 'Citizennam7.avif', 18085000, 'Citizen Tsuyosa Small Second Mechanical NK5010-51X mặt số màu vàng đồng kết hợp thiết kế kim giây rốn cổ điển. Trang bị bộ máy automatic Caliber 8322 cho thời gian trữ cót lên đến 60 giờ.'],
            ['Citizen NH8353-00H', 'Citizennam8.avif', 6785000, 'Đồng hồ nam Citizen NH8353-00H mạ vàng PVD kích thước 40mm dành cho những anh em có tay từ 16cm trở lên. Trang bị bộ máy cơ tự động sản xuất in-house cho thời gian trữ cót 45 giờ.'],
            ['Citizen Tsuyosa NJ0151-88W ', 'Citizennam9.avif', 12485000, 'Citizen Tsuyosa Beige Automatic NJ0151-88W mặt số màu Beige/sồi ra mắt năm 2024, kiểu dáng hình thùng Tonneau sang trọng cùng dây đeo President tinh tế. Sử dụng bộ máy 8210 in-house cho thời gian trữ cót 40h.'],
            ['Citizen NH8350-59L', 'Citizennam10.avif', 6485000, 'Citizen NH8350-59L là mẫu đồng hồ cơ tự động giá rẻ cho nam đến từ thương hiệu Nhật Bản. Kiểu dáng thiết kế sang trọng nhờ dây đeo thép không gỉ đánh bóng tỉ mỉ, sử dụng bộ máy automatic tích cót 45 giờ ổn định, bền bỉ.'],
            ['Citizen Tsuyosa NJ0150-81L', 'Citizennam11.avif', 12785000, 'Citizen Tsuyosa NJ0150-81L 40mm mặt số xanh dương đậm, khung vỏ Tonneau thể thao sang trọng. Bộ máy in-house trữ cót 45 giờ bền bỉ, chính xác theo thời gian. Dây President thêm phần đẳng cấp.'],
            ['Citizen Tsuyosa NJ0150-81E', 'Citizennam12.avif', 12785000, 'Citizen Tsuyosa Black NJ0150-81E là mẫu đồng hồ cơ thể thao sang trọng lấy cảm hứng từ thập niên 70. Kiểu dáng vỏ hình thùng Tonneau kết hợp dây đeo President vô cùng sang trọng, tính biểu tượng cao.'],
            ['Citizen BI5104-57E', 'Citizennam13.avif', 5285000, 'Citizen BI5104-57E gây ấn tượng bởi cấu trúc Cushion Lug (vấu đệm) mang đến phong cách thể thao sang trọng. Bộ máy quartz in-house đảm bảo thời gian luôn hiển thị chính xác trong khoảng +/- 15 giây mỗi tháng.'],
            ['Citizen Tsuyosa NJ0151-88M', 'Citizennam14.avif', 12485000, 'Citizen Tsuyosa NJ0151-88M mang đến hơi thở tươi mới từ đại dương, theo đuổi phong cách năng động, trẻ trung, kích thước mặt số 40mm phù hợp đa số với quý ông.'],
            ['Citizen AW1670-82A', 'Citizennam15.avif', 6485000, 'Citizen AW1670-82A, đồng hồ bình dân sử dụng máy pin trang bị công nghệ Eco-Drive (Năng Lượng Ánh Sáng) với mặt số trắng kích thước 41mm phù hợp đại đa số nam giới.'],
            ['Citizen NH9130-84L', 'Citizennam16.avif', 10585000, 'Citizen Automatic NH9130-84L thiết kế Open heart cùng họa tiết Guilloche hoàn toàn mới mang đến diện mạo nam tính, lịch lãm. Trang bị bộ máy cơ Japan Movt trữ cót 40 giờ, tự động lên cót khi đeo liên tục mỗi ngày.'],
            ['Citizen NH9130-17A', 'Citizennam17.avif', 8985000, 'Citizen automatic NH9130-17A thiết kế Open heart (máy cơ lộ tim) tạo nên vẻ độc đáo trên nền mặt số tone màu vàng Champage (sâm panh) kích thước 40mm, phù hợp cho mọi cổ tay phái mạnh.'],
            ['Citizen NH8352-53P', 'Citizennam18.avif', 7425000, 'Mẫu đồng hồ cơ giá rẻ Citizen NH8352-53P thiết kế mạ vàng PVD full dây đeo, vỏ. Sử dụng bộ máy automatic Miyota 8200 trữ cót 45 giờ Made In Japan có độ ổn định, hiệu suất cao.'],
            ['Citizen Series 8 831 NB6011-11Wr', 'Citizennam19.avif', 33085000, 'Citizen Series 8 831 NB6011-11W là mẫu đồng hồ cơ khí giới hạn sản xuất 888 chiếc toàn thế giới. Sử dụng bộ máy in-house kháng từ 16,000 A/m đảm bảo độ chính xác ấn tượng. Cấu trúc vỏ bát giác thể hiện trình độ tay nghề cao cùng họa tiết Guilloche đồng tâm màu đỏ thẫm.'],
            ['Citizen Series 8 890 NB6066-51W ', 'Citizennam20.avif', 43485000, 'Citizen Series 8 890 NB6066-51W – Phiên bản giới hạn 1.700 chiếc toàn cầu là mẫu đồng hồ lặn (Diver watch) mới ra mắt năm 2024. Thiết kế mặt bát giác cùng bezel xoay hai chiều phía trong, sử dụng bộ máy automatic tích cót 42 giờ, kháng từ 16,000 A/m giúp hoạt động ổn định và chính xác hơn đồng hồ cơ thông thường.'],
            ['Citizen Series 8 890 NB6060-58L', 'Citizennam21.avif', 43485000, 'Citizen Series 8 890 NB6060-58L là mẫu đồng hồ lặn (Diver watch) mới ra mắt năm 2024. Thiết kế mặt bát giác kết hợp bezel xoay bên trọng, sử dụng bộ máy automatic tích cót 42 giờ, kháng từ 16,000 A/m giúp hoạt động ổn định và chính xác hơn so với đồng hồ cơ thông thường.'],
            ['Citizen Series 8 GMT NB6031-56E', 'Citizennam22.avif', 47285000, 'Citizen Series 8 NB6031-56E là mẫu đồng hồ thể thao sang trọng, người bạn đồng hành trong những chuyến du lịch mới ra mắt năm 2024. Sử dụng bộ máy automatic in-house tích cót 50 giờ, khả năng kháng từ 16.000 a/m cùng chức năng hiển thị giờ GMT chính xác.'],
            ['Citizen Tsuyosa NJ0153-82X', 'Citizennam23.avif', 13985000, 'Citizen Tsuyosa Automatic Red Dial NJ0153-82X phiên bản mạ vàng với vẻ ngoài sang trọng cùng sắc đỏ mận hiếm gặp và thiết kế bộ kiệm, cọc vạch số tạo hình dày dặn phủ dạ quang.'],
            ['Citizen Tsuyosa NJ0151-53W', 'Citizennam24.avif', 14285000, 'Citizen Tsuyosa Multiple Automatic NJ0151-53W phiên bản mặt xanh dương Multiple chuyển sắc ấn tượng, dây đeo thép không gỉ lấy cảm hứng từ Rolex Day-Date. Sử dụng bộ máy automatic Calibre 8210 in-house tích cót 40 giờ.'],
        ];

        foreach ($products as [$name, $image, $price, $description]) {
            DB::table('products')->insert([
                'name' => $name,
                'slug' => $makeSlug($name),
                'description' => $description,
                'price' => $price,
                'image' => $image,
                'images' => json_encode([$image, str_replace('.', '_2.', $image), str_replace('.', '_3.', $image), str_replace('.', '_4.', $image)]),
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'warranty_months' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
// ---------Rado nam-----------------
        $category = Category::where('name', 'Nam')->first();
        $brand  = Brand::where('name', 'Rado')->first();

        if (!$category || !$brand) {
            $this->command->error('Không tìm thấy categories "Nam" hoặc brands "Rado"!');
            return;
        }

        $products = [

            ['Rado Chronograph Blue Leather', 'rado-nam-1.webp', 25900000, 'Mặt xanh navy nổi bật, dây da lịch lãm, thiết kế thể thao sang trọng.'],
            ['Rado Classic Black Leather', 'rado-nam-2.webp', 23800000, 'Thiết kế cổ điển, mặt đen tối giản, dây da nâu sang trọng.'],
            ['Rado Green Stainless Steel', 'rado-nam-3.webp', 27500000, 'Mặt xanh lạ mắt, dây kim loại sáng bóng – thanh lịch và hiện đại.'],
            ['Rado Diastar Original Black', 'rado-nam-4.webp', 28900000, 'Mặt oval đặc trưng, dây thép không gỉ – phong cách cổ điển, bền bỉ.'],
            ['Rado White Classic Leather', 'rado-nam-5.webp', 23900000, 'Mặt trắng tinh tế, dây da đen – thiết kế thanh lịch, phù hợp công sở.'],
            ['Rado Two-Tone Black Gold', 'rado-nam-6.webp', 26500000, 'Mặt đen sang trọng, dây demi vàng bạc – quý phái và nổi bật.'],
            ['Rado Ceramica Black', 'rado-nam-7.webp', 27800000, 'Thiết kế gọn nhẹ, mặt đen huyền bí – dây ceramic bóng bẩy.'],
            ['Rado Transparent Skeleton', 'rado-nam-8.webp', 30500000, 'Thiết kế trong suốt, lộ máy ấn tượng – hiện đại và cá tính.'],
            ['Rado Ceramica Square Black', 'rado-nam-9.webp', 25900000, 'Dáng vuông mạnh mẽ, mặt đen tinh tế – dây ceramic đen bóng.'],
            ['Rado Chronograph Two-Tone', 'rado-nam-10.webp', 28900000, 'Mặt đồng hồ phụ nổi bật, dây demi thời trang – đậm chất nam tính.'],
            ['Rado Beige Leather Automatic', 'rado-nam-11.webp', 24800000, 'Mặt màu kem sang trọng, dây da nâu cổ điển – máy automatic bền bỉ.'],
            ['Rado Textured White Dial', 'rado-nam-12.webp', 23900000, 'Mặt vân nổi 3D, dây da đen – thiết kế lịch thiệp và cao cấp.'],
            ['Rado Tradition Classic Silver', 'rado-nam-13.webp', 23800000, 'Mặt trắng vân caro, kim xanh nổi bật – dây da lịch lãm, cổ điển.'],
            ['Rado Integral Black Ceramic', 'rado-nam-14.webp', 27500000, 'Thiết kế vuông đẳng cấp, dây ceramic đen bạc – sang trọng và tinh tế.'],
            ['Rado Diastar Gold Skeleton', 'rado-nam-15.webp', 28900000, 'Lộ máy toàn phần, vỏ vàng ấn tượng – thiết kế độc đáo và cá tính.'],
            ['Rado Florence Classic Black', 'rado-nam-16.webp', 24500000, 'Mặt đen sang trọng, dây demi bóng sáng – tối giản mà thu hút.'],
            ['Rado Blue Semi-Skeleton', 'rado-nam-17.webp', 28900000, 'Thiết kế lộ cơ 1 phần, mặt xanh navy – dây ceramic bóng bẩy.'],
            ['Rado Ceramica Square Skeleton', 'rado-nam-18.webp', 31500000, 'Dáng vuông mạnh mẽ, máy cơ lộ xương – hiện đại và cá tính.'],
            ['Rado True Open Heart Black', 'rado-nam-19.webp', 29800000, 'Mặt lộ cơ góc cạnh, dây ceramic đen bóng – phong cách thể thao sang trọng.'],
            ['Rado True White Ceramic', 'rado-nam-20.webp', 26500000, 'Màu trắng tinh khiết, thiết kế siêu mỏng – thanh lịch và hiện đại.'],
            ['Rado Captain Cook Blue', 'rado-nam-21.webp', 32500000, 'Dòng lặn cổ điển, mặt xanh biển – dây thép lưới chắc chắn.'],
            ['Rado Sintra Chronograph Silver', 'rado-nam-22.webp', 29500000, 'Vỏ vuốt cong lạ mắt, mặt trắng 3 kim – phong cách thời thượng.'],
            ['Rado Ceramica Skeleton Square', 'rado-nam-23.webp', 31900000, 'Máy cơ lộ rõ, khung vuông hiện đại – dây ceramic đen tuyền.'],
            ['Rado Diamond Black Leather', 'rado-nam-24.webp', 23900000, 'Mặt vân chéo đen, vỏ hồng sang trọng – dây da nâu cổ điển.'],

        ];

        foreach ($products as [$name, $image, $price, $description]) {
            DB::table('products')->insert([
                'name' => $name,
                'slug' => $makeSlug($name),
                'description' => $description,
                'price' => $price,
                'image' => $image,
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'warranty_months' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

// ---------Rolex nam-----------------
        $category = Category::where('name', 'Nam')->first();
        $brand  = Brand::where('name', 'Rolex')->first();

        if (!$category || !$brand) {
            $this->command->error('Không tìm thấy categories "Nam" hoặc brands "Rolex"!');
            return;
        }

        $products = [
            ['Rolex Audemars Piguet Millenary', 'rolex-nam-1.jpg', 4652900000, 'Thiết kế độc đáo với mặt hồng và dây đính kim cương, dành cho con người yêu thích sự sang trọng và khác biệt.'],
            ['Rolex Audemars Piguet Royal Oak Selfwinding', 'rolex-nam-2.jpg', 1129000000, 'Thiết kế Royal Oak biểu tượng với mặt đen “Grande Tapisserie”, vỏ và dây thép, viền kim cương lấp lánh, tích hợp bộ máy automatic, sang trọng và tinh tế.'],
            ['Rolex Audemars Piguet Royal Oak Double Balance Wheel Openworked', 'rolex-nam-3.jpg', 9098000000, 'Thiết kế skeleton lộ cơ Calibre 3132 với hai bánh cân bằng, vỏ và dây bằng vàng 18K, bezel đính đá nhiều màu “rainbow” baguette'],
            ['Rolex Audemars Piguet Royal Oak Concept “Black Panther” Flying', 'rolex-nam-4.png', 395000000, 'Vỏ titan 42 mm và vành bezel ceramic đen, tourbillon lộ cơ, hình tượng 3D Black Panther bằng vàng trắng sơn tay, dây cao su tím – mẫu giới hạn, phối hợp nghệ thuật và kỹ thuật đỉnh cao.'],
            ['Rolex Audemars Piguet Royal Oak Concept Flying Tourbillon Rainbow', 'rolex-nam-5.jpg', 8290000000, 'Tourbillon bay, mặt lộ cơ đính kim cương nhiều màu, thiết kế nghệ thuật và đẳng cấp xa xỉ.'],
            ['Rolex Audemars Piguet Code 11.59 Blue Aventurine Dial', 'rolex-nam-6.jpg', 1320000000, 'Mặt xanh đá aventurine nổi bật, thiết kế tối giản sang trọng, dây da cá sấu đồng màu.'],
            ['Rolex Audemars Piguet Code Chronograph Blue Dial', 'rolex-nam-7.jpg', 2050000000, 'Thiết kế thể thao cao cấp, chronograph vàng hồng 41mm, mặt xanh navy cá tính.'],
            ['Rolex Audemars Piguet Code 11.6 White Dial Pink Gold', 'rolex-nam-8.jpg', 1220000000, 'Phong cách cổ điển thanh lịch, mặt trắng, vỏ vàng hồng, dây da nâu sang trọng.'],
            ['Rolex Audemars Piguet Code 11.59 Chronograph Silver Dial', 'rolex-nam-9.jpg', 800000000, 'Chronograph 41mm, mặt bạc, vỏ thép, dây da xám – lịch lãm & tinh tế.'],
            ['Rolex Audemars Piguet Code Skeleton Tourbillon', 'rolex-nam-10.jpg', 6550000000, 'Open‑worked tourbillon flyback chronograph 41mm, vỏ vàng trắng đỉnh cao kỹ thuật.'],
            ['Rolex Audemars Piguet Diamond Bracelet Watch', 'rolex-nam-11.jpg', 1300000000, 'Lắc tay/bracelet full kim cương, vỏ kim loại quý – xa xỉ & nổi bật.'],
            ['Rolex MB&F Legacy Machine Inspired', 'rolex-nam-12.jpg', 4270000000, 'MB&F Legacy Machine, tourbillon treo, vỏ vàng + mặt kỹ thuật – nghệ thuật cao.'],
            ['Rolex Audemars Piguet Code 11.59 Tourbillon Openworked Chronograph', 'rolex-nam-13.jpg', 4400000000, 'Open‑worked tourbillon + flyback chronograph 41mm vàng hồng – tinh hoa kỹ thuật.' ],
            ['Rolex Audemars Piguet Code 11.59 Perpetual Calendar Blue Aventurine', 'rolex-nam-14.jpg', 3800000000, 'Perpetual calendar 41mm vàng hồng, mặt aventurine xanh – lịch vĩnh cửu & sang trọng.'],
            ['Rolex Audemars Piguet Royal Oak Offshore Music Edition 37mm', 'rolex-nam-15.png', 3200000000, 'Offshore 37mm gốm đen, mặt VU‑meter đá xanh & ruby – phong cách âm nhạc pop.' ],
            ['Rolex Audemars Piguet Code 11.59 Skeleton Chronograph Rose Gold', 'rolex-nam-16.jpg', 4900000000, 'Skeleton chronograph 41mm vàng hồng, mặt lộ cơ kỹ thuật – cá tính & phô diễn cơ khí.' ],
            ['Rolex Audemars Piguet Royal Oak Selfwinding Flying Tourbillon Blue Dial RG', 'rolex-nam-17.jpg', 7955000000, 'Tourbillon bay 41mm, vỏ & dây vàng hồng, mặt xanh Grande Tapisserie – sang trọng & tinh tế.'],
            ['Rolex Audemars Piguet Royal Oak Concept Spider Man Tourbillon', 'rolex-nam-18.jpg', 7130000000, 'Tourbillon 42mm titan‑gốm, dial skeleton có tượng 3D Spider‑Man – kỹ thuật & pop culture.'],
            ['Rolex Audemars Piguet Royal Oak Openworked Blue Baguette BEZEL', 'rolex-nam-19.jpg', 7900000000, 'Skeleton 41mm vàng trắng/stainless với vành baguette xanh, lộ cơ phô diễn kỹ thuật.'],
            ['Rolex Audemars Piguet Royal Oak Concept Flying Tourbillon Rainbow', 'rolex-nam-20.jpg', 8290000000, 'Skeleton tourbillon 38.5mm vàng hồng, bezel & cọc đa sắc – cực kỳ nghệ thuật & xa xỉ.'],
            ['Rolex Audemars Piguet Royal Oak Offshore Lady Chronograph', 'rolex-nam-21.jpg', 1336000000, 'Chronograph 37 mm, vỏ vàng hồng, bezel kim cương ~1carat, dây cao su trắng – dành cho nữ, sang trọng & thể thao.'],
            ['Rolex Audemars Piguet Royal Oak Concept Openworked Chronograph', 'rolex-nam-22.jpg', 4025000000, 'Chronograph flyback 44 mm titan/stainless open‑worked, dây cao su đen – kỹ thuật & hiện đại.'],
            ['Rolex Cartier Ballon Bleu Pink Gold Brown Strap', 'rolex-nam-23.jpg', 550000000, 'Ballon Bleu 36 mm, vỏ vàng hồng, mặt trắng La queu d’or, dây da nâu – cổ điển & lịch lãm.'],
            ['Rolex Franck Muller Heart Diamond Rose Gold', 'rolex-nam-24.jpg', 380000000, 'Vỏ vàng hồng, đính kim cương bezel + cọc số trái tim, mặt số ngọc trai, dây da đỏ – nữ tính & quý phái.'],
        ];

        foreach ($products as [$name, $image, $price, $description]) {
            DB::table('products')->insert([
                'name' => $name,
                'slug' => $makeSlug($name),
                'description' => $description,
                'price' => $price,
                'image' => $image,
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'warranty_months' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

// ---------Seiko nam-----------------
        $category = Category::where('name', 'Nam')->first();
        $brand  = Brand::where('name', 'Seiko')->first();

        if (!$category || !$brand) {
            $this->command->error('Không tìm thấy categories "Nam" hoặc brands "Seiko"!');
            return;
        }

        $products = [
            ['Seiko 5 Sports x Pepsi SSK047K1', 'Seikonam1.avif', 16012500, 'Seiko 5 Sports SSK047K1 hợp tác cùng Pepsi, sử dụng bộ máy Automatic 4R34 in-house nổi tiếng của Seiko, trữ cót đến 41 giờ. Có chức năng GMT tiện lợi. Phiên bản giới hạn chỉ 7,000 chiếc toàn thế giới.'],
            ['Seiko 5 Sports x Pepsi SRPL99K1', 'Seikonam2.avif', 11287500, 'Đồng hồ Seiko 5 Sports SRPL99K1 kết hợp cùng Pepsi, sử dụng bộ máy Automatic 4R36 in-house nổi tiếng của Seiko, trữ cót đến 41 giờ. Phiên bản giới hạn chỉ 7,000 chiếc trên toàn thế giới.'],
            ['Seiko 5 Sports SRPL91K1', 'Seikonam3.avif', 13387500, 'Đồng hồ Seiko 5 Sports SRPL91K1 sử dụng bộ máy Automatic 4R36 in-house nổi tiếng của Seiko, trữ cót đến 41 giờ. Phiên bản giới hạn chỉ 9,999 chiếc trên toàn thế giới.'],
            ['Seiko 5 Sports SRPL93K1', 'Seikonam4.avif', 13387500, 'Đồng hồ Seiko 5 Sports SRPL93K1 sử dụng bộ máy Automatic 4R36 in-house nổi tiếng của Seiko, trữ cót đến 41 giờ. Phiên bản giới hạn chỉ 9,999 chiếc trên toàn thế giới.'],
            ['Seiko Presage Cocktail SRPB41J1', 'Seikonam5.avif', 13960000, 'Seiko Presage Cocktail SRPB41J1 là mẫu đồng hồ sang trọng đáng sở hữu bởi: Hardlex Crystal (chất liệu độc quyền của Seiko) – Bộ máy in-house 4R35 ổn định, chính xác cao – Màu sắc xanh dương (Blue Moon Cocktail).'],
            ['Seiko 5 Sports SRPK09K1', 'Seikonam6.avif', 10270000, 'Seiko 5 Sports Rally Diver SRPK09K1 ra mắt nhân dịp kỷ niệm 55 năm phát hành dòng Seiko 5 Sport. Kết hợp màu sắc cổ điển cùng bộ máy tân tiến 4R36 trữ cót 41 giờ.'],
            ['Seiko 5 Field Sports Style SRPG29K1', 'Seikonam7.avif', 9430000, 'Seiko SRPG29K1 là mẫu đồng hồ Seiko 5 Field quân đội với phong cách sang trọng. Mặt số xanh sunray cùng cọc số, kim giờ phủ dạ quang Lumibrite siêu sáng. Sử dụng máy automatic in-house trữ cót khoảng 41 giờ.'],
            ['Seiko 5 Sports Vintage Car SRPL49K1', 'Seikonam8.avif', 9658000, 'Seiko 5 Sports Vintage Car SRPL49K1 là phiên bản đặc biệt của bộ sưu tập, lấy cảm hứng từ những chiếc xe thể thao cổ điển. Thể hiện tinh thần mạnh mẽ, nam tính. Kết hợp cùng bộ máy Seiko Automatic 4R36 tiên tiến.'],
            ['Seiko 5 Sports SSK003K1', 'Seikonam9.avif', 14540000, 'Seiko 5 Sports GMT SSK003K1 kiểu dáng thể thao sang trọng, tích hợp chức năng GMT phục vụ cho người dùng thường xuyên du lịch, công tác tại nước ngoài.'],
            ['Seiko Presage Style 60’s SSA425J1', 'Seikonam10.avif', 17970000, 'Seiko SSA425J1, đồng hồ cơ Nhật Bản sang trọng lấy cảm hứng từ phong cách cổ điển thập niên 60. Trang bị máy 4R39 độc quyền cho thời gian trữ cót 41 giờ.'],
            ['Seiko Presage SSA377J1', 'Seikonam11.avif', 18790000, 'Seiko Presage Japanese Garden SSA377J1 mang đến trải nghiệm bộ máy cơ tự động cao cấp 4R39 với chi tiết lộ cơ góc 9 giờ. Bề mặt số được chế tác tinh xảo với họa tiết Guilloche, lấy cảm hứng từ hình ảnh khu vườn Nhật Bản.'],
            ['Seiko Prospex Alpinist SPB121J1', 'Seikonam12.avif', 22840000, 'Seiko Prospex Alpinist SPB121J1 là dòng đồng hồ cơ cao cấp của thương hiệu Seiko. Dành cho những nhà thám hiểm với chức năng la bàn ngay trên mặt số. Bộ máy cơ in-house trữ cót đến 70 giờ.'],
            ['Seiko Prospex Speedtimer SSC941P1', 'Seikonam13.avif', 24930000, 'Seiko Prospex Speedtimer SSC941P1 là mẫu đồng hồ thể thao sang trọng với chức năng: Chronograph – Tachymeter – Lịch ngày – Kim xăng báo năng lượng còn lại. Sử dụng bộ máy Solar ánh sáng cho thời gian sử dụng lên đến 10 năm.'],
            ['Seiko Prospex SSC817P1', 'Seikonam14.avif', 22000000, 'Seiko Prospex Speedtimer SSC817P1 với thiết kế lấy cảm hứng từ di sản đồng hồ chuyên nghiệp của Seiko. Sử dụng bộ máy năng lượng ánh sáng, tích hợp nhiều tính năng hữu ích trong đời sống.'],
            ['Seiko Prospex SSC815P1', 'Seikonam15.avif', 22000000, 'Seiko Prospex Speedtimer SSC815P1 với thiết kế lấy cảm hứng từ di sản đồng hồ chuyên nghiệp của Seiko. Sử dụng bộ máy năng lượng ánh sáng, tích hợp nhiều tính năng hữu ích trong đời sống.'],
            ['Seiko Presage Cocktail SSA459J1', 'Seikonam16.avif', 18270000, 'Đồng hồ Seiko Presage Cocktail SSA459J1 lấy cảm hứng từ món Cocktail Mockingbird tuyệt đẹp. Với thiết kế dây da lịch lãm, mặt số kích thước 40mm phối màu xanh ngọc bích trên nền họa tiết chải tia độc đáo.'],
            ['Seiko Prospex SSC913P1', 'Seikonam17.avif', 22750000, 'Seiko Prospex Speedtimer SSC913P1, mẫu đồng hồ solar trang bị tính năng Chronograph trên mặt số Pepsi độc đáo. Thiết kế bằng thép không gỉ 316L mang đến diện mạo thể thao, nam tính.'],
            ['Seiko Presage Cocktail Grasshopper SSA441J1', 'Seikonam18.avif', 14780000, 'Seiko Presage Cocktail Grasshopper SSA441J1 là mẫu đồng hồ sang trọng đáng sở hữu bởi: Thiết kế Open Heart (Trải nghiệm chuyển động cơ học đẳng cấp) – Bộ máy in-house 4R38 ổn định, chính xác cao – Màu sắc xanh lá (Grasshopper Cocktail).'],
            ['Seiko 5 Sports Field SRPJ87K1', 'Seikonam19.avif', 9460000, 'Đồng hồ Seiko 5 Sports Field SRPJ87K1 phong cách thể thao, đường phố có thể đeo hàng ngày hay các hoạt động ngoài trời. Bộ máy tự động 4R36 có kháng từ đảm bảo hiển thị chính xác, ổn định theo thời gian.'],
            ['Seiko Presage Cocktail SRPE15J1', 'Seikonam20.avif', 14490000, 'Đồng hồ Seiko Presage Cocktail SRPE15J1 màu xanh ngọc lục bảo lấy ý tưởng từ loại cocktail Mockingbird. Kiểu dáng sang trọng kết hợp bộ máy cơ in-house, thích hợp cho dịp lễ tiệc, đi làm hàng ngày.'],
            ['Seiko Presage Japanese Garden SSA464J1', 'Seikonam21.avif', 19688000, 'Đồng hồ Seiko Presage Japanese Garden SSA464J1lấy cảm hứng từ vẻ đẹp thanh bình, giản dị của khu vườn thiền Nhật Bản. Kiểu dáng mạ vàng demi sang trọng kết hợp bộ máy cơ in-house trữ cót 41 giờ vô cùng tinh xảo.'],
            ['Seiko Presage SRPK15J1', 'Seikonam22.avif', 13960000, 'TSeiko Presage Cocktail SRPK15J1 phiên bản mặt số màu Cocktail Blue Moon, thiết kế dây da vẻ ngoài lịch lãm. Trang bị máy automatic sản xuất in-house cho thời gian tích cót khoảng 41 giờ mạnh mẽ.'],
            ['Seiko Presage Cocktail Green SRPD37J1', 'Seikonam23.avif', 13960000, 'Seiko Presage Cocktail Green SRPD37J1 kết hợp gu thẩm mỹ Nhật Bản với nghề thủ công truyền thống và kỹ năng chế tác đồng hồ của Seiko hơn 100 năm. Thiết kế lấy cảm hứng từ văn hóa thưởng thức Cocktail sau giờ tan làm qua màu sắc mặt số độc đáo.'],
            ['Seiko 5 Sports SRPK29K1', 'Seikonam24.avif', 10030000, 'Seiko 5 Sports SRPK29K1, mẫu đồng hồ cơ kiểu dáng thể thao với mặt số full đen, vành bezel răng cửa xoay 1 chiều độc đáo. Sử dụng bộ máy in-house 4R36 nổi tiếng nhờ hiệu suất ấn tượng, độ bền bỉ theo thời gian.'],
        ];

        foreach ($products as [$name, $image, $price, $description]) {
            DB::table('products')->insert([
                'name' => $name,
                'slug' => $makeSlug($name),
                'description' => $description,
                'price' => $price,
                'image' => $image,
                'images' => json_encode([$image, str_replace('.', '_2.', $image), str_replace('.', '_3.', $image), str_replace('.', '_4.', $image)]),
                'category_id' => $category->id,
                'brand_id' => $brand->id,
                'warranty_months' => 12,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

