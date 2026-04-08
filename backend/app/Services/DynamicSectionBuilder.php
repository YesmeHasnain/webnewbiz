<?php

namespace App\Services;

use App\Services\Layouts\AbstractLayout;
use Illuminate\Support\Facades\Log;

/**
 * Builds Elementor elements dynamically from structure sections.
 * Uses AbstractLayout helpers for professional design across ALL themes.
 */
class DynamicSectionBuilder
{
    private AbstractLayout $layout;
    private array $content;
    private array $imageUrls;
    private string $businessName;
    private string $businessType;
    private string $userPrompt;
    private array $colors;
    private array $fonts;
    private array $aiContent = [];

    public function __construct(AbstractLayout $layout, array $content, array $imageUrls, string $businessName, string $businessType = 'business', string $userPrompt = '')
    {
        $this->layout = $layout;
        $this->content = $content;
        $this->imageUrls = $imageUrls;
        $this->businessName = $businessName;
        $this->businessType = $businessType;
        $this->userPrompt = $userPrompt;
        $this->colors = $layout->colors();
        $this->fonts = $layout->fonts();
    }

    /**
     * Generate AI content for ALL sections of a page in one call.
     * Uses business-specific prompts for unique, realistic content.
     */
    public function generatePageContent(array $sections, string $pageTitle): void
    {
        $sectionList = [];
        foreach ($sections as $sec) {
            $label = $sec['label'] ?? '';
            if (in_array(strtolower($label), ['header', 'footer'])) continue;
            $sectionList[] = ['type' => $sec['type'] ?? 'content', 'label' => $label, 'prompt' => $sec['prompt'] ?? ''];
        }
        if (empty($sectionList)) return;

        try {
            $aiService = app(AIContentService::class);
            $businessContext = $this->getBusinessContext();

            $prompt = "You are generating website content for \"{$this->businessName}\", a {$this->businessType} business.";
            if ($this->userPrompt) {
                $prompt .= "\nBusiness description: \"{$this->userPrompt}\"";
            }
            $prompt .= "\nPage: \"{$pageTitle}\"\n\n";
            $prompt .= "Generate SPECIFIC, REALISTIC content for each section. Content MUST be unique to this {$this->businessType} — NOT generic corporate text.\n";
            $prompt .= "{$businessContext}\n\n";
            $prompt .= "Sections to generate (use EXACT labels as JSON keys):\n";
            foreach ($sectionList as $s) {
                $prompt .= "- \"{$s['label']}\" (type: {$s['type']})";
                if ($s['prompt']) $prompt .= " — {$s['prompt']}";
                $prompt .= "\n";
            }
            $prompt .= "\nReturn format — include ONLY fields relevant to each section type:\n";
            $prompt .= "{\"Exact Section Label\": {\n";
            $prompt .= "  \"subtitle\": \"compelling one-line description\",\n";
            $prompt .= "  \"items\": [{\"title\": \"...\", \"description\": \"2-3 specific sentences\", \"icon\": \"relevant emoji\"}],\n";
            $prompt .= "  \"stats\": [{\"number\": \"500+\", \"label\": \"specific metric\"}],\n";
            $prompt .= "  \"testimonials\": [{\"name\": \"realistic local name\", \"role\": \"role relevant to {$this->businessType}\", \"text\": \"specific detailed review mentioning the business\"}],\n";
            $prompt .= "  \"faqs\": [{\"question\": \"question specific to {$this->businessType}\", \"answer\": \"detailed helpful answer\"}],\n";
            $prompt .= "  \"steps\": [{\"title\": \"...\", \"description\": \"...\"}],\n";
            $prompt .= '  "plans": [{"name": "...", "price": "$99", "features": ["specific to ' . $this->businessType . '"]}],' . "\n";
            $prompt .= "  \"team\": [{\"name\": \"realistic name\", \"role\": \"specific {$this->businessType} role\"}]\n";
            $prompt .= "}}\n";
            $prompt .= "CRITICAL: Use the EXACT section label text as each JSON key. All names, prices, reviews must feel real for a {$this->businessType}. ONLY valid JSON, no markdown.";

            $result = $aiService->generateContent($prompt, "Return ONLY valid JSON. No markdown. No code blocks. All content must be specific to a {$this->businessType} called \"{$this->businessName}\".");
            if ($result['success'] && !empty($result['data'])) {
                $raw = trim($result['data']);
                $raw = preg_replace('/^```(?:json)?\s*\n?/m', '', $raw);
                $raw = preg_replace('/\n?```\s*$/m', '', $raw);
                $first = strpos($raw, '{');
                $last = strrpos($raw, '}');
                if ($first !== false && $last !== false) {
                    $parsed = json_decode(substr($raw, $first, $last - $first + 1), true);
                    if (is_array($parsed)) {
                        $this->aiContent = $parsed;
                        Log::info("AI content generated for page '{$pageTitle}': " . count($parsed) . " sections");
                    }
                }
            }
        } catch (\Exception $e) {
            Log::warning("AI page content failed: " . $e->getMessage());
        }
    }

    /**
     * Get business-specific context hints for the AI prompt.
     */
    private function getBusinessContext(): string
    {
        $type = strtolower($this->businessType);

        $contexts = [
            'restaurant' => "Include: signature dishes with prices, cuisine specialties, chef background, dining atmosphere, opening hours, reservation info. Testimonials from diners. Stats like years open, dishes served, happy guests. Use food emojis.",
            'cafe' => "Include: coffee drinks with prices (latte, cappuccino, etc.), pastry/food menu, barista profiles, cozy atmosphere details, WiFi/workspace info, opening hours. Use coffee/food emojis.",
            'coffee' => "Include: coffee drinks with prices, brewing methods, bean origins, barista team, cafe vibe. Use coffee emojis.",
            'barbershop' => "Include: haircut/grooming services with prices (fade, beard trim, hot towel shave), grooming packages, barber profiles with specialties, walk-in vs appointment policy. Testimonials from gentlemen. Use grooming emojis.",
            'barber' => "Include: haircut services with prices, grooming rituals, master barber profiles, booking info. Use grooming emojis.",
            'salon' => "Include: hair/beauty/nail services with prices, stylist profiles with specialties, treatment descriptions, product brands used. Use beauty emojis.",
            'spa' => "Include: massage/facial/body treatments with prices and durations, therapist qualifications, wellness packages, relaxation atmosphere. Use wellness emojis.",
            'dental' => "Include: treatments (cleaning, whitening, implants, braces) with price ranges, dentist profiles with credentials/degrees, insurance accepted, emergency hours, patient comfort features.",
            'clinic' => "Include: medical services, doctor profiles with specialties and credentials, insurance/payment info, appointment booking, emergency contact, patient care philosophy.",
            'gym' => "Include: membership plans with monthly prices, class schedule (yoga, HIIT, spin), trainer profiles with certifications, equipment/facility features, free trial offer.",
            'fitness' => "Include: workout programs, personal training packages with prices, class types, transformation stats, trainer certifications, trial membership.",
            'photography' => "Include: photography packages with prices (basic, premium, deluxe), session types (portrait, event, commercial), photographer background, booking process, delivery timeline.",
            'wedding' => "Include: wedding packages with prices, ceremony/reception types, vendor partnerships, portfolio highlights, planning timeline, couple testimonials.",
            'real estate' => "Include: property types handled, neighborhood expertise, agent profiles with sales records, buying/selling process steps, market statistics, free consultation offer.",
            'hotel' => "Include: room types with nightly rates (standard, deluxe, suite), amenities list, dining options, concierge services, location highlights, booking/cancellation policy.",
            'law' => "Include: practice areas (family, criminal, corporate), attorney profiles with credentials/bar admissions/years experience, case results, consultation process.",
            'agency' => "Include: service offerings (branding, digital marketing, web design), methodology/process, team expertise, case studies with measurable results, client industries served.",
            'tech' => "Include: product features, integration capabilities, pricing tiers (starter, pro, enterprise), API documentation mention, support levels, security certifications.",
            'ecommerce' => 'Include: product categories, bestsellers with prices, shipping info (free over $50), return policy, customer reviews, loyalty program.',
            'school' => "Include: programs/courses offered, faculty profiles with degrees, campus facilities, admission process, tuition/fees, student achievement stats.",
            'construction' => "Include: services (residential, commercial, renovation), project portfolio, team certifications/licenses, safety record, free estimate offer, years in business.",
            'cleaning' => "Include: service types (deep clean, regular, move-in/out) with prices, cleaning checklist, products used (eco-friendly), satisfaction guarantee, booking process.",
            'auto' => "Include: services (oil change, brakes, tires, diagnostics) with prices, certified mechanic profiles, warranty info, loaner car availability, service hours.",
        ];

        foreach ($contexts as $key => $context) {
            if (str_contains($type, $key)) {
                return "CONTENT GUIDANCE: " . $context;
            }
        }

        return "CONTENT GUIDANCE: Include realistic details specific to a {$this->businessType}. Use real-sounding names, specific prices, concrete service descriptions. Avoid generic corporate language like 'synergy' or 'leverage'. Every piece of content should feel like it belongs to THIS specific business.";
    }

    /**
     * Find AI content for a section label using fuzzy matching.
     * Tries exact → case-insensitive → partial → word overlap.
     */
    private function findAiContent(string $label): array
    {
        if (empty($this->aiContent)) return [];

        // 1. Exact match
        if (isset($this->aiContent[$label]) && is_array($this->aiContent[$label])) {
            return $this->aiContent[$label];
        }

        // 2. Case-insensitive match
        $labelLower = strtolower(trim($label));
        foreach ($this->aiContent as $key => $value) {
            if (strtolower(trim($key)) === $labelLower && is_array($value)) {
                return $value;
            }
        }

        // 3. Partial containment (label contains key or key contains label)
        foreach ($this->aiContent as $key => $value) {
            $keyLower = strtolower(trim($key));
            if (strlen($keyLower) > 4 && strlen($labelLower) > 4 && is_array($value)) {
                if (str_contains($labelLower, $keyLower) || str_contains($keyLower, $labelLower)) {
                    return $value;
                }
            }
        }

        // 4. Word overlap — find best match by shared significant words
        $labelWords = array_filter(explode(' ', $labelLower), fn($w) => strlen($w) > 3);
        if (empty($labelWords)) return [];

        $bestMatch = null;
        $bestScore = 0;
        foreach ($this->aiContent as $key => $value) {
            if (!is_array($value)) continue;
            $keyWords = array_filter(explode(' ', strtolower($key)), fn($w) => strlen($w) > 3);
            $overlap = count(array_intersect($labelWords, $keyWords));
            if ($overlap > $bestScore) {
                $bestScore = $overlap;
                $bestMatch = $value;
            }
        }

        return ($bestScore >= 1 && $bestMatch) ? $bestMatch : [];
    }

    /**
     * Build all Elementor elements for a page from its structure sections.
     * Wraps output with layout's global CSS/JS for proper theme styling.
     */
    public function buildPage(array $sections, string $pageTitle): array
    {
        $elements = [];
        $delay = 1;

        foreach ($sections as $section) {
            $type = $section['type'] ?? 'content';
            $label = $section['label'] ?? $pageTitle;

            if (in_array(strtolower($label), ['header', 'footer'])) continue;

            // Fuzzy match AI content to section label
            $ai = $this->findAiContent($label);
            $subtitle = $ai['subtitle'] ?? $ai['headline'] ?? '';
            $d = min($delay, 4);

            $el = match ($type) {
                'hero' => $this->hero($label, $subtitle, $d),
                'features', 'feature', 'services_list' => $this->features($label, $ai, $d),
                'about_preview', 'about', 'content', 'introduction' => $this->aboutContent($label, $subtitle, $d),
                'testimonials' => $this->testimonials($label, $ai, $d),
                'stats', 'statistics' => $this->stats($label, $ai, $d),
                'cta', 'call_to_action' => $this->cta($label, $subtitle, $d),
                'gallery', 'portfolio' => $this->gallery($label, $d),
                'pricing' => $this->pricing($label, $ai, $d),
                'team' => $this->team($label, $ai, $d),
                'faq' => $this->faq($label, $ai, $d),
                'contact_form' => $this->contactForm($label, $subtitle, $d),
                'process' => $this->process($label, $ai, $d),
                'logos' => $this->logos($label, $d),
                'banner' => $this->hero($label, $subtitle, $d),
                default => $this->aboutContent($label, $subtitle, $d),
            };

            if ($el) {
                $elements[] = $el;
                $delay++;
            }
        }

        // Wrap with layout's global CSS and JS — critical for theme animations,
        // fonts, custom cursor, scroll-reveal, eyebrow styles, etc.
        $cssSection = AbstractLayout::container([
            'content_width' => 'full',
            'flex_direction' => 'column',
            'padding' => AbstractLayout::pad(0),
        ], [AbstractLayout::html($this->layout->buildGlobalCss())]);

        $jsSection = AbstractLayout::container([
            'content_width' => 'full',
            'flex_direction' => 'column',
            'padding' => AbstractLayout::pad(0),
        ], [AbstractLayout::html($this->layout->buildGlobalJs())]);

        return array_merge([$cssSection], $elements, [$jsSection]);
    }

    // ─── Business-Specific Fallback Defaults ───

    /**
     * Returns fallback content specific to the business type.
     * Used when AI content generation fails — still looks realistic.
     */
    private function getBusinessDefaults(): array
    {
        $bn = $this->businessName;
        $bt = $this->businessType;
        $type = strtolower($bt);

        if (str_contains($type, 'restaurant') || str_contains($type, 'food') || str_contains($type, 'kitchen') || str_contains($type, 'diner')) {
            return [
                'features' => [
                    ['title' => 'Farm-to-Table Fresh', 'description' => "Every dish at {$bn} uses locally sourced, seasonal ingredients for maximum flavor.", 'icon' => '🥬'],
                    ['title' => 'Signature Recipes', 'description' => 'Our chef\'s original creations blend tradition with modern culinary innovation.', 'icon' => '👨‍🍳'],
                    ['title' => 'Cozy Atmosphere', 'description' => 'Warm lighting, comfortable seating, and a welcoming vibe for every occasion.', 'icon' => '🕯️'],
                    ['title' => 'Full Bar & Cocktails', 'description' => 'Handcrafted cocktails, curated wines, and craft beers to complement your meal.', 'icon' => '🍷'],
                    ['title' => 'Private Dining', 'description' => 'Host your special events in our elegant private dining room for up to 30 guests.', 'icon' => '🎉'],
                    ['title' => 'Online Reservations', 'description' => 'Book your table in seconds. Walk-ins always welcome, reservations recommended.', 'icon' => '📱'],
                ],
                'testimonials' => [
                    ['name' => 'Sophia R.', 'role' => 'Food Enthusiast', 'text' => "The pasta was hands-down the best I've had outside of Italy. {$bn} is now our weekly date night spot."],
                    ['name' => 'Ahmed K.', 'role' => 'Local Regular', 'text' => "Been coming here since opening day. The consistency and warmth of the staff keeps us coming back."],
                    ['name' => 'Jessica M.', 'role' => 'Event Host', 'text' => "Hosted my husband's birthday dinner here — the private room was gorgeous and the service was flawless."],
                ],
                'stats' => [['number' => '8+', 'label' => 'Years Serving'], ['number' => '50K+', 'label' => 'Meals Served'], ['number' => '4.8', 'label' => 'Google Rating'], ['number' => '200+', 'label' => 'Seats Available']],
                'pricing' => [
                    ['name' => 'Lunch Special', 'price' => '$18', 'features' => ['Appetizer + Entrée', 'Fresh bread basket', 'Soft drink included', 'Mon–Fri 11am–3pm']],
                    ['name' => 'Dinner Experience', 'price' => '$45', 'features' => ['3-course meal', 'Chef\'s appetizer', 'Premium entrée', 'Dessert of the day', 'Glass of house wine']],
                    ['name' => 'Chef\'s Table', 'price' => '$85', 'features' => ['7-course tasting menu', 'Wine pairing available', 'Personal chef greeting', 'Priority seating', 'Complimentary dessert']],
                ],
                'team' => [['name' => 'Marco Rossi', 'role' => 'Head Chef'], ['name' => 'Elena Vega', 'role' => 'Sous Chef'], ['name' => 'Daniel Park', 'role' => 'Restaurant Manager']],
                'faq' => [
                    ['question' => 'Do you take reservations?', 'answer' => 'Yes! Book online or call us. Walk-ins are welcome but reservations are recommended for weekends.'],
                    ['question' => 'Do you accommodate dietary restrictions?', 'answer' => 'Absolutely. We offer vegetarian, vegan, and gluten-free options. Please inform your server of any allergies.'],
                    ['question' => 'Is there parking available?', 'answer' => 'We have a dedicated parking lot behind the building with 40 spots. Street parking is also available.'],
                    ['question' => 'Do you offer catering?', 'answer' => 'Yes, we cater events of all sizes. Contact us for custom menus and pricing.'],
                ],
            ];
        }

        if (str_contains($type, 'cafe') || str_contains($type, 'coffee')) {
            return [
                'features' => [
                    ['title' => 'Single-Origin Beans', 'description' => 'We source specialty beans from the world\'s best growing regions, roasted in small batches.', 'icon' => '☕'],
                    ['title' => 'Artisan Pastries', 'description' => 'Freshly baked croissants, muffins, and cakes made in-house every morning.', 'icon' => '🥐'],
                    ['title' => 'Free High-Speed WiFi', 'description' => 'Work remotely in comfort with fast internet and plenty of power outlets.', 'icon' => '📶'],
                    ['title' => 'Specialty Drinks', 'description' => 'From oat milk lattes to matcha cold brew — our baristas craft your perfect drink.', 'icon' => '🧋'],
                    ['title' => 'Cozy Workspace', 'description' => 'Comfortable seating, natural light, and a quiet atmosphere for focused work.', 'icon' => '💻'],
                    ['title' => 'Loyalty Rewards', 'description' => 'Earn points on every purchase. Every 10th drink is on us!', 'icon' => '🎁'],
                ],
                'testimonials' => [
                    ['name' => 'Laura W.', 'role' => 'Remote Worker', 'text' => "My go-to workspace. Great coffee, fast WiFi, and the staff remembers my order. Can't ask for more."],
                    ['name' => 'Ryan P.', 'role' => 'Coffee Lover', 'text' => "Best oat milk latte in town. The single-origin pour-over is incredible too. Worth every penny."],
                    ['name' => 'Priya S.', 'role' => 'Weekend Regular', 'text' => "We come every Saturday morning for pastries and coffee. The kids love the hot chocolate too!"],
                ],
                'stats' => [['number' => '500+', 'label' => 'Cups Brewed Daily'], ['number' => '12', 'label' => 'Bean Origins'], ['number' => '4.9', 'label' => 'Avg Rating'], ['number' => '7am–9pm', 'label' => 'Open Daily']],
                'pricing' => [
                    ['name' => 'Coffee', 'price' => '$4.50', 'features' => ['Espresso / Americano', 'Drip Coffee', 'Cold Brew', 'Pour Over (+$1)']],
                    ['name' => 'Specialty', 'price' => '$6.50', 'features' => ['Oat Milk Latte', 'Caramel Macchiato', 'Matcha Latte', 'Chai Latte', 'Any milk alternative']],
                    ['name' => 'Food & Combos', 'price' => '$9.50', 'features' => ['Any drink + pastry', 'Avocado toast', 'Breakfast sandwich', 'Açaí bowl', '10% loyalty discount']],
                ],
                'team' => [['name' => 'Sam Nguyen', 'role' => 'Head Barista'], ['name' => 'Mia Johnson', 'role' => 'Pastry Chef'], ['name' => 'Jake Torres', 'role' => 'Cafe Manager']],
                'faq' => [
                    ['question' => 'Do you have WiFi?', 'answer' => 'Yes! Free high-speed WiFi for all customers. Password is on your receipt.'],
                    ['question' => 'Do you offer dairy-free milk?', 'answer' => 'We have oat, almond, soy, and coconut milk at no extra charge.'],
                    ['question' => 'Can I work here all day?', 'answer' => 'Absolutely! We\'re laptop-friendly with plenty of outlets. Just grab a drink every couple hours.'],
                    ['question' => 'Do you sell beans to take home?', 'answer' => 'Yes! All our single-origin and house blend beans are available in 250g and 500g bags.'],
                ],
            ];
        }

        if (str_contains($type, 'barber') || str_contains($type, 'grooming')) {
            return [
                'features' => [
                    ['title' => 'Precision Fades', 'description' => 'Clean, sharp fades from skin to high — tailored to your face shape and style.', 'icon' => '✂️'],
                    ['title' => 'Hot Towel Shave', 'description' => 'Traditional straight razor shave with hot towel, lather, and aftershave finish.', 'icon' => '🪒'],
                    ['title' => 'Beard Sculpting', 'description' => 'Expert beard shaping and maintenance to keep your look sharp between visits.', 'icon' => '🧔'],
                    ['title' => 'Premium Products', 'description' => 'We use only professional-grade products — Layrite, Baxter, American Crew.', 'icon' => '💈'],
                    ['title' => 'Walk-Ins Welcome', 'description' => 'No appointment needed. Walk in any time, or book ahead to skip the wait.', 'icon' => '🚪'],
                    ['title' => 'VIP Grooming', 'description' => 'Full grooming package: cut, shave, facial, and scalp massage in private suite.', 'icon' => '👑'],
                ],
                'testimonials' => [
                    ['name' => 'Chris M.', 'role' => 'Regular Client', 'text' => "Best fade in the city. Been coming here every two weeks for a year. Nobody else touches my hair."],
                    ['name' => 'Derek J.', 'role' => 'First-Timer', 'text' => "Walked in without an appointment, got a perfect cut in 20 minutes. New regular for sure."],
                    ['name' => 'Miguel R.', 'role' => 'VIP Member', 'text' => "The VIP package is worth every penny. Hot towel shave + facial + cut = walk out feeling like a new man."],
                ],
                'stats' => [['number' => '10K+', 'label' => 'Cuts This Year'], ['number' => '5', 'label' => 'Master Barbers'], ['number' => '4.9', 'label' => 'Google Rating'], ['number' => '15min', 'label' => 'Avg Wait Time']],
                'pricing' => [
                    ['name' => 'Classic Cut', 'price' => '$35', 'features' => ['Precision haircut', 'Hot towel finish', 'Style consultation', 'Product application']],
                    ['name' => 'Cut & Beard', 'price' => '$55', 'features' => ['Full haircut', 'Beard trim & shape', 'Line-up', 'Hot towel', 'Aftershave finish']],
                    ['name' => 'VIP Experience', 'price' => '$85', 'features' => ['Premium haircut', 'Straight razor shave', 'Facial treatment', 'Scalp massage', 'Complimentary drink']],
                ],
                'team' => [['name' => 'Anthony Rivera', 'role' => 'Master Barber & Owner'], ['name' => 'Damon Lee', 'role' => 'Senior Barber'], ['name' => 'Omar Hassan', 'role' => 'Barber & Stylist']],
                'faq' => [
                    ['question' => 'Do I need an appointment?', 'answer' => 'Walk-ins are always welcome! For guaranteed times, book online or call ahead.'],
                    ['question' => 'How long does a haircut take?', 'answer' => 'A standard cut takes about 25-30 minutes. The VIP package is about 60 minutes.'],
                    ['question' => 'What products do you use?', 'answer' => 'We use professional-grade products from Layrite, Baxter of California, and American Crew.'],
                    ['question' => 'Do you do kids\' haircuts?', 'answer' => 'Yes! Kids 12 and under get a discounted rate. We\'re patient and experienced with young clients.'],
                ],
            ];
        }

        if (str_contains($type, 'dental') || str_contains($type, 'dentist')) {
            return [
                'features' => [
                    ['title' => 'General Dentistry', 'description' => 'Comprehensive check-ups, cleanings, fillings, and preventive care for the whole family.', 'icon' => '🦷'],
                    ['title' => 'Teeth Whitening', 'description' => 'Professional in-office whitening that brightens your smile up to 8 shades in one visit.', 'icon' => '✨'],
                    ['title' => 'Dental Implants', 'description' => 'Permanent tooth replacement with titanium implants — looks and feels completely natural.', 'icon' => '🔧'],
                    ['title' => 'Orthodontics', 'description' => 'Clear aligners and braces for teens and adults. Straight teeth, zero metal look.', 'icon' => '😁'],
                    ['title' => 'Emergency Care', 'description' => 'Same-day emergency appointments for pain, broken teeth, and dental trauma.', 'icon' => '🚨'],
                    ['title' => 'Sedation Options', 'description' => 'Nervous about dental work? We offer nitrous oxide and oral sedation for comfort.', 'icon' => '😌'],
                ],
                'testimonials' => [
                    ['name' => 'Karen L.', 'role' => 'Family Patient', 'text' => "The whole family comes here. Dr. is amazing with kids — my daughter actually looks forward to her check-ups."],
                    ['name' => 'Robert S.', 'role' => 'Implant Patient', 'text' => "Got two implants done here. Pain-free procedure, and they look completely natural. Absolutely worth it."],
                    ['name' => 'Nina T.', 'role' => 'Whitening Client', 'text' => "My teeth went from yellow to brilliant white in one session. Everyone asks what I did. Highly recommend!"],
                ],
                'stats' => [['number' => '15+', 'label' => 'Years Practice'], ['number' => '10K+', 'label' => 'Happy Patients'], ['number' => '4.9', 'label' => 'Patient Rating'], ['number' => '6', 'label' => 'Dental Specialists']],
                'pricing' => [
                    ['name' => 'Check-Up', 'price' => '$99', 'features' => ['Full examination', 'Digital X-rays', 'Professional cleaning', 'Treatment plan']],
                    ['name' => 'Whitening', 'price' => '$349', 'features' => ['In-office treatment', 'Up to 8 shades brighter', '60-minute session', 'Take-home touch-up kit']],
                    ['name' => 'Implant', 'price' => '$2,499', 'features' => ['Titanium implant post', 'Custom crown', 'All follow-up visits', 'Lifetime warranty', 'Payment plans available']],
                ],
                'team' => [['name' => 'Dr. Sarah Mitchell', 'role' => 'Lead Dentist, DDS'], ['name' => 'Dr. James Park', 'role' => 'Orthodontist'], ['name' => 'Lisa Gomez', 'role' => 'Dental Hygienist']],
                'faq' => [
                    ['question' => 'Do you accept insurance?', 'answer' => 'Yes! We accept most major dental insurance plans including Delta, Cigna, Aetna, and MetLife.'],
                    ['question' => 'How often should I visit?', 'answer' => 'We recommend check-ups and cleanings every 6 months for optimal oral health.'],
                    ['question' => 'Is teeth whitening safe?', 'answer' => 'Absolutely. Our professional whitening is ADA-approved and supervised by our dentists.'],
                    ['question' => 'Do you offer payment plans?', 'answer' => 'Yes, we offer flexible payment plans and accept CareCredit for larger treatments.'],
                ],
            ];
        }

        if (str_contains($type, 'salon') || str_contains($type, 'beauty') || str_contains($type, 'spa') || str_contains($type, 'hair')) {
            return [
                'features' => [
                    ['title' => 'Hair Styling', 'description' => 'Cuts, color, highlights, and blowouts by experienced stylists who know the latest trends.', 'icon' => '💇‍♀️'],
                    ['title' => 'Color & Highlights', 'description' => 'From subtle balayage to bold fashion colors — we use premium, damage-free products.', 'icon' => '🎨'],
                    ['title' => 'Nail Services', 'description' => 'Manicures, pedicures, gel, acrylics, and nail art in our relaxing nail studio.', 'icon' => '💅'],
                    ['title' => 'Facial Treatments', 'description' => 'Rejuvenating facials, chemical peels, and skin treatments for a glowing complexion.', 'icon' => '✨'],
                    ['title' => 'Bridal Packages', 'description' => 'Complete wedding day prep — hair, makeup, nails, and skin for the bride and party.', 'icon' => '👰'],
                    ['title' => 'Relaxation Lounge', 'description' => 'Enjoy complimentary tea, coffee, and a calming atmosphere during every visit.', 'icon' => '🧘'],
                ],
                'testimonials' => [
                    ['name' => 'Isabella G.', 'role' => 'Color Client', 'text' => "Finally found a stylist who gets my vision! My balayage is absolutely perfect. Never going anywhere else."],
                    ['name' => 'Aisha B.', 'role' => 'Bridal Client', 'text' => "The whole bridal party got ready here on my wedding day. Everyone looked stunning. Magical experience."],
                    ['name' => 'Tanya R.', 'role' => 'Regular Client', 'text' => "I come monthly for hair and nails. The quality is consistently excellent and the staff is so friendly."],
                ],
                'stats' => [['number' => '5K+', 'label' => 'Happy Clients'], ['number' => '12', 'label' => 'Expert Stylists'], ['number' => '4.8', 'label' => 'Avg Rating'], ['number' => '200+', 'label' => 'Weddings Done']],
                'pricing' => [
                    ['name' => 'Express', 'price' => '$45', 'features' => ['Wash & blowout', 'Basic styling', 'Product application']],
                    ['name' => 'Signature', 'price' => '$95', 'features' => ['Cut & style', 'Deep conditioning', 'Scalp massage', 'Complimentary beverage']],
                    ['name' => 'Luxury Package', 'price' => '$175', 'features' => ['Full color service', 'Precision cut', 'Treatment mask', 'Blowout & style', 'Take-home products']],
                ],
                'team' => [['name' => 'Vanessa Reyes', 'role' => 'Creative Director & Lead Stylist'], ['name' => 'Kim Patel', 'role' => 'Color Specialist'], ['name' => 'Jordan Blake', 'role' => 'Nail Artist']],
                'faq' => [
                    ['question' => 'Do I need an appointment?', 'answer' => 'Appointments are recommended, especially for color services. Some walk-in availability for cuts.'],
                    ['question' => 'What products do you use?', 'answer' => 'We use premium brands including Olaplex, Redken, Kerastase, and OPI for nails.'],
                    ['question' => 'How long does a color service take?', 'answer' => 'Color services typically take 2-3 hours depending on the technique and hair length.'],
                    ['question' => 'Do you offer gift cards?', 'answer' => 'Yes! Gift cards are available in any amount — perfect for birthdays and holidays.'],
                ],
            ];
        }

        if (str_contains($type, 'gym') || str_contains($type, 'fitness') || str_contains($type, 'crossfit') || str_contains($type, 'training')) {
            return [
                'features' => [
                    ['title' => 'State-of-the-Art Equipment', 'description' => 'Latest cardio machines, free weights, squat racks, and functional training zones.', 'icon' => '🏋️'],
                    ['title' => 'Group Classes', 'description' => 'HIIT, yoga, spin, boxing, and CrossFit classes led by certified instructors.', 'icon' => '🤸'],
                    ['title' => 'Personal Training', 'description' => 'One-on-one sessions with certified trainers who create custom programs for your goals.', 'icon' => '💪'],
                    ['title' => 'Nutrition Coaching', 'description' => 'Personalized meal plans and supplement guidance to maximize your results.', 'icon' => '🥗'],
                    ['title' => 'Open 24/7', 'description' => 'Train on your schedule. Key card access around the clock, 365 days a year.', 'icon' => '🕐'],
                    ['title' => 'Recovery Zone', 'description' => 'Sauna, steam room, cold plunge, and foam rolling area for optimal recovery.', 'icon' => '🧊'],
                ],
                'testimonials' => [
                    ['name' => 'Marcus D.', 'role' => 'Member - 2 Years', 'text' => "Lost 40 pounds in 6 months with the personal training program. This gym changed my life."],
                    ['name' => 'Sarah K.', 'role' => 'CrossFit Member', 'text' => "The community here is incredible. Everyone pushes you to be better. Best gym I've ever joined."],
                    ['name' => 'Tom W.', 'role' => 'New Member', 'text' => "Joined last month and already seeing results. The trainers are knowledgeable and the facility is spotless."],
                ],
                'stats' => [['number' => '2K+', 'label' => 'Active Members'], ['number' => '40+', 'label' => 'Weekly Classes'], ['number' => '15', 'label' => 'Certified Trainers'], ['number' => '24/7', 'label' => 'Always Open']],
                'pricing' => [
                    ['name' => 'Basic', 'price' => '$29/mo', 'features' => ['Full gym access', 'Locker room', '24/7 key card', 'Free parking']],
                    ['name' => 'Premium', 'price' => '$59/mo', 'features' => ['Unlimited classes', 'Recovery zone access', 'Guest passes (2/mo)', '1 PT session/month', 'Towel service']],
                    ['name' => 'Elite', 'price' => '$99/mo', 'features' => ['Everything in Premium', '4 PT sessions/month', 'Nutrition coaching', 'Priority booking', 'Free supplements']],
                ],
                'team' => [['name' => 'Mike Johnson', 'role' => 'Head Trainer, CSCS'], ['name' => 'Ana Petrova', 'role' => 'Yoga & Pilates Instructor'], ['name' => 'Ray Chen', 'role' => 'CrossFit Coach, L3']],
                'faq' => [
                    ['question' => 'Is there a contract?', 'answer' => 'No long-term contracts! Month-to-month membership. Cancel anytime with 30 days notice.'],
                    ['question' => 'Do you offer a free trial?', 'answer' => 'Yes! Get a free 3-day pass to try the gym, classes, and meet our trainers.'],
                    ['question' => 'What are the peak hours?', 'answer' => 'Busiest times are 6-8am and 5-7pm on weekdays. Early morning and late night are quieter.'],
                    ['question' => 'Can I freeze my membership?', 'answer' => 'Yes, you can freeze for up to 3 months per year at no charge.'],
                ],
            ];
        }

        // Generic fallback using business name/type — better than hardcoded "TechCorp"
        return [
            'features' => [
                ['title' => 'Expert ' . ucfirst($bt), 'description' => "Professional {$bt} services delivered by our experienced team at {$bn}.", 'icon' => '⭐'],
                ['title' => 'Quality Guaranteed', 'description' => "We maintain the highest standards in every aspect of our {$bt} work.", 'icon' => '✅'],
                ['title' => 'Fast Turnaround', 'description' => "Efficient service without compromising on quality. Your time matters to us.", 'icon' => '⚡'],
                ['title' => 'Personalized Approach', 'description' => "Every client gets a customized solution tailored to their specific needs.", 'icon' => '🎯'],
                ['title' => 'Competitive Pricing', 'description' => "Transparent pricing with no hidden fees. Great value for premium {$bt} services.", 'icon' => '💰'],
                ['title' => 'Trusted By Many', 'description' => "Hundreds of satisfied clients trust {$bn} for their {$bt} needs.", 'icon' => '🤝'],
            ],
            'testimonials' => [
                ['name' => 'Rachel M.', 'role' => 'Satisfied Client', 'text' => "Absolutely professional from start to finish. {$bn} exceeded all my expectations. Highly recommend!"],
                ['name' => 'David L.', 'role' => 'Returning Customer', 'text' => "This is our third time using {$bn} and the quality is consistently excellent every single time."],
                ['name' => 'Aisha K.', 'role' => 'New Client', 'text' => "Was recommended by a friend and now I see why. Fantastic service, fair pricing, and great results."],
            ],
            'stats' => [['number' => '500+', 'label' => 'Happy Clients'], ['number' => '1,000+', 'label' => 'Projects Done'], ['number' => '10+', 'label' => 'Years Experience'], ['number' => '4.8', 'label' => 'Client Rating']],
            'pricing' => [
                ['name' => 'Starter', 'price' => '$49', 'features' => ["Basic {$bt} service", 'Email support', 'Standard delivery']],
                ['name' => 'Professional', 'price' => '$99', 'features' => ["Full {$bt} package", 'Priority support', 'Faster delivery', 'Custom options']],
                ['name' => 'Premium', 'price' => '$199', 'features' => ["Complete {$bt} solution", 'Dedicated manager', 'Rush delivery', 'Unlimited revisions', 'VIP support']],
            ],
            'team' => [['name' => 'Alex Rivera', 'role' => "Founder & Lead {$bt} Expert"], ['name' => 'Jordan Lee', 'role' => 'Senior Specialist'], ['name' => 'Sam Patel', 'role' => 'Client Relations Manager']],
            'faq' => [
                ['question' => "What {$bt} services do you offer?", 'answer' => "We offer a comprehensive range of {$bt} services tailored to individual needs. Contact us for a full breakdown."],
                ['question' => 'How do I get started?', 'answer' => "Simply reach out via our contact form or give us a call. We'll schedule a free consultation to discuss your needs."],
                ['question' => 'What are your hours?', 'answer' => "We're open Monday through Saturday. Check our contact page for specific hours or book online anytime."],
                ['question' => 'Do you offer free consultations?', 'answer' => "Yes! Your first consultation is completely free. We'll assess your needs and provide a custom quote."],
            ],
        ];
    }

    // ─── Section Builders (using layout helpers) ───

    private function hero(string $title, string $subtitle, int $d): array
    {
        $c = $this->colors;
        $defaults = $this->getBusinessDefaults();
        $subtitle = $subtitle ?: "Welcome to {$this->businessName} — your trusted {$this->businessType} destination. Discover what makes us the preferred choice.";
        $img = $this->imageUrls['hero'] ?? '';

        return $this->layout->section([
            'background_background' => 'classic',
            'background_color' => $c['bg'],
            'padding' => AbstractLayout::pad(100, 40, 100, 40),
        ], [
            $this->layout->eyebrow(strtoupper($this->businessType)),
            $this->layout->headline($title),
            $this->layout->bodyText($subtitle),
            AbstractLayout::spacer(16),
            AbstractLayout::container([
                'flex_direction' => 'row',
                'gap' => ['size' => 16, 'unit' => 'px'],
                'css_classes' => 'sr d' . $d,
            ], [
                $this->layout->ctaButton('Get Started', '#contact'),
                $this->layout->ghostButton('Learn More', '#about'),
            ]),
        ]);
    }

    private function features(string $title, array $ai, int $d): array
    {
        $c = $this->colors;
        $defaults = $this->getBusinessDefaults();
        $items = $ai['items'] ?? $defaults['features'];

        $cards = [];
        foreach (array_slice($items, 0, 6) as $i => $item) {
            $icon = $item['icon'] ?? '⭐';
            $itemTitle = $item['title'] ?? 'Service';
            $desc = $item['description'] ?? $item['desc'] ?? '';
            $cards[] = AbstractLayout::container([
                'content_width' => 'full',
                'flex_direction' => 'column',
                'padding' => AbstractLayout::pad(32, 28),
                'background_background' => 'classic',
                'background_color' => $c['surface'] ?? $c['bg'],
                'border_border' => 'solid',
                'border_width' => ['top' => '1', 'right' => '1', 'bottom' => '1', 'left' => '1', 'unit' => 'px'],
                'border_color' => $c['border'],
                'border_radius' => AbstractLayout::radius(12),
                'css_classes' => 'sr d' . min($i + 1, 4),
            ], [
                AbstractLayout::textEditor("<span style='font-size:28px;'>{$icon}</span>"),
                AbstractLayout::heading($itemTitle, 'h3', [
                    'title_color' => $c['text'],
                    'typography_font_family' => $this->fonts['heading'],
                    'typography_font_size' => AbstractLayout::size(18),
                    'typography_font_weight' => '700',
                ]),
                $this->layout->bodyText($desc),
            ]);
        }

        return $this->layout->section([
            'padding' => AbstractLayout::pad(80, 20),
        ], [
            $this->layout->headline($title),
            AbstractLayout::spacer(32),
            AbstractLayout::cardGrid($cards, 3),
        ]);
    }

    private function aboutContent(string $title, string $subtitle, int $d): array
    {
        $subtitle = $subtitle ?: "At {$this->businessName}, we're passionate about delivering the best {$this->businessType} experience. Our dedicated team combines expertise with genuine care to ensure every client receives outstanding service.";
        $img = $this->imageUrls['about'] ?? ($this->imageUrls['hero'] ?? '');

        $left = [
            $this->layout->headline($title),
            $this->layout->bodyText($subtitle),
            AbstractLayout::spacer(16),
            $this->layout->ctaButton('Learn More', '#'),
        ];
        $right = $img ? [AbstractLayout::image($img, ['border_radius' => AbstractLayout::radius(12)])] : [];

        return AbstractLayout::twoCol($left, $right, 55, [
            'padding' => AbstractLayout::pad(80, 40),
            'css_classes' => 'sr d' . $d,
        ]);
    }

    private function testimonials(string $title, array $ai, int $d): array
    {
        $c = $this->colors;
        $defaults = $this->getBusinessDefaults();
        $reviews = $ai['testimonials'] ?? $ai['items'] ?? $defaults['testimonials'];

        $cards = [];
        foreach (array_slice($reviews, 0, 3) as $i => $r) {
            $name = $r['name'] ?? $r['title'] ?? 'Client';
            $role = $r['role'] ?? $r['position'] ?? '';
            $text = $r['text'] ?? $r['content'] ?? $r['quote'] ?? 'Great experience!';
            $cards[] = AbstractLayout::container([
                'content_width' => 'full',
                'flex_direction' => 'column',
                'padding' => AbstractLayout::pad(32),
                'background_background' => 'classic',
                'background_color' => $c['surface'] ?? $c['bg'],
                'border_border' => 'solid',
                'border_width' => ['top' => '1', 'right' => '1', 'bottom' => '1', 'left' => '1', 'unit' => 'px'],
                'border_color' => $c['border'],
                'border_radius' => AbstractLayout::radius(16),
                'css_classes' => 'sr d' . min($i + 1, 4),
            ], [
                $this->layout->bodyText('"' . $text . '"', ['_element_custom_width' => ['size' => 100, 'unit' => '%']]),
                AbstractLayout::spacer(16),
                AbstractLayout::heading($name, 'h4', ['title_color' => $c['text'], 'typography_font_size' => AbstractLayout::size(15), 'typography_font_weight' => '700']),
                AbstractLayout::textEditor("<span style='color:{$c['muted']};font-size:13px;'>{$role}</span>"),
            ]);
        }

        return $this->layout->section([
            'padding' => AbstractLayout::pad(80, 20),
            'background_background' => 'classic',
            'background_color' => $c['surface2'] ?? $c['bg'],
        ], [
            $this->layout->headline($title),
            AbstractLayout::spacer(32),
            AbstractLayout::cardGrid($cards, 3),
        ]);
    }

    private function stats(string $title, array $ai, int $d): array
    {
        $c = $this->colors;
        $defaults = $this->getBusinessDefaults();
        $items = $ai['stats'] ?? $ai['items'] ?? $defaults['stats'];

        $statEls = [];
        foreach (array_slice($items, 0, 4) as $i => $s) {
            $num = $s['number'] ?? $s['num'] ?? $s['value'] ?? $s['title'] ?? '100+';
            $label = $s['label'] ?? $s['description'] ?? 'Metric';
            $statEls[] = AbstractLayout::container([
                'content_width' => 'full',
                'flex_direction' => 'column',
                'flex_align_items' => 'center',
                'css_classes' => 'sr d' . min($i + 1, 4),
            ], [
                AbstractLayout::heading($num, 'h3', [
                    'align' => 'center',
                    'title_color' => $c['primary'],
                    'typography_font_family' => $this->fonts['heading'],
                    'typography_font_size' => AbstractLayout::size(42),
                    'typography_font_weight' => '800',
                ]),
                AbstractLayout::textEditor("<p style='text-align:center;color:{$c['muted']};font-size:14px;'>{$label}</p>"),
            ]);
        }

        return $this->layout->section([
            'padding' => AbstractLayout::pad(60, 20),
        ], [
            $this->layout->headline($title),
            AbstractLayout::spacer(32),
            AbstractLayout::container([
                'flex_direction' => 'row',
                'flex_wrap' => 'wrap',
                'flex_justify_content' => 'center',
                'gap' => ['size' => 48, 'unit' => 'px'],
            ], $statEls),
        ]);
    }

    private function cta(string $title, string $subtitle, int $d): array
    {
        $c = $this->colors;
        $subtitle = $subtitle ?: "Ready to experience the best {$this->businessType} service? Contact {$this->businessName} today and let's get started.";

        return $this->layout->section([
            'padding' => AbstractLayout::pad(80, 40),
            'background_background' => 'classic',
            'background_color' => $c['primary'],
        ], [
            AbstractLayout::heading($title, 'h2', [
                'align' => 'center',
                'title_color' => '#FFFFFF',
                'typography_font_family' => $this->fonts['heading'],
                'typography_font_size' => AbstractLayout::responsiveSize(36, 28, 24),
                'typography_font_weight' => '800',
                'css_classes' => 'sr d1',
            ]),
            AbstractLayout::textEditor("<p style='text-align:center;color:rgba(255,255,255,0.8);font-size:16px;max-width:600px;margin:8px auto 0;'>{$subtitle}</p>"),
            AbstractLayout::spacer(24),
            AbstractLayout::container(['flex_justify_content' => 'center'], [
                AbstractLayout::button('Get Started', '#contact', [
                    'background_color' => '#FFFFFF',
                    'button_text_color' => $c['primary'],
                    'border_radius' => AbstractLayout::radius(50),
                    'typography_font_weight' => '600',
                ]),
            ]),
        ]);
    }

    private function gallery(string $title, int $d): array
    {
        $imgs = [];
        foreach ($this->imageUrls as $url) {
            if ($url) $imgs[] = $url;
        }
        $imgs = array_slice(array_values(array_unique($imgs)), 0, 6);

        $imageEls = [];
        foreach ($imgs as $i => $url) {
            $imageEls[] = AbstractLayout::container([
                'content_width' => 'full',
                'overflow' => 'hidden',
                'border_radius' => AbstractLayout::radius(12),
                'css_classes' => 'sr d' . min($i + 1, 4),
            ], [
                AbstractLayout::image($url, ['width' => ['size' => 100, 'unit' => '%']]),
            ]);
        }

        return $this->layout->section([
            'padding' => AbstractLayout::pad(80, 20),
        ], [
            $this->layout->headline($title),
            AbstractLayout::spacer(32),
            AbstractLayout::cardGrid($imageEls, 3),
        ]);
    }

    private function pricing(string $title, array $ai, int $d): array
    {
        $c = $this->colors;
        $defaults = $this->getBusinessDefaults();
        $plans = $ai['plans'] ?? $ai['items'] ?? $defaults['pricing'];

        $cards = [];
        foreach (array_slice($plans, 0, 3) as $i => $p) {
            $name = $p['name'] ?? $p['title'] ?? 'Plan';
            $price = $p['price'] ?? $p['cost'] ?? '$99';
            $features = $p['features'] ?? $p['items'] ?? ['Feature 1', 'Feature 2'];
            $isPop = $i === 1;

            $featHtml = '';
            foreach ($features as $f) {
                $fText = is_string($f) ? $f : ($f['title'] ?? $f['name'] ?? '');
                $featHtml .= "<div style='padding:8px 0;border-bottom:1px solid " . ($isPop ? 'rgba(255,255,255,0.15)' : $c['border']) . ";font-size:14px;color:" . ($isPop ? 'rgba(255,255,255,0.8)' : $c['muted']) . ";'>✓ {$fText}</div>";
            }

            $cards[] = AbstractLayout::container([
                'content_width' => 'full',
                'flex_direction' => 'column',
                'flex_align_items' => 'center',
                'padding' => AbstractLayout::pad(40, 32),
                'background_background' => 'classic',
                'background_color' => $isPop ? $c['primary'] : ($c['surface'] ?? '#fff'),
                'border_border' => $isPop ? 'none' : 'solid',
                'border_width' => ['top' => '1', 'right' => '1', 'bottom' => '1', 'left' => '1', 'unit' => 'px'],
                'border_color' => $c['border'],
                'border_radius' => AbstractLayout::radius(16),
                'css_classes' => 'sr d' . min($i + 1, 4),
            ], [
                AbstractLayout::heading($name, 'h3', ['align' => 'center', 'title_color' => $isPop ? '#fff' : $c['text'], 'typography_font_size' => AbstractLayout::size(20), 'typography_font_weight' => '700']),
                AbstractLayout::heading($price, 'h2', ['align' => 'center', 'title_color' => $isPop ? '#fff' : $c['text'], 'typography_font_size' => AbstractLayout::size(42), 'typography_font_weight' => '800']),
                AbstractLayout::html($featHtml),
            ]);
        }

        return $this->layout->section([
            'padding' => AbstractLayout::pad(80, 20),
        ], [
            $this->layout->headline($title),
            AbstractLayout::spacer(32),
            AbstractLayout::cardGrid($cards, 3),
        ]);
    }

    private function team(string $title, array $ai, int $d): array
    {
        $c = $this->colors;
        $defaults = $this->getBusinessDefaults();
        $members = $ai['team'] ?? $ai['items'] ?? $defaults['team'];

        $cards = [];
        foreach (array_slice($members, 0, 3) as $i => $m) {
            $name = $m['name'] ?? $m['title'] ?? 'Team Member';
            $role = $m['role'] ?? $m['position'] ?? 'Specialist';
            $cards[] = AbstractLayout::container([
                'content_width' => 'full',
                'flex_direction' => 'column',
                'flex_align_items' => 'center',
                'padding' => AbstractLayout::pad(32),
                'css_classes' => 'sr d' . min($i + 1, 4),
            ], [
                AbstractLayout::html("<div style='width:100px;height:100px;border-radius:50%;background:" . ($c['surface2'] ?? $c['surface'] ?? '#f1f5f9') . ";display:flex;align-items:center;justify-content:center;font-size:32px;margin-bottom:16px;'>👤</div>"),
                AbstractLayout::heading($name, 'h3', ['align' => 'center', 'title_color' => $c['text'], 'typography_font_size' => AbstractLayout::size(18), 'typography_font_weight' => '700']),
                AbstractLayout::textEditor("<p style='text-align:center;color:{$c['muted']};font-size:14px;'>{$role}</p>"),
            ]);
        }

        return $this->layout->section([
            'padding' => AbstractLayout::pad(80, 20),
        ], [
            $this->layout->headline($title),
            AbstractLayout::spacer(32),
            AbstractLayout::cardGrid($cards, 3),
        ]);
    }

    private function faq(string $title, array $ai, int $d): array
    {
        $c = $this->colors;
        $defaults = $this->getBusinessDefaults();
        $faqs = $ai['faqs'] ?? $ai['items'] ?? $defaults['faq'];

        $faqEls = [];
        foreach (array_slice($faqs, 0, 5) as $i => $f) {
            $q = $f['question'] ?? $f['q'] ?? $f['title'] ?? 'Question';
            $a = $f['answer'] ?? $f['a'] ?? $f['description'] ?? 'Answer';
            $faqEls[] = AbstractLayout::container([
                'content_width' => 'full',
                'flex_direction' => 'column',
                'padding' => AbstractLayout::pad(20, 0),
                'border_border' => 'solid',
                'border_width' => ['top' => '0', 'right' => '0', 'bottom' => '1', 'left' => '0', 'unit' => 'px'],
                'border_color' => $c['border'],
                'css_classes' => 'sr d' . min($i + 1, 4),
            ], [
                AbstractLayout::heading($q, 'h4', ['title_color' => $c['text'], 'typography_font_size' => AbstractLayout::size(16), 'typography_font_weight' => '600']),
                $this->layout->bodyText($a),
            ]);
        }

        return $this->layout->section([
            'padding' => AbstractLayout::pad(80, 20),
            'content_width' => ['size' => 800, 'unit' => 'px'],
        ], array_merge(
            [$this->layout->headline($title), AbstractLayout::spacer(24)],
            $faqEls
        ));
    }

    private function contactForm(string $title, string $subtitle, int $d): array
    {
        $c = $this->colors;
        $subtitle = $subtitle ?: "We'd love to hear from you. Get in touch today.";

        $formHtml = "<form style='display:flex;flex-direction:column;gap:14px;max-width:500px;margin:0 auto;'>";
        $inputStyle = "padding:12px 16px;border:1px solid {$c['border']};border-radius:8px;font-size:14px;font-family:'{$this->fonts['body']}',sans-serif;background:" . ($c['surface'] ?? '#fff') . ";color:{$c['text']};outline:none;";
        $formHtml .= "<input placeholder='Your Name' style='{$inputStyle}'>";
        $formHtml .= "<input placeholder='Email Address' type='email' style='{$inputStyle}'>";
        $formHtml .= "<input placeholder='Phone' style='{$inputStyle}'>";
        $formHtml .= "<textarea placeholder='Your Message' rows='4' style='{$inputStyle}resize:none;'></textarea>";
        $formHtml .= "<button style='padding:14px;background:{$c['primary']};color:#fff;border:none;border-radius:8px;font-size:15px;font-weight:600;cursor:pointer;font-family:\"{$this->fonts['heading']}\",sans-serif;'>Send Message</button>";
        $formHtml .= "</form>";

        return $this->layout->section([
            'padding' => AbstractLayout::pad(80, 20),
        ], [
            $this->layout->headline($title),
            $this->layout->bodyText($subtitle, ['align' => 'center']),
            AbstractLayout::spacer(24),
            AbstractLayout::html($formHtml),
        ]);
    }

    private function process(string $title, array $ai, int $d): array
    {
        $c = $this->colors;
        $steps = $ai['steps'] ?? $ai['items'] ?? [
            ['title' => 'Get In Touch', 'description' => "Reach out to {$this->businessName} and tell us what you need."],
            ['title' => 'Free Consultation', 'description' => "We'll discuss your requirements and create a personalized plan."],
            ['title' => 'We Get To Work', 'description' => "Our expert team delivers your {$this->businessType} service with precision."],
            ['title' => 'You Enjoy Results', 'description' => 'Sit back and enjoy the outcome. We follow up to ensure satisfaction.'],
        ];

        $stepEls = [];
        foreach (array_slice($steps, 0, 4) as $i => $s) {
            $sTitle = $s['title'] ?? $s['name'] ?? 'Step ' . ($i + 1);
            $sDesc = $s['description'] ?? $s['desc'] ?? '';
            $num = str_pad((string)($i + 1), 2, '0', STR_PAD_LEFT);
            $stepEls[] = AbstractLayout::container([
                'content_width' => 'full',
                'flex_direction' => 'column',
                'flex_align_items' => 'center',
                'css_classes' => 'sr d' . min($i + 1, 4),
            ], [
                AbstractLayout::html("<div style='width:48px;height:48px;border-radius:50%;background:{$c['primary']};color:#fff;display:flex;align-items:center;justify-content:center;font-size:16px;font-weight:800;margin-bottom:12px;'>{$num}</div>"),
                AbstractLayout::heading($sTitle, 'h4', ['align' => 'center', 'title_color' => $c['text'], 'typography_font_size' => AbstractLayout::size(16), 'typography_font_weight' => '700']),
                AbstractLayout::textEditor("<p style='text-align:center;color:{$c['muted']};font-size:13px;'>{$sDesc}</p>"),
            ]);
        }

        return $this->layout->section([
            'padding' => AbstractLayout::pad(80, 20),
        ], [
            $this->layout->headline($title),
            AbstractLayout::spacer(32),
            AbstractLayout::container([
                'flex_direction' => 'row',
                'flex_wrap' => 'wrap',
                'flex_justify_content' => 'center',
                'gap' => ['size' => 32, 'unit' => 'px'],
            ], $stepEls),
        ]);
    }

    private function logos(string $title, int $d): array
    {
        $c = $this->colors;
        return $this->layout->section([
            'padding' => AbstractLayout::pad(40, 20),
        ], [
            AbstractLayout::textEditor("<p style='text-align:center;color:{$c['muted']};font-size:12px;text-transform:uppercase;letter-spacing:2px;'>{$title}</p>"),
            AbstractLayout::spacer(16),
            AbstractLayout::html("<div style='display:flex;justify-content:center;gap:40px;opacity:0.3;'>" .
                "<span style='font-size:20px;font-weight:700;color:{$c['text']};'>Brand 1</span>" .
                "<span style='font-size:20px;font-weight:700;color:{$c['text']};'>Brand 2</span>" .
                "<span style='font-size:20px;font-weight:700;color:{$c['text']};'>Brand 3</span>" .
                "<span style='font-size:20px;font-weight:700;color:{$c['text']};'>Brand 4</span>" .
                "<span style='font-size:20px;font-weight:700;color:{$c['text']};'>Brand 5</span>" .
            "</div>"),
        ]);
    }
}
