# Webnewbiz — Complete Project Roadmap & UI Structure

## Project Overview

**Webnewbiz** is an AI-powered WordPress website builder SaaS platform. Users describe their business in plain language, and the system auto-generates a fully functional WordPress website with Elementor-based layouts, AI-generated content, and curated images — all deployed on local XAMPP (dev) or DigitalOcean servers (production).

**Tech Stack:**
- **Backend:** Laravel 11, PHP 8.2
- **Frontend:** Blade templates, Tailwind CSS, Alpine.js
- **WordPress Engine:** Direct MySQL provisioning (no WP-CLI), Elementor page builder, Hello Elementor theme
- **AI Services:** Claude API (content generation), Ideogram API (logo/favicon), Unsplash API (stock photos)
- **Infrastructure:** XAMPP (local dev), DigitalOcean + CloudPanel (production), Cloudflare DNS

---

## System Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                    LARAVEL APPLICATION                       │
├─────────────┬──────────────┬─────────────┬──────────────────┤
│  Controllers│   Services   │    Models   │     Jobs         │
│             │              │             │                  │
│ Home        │ AIContent    │ User        │ ProvisionWebsite │
│ Auth        │ WordPress    │ Website     │                  │
│ Dashboard   │ WebsiteBuilder│ Plan       │                  │
│ Builder     │ Ideogram     │ Subscription│                  │
│ Website     │ Unsplash     │ Server      │                  │
│ Chatbot     │ Anthropic    │ ChatMessage │                  │
│ Settings    │ ChatAction   │ Domain      │                  │
│             │ Dns          │ SslCert     │                  │
│             │ Ssl          │ ApiKey      │                  │
│             │ Screenshot   │ ActivityLog │                  │
│             │ Templates/   │ WP Backup   │                  │
│             │  (7 styles)  │ WP Plugin   │                  │
│             │              │ WP Theme    │                  │
└─────────────┴──────────────┴─────────────┴──────────────────┘
         │                            │
         ▼                            ▼
┌─────────────────┐          ┌─────────────────────┐
│  External APIs  │          │  WordPress (XAMPP)   │
│                 │          │                      │
│ Claude API      │          │ MySQL databases      │
│ Ideogram API    │          │ wp-content/uploads   │
│ Unsplash API    │          │ Elementor JSON data  │
│ Cloudflare DNS  │          │ Hello Elementor theme│
└─────────────────┘          └─────────────────────┘
```

---

## Database Schema (19 tables)

### Core Tables
| Table | Purpose | Key Columns |
|-------|---------|-------------|
| `users` | Platform users | name, email, password, role (user/admin/superadmin), status, phone, company |
| `plans` | Subscription tiers | name, slug, price, billing_cycle, max_websites, storage_gb, features (JSON) |
| `subscriptions` | User-plan bindings | user_id, plan_id, status (active/cancelled/expired/trial), starts_at, ends_at |
| `servers` | Hosting servers | name, ip_address, region, status, max_websites, cpu/memory/disk usage |
| `websites` | Generated websites | user_id, server_id, name, subdomain, url, status, wp credentials, ai_prompt, ai_business_type, ai_style, ai_generated_content (JSON) |

### Supporting Tables
| Table | Purpose |
|-------|---------|
| `domains` | Custom domain mappings (DNS status, SSL status) |
| `ssl_certificates` | Let's Encrypt cert tracking |
| `website_backups` | Backup records (full/database/files) |
| `website_plugins` | Installed WP plugins per site |
| `website_themes` | Installed WP themes per site |
| `api_keys` | Developer API keys (wnb_ prefix) |
| `activity_logs` | Audit trail (polymorphic) |
| `chat_messages` | AI chatbot conversation history per website |
| `sessions`, `cache`, `jobs`, `personal_access_tokens` | Laravel infrastructure |

---

## User Flows & UI Structure

### Flow 1: Landing → Registration → Dashboard

```
┌──────────────┐     ┌──────────────┐     ┌──────────────┐
│  Home Page   │────▶│   Register   │────▶│  Dashboard   │
│  (/)         │     │  (/register) │     │  (/dashboard)│
│              │     │              │     │              │
│ Hero section │     │ Name         │     │ Stats cards  │
│ Features     │     │ Email        │     │ Recent sites │
│ How it works │     │ Password     │     │ Quick actions│
│ Pricing CTA  │     │ Confirm pass │     │ Plan info    │
│ Footer       │     └──────────────┘     └──────────────┘
└──────────────┘
       │
       ▼
┌──────────────┐
│  Pricing     │
│  (/pricing)  │
│              │
│ Free plan    │
│ Starter $10  │
│ Business $25 │
│ Agency $50   │
└──────────────┘
```

### Flow 2: Website Builder (Core Feature)

```
┌─────────────────────────────────────────────────────────────┐
│                    BUILDER FORM (/builder)                   │
│                                                             │
│  Step 1: Business Info                                      │
│  ┌─────────────────────────────────────────────────────┐    │
│  │ Business Name: [________________]                    │    │
│  │ Business Type: [▼ 30 categories ]                    │    │
│  │ Style:         [Modern] [Classic] [Bold] [Elegant]   │    │
│  │                [Creative] [Luxury] [Tech] [Warm]     │    │
│  │                [Minimal]                             │    │
│  └─────────────────────────────────────────────────────┘    │
│                                                             │
│  Step 2: Describe Your Business                             │
│  ┌─────────────────────────────────────────────────────┐    │
│  │ [textarea: Describe what your business does...]      │    │
│  │                                                      │    │
│  │ [✨ AI Enhance] ← expands description to ~900 chars  │    │
│  └─────────────────────────────────────────────────────┘    │
│                                                             │
│  Step 3: Color Palette                                      │
│  ┌─────────────────────────────────────────────────────┐    │
│  │ [🔵Ocean] [🟢Emerald] [🟡Sunset] [🔴Rose]           │    │
│  │ [🟣Violet] [⚫Slate]                                 │    │
│  └─────────────────────────────────────────────────────┘    │
│                                                             │
│  [🚀 Generate My Website]                                   │
└─────────────────────────────────────────────────────────────┘
          │
          ▼  POST /builder/generate → dispatches ProvisionWebsiteJob
          │
┌─────────────────────────────────────────────────────────────┐
│              PROGRESS PAGE (/builder/{id}/status)            │
│                                                             │
│  ┌─────────────────────────────────────────────────────┐    │
│  │  Building Your Website...                            │    │
│  │                                                      │    │
│  │  ✅ Creating WordPress installation                  │    │
│  │  ✅ Generating AI content                            │    │
│  │  🔄 Generating images...                             │    │
│  │  ⬜ Building page layouts                            │    │
│  │  ⬜ Final setup                                      │    │
│  │                                                      │    │
│  │  [=========>          ] 45%                           │    │
│  │                                                      │    │
│  │  ⏱️ Estimated: ~2 minutes remaining                  │    │
│  └─────────────────────────────────────────────────────┘    │
│                                                             │
│  Polls GET /builder/{id}/status (JSON) every 3 seconds      │
└─────────────────────────────────────────────────────────────┘
          │
          ▼  When status = 'active'
          │
┌─────────────────────────────────────────────────────────────┐
│            COMPLETE PAGE (/builder/{id}/complete)            │
│                                                             │
│  ┌─────────────────────────────────────────────────────┐    │
│  │  🎉 Your Website is Ready!                           │    │
│  │                                                      │    │
│  │  [Website Screenshot Preview]                        │    │
│  │                                                      │    │
│  │  URL: mysite.webnewbiz.com                           │    │
│  │                                                      │    │
│  │  [🌐 Visit Website]  [⚙️ WP Admin]  [📊 Dashboard]  │    │
│  └─────────────────────────────────────────────────────┘    │
└─────────────────────────────────────────────────────────────┘
```

### Flow 3: Website Management + AI Chatbot

```
┌─────────────────────────────────────────────────────────────┐
│               MY WEBSITES (/websites)                       │
│                                                             │
│  ┌──────────┐  ┌──────────┐  ┌──────────┐                  │
│  │[Screenshot│  │[Screenshot│  │  + New   │                  │
│  │  mysite  ]│  │  shop   ]│  │ Website  │                  │
│  │  Active   │  │  Active  │  │          │                  │
│  │ [Manage]  │  │ [Manage] │  │ [Create] │                  │
│  └──────────┘  └──────────┘  └──────────┘                  │
└─────────────────────────────────────────────────────────────┘
          │
          ▼  Click "Manage"
          │
┌─────────────────────────────────────────────────────────────┐
│          WEBSITE DETAIL (/websites/{id})                     │
│                                                             │
│  ┌───────────────────────┬──────────────────────────────┐   │
│  │   Website Info         │   AI Chatbot                 │   │
│  │                       │                              │   │
│  │   Name: My Site       │  ┌──────────────────────┐    │   │
│  │   URL: mysite.wnb.com │  │ 🤖 Hi! How can I    │    │   │
│  │   Status: ● Active    │  │    help with your    │    │   │
│  │   Type: Restaurant    │  │    website?          │    │   │
│  │   Style: Modern       │  │                      │    │   │
│  │                       │  │ 👤 Change the hero   │    │   │
│  │   [🌐 Visit Site]     │  │    text to "Welcome  │    │   │
│  │   [⚙️ WP Admin]       │  │    to Our Restaurant"│    │   │
│  │   [✏️ Elementor]      │  │                      │    │   │
│  │   [🗑️ Delete]         │  │ 🤖 Done! I've       │    │   │
│  │                       │  │    updated the hero  │    │   │
│  │   Quick Stats:        │  │    heading.          │    │   │
│  │   Storage: 45 MB      │  │                      │    │   │
│  │   Created: Feb 25     │  │ [Type message... 📤] │    │   │
│  │                       │  └──────────────────────┘    │   │
│  └───────────────────────┴──────────────────────────────┘   │
└─────────────────────────────────────────────────────────────┘
```

### Flow 4: Settings

```
┌─────────────────────────────────────────────────────────────┐
│                  SETTINGS (/settings)                        │
│                                                             │
│  Profile                                                    │
│  ┌─────────────────────────────────────────────────────┐    │
│  │ Name:    [________________]                          │    │
│  │ Email:   [________________]                          │    │
│  │ Phone:   [________________]                          │    │
│  │ Company: [________________]                          │    │
│  │ [Save Profile]                                       │    │
│  └─────────────────────────────────────────────────────┘    │
│                                                             │
│  Change Password                                            │
│  ┌─────────────────────────────────────────────────────┐    │
│  │ Current:  [________]                                 │    │
│  │ New:      [________]                                 │    │
│  │ Confirm:  [________]                                 │    │
│  │ [Update Password]                                    │    │
│  └─────────────────────────────────────────────────────┘    │
└─────────────────────────────────────────────────────────────┘
```

---

## Build Pipeline (ProvisionWebsiteJob)

The core of the platform — converts user input into a live WordPress website:

```
User Input (name, type, style, prompt, colors)
    │
    ▼
┌─── Step 1: Create WP Database ──────────────────────────┐
│  Create MySQL database + user via XAMPP                   │
│  Import WordPress SQL dump (tables, options, admin user)  │
│  ~5 seconds                                              │
└──────────────────────────────────────────────────────────┘
    │
    ▼
┌─── Step 2: Configure WordPress ─────────────────────────┐
│  Set site URL, title, tagline in wp_options               │
│  Activate Hello Elementor theme                           │
│  Activate Elementor + Elementor Pro plugins               │
│  Set static front page, create default pages              │
│  ~3 seconds                                              │
└──────────────────────────────────────────────────────────┘
    │
    ▼
┌─── Step 3: Generate AI Content ─────────────────────────┐
│  Claude API → full website content (JSON):                │
│    - Pages: Home, About, Services, Contact                │
│    - Sections per page: hero, features, about, stats,     │
│      testimonials, FAQ, team, pricing, CTA, process       │
│    - SEO meta titles & descriptions                       │
│  ~15-20 seconds                                          │
└──────────────────────────────────────────────────────────┘
    │
    ▼
┌─── Step 4: Acquire Images ──────────────────────────────┐
│  Unsplash API → 3 curated stock photos (hero, about, svc)│
│  Ideogram API → AI logo + favicon (if API key set)        │
│  Fallback → inline SVG placeholders                       │
│  Copy to wp-content/uploads/                              │
│  Register as WP media attachments (get IDs)               │
│  ~25-30 seconds (parallel)                               │
└──────────────────────────────────────────────────────────┘
    │
    ▼
┌─── Step 5: Build Elementor Layouts ─────────────────────┐
│  Select template class via TemplateRegistry               │
│  For each page:                                          │
│    1. Load JSON template (hero, CTA sections)             │
│    2. Hydrate {{placeholders}} with content/colors/images │
│    3. Build dynamic sections (features, testimonials...)  │
│    4. Merge static + dynamic into final Elementor JSON    │
│    5. Inject image attachment IDs for Elementor rendering │
│    6. Store in wp_postmeta as _elementor_data             │
│  ~5 seconds                                              │
└──────────────────────────────────────────────────────────┘
    │
    ▼
┌─── Step 6: Warmup & Finalize ───────────────────────────┐
│  HTTP request to each page → trigger Elementor CSS gen    │
│  Update website status → 'active'                         │
│  Store AI content in ai_generated_content column          │
│  ~5 seconds                                              │
└──────────────────────────────────────────────────────────┘
    │
    ▼
✅ Website Live! (~2 minutes total)
```

---

## Template System Architecture

### 7 Pre-built Elementor Templates

| Template | Style Keywords | Best For | Fonts |
|----------|---------------|----------|-------|
| **Agency** | modern, minimal | General, agencies | Montserrat + Open Sans |
| **Starter** | bold, vibrant | Startups, bold brands | Poppins + Open Sans |
| **Corporate** | classic, elegant | Law, finance, consulting | Playfair Display + Lato |
| **Flavor** | warm, organic | Restaurants, food, wellness | Raleway + Nunito |
| **Zenith** | tech, saas, clean | SaaS, tech, IT | Inter + DM Sans |
| **Prestige** | luxury, editorial | Luxury, real estate | Cormorant Garant + Libre Franklin |
| **Vivid** | creative, artistic | Portfolios, design studios | Space Grotesk + Work Sans |

### Template Structure (per template)
```
resources/templates/elementor/{template_dir}/
├── home_hero.json         ← Hero section (JSON, hydrated)
├── home_cta.json          ← CTA section (JSON, hydrated)
├── about_hero.json        ← About page hero
├── services_hero.json     ← Services page hero
└── contact_hero.json      ← Contact page hero
```

### Dynamic Section Types (built in PHP)
- `features` — Icon boxes in grid columns (3-4 per row)
- `testimonials` — Quote cards with avatars
- `about_preview` — Image + text side-by-side
- `stats` — Counter widgets in columns
- `faq` — Accordion/toggle widget
- `team` — Photo + name + role cards
- `pricing` — Pricing table columns
- `process` — Numbered step icons ("How It Works")
- `contact_form` — Contact form section
- `gallery` — Image gallery grid
- `cta` — Call-to-action (from JSON template)

### Hydration Flow
```
JSON Template                    AI Content + Colors + Images
     │                                      │
     └────────────┬─────────────────────────┘
                  ▼
         hydrateJson() — replaces {{placeholders}}
                  │
                  ▼
         injectImageIds() — adds WP attachment IDs
                  │
                  ▼
         regenerateElementorIds() — unique 7-char hex IDs
                  │
                  ▼
         Final Elementor JSON → stored in wp_postmeta
```

---

## AI Chatbot System

Post-build, users can modify their website via natural language:

### Supported Actions
| Action | Description | Example User Message |
|--------|-------------|---------------------|
| `update_site_title` | Changes WordPress site title | "Change site name to My Restaurant" |
| `update_tagline` | Changes WordPress tagline | "Update tagline to 'Best food in town'" |
| `update_hero_text` | Changes hero heading, subtitle, CTA button | "Change hero title to 'Welcome'" |
| `change_colors` | Updates primary/secondary/accent colors | "Make the site blue and gold" |
| `update_page_content` | Edits text content on any page | "Update about page text" |
| `add_page` | Creates a new WordPress page | "Add a Gallery page" |
| `delete_page` | Moves a page to trash | "Remove the blog page" |
| `change_button_text` | Updates button text on any page | "Change the CTA to 'Get Started'" |

### Chatbot Architecture
```
User Message → ChatbotController → AnthropicService (Claude API)
                                          │
                                          ▼
                                   AI returns JSON actions
                                          │
                                          ▼
                                   ChatActionService.executeActions()
                                          │
                                          ▼
                                   WordPressService (direct MySQL)
                                          │
                                          ▼
                                   Response + action results → User
```

---

## Services Layer

| Service | Responsibility |
|---------|---------------|
| **AIContentService** | Generates full website content via Claude API (pages, sections, SEO) |
| **WordPressService** | Creates WP databases, imports SQL, manages options/posts/meta via PDO |
| **WebsiteBuilderService** | Orchestrates the full build pipeline (8 steps) |
| **IdeogramService** | AI-generated logos and favicons via Ideogram API |
| **UnsplashService** | Curated stock photos by business type from Unsplash |
| **AnthropicService** | Claude API wrapper for chatbot conversations |
| **ChatActionService** | Executes chatbot action commands against WordPress databases |
| **DnsService** | Cloudflare DNS record management |
| **SslService** | Let's Encrypt SSL certificate management |
| **ScreenshotService** | Website screenshot capture |
| **TemplateRegistry** | Maps style/business type to correct template class |

---

## Pricing Tiers

| Feature | Free | Starter ($10/mo) | Business ($25/mo) | Agency ($50/mo) |
|---------|------|-------------------|--------------------|-----------------|
| Websites | 1 | 5 | 20 | 100 |
| Storage | 1 GB | 10 GB | 50 GB | 200 GB |
| Bandwidth | 10 GB | 100 GB | 500 GB | 2 TB |
| Custom Domain | No | Yes | Yes | Yes |
| Daily Backups | No | Yes | Yes | Yes |
| Priority Support | No | No | Yes | Yes |
| White Label | No | No | No | Yes |
| SSL | Yes | Yes | Yes | Yes |

---

## Admin Panel (Built, Routes Not Yet Active)

Views exist for a full admin panel at `resources/views/admin/`:

| Section | Views | Purpose |
|---------|-------|---------|
| Dashboard | `admin/dashboard/index` | System-wide stats |
| Servers | `admin/servers/index,show,create` | Server management |
| Websites | `admin/websites/index,show` | All websites across users |
| Users | `admin/users/index,show` | User management |
| Plans | `admin/plans/index,form` | Pricing plan CRUD |
| Domains | `admin/domains/index` | Domain management |
| Activity Logs | `admin/activity-logs/index` | Audit trail viewer |
| Auth | `admin/auth/login` | Separate admin login |

---

## Current Configuration

### Environment Variables Required
```
APP_NAME=Webnewbiz
APP_URL=http://webnewbiz.test

# AI Services
ANTHROPIC_API_KEY=           # Claude API for content generation + chatbot
IDEOGRAM_API_KEY=            # Logo/favicon generation
UNSPLASH_ACCESS_KEY=         # Stock photo search

# Infrastructure (Production)
CLOUDFLARE_API_TOKEN=        # DNS management
CLOUDFLARE_ZONE_ID=
PLATFORM_DOMAIN=webnewbiz.com
PLATFORM_SUBDOMAIN_SUFFIX=.webnewbiz.com

# Local Dev (XAMPP)
LOCAL_DEV_MODE=true
XAMPP_HTDOCS_PATH=C:/xampp/htdocs
```

---

## Roadmap — Phase-by-Phase

### Phase 1: Stabilize & Polish (Current) ✅ Mostly Done
- [x] Fix Elementor image rendering (attachment IDs)
- [x] Fix addslashes/PDO double-escaping bug
- [x] Rewrite Unsplash service with curated photos
- [x] Fix Elementor ID collision (regex)
- [x] Builder UI overhaul (multi-step form)
- [x] AI chatbot for post-build editing
- [ ] Fix Unsplash fallback URL (deprecated source.unsplash.com)
- [ ] Error handling for failed builds (retry mechanism)

### Phase 2: Speed Optimization (Next)
- [ ] Parallel image generation via `Http::pool()` (Ideogram batch)
- [ ] Unsplash API search instead of deprecated source URLs
- [ ] Cache AI prompts for common business types
- [ ] Pre-warm Elementor CSS during build (not on first visit)
- **Target:** Build time from ~4.5 min → ~2 min

### Phase 3: Pre-made Industry Templates
- [ ] Create 15-20 industry-specific template JSONs (full pages, not just sections)
  - Restaurant, E-commerce, Portfolio, Agency, SaaS, Healthcare, Real Estate, Fitness, Photography, Legal, Finance, Travel, Fashion, Beauty/Spa, Construction
- [ ] Each template: complete Elementor page JSON with only ~10 parameter slots:
  1. `{{business_name}}`
  2. `{{tagline}}`
  3. `{{phone}}`
  4. `{{email}}`
  5. `{{address}}`
  6. `{{primary_color}}`
  7. `{{secondary_color}}`
  8. `{{accent_color}}`
  9. `{{hero_image_url}}`
  10. `{{logo_url}}`
- [ ] Minimal AI dependency — only for generating section text (not layout)
- [ ] User can pick specific industry template in builder
- **Impact:** Faster builds, more professional layouts, lower API costs

### Phase 4: Real Authentication & Payments
- [ ] Remove dev auto-login
- [ ] Implement proper registration/login with email verification
- [ ] Integrate Stripe for subscription payments
- [ ] Enforce plan limits (website count, storage, bandwidth)
- [ ] Implement password reset flow
- [ ] OAuth (Google, GitHub) sign-in

### Phase 5: Admin Panel Activation
- [ ] Wire up admin routes (`/admin/*`)
- [ ] Admin middleware (role check: admin/superadmin)
- [ ] Admin dashboard with:
  - Total users, websites, revenue metrics
  - Server health monitoring
  - Recent activity log
- [ ] User management (suspend, delete, change plan)
- [ ] Server management (add, monitor, decommission)
- [ ] Plan/pricing management (CRUD)

### Phase 6: Production Infrastructure
- [ ] DigitalOcean server provisioning (API integration)
- [ ] CloudPanel integration for multi-tenant WordPress hosting
- [ ] Cloudflare DNS automation (subdomain + custom domain)
- [ ] Let's Encrypt SSL auto-provisioning
- [ ] Automated daily backups to S3/DigitalOcean Spaces
- [ ] Server health monitoring + alerts

### Phase 7: Advanced Features
- [ ] Website cloning (duplicate a site with new name/content)
- [ ] Template marketplace (users share/sell templates)
- [ ] Multi-language support (AI content in any language)
- [ ] E-commerce integration (WooCommerce setup)
- [ ] Blog setup with sample posts
- [ ] Contact form with email notifications
- [ ] Google Analytics integration
- [ ] SEO optimization tools
- [ ] Scheduled backups + one-click restore

### Phase 8: Scale & Monetize
- [ ] API access for developers (use existing ApiKey model)
- [ ] White-label mode for Agency plan
- [ ] Affiliate/referral system
- [ ] Usage analytics dashboard
- [ ] Rate limiting per plan tier
- [ ] CDN integration for static assets
- [ ] Multi-region server deployment

---

## Key Files Reference

### Controllers
| File | Purpose |
|------|---------|
| `app/Http/Controllers/HomeController.php` | Public pages (home, pricing, features) |
| `app/Http/Controllers/AuthController.php` | Login, register, logout |
| `app/Http/Controllers/DashboardController.php` | User dashboard |
| `app/Http/Controllers/WebsiteBuilderController.php` | Builder form, generate, status, complete |
| `app/Http/Controllers/WebsiteController.php` | Website list, detail, WP admin, delete |
| `app/Http/Controllers/ChatbotController.php` | AI chatbot messages |
| `app/Http/Controllers/SettingsController.php` | Profile & password settings |

### Services
| File | Purpose |
|------|---------|
| `app/Services/AIContentService.php` | Claude API → website content JSON |
| `app/Services/WordPressService.php` | All WordPress DB operations (PDO) |
| `app/Services/WebsiteBuilderService.php` | Build orchestration + Elementor data |
| `app/Services/IdeogramService.php` | AI logo/favicon generation |
| `app/Services/UnsplashService.php` | Stock photo acquisition |
| `app/Services/AnthropicService.php` | Claude API wrapper (chatbot) |
| `app/Services/ChatActionService.php` | Execute chatbot commands |

### Templates
| File | Purpose |
|------|---------|
| `app/Services/Templates/TemplateRegistry.php` | Style → Template class mapping |
| `app/Services/Templates/AbstractTemplate.php` | Base template (hydration, dynamic sections) |
| `app/Services/Templates/AgencyTemplate.php` | Modern/minimal style |
| `app/Services/Templates/StarterTemplate.php` | Bold/vibrant style |
| `app/Services/Templates/CorporateTemplate.php` | Classic/elegant style |
| `app/Services/Templates/FlavorTemplate.php` | Warm/organic style |
| `app/Services/Templates/ZenithTemplate.php` | Tech/SaaS style |
| `app/Services/Templates/PrestigeTemplate.php` | Luxury/editorial style |
| `app/Services/Templates/VividTemplate.php` | Creative/artistic style |
| `resources/templates/elementor/` | JSON template files per style |

### Jobs
| File | Purpose |
|------|---------|
| `app/Jobs/ProvisionWebsiteJob.php` | Full website build pipeline (async) |

### Config
| File | Purpose |
|------|---------|
| `config/webnewbiz.php` | Platform config (limits, paths, defaults) |
| `config/services.php` | API keys (Anthropic, Ideogram, Unsplash, Cloudflare) |

### Views
| Directory | Purpose |
|-----------|---------|
| `resources/views/layouts/` | App and guest layouts |
| `resources/views/partials/` | Navbar, footer, flash messages |
| `resources/views/builder/` | Builder form, progress, complete |
| `resources/views/websites/` | Website list and detail |
| `resources/views/auth/` | Login and register |
| `resources/views/admin/` | Admin panel (not yet routed) |

---

## Business Type → Template Mapping

| Business Types | Template Selected |
|---------------|-------------------|
| Agency, Marketing, General | AgencyTemplate |
| Startup, E-commerce, Blog | StarterTemplate |
| Legal, Finance, Consulting | CorporateTemplate |
| Restaurant, Food, Wellness, Bakery, Cafe | FlavorTemplate |
| SaaS, Tech, IT, Digital Products | ZenithTemplate |
| Luxury, Real Estate, Law Firm | PrestigeTemplate |
| Portfolio, Photography, Design, Art | VividTemplate |

---

## Summary Stats

- **Routes:** 17 (7 public/auth + 5 builder + 4 websites + 2 chatbot + 3 settings)
- **Models:** 12
- **Migrations:** 19
- **Services:** 11
- **Template Styles:** 7
- **Dynamic Section Types:** 11
- **Chatbot Actions:** 8
- **Admin Views:** 12 (built, not routed)
- **Business Types:** 30
- **Color Palettes:** 6
