# WEBNEWBIZ PLATFORM — Complete Developer Blueprint

> "Kuch bhi banao, kahin bhi deploy karo, sab kuch track karo"

---

## PLATFORM OVERVIEW

WebNewBiz is an all-in-one AI-powered platform where users can:
1. Build websites (WordPress, custom code, Shopify)
2. Build mobile apps (iOS + Android)
3. Design in Figma with AI assistance
4. Deploy everything with one click
5. Track analytics across all properties
6. Manage customers with built-in CRM
7. Pay with credits system

**Think:** 10Web + Replit + Bolt + HighLevel + Hostinger — all in one platform.

---

## ARCHITECTURE

```
┌─────────────────────────────────────────────────────────────────┐
│                        WEBNEWBIZ PLATFORM                       │
│                                                                  │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │                     FRONTEND (React 19)                   │   │
│  │  Dashboard │ Builders │ Analytics │ CRM │ Billing │ Auth  │   │
│  └──────────────────────────┬───────────────────────────────┘   │
│                              │ REST API + WebSocket              │
│  ┌──────────────────────────▼───────────────────────────────┐   │
│  │                     BACKEND (Laravel 12)                  │   │
│  │  Controllers │ Services │ Jobs │ Queue │ Events           │   │
│  └──────┬──────────┬──────────┬──────────┬─────────────────┘   │
│         │          │          │          │                       │
│  ┌──────▼───┐ ┌────▼────┐ ┌──▼───┐ ┌───▼────┐ ┌───────────┐  │
│  │ Claude   │ │ Gemini  │ │MySQL │ │ Redis  │ │ S3/Storage│  │
│  │ API/CLI  │ │ (backup)│ │  DB  │ │ Queue  │ │ Files     │  │
│  └──────────┘ └─────────┘ └──────┘ └────────┘ └───────────┘  │
│                                                                  │
│  ┌──────────────────────────────────────────────────────────┐   │
│  │                   EXTERNAL SERVICES                       │   │
│  │  Stripe │ Figma API │ Shopify API │ DigitalOcean │ EAS   │   │
│  │  Fastlane │ Google Play API │ App Store Connect           │   │
│  └──────────────────────────────────────────────────────────┘   │
└─────────────────────────────────────────────────────────────────┘
```

---

## MODULE 1: WORDPRESS BUILDER + AI (Like 10Web.io)

### What It Does
User describes their business → AI generates a complete WordPress website with premium design, content, images — ready to go live. After creation, AI Copilot can edit any element on the site.

### Current Status: 85% Complete

### What Exists
- 9 premium Elementor layouts (noir, ivory, azure, blush, ember, forest, slate, royal, biddut)
- AI content generation (Claude API + Gemini fallback)
- Unsplash image pipeline (20 image keys per site)
- ProvisionWebsiteJob (9-step pipeline)
- WooCommerce auto-setup for ecommerce sites
- AI Copilot with 20+ tools (edit text, style, image, add sections, etc.)
- Website dashboard (10Web-style: plugins, themes, backups, SEO, analytics, security)

### What's Missing (To Match 10Web)
1. **Theme AI Customizer** — Let users change colors, fonts, layout style via AI chat
2. **Page Builder Integration** — Deeper Elementor editing from our dashboard (not just redirect)
3. **Auto-backup scheduling** — Cron-based backups (daily/weekly)
4. **Staging environment** — Clone site for testing before pushing changes live
5. **Performance optimization** — Auto image compression, lazy loading, CDN setup
6. **Multi-language** — AI translate site content to other languages

### Database Tables
- `websites` (exists)
- `chat_messages` (exists)
- `backups` (exists)
- `domains` (exists)

### Key Files
```
Backend:
  app/Jobs/ProvisionWebsiteJob.php          — 9-step site creation pipeline
  app/Services/Layouts/*.php                — 9 premium layout classes
  app/Services/AnthropicService.php         — AI API wrapper
  app/Services/UnsplashService.php          — Image sourcing
  app/Services/WpBridgeService.php          — WordPress REST API bridge
  app/Services/AiCopilot/CopilotService.php — AI editing tools

Frontend:
  pages/website/WebsiteLayout.tsx           — Dashboard shell
  pages/website/WebsiteManage.tsx           — Site overview
  pages/website/AiEditor.tsx                — AI Copilot interface
  pages/website/WooProducts.tsx             — Product management
  pages/website/WordPressPlugins.tsx         — Plugin management
```

---

## MODULE 2: CODE BUILDER (Like Replit + Bolt + Hostinger)

### What It Does
User describes what they want → AI generates a complete website/web app in any framework (HTML, React, Next.js, Vue, Angular, PHP) → User can edit code manually or via AI chat → One-click deploy with custom domain, SSL, email hosting — exactly like Hostinger but AI-powered.

### UI Layout
```
┌──────────────────────────────────────────────────────────────┐
│  ← Logo  │  Project Name  │  ▶ Run  │  🚀 Deploy  │  ···  │
├────┬──────┬──────────────────┬───────────────────────────────┤
│ 📁 │ Files│ index.html  ×    │                               │
│ 🤖 │ ├ src│ App.jsx     ×    │     LIVE PREVIEW              │
│ 🔍 │ │ ..│                  │     (iframe + hot reload)      │
│ ⚙  │ └...│ [Monaco Editor]  │                               │
│    │      │                  │     Desktop/Tablet/Mobile     │
│    │      ├──────────────────┤     toggle                    │
│    │      │ 💬 AI Chat       │                               │
│    │      │ "Add auth page"  │                               │
│    │      │ [Send]           │                               │
├────┴──────┴──────────────────┴───────────────────────────────┤
│  Terminal   Console   Problems              Ln 42, Col 18    │
└──────────────────────────────────────────────────────────────┘
```

### Features Required
1. **AI Code Generation** — Claude Code CLI generates production-quality code
2. **Monaco Editor** — VS Code-grade code editor with syntax highlighting, IntelliSense
3. **File Explorer** — Full file tree with create/rename/delete/drag-drop
4. **Live Preview** — iframe with hot reload, responsive device toggle
5. **Terminal** — In-browser terminal (xterm.js + WebSocket to server shell)
6. **Version Control** — Git integration, commit history, branching
7. **Collaboration** — Real-time multi-user editing (like Google Docs for code)
8. **Deploy Pipeline:**
   - Custom domain connection (DNS management)
   - SSL certificate (Let's Encrypt auto-provision)
   - Email hosting (per-domain email setup)
   - CDN (Cloudflare integration)
   - Environment variables management
   - Build & deploy logs
   - Rollback to previous versions
9. **Framework Support:** HTML/CSS/JS, React, Next.js, Vue, Angular, Svelte, PHP, Python

### Database Tables
```sql
projects          — id, user_id, name, slug, framework, status, file_tree, git_repo_url
project_messages  — id, project_id, role, content, files_changed
deployments       — id, user_id, deployable_type, deployable_id, type, status, domain,
                    subdomain, url, provider, ssl_status, server_ip, dns_records,
                    env_vars, build_log, deployed_at, expires_at
```

### Key Backend Services
```
CodeGeneratorService    — Orchestrates Claude CLI for code generation
ClaudeCliService        — Wraps Claude Code CLI (--print mode, background process)
ProjectService          — File CRUD, file tree building, MIME types
DeployService           — VPS provisioning, domain/SSL setup, deploy pipeline
```

### Key Frontend Components
```
pages/CodeBuilder.tsx                    — Main IDE page
components/builder/CodeEditor.tsx        — Monaco editor with tabs
components/builder/FileExplorer.tsx       — File tree
components/builder/PreviewPanel.tsx       — Live preview with device frames
components/builder/AiChatPanel.tsx        — AI conversation panel
components/builder/Terminal.tsx           — In-browser terminal (TODO)
pages/Deployments.tsx                    — Deployment management
```

### AI Integration
- Uses Claude Code CLI (`claude --print --dangerously-skip-permissions`)
- Runs in background process (non-blocking)
- Frontend polls `/stream` endpoint for real-time progress
- Session persistence via `.claude-session` file (follow-up messages resume context)
- System prompt enforces premium design standards + framework rules

---

## MODULE 3: APP BUILDER (Like Replit for Mobile + Full Publishing)

### What It Does
User describes their app → AI generates a React Native/Expo app → Live preview in browser-based iOS & Android simulators → Build .ipa/.apk → Submit to App Store & Play Store → Analytics dashboard for downloads, users, ratings.

### UI Layout
```
┌──────────────────────────────────────────────────────────────┐
│  ← Logo  │  App Name  │  🔨 Build  │  📱 Preview  │  ···   │
├───────────┬──────────────────────────────────────────────────┤
│           │                                                   │
│  AI Chat  │     ┌─────────┐          ┌─────────┐            │
│  or       │     │ ┌─────┐ │          │ ┌─────┐ │            │
│  Code     │     │ │     │ │          │ │     │ │            │
│  Editor   │     │ │ iOS │ │          │ │ AND │ │            │
│           │     │ │     │ │          │ │     │ │            │
│  File     │     │ └─────┘ │          │ └─────┘ │            │
│  Explorer │     │ iPhone  │          │ Pixel   │            │
│           │     │ 15 Pro  │          │ 8 Pro   │            │
│           │     └─────────┘          └─────────┘            │
│           │                                                   │
│           │  Device: [iPhone 15] [SE] [Pixel 8] [Samsung]    │
│           │  Scale:  [========●====] 55%                     │
├───────────┴──────────────────────────────────────────────────┤
│  Status: Ready  │  React Native + Expo  │  12 files          │
└──────────────────────────────────────────────────────────────┘
```

### Features Required
1. **AI App Generation** — Claude generates Expo/React Native code
2. **Browser Simulators:**
   - iPhone 15 Pro, iPhone SE (iOS frames with notch/home indicator)
   - Pixel 8, Samsung S24 (Android frames with nav bar)
   - Scalable (30%-80% zoom)
   - Real device dimensions and UI chrome
   - Live preview via Expo Web or Snack embed
3. **Expo Integration:**
   - `expo init` project scaffold
   - Expo Router (file-based navigation)
   - NativeWind (Tailwind for RN)
   - Auto-generate `app.json`, `package.json`
4. **Build Pipeline:**
   - EAS Build (cloud builds for iOS + Android)
   - Generate .ipa (iOS) and .apk/.aab (Android)
   - Code signing management
5. **Store Submission:**
   - App Store Connect API — auto-submit iOS builds
   - Google Play Console API — auto-submit Android builds
   - AI generates app descriptions, keywords, screenshots
   - Review status tracking
6. **App Analytics (on our platform):**
   - Downloads (daily/weekly/monthly)
   - Active users
   - Ratings & reviews
   - Crash reports
   - Revenue (if in-app purchases)
   - Device breakdown
   - Retention rates
7. **App Update System:**
   - User asks AI to add/change features
   - AI modifies code
   - New build triggered
   - OTA update (Expo Updates) or new store submission

### Database Tables
```sql
apps              — id, user_id, name, slug, framework, status, bundle_id, version,
                    file_tree, platforms, build_config, expo_project_id,
                    ios_build_url, android_build_url
app_messages      — id, app_id, role, content, files_changed
store_submissions — id, user_id, app_id, store (appstore/playstore), status,
                    app_name, description, category, screenshots, build_url,
                    store_url, review_notes, submitted_at, approved_at
```

### Key Services
```
AppBuilderService       — App creation, AI generation, file management
ExpoService             — Expo project management, EAS Build triggers
StoreSubmitService      — App Store / Play Store submission automation
AppAnalyticsService     — Pull data from App Store Connect / Play Console APIs
```

---

## MODULE 4: FIGMA DESIGN + AI

### What It Does
A Figma plugin that connects to WebNewBiz platform. User can:
- Type a prompt → AI generates a Figma design
- Upload a screenshot → AI converts to Figma layers
- Select a design → One-click export to any platform (React, WordPress, App)
- AI suggests design improvements

### Architecture
```
┌─────────────────┐         ┌──────────────────┐
│   FIGMA PLUGIN  │  HTTP   │  WEBNEWBIZ API   │
│   (TypeScript)  │ ◄─────► │  (Laravel)       │
│                 │         │                   │
│  - UI Panel     │         │  - Claude Vision  │
│  - AI Chat      │         │  - Code Generator │
│  - Export btn   │         │  - Figma REST API │
└─────────────────┘         └──────────────────┘
```

### Figma Plugin Features
1. **AI Design Generation:**
   - Text prompt → Full page design (hero, features, footer, etc.)
   - AI creates Figma frames, text nodes, rectangles, images
   - Color palette generation
   - Component variants (mobile/tablet/desktop)
2. **Image to Design:**
   - Upload screenshot/image
   - Claude Vision analyzes layout
   - Recreates as editable Figma layers
3. **Design to Code Export:**
   - Select Figma frame
   - Choose output: React, HTML, WordPress, React Native
   - Plugin calls our API → code generated → opens in Code Builder/App Builder
4. **AI Design Review:**
   - Analyze current design for accessibility, UX issues
   - Suggest improvements
   - Auto-fix spacing, alignment, color contrast

### Tech Stack
```
Plugin:    TypeScript + Figma Plugin API
Backend:   Laravel API endpoints (new controller: FigmaController)
AI:        Claude Vision API (image analysis), Claude API (code generation)
Auth:      API key per user (generated from our dashboard)
```

### Database Tables
```sql
figma_projects    — id, user_id, figma_file_id, name, last_synced_at
figma_exports     — id, user_id, figma_project_id, output_type, project_id/app_id, status
```

### Figma Plugin File Structure
```
figma-plugin/
  manifest.json           — Plugin metadata
  src/
    code.ts               — Main plugin logic (runs in Figma sandbox)
    ui.tsx                 — Plugin UI (React)
    api.ts                 — API calls to WebNewBiz backend
    ai-designer.ts         — AI design generation logic
    exporter.ts            — Design-to-code export logic
  package.json
  tsconfig.json
```

---

## MODULE 5: SHOPIFY BUILDER + AI

### What It Does
Like WordPress Builder but for Shopify. User describes their store → AI creates a complete Shopify store with theme, products, collections, pages. User manages everything from our dashboard.

### Features
1. **Store Creation:**
   - Connect existing Shopify store OR create new via Shopify Partners API
   - AI generates theme customization (Shopify Dawn theme + custom CSS)
   - AI creates product listings with descriptions, pricing, images
   - Collection/category setup
2. **Store Management (from our dashboard):**
   - Products CRUD (add/edit/delete products with AI assistance)
   - Orders management
   - Customer list
   - Discount codes
   - Theme customization
   - Shipping settings
   - Payment gateway setup
3. **AI Features:**
   - "Add 10 products for a clothing store" → AI generates all product data
   - "Write better descriptions" → AI rewrites product descriptions
   - "Create a holiday sale collection" → AI sets up collection + discounts
   - SEO optimization for all pages
4. **Analytics:**
   - Sales dashboard (revenue, orders, AOV)
   - Traffic sources
   - Product performance
   - Customer metrics
   - Same unified analytics dashboard as WordPress sites

### Integration
```
Shopify Admin API        — Store management (products, orders, customers)
Shopify Storefront API   — Theme customization, content
Shopify Partners API     — Store creation (if offering managed stores)
Shopify Webhooks         — Real-time order/product updates
```

### Database Tables
```sql
integrations      — id, user_id, platform (shopify), store_name, store_url,
                    api_key, access_token, status, settings
                    (already created)
```

### Backend Services
```
ShopifyService          — Shopify API wrapper (products, orders, themes)
ShopifyStoreBuilder     — AI-powered store creation pipeline
ShopifyAnalytics        — Pull analytics from Shopify API
```

---

## MODULE 6: CRM SYSTEM (Like HighLevel / GoHighLevel)

### What It Does
Full CRM integrated into the platform. Every website/app/store user creates automatically feeds data into their CRM. Contacts, leads, campaigns, funnels, bookings, invoices — all managed from one place.

### Features

#### 6.1 Contact Management
- Contact database with custom fields
- Contact timeline (all interactions: website visits, purchases, emails, calls)
- Tags and segments
- Import/export (CSV)
- Merge duplicates

#### 6.2 Pipeline & Deals
- Kanban board pipeline view
- Multiple pipelines (Sales, Onboarding, Support)
- Deal stages (drag-drop)
- Deal value, probability, expected close date
- Activity logging (calls, emails, meetings, notes)

#### 6.3 Email Marketing
- Email campaign builder (drag-drop email editor)
- Email templates (AI-generated)
- Drip sequences (automated email flows)
- Broadcast emails
- Email analytics (open rate, click rate, unsubscribe)
- SMTP integration (SendGrid, Mailgun, custom SMTP)

#### 6.4 SMS Marketing
- SMS campaigns
- SMS templates
- Two-way SMS (Twilio integration)
- SMS automation triggers

#### 6.5 Funnels & Landing Pages
- Funnel builder (multi-step landing pages)
- A/B testing
- Form builder with custom fields
- Pop-up builder
- Thank you pages
- Conversion tracking

#### 6.6 Automation Workflows
- Visual workflow builder (trigger → action → condition → action)
- Triggers: form submit, purchase, tag added, date, webhook
- Actions: send email, send SMS, add tag, move pipeline, create task, webhook
- Conditions: if/else branching based on contact data
- Delay steps (wait X hours/days)

#### 6.7 Calendar & Bookings
- Appointment booking system
- Calendar sync (Google Calendar, Outlook)
- Booking widget (embed on websites)
- Reminders (email + SMS)
- Availability settings
- Group bookings

#### 6.8 Invoicing & Payments
- Invoice generator
- Recurring invoices
- Payment links (Stripe integration)
- Invoice templates
- Payment tracking
- Tax calculations

#### 6.9 Reports & Analytics
- Revenue reports
- Pipeline reports
- Email campaign analytics
- Conversion funnel analytics
- Custom report builder
- Export to PDF/CSV

#### 6.10 Chat & Communication
- Live chat widget (embed on websites)
- Unified inbox (email + SMS + chat in one view)
- Chat bot (AI-powered auto-responses)
- Internal team chat
- Call tracking

### Database Tables
```sql
-- Contacts
contacts           — id, user_id, first_name, last_name, email, phone, company,
                     title, avatar, source, status, tags, custom_fields,
                     last_activity_at, lifetime_value

-- Pipeline
pipelines          — id, user_id, name, stages (JSON array)
deals              — id, user_id, pipeline_id, contact_id, title, value,
                     stage, probability, expected_close, assigned_to, status
deal_activities    — id, deal_id, type, content, created_by

-- Email Marketing
email_campaigns    — id, user_id, name, subject, body_html, status,
                     segment_id, scheduled_at, sent_at, stats (JSON)
email_sequences    — id, user_id, name, trigger, status
email_sequence_steps — id, sequence_id, step_order, delay_hours,
                       subject, body_html, type (email/sms/wait)
email_sends        — id, campaign_id, contact_id, status, opened_at, clicked_at

-- Automation
workflows          — id, user_id, name, trigger_type, trigger_config,
                     status (active/inactive)
workflow_steps     — id, workflow_id, step_order, type, config, next_step_id
workflow_logs      — id, workflow_id, contact_id, step_id, status, executed_at

-- Calendar
calendars          — id, user_id, name, timezone, availability (JSON),
                     booking_duration, buffer_minutes
bookings           — id, calendar_id, contact_id, start_time, end_time,
                     status, notes, meeting_link

-- Invoicing
invoices_crm       — id, user_id, contact_id, number, status, due_date,
                     items (JSON), subtotal, tax, total, paid_at, stripe_invoice_id

-- Chat
conversations      — id, user_id, contact_id, channel (chat/email/sms),
                     status, last_message_at
messages_crm       — id, conversation_id, sender_type, sender_id,
                     content, type (text/image/file), read_at
```

### Backend Structure
```
app/Http/Controllers/Api/Crm/
  ContactController.php
  PipelineController.php
  DealController.php
  CampaignController.php
  SequenceController.php
  WorkflowController.php
  CalendarController.php
  BookingController.php
  InvoiceController.php
  ConversationController.php

app/Services/Crm/
  ContactService.php
  PipelineService.php
  EmailService.php          — SendGrid/Mailgun integration
  SmsService.php            — Twilio integration
  WorkflowEngine.php        — Automation execution engine
  CalendarService.php       — Google Calendar sync
  InvoiceService.php        — Stripe invoicing
  ChatService.php           — WebSocket chat
```

### Frontend Structure
```
pages/crm/
  CrmDashboard.tsx          — Overview with key metrics
  Contacts.tsx              — Contact list + detail view
  Pipeline.tsx              — Kanban board
  Campaigns.tsx             — Email campaign management
  Sequences.tsx             — Drip sequence builder
  Workflows.tsx             — Visual workflow builder
  Calendar.tsx              — Booking calendar
  Invoices.tsx              — Invoice management
  Conversations.tsx         — Unified inbox
  Reports.tsx               — Analytics & reports
```

---

## MODULE 7: UNIFIED ANALYTICS

### What It Does
Single dashboard showing analytics across ALL user's properties — websites, apps, Shopify stores. No need to visit Google Analytics, Play Console, or App Store Connect separately.

### Data Sources
```
WordPress sites      → Google Analytics API + custom tracking pixel
Custom code sites    → Custom tracking pixel (JS snippet injected)
Mobile apps          → App Store Connect API + Google Play Console API
Shopify stores       → Shopify Analytics API
CRM                  → Internal database queries
```

### Metrics
```
WEBSITES:                    APPS:                      STORES:
─────────                    ─────                      ──────
Visitors                     Downloads                  Revenue
Page views                   Active users               Orders
Bounce rate                  Ratings                    AOV
Session duration             Crash rate                 Conversion rate
Top pages                    Retention                  Top products
Traffic sources              Device breakdown           Cart abandonment
Geo breakdown                App version adoption       Customer LTV
PageSpeed score              Session length             Repeat purchase rate
SEO rankings                 In-app purchases
```

### Database Tables
```sql
analytics_events   — id, user_id, trackable_type, trackable_id, event,
                     source, country, device, browser, metadata, occurred_at
                     (already created)
```

---

## MODULE 8: CREDITS & BILLING

### What It Does
Credits-based monetization. Users buy credits or subscribe to plans. Every AI action costs credits.

### Current Status: Complete

### Credit Costs
| Action | Credits |
|--------|---------|
| Website generate (AI) | 50 |
| App generate (AI) | 100 |
| AI edit/update | 10 |
| AI chat message | 5 |
| Deploy website | 20 |
| Deploy app | 30 |
| App Store submit | 50 |
| Play Store submit | 50 |
| Image to code | 15 |
| Figma export | 20 |
| Platform convert | 25 |
| Monthly hosting | 30/mo |
| Advanced analytics | 20/mo |

### Subscription Tiers
| Plan | Price/mo | Credits/mo | Limits |
|------|----------|------------|--------|
| Free | $0 | 50 (one-time) | 1 website, 1 app |
| Starter | $19 | 200 | 5 websites, 3 apps |
| Pro | $39 | 500 | Unlimited websites, 10 apps |
| Business | $99 | 1,500 | Everything + white-label |
| Enterprise | Custom | Unlimited | Custom + SLA |

### Payment Integration
- Stripe Checkout for credit purchases
- Stripe Subscriptions for recurring plans
- Stripe Webhooks for payment confirmation
- Invoice generation

---

## MODULE 9: DEPLOY & HOSTING

### Website Hosting
1. Auto-provision VPS (DigitalOcean Droplet via API)
2. Configure Nginx + SSL (Let's Encrypt via Certbot)
3. Upload project files (rsync or SSH)
4. DNS management (Cloudflare API)
5. Custom domain connection
6. Email hosting (per-domain email — integrate with Zoho Mail or custom Postfix)
7. CDN setup (Cloudflare)
8. Automatic backups
9. One-click rollback

### App Publishing
1. EAS Build (Expo) — cloud builds for iOS + Android
2. Code signing management (certificates, provisioning profiles)
3. App Store Connect API — automated iOS submission
4. Google Play Console API — automated Android submission
5. AI-generated store listing (description, keywords, screenshots)
6. OTA updates (Expo Updates — push code changes without store review)

---

## TECH STACK

| Layer | Technology |
|-------|-----------|
| Frontend | React 19, TypeScript, Vite 7, Tailwind CSS 4 |
| Backend | Laravel 12 (PHP 8.3) |
| Database | MySQL 8 |
| Cache/Queue | Redis |
| AI Primary | Claude API (Anthropic) + Claude Code CLI |
| AI Fallback | Google Gemini API |
| AI Vision | Claude Vision API |
| Code Editor | Monaco Editor (@monaco-editor/react) |
| Real-time | WebSocket (Laravel Reverb or Socket.io) |
| File Storage | S3 (AWS) or DigitalOcean Spaces |
| Hosting | DigitalOcean API (auto VPS provisioning) |
| CDN/DNS | Cloudflare API |
| SSL | Let's Encrypt (Certbot) |
| Payments | Stripe (Checkout, Subscriptions, Webhooks) |
| Email | SendGrid or Mailgun (transactional + marketing) |
| SMS | Twilio |
| App Builds | Expo EAS Build |
| iOS Submit | Fastlane + App Store Connect API |
| Android Submit | Gradle + Google Play Console API |
| Figma | Figma Plugin API + Figma REST API |
| Shopify | Shopify Admin API + Storefront API |
| Analytics | Custom tracking + GA4 API + ASC API + Play Console API |
| Terminal | xterm.js + WebSocket to server shell |
| Container | Docker (sandboxed code execution per user) |

---

## DATABASE SCHEMA SUMMARY

### Existing Tables
```
users, websites, chat_messages, backups, domains,
personal_access_tokens, sessions, cache, jobs,
copilot_sessions, copilot_actions,
projects, project_messages,
credit_transactions, plans, credit_packages, invoices,
apps, app_messages, deployments, conversions,
store_submissions, analytics_events, integrations
```

### New Tables Needed (CRM)
```
contacts, pipelines, deals, deal_activities,
email_campaigns, email_sequences, email_sequence_steps, email_sends,
workflows, workflow_steps, workflow_logs,
calendars, bookings, invoices_crm,
conversations, messages_crm
```

### New Tables Needed (Figma)
```
figma_projects, figma_exports
```

---

## API ROUTE MAP

### Auth
```
POST   /api/auth/register
POST   /api/auth/login
POST   /api/auth/logout
GET    /api/auth/me
```

### WordPress Builder
```
GET    /api/websites
POST   /api/websites/generate
GET    /api/websites/:id
GET    /api/websites/:id/status
DELETE /api/websites/:id
GET    /api/websites/:id/overview
GET    /api/websites/:id/plugins
GET    /api/websites/:id/themes
...    (40+ website management endpoints)
```

### Code Builder
```
GET    /api/projects
POST   /api/projects
GET    /api/projects/:id
DELETE /api/projects/:id
GET    /api/projects/:id/files?path=...
PUT    /api/projects/:id/files
DELETE /api/projects/:id/files
POST   /api/projects/:id/chat
GET    /api/projects/:id/stream
GET    /api/projects/:id/messages
GET    /api/projects/:id/preview/{path}
```

### App Builder
```
GET    /api/apps
POST   /api/apps
GET    /api/apps/:id
DELETE /api/apps/:id
GET    /api/apps/:id/files
PUT    /api/apps/:id/files
POST   /api/apps/:id/chat
GET    /api/apps/:id/stream
GET    /api/apps/:id/messages
POST   /api/apps/:id/build
GET    /api/apps/:id/builds
```

### Deploy & Hosting
```
GET    /api/deployments
POST   /api/deployments
GET    /api/deployments/:id
POST   /api/deployments/:id/domain
POST   /api/deployments/:id/stop
POST   /api/deployments/:id/redeploy
GET    /api/deployments/:id/logs
```

### Store Submissions
```
GET    /api/submissions
POST   /api/submissions
GET    /api/submissions/:id
```

### Billing
```
GET    /api/billing/overview
GET    /api/billing/plans
GET    /api/billing/packages
GET    /api/billing/transactions
GET    /api/billing/invoices
POST   /api/billing/purchase-credits
POST   /api/billing/subscribe
POST   /api/billing/webhook (Stripe)
```

### Analytics
```
GET    /api/analytics/overview
POST   /api/track (public — no auth)
```

### Integrations
```
GET    /api/integrations
POST   /api/integrations
POST   /api/integrations/:id/disconnect
DELETE /api/integrations/:id
```

### Figma
```
POST   /api/figma/generate-design
POST   /api/figma/image-to-design
POST   /api/figma/export-to-code
GET    /api/figma/projects
```

### Converter
```
GET    /api/conversions
POST   /api/conversions
GET    /api/conversions/:id
```

### CRM
```
# Contacts
GET    /api/crm/contacts
POST   /api/crm/contacts
GET    /api/crm/contacts/:id
PUT    /api/crm/contacts/:id
DELETE /api/crm/contacts/:id

# Pipeline & Deals
GET    /api/crm/pipelines
POST   /api/crm/pipelines
GET    /api/crm/deals
POST   /api/crm/deals
PUT    /api/crm/deals/:id
PUT    /api/crm/deals/:id/stage

# Email Campaigns
GET    /api/crm/campaigns
POST   /api/crm/campaigns
POST   /api/crm/campaigns/:id/send
GET    /api/crm/campaigns/:id/stats

# Sequences
GET    /api/crm/sequences
POST   /api/crm/sequences
PUT    /api/crm/sequences/:id

# Workflows
GET    /api/crm/workflows
POST   /api/crm/workflows
PUT    /api/crm/workflows/:id
POST   /api/crm/workflows/:id/activate

# Calendar & Bookings
GET    /api/crm/calendars
POST   /api/crm/calendars
GET    /api/crm/bookings
POST   /api/crm/bookings

# Conversations
GET    /api/crm/conversations
GET    /api/crm/conversations/:id/messages
POST   /api/crm/conversations/:id/messages
```

---

## FRONTEND ROUTE MAP

```
/                           — Landing page
/login                      — Login
/register                   — Register
/dashboard                  — Main dashboard (all projects, apps, sites)

/code-builder               — Create new code project
/code-builder/:id           — Code IDE

/app-builder                — Create new app
/app-builder/:id            — App IDE with simulators

/builder                    — WordPress site wizard
/builder/progress/:id       — WordPress build progress

/websites/:id/manage        — WordPress site dashboard
/websites/:id/ai-editor     — AI Copilot
/websites/:id/...           — (40+ sub-routes for WP management)

/billing                    — Credits, plans, packages, invoices
/deployments                — All deployments
/analytics                  — Unified analytics
/integrations               — Shopify, Squarespace connections

/crm                        — CRM dashboard
/crm/contacts               — Contact management
/crm/pipeline               — Pipeline Kanban
/crm/campaigns              — Email campaigns
/crm/sequences              — Drip sequences
/crm/workflows              — Automation builder
/crm/calendar               — Bookings
/crm/invoices               — Invoicing
/crm/conversations          — Unified inbox
/crm/reports                — Reports
```

---

## DEVELOPMENT PRIORITY ORDER

### Sprint 1 (Week 1-2): Fix & Polish Existing
- [ ] Fix Code Builder Claude CLI streaming (background process + polling)
- [ ] Fix Code Builder live preview
- [ ] Polish WordPress Builder (match 10Web quality)
- [ ] Test billing system end-to-end

### Sprint 2 (Week 3-4): Code Builder Full Deploy
- [ ] Terminal integration (xterm.js)
- [ ] Git integration
- [ ] Deploy pipeline (DigitalOcean API)
- [ ] Domain + SSL management
- [ ] Email hosting setup

### Sprint 3 (Week 5-6): App Builder
- [ ] Expo project scaffolding
- [ ] Browser simulators with real Expo Snack/Web preview
- [ ] EAS Build integration
- [ ] App Store / Play Store submission pipeline

### Sprint 4 (Week 7-8): Figma Plugin
- [ ] Figma plugin development
- [ ] AI design generation
- [ ] Export to code flow

### Sprint 5 (Week 9-10): Shopify + Analytics
- [ ] Shopify API integration
- [ ] AI store creation
- [ ] Unified analytics dashboard
- [ ] Tracking pixel system

### Sprint 6 (Week 11-14): CRM
- [ ] Contact management
- [ ] Pipeline & deals
- [ ] Email campaigns (SendGrid)
- [ ] Automation workflows
- [ ] Calendar & bookings
- [ ] Invoicing

### Sprint 7 (Week 15-16): Scale
- [ ] Docker sandboxing for code execution
- [ ] WebSocket real-time updates
- [ ] Team collaboration features
- [ ] White-label option
- [ ] Template marketplace

---

## ENVIRONMENT SETUP

### Requirements
```
PHP 8.3+, Composer 2+
Node.js 20+, npm 10+
MySQL 8+
Redis
Claude Code CLI (npm install -g @anthropic-ai/claude-code)
Git
Docker (for sandboxed execution)
```

### Development Commands
```bash
# Backend
cd backend
composer install
cp .env.example .env
php artisan migrate
php artisan db:seed --class=BillingSeeder
php artisan serve

# Frontend
cd frontend-react
npm install
npm run dev

# Access
Frontend: http://localhost:4200
Backend:  http://localhost:8000
```

### Required API Keys (.env)
```
ANTHROPIC_API_KEY=       # Claude API
GEMINI_API_KEY=          # Google Gemini (fallback)
STRIPE_KEY=              # Stripe publishable key
STRIPE_SECRET=           # Stripe secret key
STRIPE_WEBHOOK_SECRET=   # Stripe webhook signing
DO_API_TOKEN=            # DigitalOcean API
CLOUDFLARE_API_TOKEN=    # Cloudflare DNS
SENDGRID_API_KEY=        # Email sending
TWILIO_SID=              # SMS
TWILIO_TOKEN=            # SMS
SHOPIFY_API_KEY=         # Shopify integration
FIGMA_ACCESS_TOKEN=      # Figma API
UNSPLASH_ACCESS_KEY=     # Stock images
```

---

*Blueprint Version: 1.0 — Generated 2026-03-27*
*Platform: WebNewBiz — All-in-One AI Platform*