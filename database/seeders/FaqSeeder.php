<?php

namespace Database\Seeders;

use App\Enums\FaqCategory;
use App\Models\Faq;
use Illuminate\Database\Seeder;

class FaqSeeder extends Seeder
{
    public function run(): void
    {
        $faqs = [
            // Sizing
            ['category' => FaqCategory::Sizing, 'sort_order' => 1, 'question' => 'How do I measure my nails correctly?', 'answer' => 'Use the live camera guide on the order form — it takes about 90 seconds. You\'ll take two close-up photos (fingers row + thumb) with a coin in frame as a size reference. The coin lets us calculate your exact nail width from the photo.'],
            ['category' => FaqCategory::Sizing, 'sort_order' => 2, 'question' => 'What if the sizing is off?', 'answer' => 'Your first refit is completely free. If a nail doesn\'t sit right, send me a photo on WhatsApp and I\'ll remake the affected sizes and ship them out, no charge. Getting the fit right matters to me.'],
            ['category' => FaqCategory::Sizing, 'sort_order' => 3, 'question' => 'Can I use a saved sizing profile from a previous order?', 'answer' => 'Yes — returning customers can skip the sizing step entirely. During checkout, enter your phone or email and I\'ll use the measurements on file. Your profile is saved after your first order.'],

            // Application
            ['category' => FaqCategory::Application, 'sort_order' => 1, 'question' => 'How long do press-on nails last?', 'answer' => 'With proper prep (buffed, alcohol-wiped, oil-free nails), custom-fit press-ons typically last 5–10 days. Everyday activities like typing and cooking are fine. Prolonged water exposure — washing dishes without gloves, swimming — shortens wear time.'],
            ['category' => FaqCategory::Application, 'sort_order' => 2, 'question' => 'Glue or adhesive tabs — which should I use?', 'answer' => 'Tabs for daily wear and frequent removal (e.g. if you remove for wudu). Glue for special occasions when you need maximum hold. Both are included with your order. Tabs release cleanly in warm water; glue gives a stronger bond but takes longer to remove.'],
            ['category' => FaqCategory::Application, 'sort_order' => 3, 'question' => 'How do I remove press-on nails?', 'answer' => 'Soak your fingertips in warm water for 60–90 seconds, then gently slide an orange stick under the edge near the cuticle. Never peel or force — the nail should release cleanly. This method leaves both your natural nails and your press-ons undamaged.'],

            // Payment
            ['category' => FaqCategory::Payment, 'sort_order' => 1, 'question' => 'What payment methods do you accept?', 'answer' => 'JazzCash, EasyPaisa, and bank transfer. All payments are verified manually within 24 hours. Upload your payment screenshot on the confirmation page and I\'ll confirm as soon as I\'ve checked it.'],
            ['category' => FaqCategory::Payment, 'sort_order' => 2, 'question' => 'Do I pay the full amount upfront?', 'answer' => 'For orders under Rs. 5,000: full payment before production begins. For orders Rs. 5,000 and above: 30% advance to start, remainder before dispatch. For the Bridal Trio: full payment in advance.'],
            ['category' => FaqCategory::Payment, 'sort_order' => 3, 'question' => 'What happens if I don\'t pay within 72 hours?', 'answer' => 'I\'ll send a gentle reminder at 24 and 48 hours. If payment hasn\'t been received by 72 hours, the order is automatically cancelled. You\'re welcome to place a new order any time.'],

            // Shipping
            ['category' => FaqCategory::Shipping, 'sort_order' => 1, 'question' => 'How long does delivery take?', 'answer' => 'Standard sets ship within 5–9 working days of payment confirmation. Bridal Trio: 10–14 days. Delivery across Pakistan typically takes 2–4 days after dispatch via TCS or Leopards Courier.'],
            ['category' => FaqCategory::Shipping, 'sort_order' => 2, 'question' => 'Do you ship across Pakistan?', 'answer' => 'Yes — nationwide. I ship from Mirpur, Azad Kashmir via TCS (for high-value orders) and Leopards Courier (for standard orders).'],
            ['category' => FaqCategory::Shipping, 'sort_order' => 3, 'question' => 'Can I track my order?', 'answer' => 'Yes. Once your order ships, I\'ll update the tracking number and courier name on your order page. You can track it directly at the order tracking page or via the courier\'s website.'],

            // Returns
            ['category' => FaqCategory::Returns, 'sort_order' => 1, 'question' => 'Can I return a custom order?', 'answer' => 'Custom-made press-ons cannot be returned — each set is made to your specific nail measurements. However, if there\'s a sizing issue with your first order, the refit is completely free.'],
            ['category' => FaqCategory::Returns, 'sort_order' => 2, 'question' => 'What if my order arrives damaged?', 'answer' => 'If your order is damaged in transit, send me an unboxing video and a photo of the damage within 48 hours of delivery. I\'ll remake and reship at no cost.'],

            // Bridal
            ['category' => FaqCategory::Bridal, 'sort_order' => 1, 'question' => 'How far in advance should I order for my wedding?', 'answer' => 'Order at least 4 weeks before your Mehendi date. This gives time for sizing, production (10–14 days), and one refit round if needed. I\'d rather have your nails ready early than rushed.'],
            ['category' => FaqCategory::Bridal, 'sort_order' => 2, 'question' => 'What\'s included in the Bridal Trio?', 'answer' => 'Three coordinated sets for Mehendi, Baraat, and Valima. One sizing fitting, shipped together. Includes: rigid magnetic gift box, satin lining, nail glue, prep kit, adhesive tabs, and a handwritten name card with care instructions.'],
            ['category' => FaqCategory::Bridal, 'sort_order' => 3, 'question' => 'Can I customise the designs for each night?', 'answer' => 'Yes — that\'s the whole point. Warm terracotta and gold for Mehendi. Deep and dramatic for Baraat. Romantic and soft for Valima. We discuss the designs during the order process and I\'ll suggest options based on your outfit colours if you share them.'],

            // General
            ['category' => FaqCategory::General, 'sort_order' => 1, 'question' => 'Are these gel or acrylic press-ons?', 'answer' => 'Gel. I use gel nail material for the press-on surface — it gives a more natural-looking shine than acrylic and a slightly more flexible feel. The press-on itself is custom-shaped to your nail measurements.'],
            ['category' => FaqCategory::General, 'sort_order' => 2, 'question' => 'Can Muslim women wear press-on nails?', 'answer' => 'Yes. Press-ons are removed before wudu (ablution before prayer) and reapplied after. Unlike nail polish or acrylics, they don\'t coat the nail bed — your natural nail is fully exposed to water during wudu. The removal takes about 3 minutes in warm water.'],
            ['category' => FaqCategory::General, 'sort_order' => 3, 'question' => 'Are press-ons reusable?', 'answer' => 'Yes — with proper removal and care, each set lasts 3–5 wears. Remove gently (warm water soak, never peel), clean the underside between wears, and store in the original tray. The gel surface stays looking the same from first wear to last.'],
        ];

        foreach ($faqs as $data) {
            Faq::firstOrCreate(
                ['question' => $data['question']],
                array_merge($data, ['is_active' => true])
            );
        }
    }
}
