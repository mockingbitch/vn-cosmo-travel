<?php

namespace Database\Seeders;

use App\Models\AboutPage;
use Illuminate\Database\Seeder;

/**
 * Singleton about page — copy is suitable for first deploy / staging.
 * Re-running overwrites translations (idempotent baseline for demo).
 */
class AboutPageSeeder extends Seeder
{
    public function run(): void
    {
        $payload = [
            'status' => AboutPage::STATUS_ACTIVE,
            'translations' => [
                'en' => [
                    'title' => 'About Vietnam Cosmo Travel',
                    'content' => $this->contentEn(),
                ],
                'vi' => [
                    'title' => 'Về Vietnam Cosmo Travel',
                    'content' => $this->contentVi(),
                ],
            ],
        ];

        $page = AboutPage::query()->first();
        if ($page instanceof AboutPage) {
            $page->update($payload);
        } else {
            AboutPage::query()->create($payload);
        }
    }

    private function contentEn(): string
    {
        return <<<'HTML'
<h2>Who we are</h2>
<p>Vietnam Cosmo Travel is a destination management team focused on authentic experiences across Vietnam—from the karst seascapes of Ha Long Bay and the imperial streets of Hue to the Mekong Delta and dynamic Ho Chi Minh City. We combine local expertise with responsive support so you can travel with clarity and confidence.</p>

<h2>What we do</h2>
<p>We design and operate private tours, small-group itineraries, airport transfers, and add-on services such as visa guidance, domestic transport tickets, and connectivity (SIM). Every request is handled by specialists who understand routing, seasonal demand, and realistic pacing for each region.</p>

<h2>How we work</h2>
<ul>
<li><strong>Transparent planning</strong> — clear inclusions, realistic timing, and upfront notes on optional activities.</li>
<li><strong>Local partners, vetted standards</strong> — boats, vehicles, and guides selected for safety and service consistency.</li>
<li><strong>Responsive coordination</strong> — a single point of contact before and during your trip for adjustments.</li>
</ul>

<h2>Responsible travel</h2>
<p>We encourage low-impact choices where possible—respecting heritage sites, supporting community-run experiences, and minimizing single-use plastics on our coordinated services. Ask our team about culturally sensitive visits and meaningful encounters beyond generic photo stops.</p>

<h2>Contact</h2>
<p>For itineraries, quotations, or partnership enquiries, reach us via the contact details on our website. We typically reply within one business day (Vietnam time).</p>
HTML;
    }

    private function contentVi(): string
    {
        return <<<'HTML'
<h2>Chúng tôi là ai</h2>
<p>Vietnam Cosmo Travel là đội ngũ vận hành và thiết kế hành trình tại chỗ, hướng tới những trải nghiệm chân thực khắp Việt Nam—từ vịnh Hạ Long, cố đô Huế đến Đồng bằng sông Cửu Long và TP. Hồ Chí Minh sôi động. Chúng tôi kết hợp hiểu biết địa phương với hỗ trợ nhanh để bạn an tâm khi lên kế hoạch và di chuyển.</p>

<h2>Dịch vụ</h2>
<p>Chúng tôi thiết kế và điều phối tour riêng, nhóm nhỏ, đưa đón sân bay, cùng các dịch vụ kèm theo như tư vấn visa, vé nội địa và SIM. Mỗi yêu cầu được xử lý bởi chuyên viên am hiểu lịch trình, mùa cao điểm và nhịp độ hợp lý cho từng vùng miền.</p>

<h2>Cách làm việc</h2>
<ul>
<li><strong>Rõ ràng</strong> — phạm vi dịch vụ, thời gian và hoạt động tự chọn được giải thích thẳng thắn.</li>
<li><strong>Đối tác được kiểm duyệt</strong> — tàu, xe và hướng dẫn được chọn theo tiêu chí an toàn và chất lượng phục vụ.</li>
<li><strong>Đồng hành linh hoạt</strong> — một đầu mối liên lạc trước và trong chuyến đi khi cần điều chỉnh.</li>
</ul>

<h2>Du lịch có trách nhiệm</h2>
<p>Chúng tôi khuyến khích lựa chọn ít tác động khi có thể—tôn trọng di sản, ưu tiên trải nghiệm gắn cộng đồng và hạn chế rác thải nhựa dùng một lần trong phạm vi dịch vụ do chúng tôi phối hợp. Hãy hỏi team về lịch trình văn minh và trải nghiệm ý nghĩa ngoài các điểm chụp ảnh đại trà.</p>

<h2>Liên hệ</h2>
<p>Với báo giá, thiết kế lịch trình hoặc hợp tác, vui lòng liên hệ qua thông tin trên website. Chúng tôi thường phản hồi trong một ngày làm việc (giờ Việt Nam).</p>
HTML;
    }
}
