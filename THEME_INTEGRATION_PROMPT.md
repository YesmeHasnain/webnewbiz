# Webnewbiz — New Envato Theme Integration Prompt

## Yeh Prompt Kya Hey?
Yeh prompt kisi bhi AI terminal (Claude, GPT, etc.) ko dou jab tum koi nai Envato Elementor theme integrate karna chahte ho Webnewbiz platform mai. Terminal is prompt ko follow karay ga aur puri integration kar day ga — layout class, import pipeline, AI content injection, sab kuch.

---

## PROMPT START — Copy from here:

---

You are integrating a new Envato WordPress theme into the Webnewbiz website builder platform. This platform auto-generates WordPress websites for users via AI-powered content and stock images. Users describe their business and the system builds a complete site.

### SYSTEM ARCHITECTURE

**Backend**: Laravel 12 at `C:\Users\1\Desktop\Webnewbiz\backend`
**WordPress sites**: `C:\xampp\htdocs\{slug}`
**Template**: `C:\xampp\htdocs\wp-template` (pre-built WP with Elementor + Elementor Pro + HFE)
**Theme storage**: `backend/storage/themes/{theme-slug}/`

### THE PIPELINE (ProvisionWebsiteJob.php)

When a user creates a website:
1. WordPress is installed from template (robocopy + SQL import)
2. AI selects the best layout based on business type
3. AI generates content (titles, descriptions, testimonials, FAQ, team, etc.)
4. Stock images are downloaded from Unsplash
5. **Theme-based layout**: Theme + plugins installed → Demo XML imported → AI content injected into Elementor widgets → Header/Footer customized → Nav menu cleaned
6. Site configured (permalinks, experiments, caches cleared)
7. Elementor CSS regenerated
8. Site goes live

### YOUR TASK

You are given a new Envato theme ZIP. You need to:

---

## STEP 1: Analyze the Theme

Unzip and examine the theme. Find:

### 1a. Theme Structure
```
{theme-name}/
├── {theme-name}.zip          ← Main theme
├── {theme-name}-child.zip    ← Child theme (if any)
├── plugins/                  ← Required plugins (core plugin with custom widgets)
│   └── {theme-name}-core.zip
├── Demo Data/                ← XML demo content + customizer + widgets
│   ├── contents-demo.xml
│   ├── customizer-data.dat
│   └── widget-settings.json
└── Documentation/
```

### 1b. Custom Widget Discovery (CRITICAL)
Open the theme's core plugin (`{theme-name}-core.zip`) and find all custom Elementor widgets.

Look in: `{theme-name}-core/include/elementor/` or similar paths.

For each widget, document:
- **Widget type** (e.g., `tp-slider`, `tp-heading`, `tp-cta`)
- **Settings keys** that contain user-visible text (titles, descriptions, button text)
- **Repeater fields** (lists of items like services, testimonials, team members)
- **Image fields** (where images are stored as `{url, id}` objects)

Example analysis for Biddut theme:
```
tp-slider:
  - slider_list[].tp_slider_title        ← Hero title
  - slider_list[].tp_slider_description  ← Hero subtitle
  - slider_list[].tp_slider_sub_title    ← Sub-heading (may contain brand name)
  - slider_list[].tp_btn_btn_text        ← Button text (NOT tp_slider_btn_text!)
  - slider_list[].tp_slider_image        ← {url, id} object

tp-heading:
  - tp_section_title       ← Main heading text
  - tp_section_sub_title   ← Sub-heading (usually brand name)
  - tp_section_description ← Paragraph text below heading

tp-icon-box:
  - tp_icon_box_title  ← Feature title
  - tp_icon_box_desc   ← Feature description

tp-services-box:
  - tp_services_list[].tp_service_title  ← Service name
  - tp_services_list[].tp_service_des    ← Service description

tp-cta:
  - tp_cta_title       ← CTA heading
  - tp_cta_sub_title   ← CTA sub-heading (may contain brand)
  - tp_cta_description ← CTA text
  - tp_cta_btn_text    ← CTA button

tp-testimonial-2:
  - reviews_list[].reviewer_name    ← Name
  - reviews_list[].reviewer_title   ← Role
  - reviews_list[].review_content   ← Review text

tp-team:
  - team_list[].tp_team_name        ← Name
  - team_list[].tp_team_designation  ← Role

tp-faq2:
  - accordions[].accordion_title       ← Question
  - accordions[].accordion_description ← Answer

tp-button:
  - tp_btn_text  ← Button label
```

### 1c. Custom Post Types (CPTs)
Check what custom post types the theme registers:
- Header CPT (e.g., `tp-header`)
- Footer CPT (e.g., `tp-footer`)
- Services CPT (e.g., `tp-services`)
- Portfolio CPT (e.g., `tp-portfolios`)
- Any others (galleries, pricing tables, etc.)

### 1d. Header Widget Settings
Find the header widget (e.g., `tp-header1`) and document:
- Top bar toggle setting (e.g., `tp_header_top_switch`)
- Phone field (e.g., `tp_ofc_phone`)
- Email field (e.g., `tp_ofc_email`)
- Address field (e.g., `tp_address`)
- Brand/side content fields

### 1e. Meta Dependencies
Check if the theme requires external meta plugins. Look for:
- `tpmeta_field()` calls → Need shim in child theme
- `tpmeta_kick()` calls → Need shim
- `rwmb_meta()` (Meta Box plugin) → Need shim
- `get_field()` (ACF) → Need shim

### 1f. Demo XML Base URL
Open `contents-demo.xml`, find `<link>` tag in `<channel>`:
```xml
<channel>
  <link>https://demo-site.example.com/theme-name</link>
```
This is the demo base URL for URL replacement.

---

## STEP 2: Prepare Theme Storage

Create directory structure:
```
backend/storage/themes/{theme-slug}/
├── {theme-slug}.zip
├── {theme-slug}-child.zip     (create one if theme doesn't include it)
├── Demo Data/
│   ├── contents-demo.xml
│   ├── customizer-data.dat
│   └── widget-settings.json
└── plugins/
    └── {theme-slug}-core.zip
```

If NO child theme exists, create a minimal one:
```php
// style.css
/*
Theme Name: {Theme Name} Child
Template: {theme-slug}
*/

// functions.php
<?php
add_action('wp_enqueue_scripts', function() {
    wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
});
```

---

## STEP 3: Create Layout Class

Create `backend/app/Services/Layouts/Layout{ThemeName}.php`:

```php
<?php
namespace App\Services\Layouts;

class Layout{ThemeName} extends AbstractLayout
{
    public function slug(): string { return '{theme-slug}'; }
    public function name(): string { return '{Theme Name}'; }
    public function description(): string { return 'Description of what this theme is for'; }
    public function bestFor(): array { return ['Industry1', 'Industry2', 'Industry3']; }
    public function isDark(): bool { return false; } // true if dark theme

    public function isThemeBased(): bool { return true; }
    public function themeSlug(): string { return '{theme-slug}'; }

    public function colors(): array
    {
        return [
            'primary'   => '#HEXCODE',  // Theme's primary color
            'secondary' => '#HEXCODE',
            'accent'    => '#HEXCODE',
            'bg'        => '#HEXCODE',
            'surface'   => '#HEXCODE',
            'text'      => '#HEXCODE',
            'muted'     => 'rgba(...)',
            'border'    => 'rgba(...)',
        ];
    }

    public function fonts(): array
    {
        return ['heading' => 'FontName', 'body' => 'FontName'];
    }

    // Theme-based layouts return empty arrays — content comes from demo import
    public function buildGlobalCss(): string { return ''; }
    public function buildGlobalJs(): string { return ''; }
    public function buildHomePage(array $content, array $images): array { return []; }
    public function buildAboutPage(array $content, array $images): array { return []; }
    public function buildServicesPage(array $content, array $images): array { return []; }
    public function buildPortfolioPage(array $content, array $images): array { return []; }
    public function buildContactPage(array $content, array $images): array { return []; }
    public function buildHeader(string $siteName, array $pages): array { return []; }
    public function buildFooter(string $siteName, array $pages, array $contact): array { return []; }
}
```

---

## STEP 4: Register in Config

Add to `backend/config/layouts.php`:

```php
'{theme-slug}' => [
    'class' => \App\Services\Layouts\Layout{ThemeName}::class,
    'name' => '{Theme Name}',
    'style' => 'professional',  // dark, light, modern, elegant, minimal, professional
    'primary' => '#HEXCODE',
    'accent' => '#HEXCODE',
    'preview_bg' => '#HEXCODE',
    'theme_based' => true,
    'theme_slug' => '{theme-slug}',
    'best_for' => ['Industry1', 'Industry2', 'Industry3', 'Industry4'],
    'keywords' => ['keyword1', 'keyword2', ...],  // For AI theme matching
],
```

---

## STEP 5: Add Widget Mapping to ProvisionWebsiteJob

This is the most important step. Open `backend/app/Jobs/ProvisionWebsiteJob.php` and modify the `injectIntoWidgets()` method.

### AI Content Structure (what the AI generates):
```php
$content = [
    'site_title' => 'Business Name',
    'tagline' => 'Tagline text',
    'pages' => [
        'home' => [
            'hero_title' => '...',
            'hero_subtitle' => '...',
            'hero_cta' => 'Button Text',
            'sections' => [
                ['type' => 'about_preview', 'title' => '...', 'content' => '...'],
                ['type' => 'features', 'title' => '...', 'items' => [
                    ['title' => '...', 'description' => '...'],
                    // ... 4-6 items
                ]],
                ['type' => 'stats', 'title' => '...', 'items' => [...]],
                ['type' => 'testimonials', 'title' => '...', 'items' => [
                    ['name' => '...', 'role' => '...', 'content' => '...'],
                ]],
                ['type' => 'faq', 'title' => '...', 'items' => [
                    ['question' => '...', 'answer' => '...'],
                ]],
                ['type' => 'team', 'title' => '...', 'items' => [
                    ['name' => '...', 'role' => '...'],
                ]],
                ['type' => 'cta', 'title' => '...', 'subtitle' => '...', 'button_text' => '...'],
            ],
        ],
        'about' => ['title' => '...', 'content' => '...', 'mission' => '...', 'vision' => '...'],
        'services' => ['title' => '...', 'items' => [...]],
        'contact' => ['phone' => '...', 'email' => '...', 'address' => '...'],
    ],
];
```

### Widget Mapping Template

For each new theme, map the theme's custom widgets to the AI content structure.
The mapping goes in `injectIntoWidgets()`.

**CRITICAL RULES:**
1. Use `stdClass` for `$ctx` (NOT array) — PHP arrays copy on pass, objects pass by handle. Counters must persist across recursive tree walks.
2. Check the ACTUAL field names by inspecting the demo XML or importing and querying the DB. Widget field names are NOT always intuitive (e.g., `tp_btn_btn_text` not `tp_slider_btn_text`).
3. Elementor SWITCHER control: Set to empty string `""` for OFF. PHP `if("no")` is truthy!
4. Brand replacement: Only replace in text fields, NEVER in URLs (str_replace('theme-name', 'business', url) corrupts paths).
5. Image replacement: Check `url` doesn't contain 'shape', 'bg-', 'icon' before replacing (decorative images should stay).

### Widget Field Discovery Script

Run this PHP script against an imported site to discover all widget types and their settings:

```php
<?php
$pdo = new PDO('mysql:host=127.0.0.1;dbname=wp_{db_name}', 'root', '');
$pages = $pdo->query("SELECT ID, post_title FROM wp_posts WHERE post_type = 'page' AND post_status = 'publish'")->fetchAll(PDO::FETCH_ASSOC);

foreach ($pages as $page) {
    echo "\n=== {$page['post_title']} (ID:{$page['ID']}) ===\n";
    $json = $pdo->prepare("SELECT meta_value FROM wp_postmeta WHERE post_id = ? AND meta_key = '_elementor_data'");
    $json->execute([$page['ID']]);
    $json = $json->fetchColumn();
    if (!$json) continue;

    $els = json_decode($json, true);
    $discover = function($els, $depth = 0) use (&$discover) {
        foreach ($els as $el) {
            $w = $el['widgetType'] ?? '';
            if ($w && !in_array($w, ['divider', 'spacer', 'html'])) {
                $pad = str_repeat('  ', $depth);
                echo "{$pad}{$w}:\n";
                foreach ($el['settings'] as $k => $v) {
                    if (str_starts_with($k, '_') || str_starts_with($k, '__')) continue;
                    if (is_string($v) && strlen($v) > 0 && strlen($v) < 200) {
                        echo "{$pad}  {$k} = " . substr($v, 0, 80) . "\n";
                    }
                    if (is_array($v) && isset($v[0]) && is_array($v[0])) {
                        echo "{$pad}  {$k} = [repeater: " . count($v) . " items]\n";
                        foreach ($v[0] as $rk => $rv) {
                            if ($rk !== '_id' && is_scalar($rv)) {
                                echo "{$pad}    [0].{$rk} = " . substr((string)$rv, 0, 60) . "\n";
                            }
                        }
                    }
                }
            }
            if (!empty($el['elements'])) $discover($el['elements'], $depth + 1);
        }
    };
    $discover($els);
}
```

---

## STEP 6: Handle Theme-Specific Meta Dependencies

If the theme calls functions from missing plugins, add shims to the child theme's `functions.php`.

The pipeline already adds this for Biddut. For new themes, modify the `installThemeLayout()` method to add appropriate shims.

### Common Shims:

**tpmeta_field (ThemePure themes):**
```php
if (!function_exists('tpmeta_field')) {
    function tpmeta_field($key) {
        $post_id = get_the_ID();
        if (!$post_id) return '';
        return get_post_meta($post_id, $key, true) ?: '';
    }
}
```

**rwmb_meta (Meta Box themes):**
```php
if (!function_exists('rwmb_meta')) {
    function rwmb_meta($key, $args = [], $post_id = null) {
        if (!$post_id) $post_id = get_the_ID();
        if (!$post_id) return '';
        return get_post_meta($post_id, $key, true) ?: '';
    }
}
```

**get_field (ACF themes):**
```php
if (!function_exists('get_field')) {
    function get_field($key, $post_id = false) {
        if (!$post_id) $post_id = get_the_ID();
        if (!$post_id) return '';
        return get_post_meta($post_id, $key, true) ?: '';
    }
}
```

---

## STEP 7: Update Import Script CPTs

In the `buildDemoImportScript()` method, update `$allowedTypes` to include the new theme's CPTs:

```php
$allowedTypes = ['page', 'post', 'attachment', 'nav_menu_item',
    // Add theme-specific CPTs:
    '{theme-prefix}-header', '{theme-prefix}-footer',
    '{theme-prefix}-services', '{theme-prefix}-portfolios',
    'elementor_library', 'wpcf7_contact_form', 'product'
];
```

Also update `elementor_cpt_support` in `configureSiteThemeBased()`:
```php
$cpts = serialize(['post', 'page', 'elementor-hf',
    '{theme-prefix}-header', '{theme-prefix}-footer',
    '{theme-prefix}-services', '{theme-prefix}-portfolios',
    'elementor_library'
]);
```

And the CSS regeneration post types in `regenerateElementorCss()`:
```php
'post_type' => ['page', 'post', 'elementor-hf', 'elementor_library',
    '{theme-prefix}-header', '{theme-prefix}-footer',
    '{theme-prefix}-services', '{theme-prefix}-portfolios'
],
```

---

## STEP 8: Update ID Remapping Keys

In the import script's fourth pass, add any theme-specific postmeta keys that store post IDs:

```php
$remapKeys = [
    '{theme-slug}_header_templates',
    '{theme-slug}_footer_template',
    '_elementor_template_id',
    // Add any theme-specific keys that reference post IDs
];
```

---

## STEP 9: Test

### 9a. Import Test
Create a test website through the platform or manually dispatch:
```php
php artisan tinker
>>> $w = App\Models\Website::create(['name' => 'Test Business', 'slug' => 'test-biz', 'business_type' => 'plumbing', 'ai_theme' => '{theme-slug}', 'status' => 'pending', 'user_id' => 1]);
>>> App\Jobs\ProvisionWebsiteJob::dispatch($w);
```

### 9b. Verify Checklist
- [ ] Theme installed and activated correctly
- [ ] All demo pages imported with Elementor data
- [ ] Images downloaded from demo site
- [ ] Header shows business contact info (phone, email)
- [ ] Header top bar disabled (if applicable)
- [ ] Footer shows business name (not theme name)
- [ ] Navigation menu has 5 clean pages (Home, About, Services, Portfolio, Contact)
- [ ] Hero section shows AI-generated title and subtitle
- [ ] Feature/service boxes show AI content
- [ ] Testimonials show AI-generated reviews
- [ ] CTA shows AI-generated title and button text
- [ ] No "Biddut" or theme demo brand name visible anywhere
- [ ] No broken images
- [ ] Site loads without PHP fatal errors

### 9c. Widget Discovery Verification
After import, run the discovery script (Step 5) to verify all widget settings are correctly mapped.

---

## CRITICAL GOTCHAS (MUST READ)

### 1. PHP Array vs Object
```php
// WRONG — array passed by value, counters reset on recursion:
$ctx = ['count' => 0];
function walk($els, $ctx) { $ctx['count']++; /* lost on return */ }

// CORRECT — object passed by handle:
$ctx = (object)['count' => 0];
function walk($els, $ctx) { $ctx->count++; /* persists */ }
```

### 2. Elementor SWITCHER Control
```php
// WRONG — PHP if("no") === true:
$settings['toggle'] = 'no';

// CORRECT — empty string is falsy:
$settings['toggle'] = '';
```

### 3. URL Byte Matching in Elementor JSON
Elementor JSON stores URLs with escaped slashes: `http:\/\/localhost\/site`.
For MySQL REPLACE, use:
```php
$bs = chr(92); $fs = chr(47);
$escaped = str_replace('/', $bs . $fs, $url);
```

### 4. Brand Replacement in JSON
NEVER do blind `str_replace('theme-name', 'business', $json)` — it corrupts URLs like `wp-content/themes/theme-name/assets/`.

Instead, parse JSON and only replace in TEXT setting keys:
```php
$textKeys = ['title', 'editor', 'description_text', 'tp_section_title',
             'tp_cta_sub_title', 'copyright_text', ...];
// Only replace in these keys, not in url/src/href fields
```

### 5. WooCommerce Fatal Error
Theme-based sites must SKIP WooCommerce activation. The template DB doesn't have WC tables, and WC crashes on `init` hook.

### 6. tpmeta_field / Meta Plugin Shim
Many Envato themes require meta plugins. The child theme must shim these functions or the theme's header/footer rendering will silently fail (returns empty markup).

### 7. Nav Menu Import
Demo XML has `<wp:term>` elements with `taxonomy=nav_menu`. These must be imported in the first pass BEFORE nav_menu_items are imported. Then in pass 3b, assign items to menus via `wp_set_object_terms()`.

### 8. Attachment ID Remapping
Elementor stores images as `{"url":"...","id":123}`. Without importing attachment posts and remapping IDs, `wp_get_attachment_url()` returns empty, causing blank `src=""` on images.

### 9. Elementor Cache Clearing
After ANY modification to `_elementor_data`, you MUST delete:
- `_elementor_element_cache` postmeta for that post
- `_elementor_css` postmeta for that post
- Or globally: all `_elementor_element_cache` + `_elementor_css` rows

### 10. Raw PDO for Postmeta
WordPress's `$wpdb->update()` and `update_post_meta()` run `wp_unslash()` which strips backslashes from Elementor JSON. Use raw PDO prepared statements instead:
```php
$pdo->prepare("INSERT INTO wp_postmeta (post_id, meta_key, meta_value) VALUES (?, ?, ?)")
    ->execute([$postId, $key, $rawJson]);
```

---

## FILE LOCATIONS REFERENCE

```
backend/
├── app/Jobs/ProvisionWebsiteJob.php     ← Main build pipeline (2300+ lines)
├── app/Services/Layouts/
│   ├── AbstractLayout.php               ← Base class
│   ├── LayoutBiddut.php                 ← Reference theme-based layout
│   ├── LayoutNoir.php                   ← Reference Elementor JSON layout
│   └── Layout{NewTheme}.php            ← YOUR NEW FILE
├── config/layouts.php                   ← Layout registry
└── storage/themes/
    ├── biddut/                          ← Reference theme storage
    └── {new-theme}/                     ← YOUR NEW THEME FILES
```

### Key Methods in ProvisionWebsiteJob.php:
- `installThemeLayout()` — Extracts theme, plugins, runs import
- `buildDemoImportScript()` — Generates the PHP import script (5-pass)
- `downloadDemoImages()` — Downloads all attachment images from demo site
- `injectContentIntoTheme()` — Orchestrates AI content injection
- `injectIntoWidgets()` — Walks Elementor tree, maps widgets to AI content
- `injectIntoHeader()` — Disables top bar, injects contact info
- `injectIntoFooter()` — Brand name replacement
- `cleanNavMenu()` — Deletes all items, creates 5-page menu
- `replaceBrandInElements()` — JSON-aware brand replacement
- `configureSiteThemeBased()` — Final site configuration

---

## PROMPT END

---

## EXAMPLE: How I would give this to terminal

"Here is a new Envato theme called 'Flavor' for restaurant businesses. The ZIP is at C:\Users\1\Desktop\flavor-theme.zip. Please follow the THEME_INTEGRATION_PROMPT.md guide at C:\Users\1\Desktop\Webnewbiz\THEME_INTEGRATION_PROMPT.md to integrate it into the Webnewbiz platform. Start by analyzing the theme's custom widgets, then create the layout class, register it, and add the widget mapping."
