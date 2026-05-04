<?php

namespace Database\Seeders;

use App\Models\ServicePage;
use Illuminate\Database\Seeder;

/**
 * CMS service pages (airport taxi, visa, tickets, SIM).
 * Re-running overwrites translations — baseline suitable for staging / first production deploy.
 */
class ServicePagesSeeder extends Seeder
{
    public function run(): void
    {
        foreach ($this->pages() as $type => $translations) {
            ServicePage::query()->updateOrCreate(
                ['type' => $type],
                [
                    'status' => ServicePage::STATUS_ACTIVE,
                    'translations' => $translations,
                ]
            );
        }
    }

    /**
     * @return array<string, array<string, array{title: string, content: string}>>
     */
    private function pages(): array
    {
        return [
            ServicePage::TYPE_AIRPORT_TAXI => [
                'en' => [
                    'title' => 'Airport taxi & private transfers',
                    'content' => <<<'HTML'
<h2>Door-to-door transfers</h2>
<p>Book reliable airport pickups and intercity transfers with licensed drivers and comfortable vehicles. We monitor flight delays where possible and align meet-and-greet instructions with your arrival terminal.</p>

<h2>Where we serve</h2>
<p>Popular routes include Noi Bai (HAN), Tan Son Nhat (SGN), Da Nang (DAD), Cam Ranh (CXR), and Phu Quoc (PQC). Custom routing between hotels, cruise ports, and train stations is available on request.</p>

<h2>Fleet & safety</h2>
<ul>
<li>Modern sedans, SUVs, and vans subject to availability</li>
<li>Seat belts, AC, and bottled water on longer journeys</li>
<li>Drivers briefed on safe pacing and luggage handling</li>
</ul>

<h2>How to book</h2>
<p>Share your flight number, arrival time, passenger count, and luggage profile. We confirm vehicle class, fixed-route pricing where applicable, and emergency contact before travel day.</p>
HTML,
                ],
                'vi' => [
                    'title' => 'Taxi sân bay & xe đưa đón riêng',
                    'content' => <<<'HTML'
<h2>Đưa đón trọn gói</h2>
<p>Đặt xe đón sân bay và chặng liên tỉnh với tài xế có giấy phép và xe thoải mái. Chúng tôi theo dõi delay chuyến bay khi có thông tin và thống nhất điểm đón theo nhà ga đến.</p>

<h2>Khu vực phục vụ</h2>
<p>Các tuyến thường gặp: Nội Bài (HAN), Tân Sơn Nhất (SGN), Đà Nẵng (DAD), Cam Ranh (CXR), Phú Quốc (PQC). Nhận lịch đưa đón giữa khách sạn, cảng tàu và ga khi có yêu cầu.</p>

<h2>Xe & an toàn</h2>
<ul>
<li>Sedan, SUV, xe van tùy tình trạng kho xe</li>
<li>Dây an toàn, điều hòa, nước uống cho chặng dài</li>
<li>Tài xế được nhắc nhở về tốc độ an toàn và hỗ trợ hành lý</li>
</ul>

<h2>Đặt dịch vụ</h2>
<p>Vui lòng gửi số hiệu chuyến bay, giờ hạ cánh, số khách và mô tả hành lý. Chúng tôi xác nhận hạng xe, mức giá cố định (nếu áp dụng) và số liên hệ khẩn trước ngày đi.</p>
HTML,
                ],
            ],
            ServicePage::TYPE_VISA => [
                'en' => [
                    'title' => 'Visa support',
                    'content' => <<<'HTML'
<h2>Guidance, not legal advice</h2>
<p>Visa rules change by nationality and purpose of travel. We help you understand common pathways—e-visas, exemptions, and embassy submissions—and prepare checklists aligned with official portals.</p>

<h2>What we typically assist with</h2>
<ul>
<li>Document checklist (passport validity, photos, itinerary proofs)</li>
<li>Application sequencing for Vietnam’s e-visa portal where eligible</li>
<li>Timeline expectations during peak seasons</li>
</ul>

<h2>Important</h2>
<p>Final visa issuance is determined solely by Vietnamese immigration authorities. Allow adequate processing time before issuing international flights.</p>

<h2>Next step</h2>
<p>Message us with passport country, planned entry date, and port of entry. We’ll outline the most suitable option and what to prepare.</p>
HTML,
                ],
                'vi' => [
                    'title' => 'Hỗ trợ visa',
                    'content' => <<<'HTML'
<h2>Tư vấn, không thay thế tư pháp</h2>
<p>Quy định visa thay đổi theo quốc tịch và mục đích nhập cảnh. Chúng tôi giúp bạn nắm các hướng phổ biến—e-visa, miễn thị thực, nộp hồ sơ tại ĐSQ/LHQ—and kiểm tra danh mục giấy tờ sát với cổng chính thức.</p>

<h2>Chúng tôi thường hỗ trợ</h2>
<ul>
<li>Checklist giấy tờ (hộ chiếu, ảnh, chứng minh lịch trình)</li>
<li>Trình tự nộp e-visa Việt Nam khi đủ điều kiện</li>
<li>Ước tính thời gian xử lý trong mùa cao điểm</li>
</ul>

<h2>Lưu ý</h2>
<p>Quyết định cấp visa thuộc cơ quan quản lý xuất nhập cảnh. Nên chừa thời gian xử lý trước khi chốt vé quốc tế.</p>

<h2>Liên hệ</h2>
<p>Gửi quốc tịch hộ chiếu, ngày dự kiến nhập cảnh và cửa khẩu để nhận hướng dẫn phù hợp nhất.</p>
HTML,
                ],
            ],
            ServicePage::TYPE_BUS_FLIGHT_TRAIN => [
                'en' => [
                    'title' => 'Bus, flight & train tickets',
                    'content' => <<<'HTML'
<h2>Domestic connectivity</h2>
<p>Vietnam’s trunk routes link major cities by air, rail, and sleeper bus. We help you compare realistic schedules, baggage rules, and seat classes that match your tour pacing.</p>

<h2>Air</h2>
<p>Short hops (e.g., Danang–HCMC, Hanoi–Hue) are often most time-efficient. We coordinate ticket issuance with named passengers and correct travel dates aligned to your land program.</p>

<h2>Train & bus</h2>
<p>Overnight trains and Limousine/VIP buses can be cost-effective. We explain station protocols, approximate durations, and comfort trade-offs so expectations stay clear.</p>

<h2>Booking process</h2>
<p>Provide route, date window, passenger names as per passport, and luggage needs. Availability fluctuates near holidays—early requests secure better inventory.</p>
HTML,
                ],
                'vi' => [
                    'title' => 'Vé xe khách, máy bay & tàu hỏa',
                    'content' => <<<'HTML'
<h2>Kết nối nội địa</h2>
<p>Các trục chính của Việt Nam kết nối TP lớn bằng máy bay, đường sắt và xe cabin giường nằm. Chúng tôi giúp bạn đối chiếu lịch thực tế, quy định hành lý và hạng chỗ phù hợp nhịp tour.</p>

<h2>Hàng không</h2>
<p>Các chặng ngắn (ví dụ Đà Nẵng–TP.HCM, Hà Nội–Huế) thường tiết kiệm thời gian. Chúng tôi phối hợp xuất vé đúng họ tên hộ chiếu và ngày khớp chương trình.</p>

<h2>Tàu & xe</h2>
<p>Tàu đêm và xe limousine/VIP có thể tiết kiệm chi phí. Chúng tôi giải thích quy trình ga/trạm, thời gian di chuyển và đánh đổi tiện nghi để kỳ vọng rõ ràng.</p>

<h2>Đặt vé</h2>
<p>Vui lòng gửi tuyến, khung ngày, họ tên theo hộ chiếu và nhu cầu hành lý. Gần lễ Tết chỗ khan — đặt sớm giúp giữ inventory tốt hơn.</p>
HTML,
                ],
            ],
            ServicePage::TYPE_SIM => [
                'en' => [
                    'title' => 'Travel SIM & data',
                    'content' => <<<'HTML'
<h2>Stay connected</h2>
<p>Pick up a local data SIM or eSIM guidance (where supported) so maps, messaging, and ride-hailing work smoothly from day one.</p>

<h2>What to expect</h2>
<ul>
<li>Prepaid bundles with generous data allowances for typical travel use</li>
<li>Activation steps in English / Vietnamese depending on carrier pack</li>
<li>Pickup options: airport counters, hotel delivery in select cities, or shipped where available</li>
</ul>

<h2>Compatibility</h2>
<p>Confirm your handset is unlocked and supports local LTE bands. Dual-SIM phones can keep your home number on inactive data while using Vietnam data on the second slot.</p>

<h2>Order</h2>
<p>Tell us arrival airport or hotel, travel dates, and approximate data needs. We recommend a plan that avoids mid-trip top-up stress.</p>
HTML,
                ],
                'vi' => [
                    'title' => 'SIM du lịch & dữ liệu',
                    'content' => <<<'HTML'
<h2>Luôn kết nối</h2>
<p>Nhận SIM dữ liệu tại chỗ hoặc hướng dẫn eSIM (khi máy và gói hỗ trợ) để bản đồ, nhắn tin và app gọi xe hoạt động ngay từ ngày đầu.</p>

<h2>Thông tin gói</h2>
<ul>
<li>Gói trả trước với dung lượng phù hợp nhu cầu du lịch thông thường</li>
<li>Hướng dẫn kích hoạt song ngữ tùy nhà mạng</li>
<li>Nhận SIM: quầy sân bay, giao khách sạn tại một số thành phố hoặc chuyển phát khi có</li>
</ul>

<h2>Tương thích thiết bị</h2>
<p>Vui lòng xác nhận máy đã mở khóa mạng và hỗ trợ băng tần LTE địa phương. Máy 2 SIM có thể giữ SIM nhà không dùng data và chạy data Việt Nam ở khe thứ hai.</p>

<h2>Đặt hàng</h2>
<p>Cho biết sân bay hoặc khách sạn nhận, ngày di chuyển và nhu cầu data ước tính — chúng tôi đề xuất gói hạn chế nạp tiền giữa chừng.</p>
HTML,
                ],
            ],
        ];
    }
}
