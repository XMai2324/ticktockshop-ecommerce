<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Support\Facades\DB;

class ProductsSeeder extends Seeder
{
    public function run(): void
    {
                DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        // ❌ Xoá toàn bộ dữ liệu bảng products trước khi thêm lại
        DB::table('products')->truncate();
                DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // ---------Casio nữ-----------------
        $category = Category::where('name', 'Nữ')->first();
        $brand  = Brand::where('name', 'Casio')->first();

            if (!$category || !$brand) {
                $this->command->error('Không tìm thấy categories "Nữ" hoặc brands "Casio"!');
                return;
            }

            $products = [
                ['Casio LTP-VT01L-1B2UDF', 'Casio1.jpg', 970000, 'Thiết kế cổ điển với dây da đen, phù hợp phong cách thanh lịch, giản dị.'],
                ['Casio LTP-V007L-7B', 'Casio2.webp', 1130000, 'Mặt vuông nhỏ gọn, dây da nâu tinh tế cho quý cô yêu nét hoài cổ.'],
                ['Casio LTP-VT01D-2B', 'Casio3.webp', 2160000, 'Tone xanh navy dịu mắt kết hợp dây kim loại sáng bóng, nữ tính và hiện đại.'],
                ['Casio LTP-E157MR-9ADF', 'Casio4.webp', 3190000, 'Màu vàng hồng nổi bật, dây kim loại dạng lưới cho phong cách thời thượng.'],
                ['Casio LTP-V002GL-7BUDF', 'Casio5.webp', 12200000, 'Kiểu mặt tròn cổ điển, dây da nâu và mặt trắng trang nhã, dễ phối đồ.'],
                ['Casio LTP-V300L-7A', 'Casio6.webp', 5250000, 'Đồng hồ 3 kim phụ với dây da trắng sang trọng, nữ tính và đa dụng.'],
                ['Casio LTP-1358D-2AVDF', 'Casio7.webp', 880000, 'Phong cách năng động với mặt xanh lam cá tính, vỏ và dây thép không gỉ.'],
                ['Casio LTP-V300D-7A2', 'Casio8.webp', 610000, 'Thiết kế đa năng với lịch thứ, ngày và 24 giờ, dây kim loại bạc.'],
                ['Casio LTP-E414SG-9ADF', 'Casio9.jpeg', 13400000, 'Viền vàng phối dây bạc sang trọng, tôn lên nét tinh tế của cổ tay.'],
                ['Casio SHE-4052PGL-7AUDF', 'Casio10.png', 1370000, 'Mặt số được đính đá pha lê, dây da nâu nhã nhặn, thanh lịch và thời trang.'],
                ['Casio LTP-V002D-7BUDF', 'Casio11.png', 1400000, 'Dây kim loại truyền thống, mặt trắng nền xanh dịu dàng, phù hợp đi làm.'],
                ['Casio LTP-E157D-1ADF', 'Casio12.png', 1430000, 'Tông đen bạc cá tính, thiết kế mỏng nhẹ, đơn giản nhưng nổi bật.'],
                ['Casio LTP-V300L-2A2', 'Casio13.jpg', 14600000, 'Dây da xanh pastel lạ mắt, mặt số đa chức năng cho quý cô hiện đại.'],
                ['Casio LTP-V002GL-1BUDF', 'Casio14.jpg', 1490000, 'Mặt trắng, dây da đen và viền vàng cổ điển, thích hợp phong cách công sở.'],
                ['Casio LTP-V005D-1AUDF', 'Casio15.png', 1520000, 'Mặt đen kết hợp dây thép không gỉ bạc, đơn giản nhưng cuốn hút.'],
                ['Casio LTP-V002L-7B3UDF', 'Casio16.png', 1550000, 'Dây da nâu sáng cùng mặt số trắng nhẹ nhàng, phù hợp phong cách nữ tính.'],
                ['Casio LTP-VT01GL-9BUDF', 'Casio17.png', 1580000, 'Viền vàng hồng sang trọng, dây da nâu đậm, thanh lịch và quý phái.'],
                ['Casio LTP-V007SG-9EUDF', 'Casio18.webp', 1610000, 'Dây kim loại 2 tông màu, mặt vuông thanh lịch, phù hợp đi làm, đi tiệc.'],
                ['Casio LTP-E406GL-7A', 'Casio19.png', 1640000, 'Mặt số đơn giản, dây da nâu mềm mại, tạo cảm giác nhẹ nhàng, thoải mái.'],
                ['Casio LTP-V007D-7E', 'Casio20.png', 1670000, 'Kiểu dáng truyền thống, mặt số rõ ràng, dây kim loại sáng bóng.'],
                ['Casio LTP-V300L-1A', 'Casio21.png', 1700000, 'Mặt đen huyền bí, dây da đen cho phong cách mạnh mẽ, cá tính.'],
                ['Casio LTP-VT01D-1BUDF', 'Casio22.png', 1730000, 'Sự kết hợp hoàn hảo giữa nét cổ điển và hiện đại với tone đen bạc.'],
                ['Casio LTP-V005L-2BUDF', 'Casio23.jpg', 1760000, 'Dây da xanh navy trẻ trung, thiết kế đơn giản dễ đeo.'],
                ['Casio LTP-V002GL-9AUDF', 'Casio24.png', 5790000, 'Màu vàng đồng chủ đạo, mặt số cổ điển, thích hợp mọi lứa tuổi.'],
            ]; 
            foreach ($products as [$name, $image, $price, $description]) {
            DB::table('products')->insert([
                'name' => $name,
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
            ['Citizen Eco-Drive Silhouette', 'Citizen1.png', 4900000, 'Đồng hồ Eco-Drive Silhouette với dây kim loại mạ vàng hồng, mặt số khảm trai sang trọng, hoạt động bằng ánh sáng.'],
            ['Citizen Eco-Drive Bianca', 'Citizen2.jpg', 5300000, 'Thiết kế nữ tính với dây da trắng, mặt số xanh navy ánh trai, công nghệ ánh sáng không cần thay pin.'],
            ['Citizen L Ambiluna', 'Citizen3.jpg', 8600000, 'Đồng hồ Citizen L với triết lý tối giản, vỏ titanium siêu nhẹ và mặt số đá bán quý.'],
            ['Citizen Eco-Drive EM0686-01A', 'Citizen4.jpg', 4500000, 'Thiết kế tinh tế, dây da nâu, vỏ vàng hồng và mặt số bạc trắng dịu dàng.'],
            ['Citizen L EM0576-80A', 'Citizen5.jpg', 5950000, 'Đồng hồ dây thép không gỉ, mặt số ánh xà cừ với điểm nhấn kim vàng hồng hiện đại.'],
            ['Citizen Silhouette Crystal', 'Citizen6.webp', 6250000, 'Dây kim loại đính pha lê Swarovski, mặt số khảm trai, phù hợp dự tiệc và công sở.'],
            ['Citizen L Sunrise', 'Citizen7.webp', 8700000, 'Thiết kế đột phá với mặt số 3D, đính đá tự nhiên, dây da màu nude thời thượng.'],
            ['Citizen Eco-Drive EM0500-73L', 'Citizen8.jpg', 3990000, 'Mặt số xanh đậm chải tia, dây thép không gỉ tông bạc, hoạt động bằng ánh sáng.'],
            ['Citizen L Carina', 'Citizen9.webp', 7400000, 'Mẫu đồng hồ lấy cảm hứng từ chòm sao Carina, mặt số khảm trai cùng chi tiết lấp lánh.'],
            ['Citizen EM0930-80D', 'Citizen10.jpg', 5800000, 'Dây kim loại mảnh, mặt số xà cừ với họa tiết hoa tinh tế, phù hợp cổ tay nhỏ.'],
            ['Citizen Eco-Drive EM0683-58P', 'Citizen11.webp', 5100000, 'Thiết kế vàng champagne, mặt số đơn giản dễ nhìn, phong cách cổ điển nhẹ nhàng.'],
            ['Citizen L Gaia', 'Citizen12.webp', 9900000, 'Đồng hồ thân thiện môi trường, vỏ thép không gỉ tái chế, mặt số xà cừ xanh biển độc đáo.'],
            ['Citizen EQ9064-52D', 'Citizen13.jpg', 4150000, 'Vẻ đẹp tối giản, mặt số trắng bạc, dây kim loại vàng kết hợp bạc trẻ trung.'],
            ['Citizen EM0680-53D', 'Citizen14.jpg', 4880000, 'Mặt số khảm trai trắng, dây thép sáng bóng, phù hợp phong cách công sở hiện đại.'],
            ['Citizen Eco-Drive xC', 'Citizen15.jpg', 10600000, 'Kết hợp giữa công nghệ hiện đại và thiết kế thanh lịch Nhật Bản, mặt kính sapphire chống xước.'],
            ['Citizen EM0993-82X', 'Citizen16.jpg', 5600000, 'Dây kim loại vàng , mặt số la mã đơn giản, thiết kế thanh lịch hàng ngày.'],
            ['Citizen Eco-Drive EM0843-51D', 'Citizen17.webp', 6400000, 'Đính đá pha lê quanh viền, mặt số khảm trai trắng với hiệu ứng chuyển sắc tinh tế.'],
            ['Citizen L Ceci', 'Citizen18.jpg', 9200000, 'Thiết kế tinh xảo với dây da đen và mặt số được trang trí bằng các đường cong nghệ thuật.'],
            ['Citizen Eco-Drive EM0572-58P', 'Citizen19.webp', 5200000, 'Phong cách cổ điển, dây thép mạ vàng, mặt số đơn sắc nhẹ nhàng, thanh lịch.'],
            ['Citizen EM0896-89Y', 'Citizen20.jpg', 5900000, 'Dây kim loại rose gold, mặt số khảm trai hồng pastel, điểm nhấn kim xanh cá tính.'],
            ['Citizen Eco-Drive EM0492-02A', 'Citizen21.jpg', 4700000, 'Dây da nâu cổ điển, mặt số trắng tinh tế, phù hợp phong cách vintage nữ tính.'],
            ['Citizen EW1676-52D', 'Citizen22.webp', 6150000, 'Dây kim loại tông vàng phối bạc, mặt số khảm trai với cọc số ánh vàng sang trọng.'],
            ['Citizen EM0990-53L', 'Citizen23.png', 5650000, 'Thiết kế dây thép hiếm có, mặt số tông xanh – bạc tạo sự nổi bật.'],
            ['Citizen L Crystal Garden', 'Citizen24.jpg', 8900000, 'Đồng hồ đính pha lê Swarovski, họa tiết hoa mờ trên mặt số tạo hiệu ứng huyền ảo.'],
        ];      
        foreach ($products as [$name, $image, $price, $description]) {
            DB::table('products')->insert([
                'name' => $name,
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

        // -----------Rado nữ----------
        $category = Category::where('name', 'Nữ')->first();
        $brand = Brand::where('name', 'Rado')->first();

        if (!$category || !$brand) {
            $this->command->error('Không tìm thấy categories "Nữ" hoặc brands "Rado"!');
            return;
        }

        $products = [
            ['Rado Chic Quartz', 'Rado1.jpg', 15800000, 'Thiết kế sang trọng với viền đính kim cương và dây ceramic trắng.'],
            ['Rado Centrix Automatic Diamonds Open Heart', 'Rado2.jpg', 42100000, 'Thiết kế lộ cơ tinh tế, đính kim cương, dây thép phủ PVD vàng hồng.'],
            ['Rado Anatom Automatic Diamonds', 'Rado3.jpg', 26800000, 'Mặt kính vát cạnh liền dây ceramic, lộ cơ, đính kim cương.'],
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
            ['Seiko Classic White Dial', 'Seiko1.webp', 1250000, 'Mặt số trắng đơn giản, dây thép không gỉ, phù hợp đi làm hoặc đi học.'],
            ['Seiko Lady Two-Tone', 'Seiko2.webp', 2750000, 'Thiết kế phối màu vàng - bạc, mặt số nhỏ gọn, lịch sự và nữ tính.'],
            ['Seiko Quartz Leather Strap', 'Seiko3.jpg', 980000, 'Dây da đen mềm mại, mặt số đen cổ điển, phù hợp phong cách thanh lịch.'],
            ['Seiko Dress Silver Dial', 'Seiko4.jpg', 3190000, 'Vỏ thép bạc sáng, mặt trắng đính cọc số mảnh – trang nhã, dễ phối đồ.'],
            ['Seiko Solar Elegant', 'Seiko5.jpg', 6980000, 'Máy năng lượng mặt trời, mặt số hoa văn tinh tế, dây kim loại mỏng nhẹ.'],
            ['Seiko Petite Pink', 'Seiko6.jpg', 2450000, 'Mặt số hồng pastel, dây thép nhỏ gọn – dễ thương và trẻ trung.'],
            ['Seiko Basic Quartz Black', 'Seiko7.jpg', 1090000, 'Thiết kế tối giản với dây da đen, mặt trắng, phù hợp mọi dịp.'],
            ['Seiko Fashion Slim', 'Seiko8.jpg', 3720000, 'Dây thép ánh bạc, vỏ siêu mỏng nhẹ, thời trang và hiện đại.'],
            ['Seiko White Ceramic Accent', 'Seiko9.webp', 5490000, 'Dây phối ceramic trắng sáng, mặt số đính đá nhỏ, cao cấp.'],
            ['Seiko Vintage Champagne', 'Seiko10.jpg', 2950000, 'Mặt số ánh vàng champagne, dây kim loại kiểu mắt nhỏ cổ điển.'],
            ['Seiko Trendy Mesh Strap', 'Seiko11.jpg', 1890000, 'Dây lưới kim loại thời trang, mặt số xám đậm cá tính.'],
            ['Seiko Gold Plated Quartz', 'Seiko12.jpg', 4550000, 'Dây và vỏ mạ vàng, mặt trắng đơn giản – sang trọng, phù hợp đi tiệc.'],
            ['Seiko Casual Everyday', 'Seiko13.jpg', 1580000, 'Thiết kế đơn giản, dễ sử dụng hằng ngày, dây da nâu, mặt trắng.'],
            ['Seiko Rose Gold Tone', 'Seiko14.jpg', 3980000, 'Dây thép vàng hồng phối mặt trắng – nữ tính, hiện đại.'],
            ['Seiko Mini Dial Square', 'Seiko15.jpg', 990000, 'Mặt vuông nhỏ xinh, dây kim loại, phong cách Hàn Quốc nhẹ nhàng.'],
            ['Seiko Elegant Sunray Blue', 'Seiko16.jpg', 3280000, 'Mặt xanh ánh kim vân tia, dây kim loại bạc – cuốn hút và độc đáo.'],
            ['Seiko Silver Slim Date', 'Seiko17.webp', 2680000, 'Vỏ mỏng nhẹ, hiển thị ngày rõ ràng, tiện dụng và thanh lịch.'],
            ['Seiko Red Wine Leather', 'Seiko18.webp', 2190000, 'Dây da màu đỏ rượu, mặt trắng cổ điển, cho nàng phong cách chín chắn.'],
            ['Seiko Titanium Light', 'Seiko19.webp', 6990000, 'Chất liệu titanium siêu nhẹ, dây kim loại cao cấp, chống dị ứng.'],
            ['Seiko Blue Dial Crystal', 'Seiko20.jpg', 3590000, 'Mặt xanh dương đậm, cọc số đính đá, dây thép mắt nhỏ thanh lịch.'],
            ['Seiko Gold Mesh Limited', 'Seiko21.jpg', 6090000, 'Dây lưới vàng thời trang, mặt số khảm đá nhỏ, thiết kế giới hạn.'],
            ['Seiko Casual Pink Leather', 'Seiko22.webp', 1320000, 'Dây da hồng dễ thương, phù hợp với học sinh – sinh viên.'],
            ['Seiko Modern Line Black', 'Seiko23.jpg', 2790000, 'Mặt đen nhám, dây thép mờ xám – hiện đại, hợp xu hướng.'],
            ['Seiko SSA880 Sportura', 'Seiko24.webp', 6790000, 'Dây thép tích hợp ceramic trắng, mặt đơn giản với cửa sổ lộ cơ nhỏ, kiểu dáng thanh lịch và hiện đại.'],
        ];

            foreach ($products as [$name, $image, $price, $description]) {
                DB::table('products')->insert([
                'name' => $name,
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
        //---------------------------Casio cặp--------------------------
        $category = Category::where('name', 'Cặp đôi')->first();
        $brand = Brand::where('name', 'Casio')->first();

        if (!$category || !$brand) {
            $this->command->error('Không tìm thấy categories "Cặp đôi" hoặc brands "Casio"!');
            return;
        }

        $products = [
            ['Casio Couple Classic Black', 'CasioCouple1.jpg', 3120000, 'Thiết kế tối giản, dây cao su đen bền bỉ – thích hợp cho cặp đôi yêu thích sự giản dị.'],
            ['Casio His & Her Silver', 'CasioCouple2.jpg', 4650000, 'Cặp đôi dây kim loại bạc sang trọng, mặt số đơn giản phù hợp đi làm và hẹn hò.'],
            ['Casio Romantic Rose Gold', 'CasioCouple3.jpg', 5920000, 'Thiết kế viền vàng hồng thời thượng, dành cho cặp đôi yêu phong cách nhẹ nhàng.'],
            ['Casio Sporty Duo Black-Gold', 'CasioCouple4.jpg', 5290000, 'Cặp đôi phong cách thể thao, mặt đen viền vàng khỏe khoắn và cá tính.'],
            ['Casio Minimal Brown Leather', 'CasioCouple5.jpg', 3890000, 'Dây da nâu thanh lịch, mặt số gọn nhẹ – dành cho cặp đôi yêu thích sự cổ điển.'],
        ];

        foreach ($products as [$name, $image, $price, $description]) {
            DB::table('products')->insert([
                'name' => $name,
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



//---------------------------Rolex cặp--------------------------
        $category = Category::where('name', 'Cặp đôi')->first();
        $brand = Brand::where('name', 'Rolex')->first();

        if (!$category || !$brand) {
            $this->command->error('Không tìm thấy categories "Cặp đôi" hoặc brands "Casio"!');
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
            $this->command->error('Không tìm thấy categories "Cặp đôi" hoặc brands "Casio"!');
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
            $this->command->error('Không tìm thấy categories "Cặp đôi" hoặc brands "Casio"!');
            return;
        }

        $products = [
            ['Citizen Couple Eco-Drive AW1234-83P', 'CitizenCouple.jpg', 8350000, 'Nam AW1234 mặt đen, nữ AW1234 mặt bạc – bộ đôi Citizen Eco‑Drive sạc năng lượng ánh sáng, không dùng pin.'],
            ];


        foreach ($products as [$name, $image, $price, $description]) {
            DB::table('products')->insert([
                'name' => $name,
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
        $brand = Brand::where('name', 'Seiko')->first();

        if (!$category || !$brand) {
            $this->command->error('Không tìm thấy categories "Cặp đôi" hoặc brands "Casio"!');
            return;
        }

       $products = [
    ['Seiko 5 Classic Black-Gold Pair', 'SeikoCouple1.jpg', 3000000, 'Nam mặt đen, nữ mặt vàng demi, dây thép mạ vàng và bạc – phong cách trang nhã.'],
    ['Seiko 5 Silver-Gold Two-Tone Set', 'SeikoCouple2.webp', 3200000, 'Mặt trắng & trắng, dây thép hai tầng vàng‑bạc – đôi hoàn hảo cho mọi sự kiện.'],
    ['Seiko SUR525P1 & SUR531P1', 'SeikoCouple3.jpg', 2800000, 'Nam SUR525P1 xanh dương và nữ SUR531P1 trắng, thiết kế nhẹ nhàng, kính sapphire chất lượng cao.' ],
    ['Seiko SRP/SRP His & Hers Military', 'SeikoCouple4.jpg', 3500000, 'Nam SRP227 & nữ SRP189 – phong cách quân đội, tự động (automatic), đồng bộ dây và bộ máy.' ],
];



        foreach ($products as [$name, $image, $price, $description]) {
            DB::table('products')->insert([
                'name' => $name,
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
            ['Casio World Time AE-1200WHD-1A', 'casio-nam-1.jpg', 750000, 'Digital world time, pin 10 năm, WR 100m, nhiều múi giờ + báo thức – tiện dụng & bền bỉ.'],
            ['Casio AE-1300WH Analog-Digital', 'casio-nam-2.jpg', 900000, 'Kết hợp kim & số điện tử, WR 100m, led, báo thức – thể thao & linh hoạt.'],
            ['Casio AMW-870DA-2A2V Analog-Digital Blue', 'casio-nam-3.jpg', 2000000, 'Mặt xanh, vỏ thép + dây da, WR 100m, kim số & điện tử – lịch lãm & đa năng.'],
            ['Casio MTD-130D-1A2V Analog Stainless Steel', 'casio-nam-4.jpg', 1800000, 'Quartz thép không gỉ, WR 100m, thiết kế đơn giản & sang trọng hàng ngày.'],
            ['Casio Tough Solar AQ-S810W-5AV', 'casio-nam-5.jpg', 690000, 'Tough Solar, kim + số điện tử, WR 100m, 5 báo thức – bền bỉ & đa chức năng.'],
            ['Casio Vintage Calculator CA-53WF-8B', 'casio-nam-6.jpg', 375000, 'Máy tính 8 số, stopwatch, giờ kép – thiết kế retro tiện dụng.'],
            ['Casio W-737H-8AV Digital 10-Year Battery', 'casio-nam-7.jpg', 850000, 'Điện tử full màn hình, pin 10 năm, WR 100m, thiết kế mạnh mẽ & thực dụng.'],
            ['Casio MRW-200H-1BV Analog Diver', 'casio-nam-8.jpg', 1140000, 'Analog diver style, WR 100m, mặt trắng dễ đọc, kim đỏ nổi bật – đơn giản & thực dụng.'],
            ['Casio MTP-V002D-7B3', 'casio-nam-9.jpg', 390000, 'Thiết kế đơn giản, mặt trắng dây nhựa đen, lịch ngày – dễ đeo, giá rẻ.'],
            ['Casio A168WEGB-1BVT', 'casio-nam-10.jpg', 780000, 'Thiết kế retro, mặt số điện tử, viền vàng, dây đen – phong cách cổ điển cá tính.'],
            ['Casio MRW-200HD-2BV', 'casio-nam-11.jpg', 950000, 'Diver style, mặt xanh ngọc, dây thép không gỉ, WR 100m – năng động và bền bỉ.'],
            ['Casio MDV-107D-2A2V', 'casio-nam-12.jpg', 1190000, 'Dòng lặn 200m, mặt trắng viền xanh, dây thép – mạnh mẽ và dễ phối đồ.'],
            ['Casio MTD-E501D-5EVDF', 'casio-nam-13.jpg', 1650000, 'Chronograph 42 mm, vỏ & dây thép mạ bạc, mặt beige – lịch lãm & tiện dụng hàng ngày.'],
            ['Casio A700WE-2A Vintage Slim Digital', 'casio-nam-14.jpg', 700000, 'Digital mặt xanh nhạt, thiết kế mỏng, pin nhiều năm – retro & nhẹ nhàng.'],
            ['Casio LTP-V007D-1B Elegance Analog', 'casio-nam-15.jpg', 800000, 'Mặt chữ nhật đen, kim La Mã, vỏ & dây thép mạ bạc – cổ điển & thanh lịch.'],
            ['Casio MTP-V006G-3B1 Day-Date', 'casio-nam-16.jpg', 950000, 'Analog mặt xanh lá, lịch ngày & thứ, vỏ & dây thép mạ vàng – nổi bật & thanh thoát.'],
            ['Casio MTP-V002D-1B3 Day-Date Analog', 'casio-nam-17.jpg', 900000, 'Analog mặt đen, lịch ngày-thứ, vỏ & dây thép – tối giản & dễ phối.'],
            ['Casio MDV-106D-9A Diver 200m', 'casio-nam-18.jpg', 1450000, 'Diver 200m, mặt vàng bold, bezel xoay, dây thép – mạnh mẽ & nổi bật.'],
            ['Casio MTP-1302L-7BV', 'casio-nam-19.jpg', 580000, 'Analog mặt trắng, kim số to, dây da nâu – thanh lịch & phổ thông.'],
            ['Casio MTP-V003D-1B3 Day-Date', 'casio-nam-20.jpg', 900000, 'Analog mặt đen, lịch ngày-thứ, vỏ & dây thép – đơn giản & tinh tế.'],
            ['Casio Edifice EFR-S572PB-1AV', 'casio-nam-21.jpg', 2100000, 'Chronograph 45 mm, vỏ thép + bezel resin, WR 100m – thể thao & chắc chắn.'],
            ['Casio Illuminator F-108WH-8A', 'casio-nam-22.jpg', 780000, 'Digital chắc chắn, WR 50m, pin nhiều năm, đèn nền – retro & bền.'],
            ['Casio Edifice EFR-S108D-2AV', 'casio-nam-23.jpg', 1700000, 'Chronograph mặt xanh nhạt, vỏ & dây thép, WR 100m – thanh lịch & năng động.'],
            ['Casio A168WEGG-1BEF', 'casio-nam-24.jpg', 650000, 'Digital iconic, viền & dây vàng đen, pin lâu – retro cá tính hàng ngày.'],
        ];

        foreach ($products as [$name, $image, $price, $description]) {
            DB::table('products')->insert([
                'name' => $name,
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

 // ---------Citizen nam-----------------
        $category = Category::where('name', 'Nam')->first();
        $brand  = Brand::where('name', 'Citizen')->first();

        if (!$category || !$brand) {
            $this->command->error('Không tìm thấy categories "Nam" hoặc brands "Citizen"!');
            return;
        }

        $products = [
            ['Citizen Eco‑Drive Chronograph Blue Dial AT8020-54L', 'citizen-nam-1.webp', 23500000, 'Chronograph 43 mm, Eco‑Drive, chronometer, sapphire, thép bạc mặt xanh – kỹ thuật & chuyên nghiệp.'],
            ['Citizen Promaster Automatic Diver Brown Dial NB6021-17X', 'citizen-nam-2.webp', 10300000, 'Automatic Diver 44 mm, WR 200 m, mặt nâu, thép + bezel nâu – mạnh mẽ & phiêu lưu.'],
            ['Citizen Promaster Diver Eco‑Drive BN0151-17L', 'citizen-nam-3.webp', 5300000, 'Eco‑Drive Diver 200 m, mặt xanh, thép – giá mềm, bền bỉ và phục vụ lặn.'],
            ['Citizen AT0550-11X Sporty Chronograph Eco‑Drive Brown', 'citizen-nam-4.webp', 4500000, 'Chronograph 42 mm, Eco‑Drive, mặt nâu, dây da – thể thao & hiện đại.'],
            ['Citizen Eco‑Drive Day-Date Silver Dial', 'citizen-nam-5.webp', 2950000, 'Eco‑Drive, lịch ngày-thứ, mặt bạc tia nắng, vỏ & dây thép – thanh lịch & bền.'],
            ['Citizen Quartz Day-Date Gold PVD', 'citizen-nam-6.webp', 2400000, 'Quartz, lịch ngày-thứ, mạ vàng PVD, mặt vàng kim La Mã – cổ điển & sang trọng.'],
            ['Citizen Quartz Day-Date Blue Dial', 'citizen-nam-7.webp', 2950000, 'Quartz, lịch ngày-thứ, mặt xanh navy, vỏ & dây thép – phổ thông & dễ dùng.'],
            ['Citizen Quartz Black Dial Gold Case', 'citizen-nam-8.webp', 2700000, 'Quartz, mặt đen, kim vàng, vỏ mạ vàng, dây da đen – nổi bật & thanh nhã.'],
            ['Citizen GMT Two-Tone Gold Black', 'citizen-nam-9.webp', 7200000, 'GMT 40 mm, vỏ & dây hai tông vàng‑thép, bezel xoay, dial carbon – chuyên nghiệp & mạnh mẽ.'],
            ['Citizen Automatic Blue Dial Stainless', 'citizen-nam-10.webp', 4150000, 'Automatic, lịch ngày, mặt xanh tia nắng, thép – tinh tế & chất mechanism.'],
            ['Citizen Automatic Titanium Silver', 'citizen-nam-11.webp', 5400000, 'Automatic, titanium nhẹ, mặt bạc, lịch ngày – bền bỉ & cao cấp.'],
            ['Citizen Eco‑Drive Chronograph Blue Dial', 'citizen-nam-12.webp', 5250000, 'Eco‑Drive chronograph, mặt xanh, kim số, dây thép – thể thao & đa dùng.'],
            ['Citizen Eco‑Drive Diver Green Dial', 'citizen-nam-13.webp', 7200000, 'Eco‑Drive Diver 200m, mặt xanh lá, thép + bezel xoay – năng động & bền bỉ.'],
            ['Citizen Eco‑Drive Diver Black Dial Two‑Tone', 'citizen-nam-14.webp', 7200000, 'Diver 200m, mặt đen + bezel vàng, dây thép hai tông – thể thao & lịch lãm.'],
            ['Citizen Automatic Beach Blue Dial', 'citizen-nam-15.webp', 6500000, 'Automatic 40mm, mặt xanh biển tia nắng, vỏ & dây thép – tinh tế & cơ khí.'],
            ['Citizen Promaster Series RT Pilot', 'citizen-nam-16.webp', 17500000, 'Eco‑Drive GMT, nhiều múi giờ, dây titanium – phi công & công nghệ cao.'],
            ['Citizen Eco‑Drive Chronograph Blue Leather', 'citizen-nam-17.webp', 6200000, 'Chronograph Eco‑Drive, mặt xanh, dây da đen – thể thao & lịch lãm.'],
            ['Citizen Quartz Green Dial Two‑Tone', 'citizen-nam-18.webp', 3800000, 'Quartz, mặt xanh ngọc, vỏ thép + bezel vàng – nổi bật & thanh nhã.'],
            ['Citizen Automatic TC Automatic Silver', 'citizen-nam-19.webp', 5400000, 'Automatic titanium nhẹ, mặt bạc, lịch ngày – công nghệ & bền bỉ.'],
            ['Citizen Eco‑Drive Chronograph Blue Pattern', 'citizen-nam-20.webp', 5600000, 'Eco‑Drive chronograph, mặt xanh họa tiết, dây thép – thể thao & thanh lịch.'],
            ['Citizen Automatic Sand Dial', 'citizen-nam-21.webp', 4500000, 'Automatic, mặt cát tia nắng, thép – cổ điển & cơ khí.'],
            ['Citizen Eco‑Drive Executive Gold Steel', 'citizen-nam-22.webp', 8000000, 'Eco‑Drive, mặt xanh trơn, dây thép bicolor vàng – sang trọng & tinh tế.'],
            ['Citizen Automatic Diver Turquoise Dial', 'citizen-nam-23.webp', 6500000, 'Automatic Diver 200m, mặt xanh ngọc, dây cao su – thể thao & nổi bật.'],
            ['Citizen Eco‑Drive Minimal Blue Gold', 'citizen-nam-24.webp', 6300000, 'Eco‑Drive, mặt xanh cổ điển, dây thép bicolor – thanh lịch & tối giản.'],
        ];

        foreach ($products as [$name, $image, $price, $description]) {
            DB::table('products')->insert([
                'name' => $name,
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
            ['Seiko 5 Sports Automatic Black', 'seiko-nam-1.webp', 4150000, 'Thiết kế thể thao mạnh mẽ, dây thép không gỉ và bộ máy cơ tự động.'],
            ['Seiko Prospex Speedtimer Chronograph', 'seiko-nam-2.webp', 8990000, 'Đồng hồ bấm giờ thể thao, mặt xanh xám cá tính – chuẩn phong cách mạnh mẽ.'],
            ['Seiko Presage Champagne Dial', 'seiko-nam-3.webp', 7390000, 'Mặt vàng champagne cổ điển, dây demi lịch lãm – dành cho quý ông tinh tế.'],
            ['Seiko Presage Skeleton Black', 'seiko-nam-4.webp', 8690000, 'Thiết kế lộ cơ độc đáo, dây da cao cấp – sang trọng và kỹ thuật.'],
            ['Seiko Presage Open Heart Gold', 'seiko-nam-5.webp', 8390000, 'Mặt vàng nhạt tinh tế, lộ máy góc 9h – vẻ đẹp cơ khí kết hợp truyền thống.'],
            ['Seiko Prospex Panda Chronograph', 'seiko-nam-6.webp', 8990000, 'Mặt trắng viền đen “Panda” độc đáo, chronograph thể thao chuyên nghiệp.'],
            ['Seiko 5 Two-Tone Silver Gold', 'seiko-nam-7.webp', 5650000, 'Tông vàng-trắng thanh lịch, hiển thị lịch thứ ngày tiện dụng.'],
            ['Seiko Neo Classic Black Dial', 'seiko-nam-8.webp', 5290000, 'Mặt đen đơn giản hiện đại, vỏ thép không gỉ – dễ phối đồ hằng ngày.'],
            ['Seiko 5 GMT Automatic', 'seiko-nam-9.webp', 6190000, 'Mặt số dễ đọc, kim GMT nổi bật – thích hợp cho người hay di chuyển.'],
            ['Seiko Presage Textured Grey', 'seiko-nam-10.webp', 8490000, 'Mặt xám vân lụa độc đáo, dây thép chắc chắn – sang trọng và tinh xảo.'],
            ['Seiko Presage Blue Open Heart', 'seiko-nam-11.webp', 8990000, 'Mặt xanh sapphire, lộ cơ và đồng hồ phụ – đẳng cấp và hiện đại.'],
            ['Seiko 5 Sports Green Leather', 'seiko-nam-12.webp', 4890000, 'Mặt xanh lá cá tính, dây da tối giản – thể thao, phong trần và độc đáo.'],
            ['Seiko Presage Brown Leather', 'seiko-nam-13.webp', 7690000, 'Mặt nâu vân guilloché tinh xảo, dây da nâu – sang trọng và cổ điển.'],
            ['Seiko 5 Military Field Beige', 'seiko-nam-14.webp', 3790000, 'Thiết kế quân đội mạnh mẽ, dây vải kaki bền bỉ – phù hợp dã ngoại.'],
            ['Seiko Presage Cocktail Green', 'seiko-nam-15.webp', 7990000, 'Mặt xanh lục ánh tia, dây da nâu – thanh lịch và nổi bật.'],
            ['Seiko Chronograph Sporty Nato', 'seiko-nam-16.webp', 6950000, 'Mặt xanh đậm phối cam, dây vải NATO – đậm chất thể thao năng động.'],
            ['Seiko Presage Ice Blue Mesh', 'seiko-nam-17.webp', 8750000, 'Mặt xanh băng độc đáo, dây lưới kim loại – hiện đại và cá tính.'],
            ['Seiko 5 Sports Orange Dial', 'seiko-nam-18.webp', 5650000, 'Mặt cam nổi bật, dây vải thể thao – thiết kế cá tính và trẻ trung.'],
            ['Seiko Prospex Tuna Black Rubber', 'seiko-nam-19.webp', 11250000, 'Dáng Tuna huyền thoại, dây cao su đen – chuyên dụng cho lặn.'],
            ['Seiko Presage Blue Open Heart', 'seiko-nam-20.webp', 9190000, 'Mặt xanh trời thanh lịch, lộ cơ tinh xảo – phù hợp công sở và tiệc.'],
            ['Seiko 5 Sports Explorer Style', 'seiko-nam-21.webp', 4250000, 'Mặt đen đơn giản, kim dạ quang – dây thép chắc chắn, dễ sử dụng.'],
            ['Seiko Presage Roman Open Heart', 'seiko-nam-22.webp', 8990000, 'Thiết kế cổ điển La Mã, lộ cơ nghệ thuật – đậm chất thanh lịch.'],
            ['Seiko Prospex Urban Safari', 'seiko-nam-23.webp', 10290000, 'Mặt đen mạnh mẽ, dây da lộn – phong cách phiêu lưu và bền bỉ.'],
            ['Seiko Presage Style 60s Black', 'seiko-nam-24.webp', 8850000, 'Mặt đen hoài cổ, viền nâu vàng vintage – kết hợp giữa cổ điển và hiện đại.'],
        ];

        foreach ($products as [$name, $image, $price, $description]) {
            DB::table('products')->insert([
                'name' => $name,
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

    }
}