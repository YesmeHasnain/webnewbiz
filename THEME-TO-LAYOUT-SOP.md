# Theme-to-Layout Conversion SOP (Standard Operating Procedure)

## Copy-paste this prompt to each Claude terminal:

---

### PROMPT FOR CLAUDE INSTANCES:

```
You are converting an Envato Elementor theme into a Layout class for the Webnewbiz project.

## Project Location
- Backend: c:\Users\1\Desktop\Webnewbiz\backend
- Layouts: c:\Users\1\Desktop\Webnewbiz\backend\app\Services\Layouts\
- Config: c:\Users\1\Desktop\Webnewbiz\backend\config\layouts.php

## STEP-BY-STEP PROCESS:

### Step 1: Read the reference files FIRST (MANDATORY)
- Read `backend/app/Services/Layouts/AbstractLayout.php` — understand all helper methods
- Read `backend/app/Services/Layouts/LayoutAzure.php` — this is the REFERENCE layout, follow its pattern exactly
- Read `backend/config/layouts.php` — see existing layouts
- Read `.claude/projects/c--Users-1-Desktop-Webnewbiz/memory/elementor-json.md` — Elementor JSON rules

### Step 2: Analyze the theme I give you
- I will provide theme files (Elementor JSON exports, screenshots, or template kit)
- Extract ONLY the visual design structure: sections, containers, layout pattern, colors, fonts
- Identify: hero style, services/features card style, testimonial style, CTA style, about style
- Note the color scheme (primary, secondary, accent, backgrounds)
- Note fonts used

### Step 3: Create the Layout class
File: `backend/app/Services/Layouts/Layout{Name}.php`
- Extend `AbstractLayout`
- Implement ALL abstract methods:
  - slug(), name(), description(), bestFor(), isDark()
  - colors(), fonts()
  - buildGlobalCss(), buildGlobalJs()
  - buildHomePage(), buildAboutPage(), buildServicesPage(), buildPortfolioPage(), buildContactPage()
  - buildHeader(), buildFooter()

### AVAILABLE HELPERS from AbstractLayout:

#### Element Primitives:
- `self::eid()` — 7-char hex Elementor ID [a-f0-9]{7}
- `self::container($settings, $elements)` — Elementor container (default: content_width='full')
- `self::heading($text, $tag, $settings)` — heading widget (h1-h6)
- `self::textEditor($html, $settings)` — text editor widget
- `self::image($url, $settings)` — image widget
- `self::button($text, $url, $settings)` — button widget
- `self::spacer($px)` — spacer widget
- `self::divider($settings)` — divider widget
- `self::html($code)` — raw HTML widget (for CSS/JS injection)
- `self::icon($iconClass)` — icon widget

#### Layout Helpers:
- `$this->section($settings, $elements)` — boxed section (1200px, with responsive padding)
- `self::twoCol($leftElements, $rightElements, $leftPct, $rowSettings, $leftSettings, $rightSettings)` — responsive 2-column layout
- `self::cardGrid($cards, $cols, $gridSettings)` — responsive card grid (3-col default, auto mobile stacking)

#### Sizing Helpers:
- `self::pad($top, $right, $bottom, $left)` — padding array
- `self::margin($top, $right, $bottom, $left)` — margin array
- `self::size($desktop, $tablet, $mobile, $unit)` — responsive size
- `self::responsiveSize($desktop, $tablet, $mobile, $unit)` — font size with responsive variants
- `self::radius($px)` — border radius
- `self::rWidth($desktop, $tablet, $mobile)` — responsive width percentages

#### Instance Helpers (use $this->):
- `$this->headline($text, $tag, $settings)` — styled heading using layout fonts
- `$this->eyebrow($text)` — small uppercase label
- `$this->bodyText($text, $settings)` — styled paragraph
- `$this->ctaButton($text, $url)` — primary CTA button
- `$this->ghostButton($text, $url)` — outline/ghost button

### CRITICAL ELEMENTOR JSON RULES:

1. **content_width**: Use `'full'` (NOT `'full-width'`!)
2. **Gap**: Use `'flex_gap' => ['size'=>24,'unit'=>'px','column'=>'24','row'=>'24']` (NOT `'gap'`!)
3. **Mobile stacking**: Use `'flex_direction_mobile' => 'column'` (NOT CSS media queries)
4. **Mobile widths**: Use `'width_mobile' => ['size'=>100,'unit'=>'%','sizes'=>[]]`
5. **Responsive padding**: Always set `padding_mobile` and `padding_tablet` alongside `padding`
6. **Sections**: DON'T set content_width on sections (defaults to boxed). DO set `boxed_width`
7. **All size values**: Include `'sizes' => []` array (e.g. `['size'=>100,'unit'=>'vh','sizes'=>[]]`)
8. **Responsive widths**: Use `self::rWidth(33, 48, 100)` — returns width + width_tablet + width_mobile
9. **Card grids**: Use `self::cardGrid($cards, $cols)` — handles responsive widths + flex_wrap automatically
10. **Two columns**: Use `self::twoCol($left, $right, $pct)` — handles mobile stacking automatically

### Step 4: Content variables — use these placeholders from $content array:
```php
// Home page — $c is the $content parameter
$c['site_name']           // Business name (fallback: 'Business Name')
$c['hero_title']          // Main hero heading
$c['hero_subtitle']       // Hero subtext
$c['hero_cta']            // Hero CTA button text
$c['hero_eyebrow']        // Small text above hero title
$c['hero_cta_url']        // CTA link (default: '#services')
$c['hero_ghost_cta']      // Secondary button text (default: 'Learn More')

$c['about_title']         // About section title
$c['about_text']          // About section paragraph 1
$c['about_text2']         // About section paragraph 2

$c['services_title']      // Services section title
$c['services_subtitle']   // Services section subtitle
$c['services']            // array of ['icon'=>'⚡', 'title'=>'Name', 'desc'=>'Description']

$c['benefits_title']      // Benefits section title
$c['benefits']            // array of ['icon'=>'🚀', 'title'=>'Name', 'desc'=>'Description']

$c['stats']               // array of ['number'=>'500', 'suffix'=>'+', 'label'=>'Projects']

$c['testimonials_title']  // Testimonials section title
$c['testimonials']        // array of ['quote'=>'...', 'name'=>'John', 'role'=>'CEO', 'initials'=>'JD']

$c['cta_title']           // Final CTA title
$c['cta_text']            // Final CTA subtitle
$c['cta_button']          // CTA button text (default: 'Get Started')

// Images from $images array ($img parameter)
$img['hero'] ?? ''        // Hero background/image
$img['about'] ?? ''       // About section image
$img['gallery1'] ?? ''    // Gallery image 1
$img['gallery2'] ?? ''    // Gallery image 2
$img['services'] ?? ''    // Services background
```

ALWAYS use `?? 'fallback'` or `?? []` — NEVER access a key without fallback.

### Step 5: Register in config
Add entry to `backend/config/layouts.php`:
```php
'slugname' => [
    'class' => \App\Services\Layouts\LayoutSlugname::class,
    'name' => 'Display Name',
    'style' => 'dark|light|modern|elegant|minimal|professional',
    'primary' => '#hexcolor',
    'accent' => '#hexcolor',
    'preview_bg' => '#hexcolor',
    'best_for' => ['Industry1', 'Industry2'],
    'keywords' => ['keyword1', 'keyword2', ...at least 15 keywords],
],
```

### Step 6: Verify
- Check PHP syntax: `php -l backend/app/Services/Layouts/Layout{Name}.php`
- Ensure no Elementor Pro widgets used
- Ensure all abstract methods implemented
- Ensure colors() returns all 9 keys: primary, secondary, accent, bg, surface, surface2, text, muted, border
- Ensure fonts() returns: heading, body
- Test build: `php -r "require 'vendor/autoload.php'; $l = new App\Services\Layouts\Layout{Name}(); echo json_encode($l->buildPage('home', ['site_name'=>'Test'], []));" | php -r "echo strlen(file_get_contents('php://stdin')).' bytes';"`

## CRITICAL RULES:

1. **content_width** in containers: use `'full'` (NOT `'full-width'`)
2. **Gap**: use `'flex_gap'` with `column` and `row` string keys (NOT `'gap'`)
3. **Mobile responsive**: use native Elementor responsive settings:
   - `flex_direction_mobile => 'column'` (stacks children vertically on mobile)
   - `width_mobile => ['size'=>100,'unit'=>'%','sizes'=>[]]` (full width on mobile)
   - `padding_mobile => self::pad(50, 15)` (smaller padding on mobile)
4. **Use rWidth()** for responsive child widths: `self::rWidth(33, 48, 100)` = 33% desktop, 48% tablet, 100% mobile
5. **Use cardGrid()** for grids: `self::cardGrid($cards, 3)` — handles flex_wrap, flex_gap, responsive widths
6. **Use twoCol()** for splits: `self::twoCol($left, $right, 50)` — handles mobile stacking
7. **Sections**: Use `$this->section()` which gives boxed 1200px + responsive padding. DON'T set content_width on sections.
8. **Element IDs**: Always use `self::eid()` — generates 7-char hex [a-f0-9]
9. **All size arrays**: MUST include `'sizes' => []` key
10. **No $content keys without fallbacks** — always use `?? ''` or `?? []`
11. **CSS class names**: prefix with layout slug to avoid conflicts (e.g., `.azure-card`, `.noir-card`)
12. **Widgets allowed**: heading, text-editor, image, button, icon, spacer, divider, html (Elementor FREE only)
13. **NO Elementor Pro widgets** (form, slides, nav-menu, etc.) — use HTML widget to build them

## NAMING CONVENTION:
- Layout slug: lowercase, single word (e.g., 'techstore', 'clinic', 'academy')
- Class name: Layout + PascalCase slug (e.g., LayoutTechstore, LayoutClinic)
- CSS prefix: slug name (e.g., .techstore-card, .clinic-hero)

Now I will give you the theme. Extract ONLY the design/layout structure. Recreate it using AbstractLayout helpers. Make it pixel-close to the original design but using dynamic $content and $images.
```

---

## DIVISION STRATEGY FOR MULTIPLE TERMINALS:

### Terminal 1: Themes 1-5
### Terminal 2: Themes 6-10
### Terminal 3: Themes 11-15
### Terminal 4: Themes 16-20

Each terminal creates its own Layout files — no conflicts since each layout has a unique name.

## AFTER ALL TERMINALS DONE:
- Collect all Layout*.php files in backend/app/Services/Layouts/
- Merge all config entries into backend/config/layouts.php
- Run: `php -l backend/app/Services/Layouts/Layout*.php` to syntax check all
- Update frontend layout colors in builder-wizard.ts and WebsiteController validation

## IMPORTANT NOTES:
- Each Claude instance works INDEPENDENTLY — no shared state
- Each creates NEW files — no editing same file (except config at the end)
- Config merge should be done LAST by ONE terminal only
- Theme files should be placed in a temp folder per terminal (e.g., c:\tmp\themes-t1\)

## WHAT TO GIVE CLAUDE WITH EACH THEME:
Best input (in order of preference):
1. **Elementor JSON export** — most accurate, Claude can extract exact structure
2. **Screenshots** — Claude can see the visual layout and recreate it
3. **Theme URL/demo** — Claude can understand the design concept
4. **Template kit ZIP** — contains JSON files for each page

Example:
```
"Yeh theme hai: TechHub Pro. Screenshots attached. Isko Layout class mein convert karo."
```
