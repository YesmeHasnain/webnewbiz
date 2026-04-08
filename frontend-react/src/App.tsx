import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom';
import { AuthProvider } from './contexts/AuthContext';
import AuthGuard from './guards/AuthGuard';
import GuestGuard from './guards/GuestGuard';

import Home from './pages/Home';
import Login from './pages/Login';
import Register from './pages/Register';
import Dashboard from './pages/Dashboard';
import BuilderWizard from './pages/BuilderWizard';
import WebsiteGenerator from './pages/WebsiteGenerator';
import StructureEditor from './pages/StructureEditor';
import BuildProgress from './pages/BuildProgress';
import WebsiteLayout from './pages/website/WebsiteLayout';
import WebsiteManage from './pages/website/WebsiteManage';
// ComingSoon no longer used — all pages implemented
import WooProducts from './pages/website/WooProducts';
import WooOrders from './pages/website/WooOrders';
import AIBuilder from './pages/website/AIBuilder';
import WordPressPlugins from './pages/website/WordPressPlugins';
import WordPressThemes from './pages/website/WordPressThemes';
import AnalyticsPage from './pages/website/AnalyticsPage';
import BoosterMain from './pages/website/BoosterMain';
import ImageOptimizerPage from './pages/website/ImageOptimizerPage';
import BackupsPage from './pages/website/BackupsPage';
import SecurityPage from './pages/website/SecurityPage';
import SeoPage from './pages/website/SeoPage';
import LogoAssets from './pages/website/LogoAssets';
import AiEditor from './pages/website/AiEditor';
import DomainsPage from './pages/website/DomainsPage';
import EcomCustomers from './pages/website/EcomCustomers';
import EcomEmails from './pages/website/EcomEmails';
import EcomSettings from './pages/website/EcomSettings';
import BrandingOverview from './pages/website/BrandingOverview';
import { BusinessCard, SocialMedia, SocialPublishing, AdCampaigns, LinkInBio, EmailSignature, BrandedInvoices } from './pages/website/BrandingTool';
import SeoAudit from './pages/website/SeoAudit';
import SeoSuggestions from './pages/website/SeoSuggestions';
import SeoPages from './pages/website/SeoPages';
import SeoHistory from './pages/website/SeoHistory';
import BoosterPages from './pages/website/BoosterPages';
import BoosterCloudflare from './pages/website/BoosterCloudflare';
import BoosterSettings from './pages/website/BoosterSettings';
import { CrmDashboardSub, CrmCampaignsSub, CrmLeads, CrmSubscribers, CrmBookingsSub, CrmAbandonedCarts, CrmChatbot } from './pages/website/WebsiteCrm';
import { lazy, Suspense } from 'react';
const PromptPage = lazy(() => import('./pages/PromptPage'));
const CodeBuilder = lazy(() => import('./pages/CodeBuilder'));
const Billing = lazy(() => import('./pages/Billing'));
const AppBuilder = lazy(() => import('./pages/AppBuilder'));
const Deployments = lazy(() => import('./pages/Deployments'));
const PlatformAnalytics = lazy(() => import('./pages/PlatformAnalytics'));
const Integrations = lazy(() => import('./pages/Integrations'));
const CrmDashboard = lazy(() => import('./pages/crm/CrmDashboard'));
const Contacts = lazy(() => import('./pages/crm/Contacts'));
const CrmPipeline = lazy(() => import('./pages/crm/Pipeline'));
const Campaigns = lazy(() => import('./pages/crm/Campaigns'));
const Sequences = lazy(() => import('./pages/crm/Sequences'));
const CrmWorkflows = lazy(() => import('./pages/crm/Workflows'));
const CalendarPage = lazy(() => import('./pages/crm/CalendarPage'));
const CrmInvoices = lazy(() => import('./pages/crm/Invoices'));
const CrmConversations = lazy(() => import('./pages/crm/Conversations'));
const CrmReports = lazy(() => import('./pages/crm/Reports'));

export default function App() {
  return (
    <BrowserRouter>
      <AuthProvider>
        <Routes>
          {/* Public */}
          <Route path="/" element={<Home />} />

          {/* Guest only */}
          <Route path="/login" element={<GuestGuard><Login /></GuestGuard>} />
          <Route path="/register" element={<GuestGuard><Register /></GuestGuard>} />

          {/* Auth required */}
          <Route path="/dashboard" element={<AuthGuard><Dashboard /></AuthGuard>} />
          <Route path="/builder" element={<AuthGuard><WebsiteGenerator /></AuthGuard>} />
          <Route path="/builder/customize" element={<AuthGuard><StructureEditor /></AuthGuard>} />
          <Route path="/builder-old" element={<AuthGuard><BuilderWizard /></AuthGuard>} />
          <Route path="/builder/progress/:id" element={<AuthGuard><BuildProgress /></AuthGuard>} />

          {/* Code Builder (Lovable-style) */}
          <Route path="/code-builder" element={<AuthGuard><Suspense fallback={null}><PromptPage /></Suspense></AuthGuard>} />
          <Route path="/code-builder/:id" element={<AuthGuard><Suspense fallback={null}><CodeBuilder /></Suspense></AuthGuard>} />

          {/* App Builder */}
          <Route path="/app-builder" element={<AuthGuard><Suspense fallback={null}><AppBuilder /></Suspense></AuthGuard>} />
          <Route path="/app-builder/:id" element={<AuthGuard><Suspense fallback={null}><AppBuilder /></Suspense></AuthGuard>} />

          {/* Deployments */}
          <Route path="/deployments" element={<AuthGuard><Suspense fallback={null}><Deployments /></Suspense></AuthGuard>} />

          {/* Analytics */}
          <Route path="/analytics" element={<AuthGuard><Suspense fallback={null}><PlatformAnalytics /></Suspense></AuthGuard>} />

          {/* Integrations */}
          <Route path="/integrations" element={<AuthGuard><Suspense fallback={null}><Integrations /></Suspense></AuthGuard>} />

          {/* Billing & Credits */}
          <Route path="/billing" element={<AuthGuard><Suspense fallback={null}><Billing /></Suspense></AuthGuard>} />

          {/* CRM Standalone Routes */}
          <Route path="/crm" element={<AuthGuard><Suspense fallback={null}><CrmDashboard /></Suspense></AuthGuard>} />
          <Route path="/crm/contacts" element={<AuthGuard><Suspense fallback={null}><Contacts /></Suspense></AuthGuard>} />
          <Route path="/crm/pipeline" element={<AuthGuard><Suspense fallback={null}><CrmPipeline /></Suspense></AuthGuard>} />
          <Route path="/crm/campaigns" element={<AuthGuard><Suspense fallback={null}><Campaigns /></Suspense></AuthGuard>} />
          <Route path="/crm/sequences" element={<AuthGuard><Suspense fallback={null}><Sequences /></Suspense></AuthGuard>} />
          <Route path="/crm/workflows" element={<AuthGuard><Suspense fallback={null}><CrmWorkflows /></Suspense></AuthGuard>} />
          <Route path="/crm/calendar" element={<AuthGuard><Suspense fallback={null}><CalendarPage /></Suspense></AuthGuard>} />
          <Route path="/crm/invoices" element={<AuthGuard><Suspense fallback={null}><CrmInvoices /></Suspense></AuthGuard>} />
          <Route path="/crm/conversations" element={<AuthGuard><Suspense fallback={null}><CrmConversations /></Suspense></AuthGuard>} />
          <Route path="/crm/reports" element={<AuthGuard><Suspense fallback={null}><CrmReports /></Suspense></AuthGuard>} />

          {/* Website management */}
          <Route path="/websites/:id" element={<AuthGuard><WebsiteLayout /></AuthGuard>}>
            <Route index element={<Navigate to="manage" replace />} />
            <Route path="manage" element={<WebsiteManage />} />
            <Route path="ai-builder" element={<AIBuilder />} />
            <Route path="ai-editor" element={<AiEditor />} />

            {/* Ecommerce */}
            <Route path="ecommerce/orders" element={<WooOrders />} />
            <Route path="ecommerce/products" element={<WooProducts />} />
            <Route path="ecommerce/customers" element={<EcomCustomers />} />
            <Route path="ecommerce/emails" element={<EcomEmails />} />
            <Route path="ecommerce/settings" element={<EcomSettings />} />

            {/* Domains & Backups */}
            <Route path="domains" element={<DomainsPage />} />
            <Route path="backups" element={<BackupsPage />} />

            {/* CRM */}
            <Route path="crm/dashboard" element={<CrmDashboardSub />} />
            <Route path="crm/campaigns" element={<CrmCampaignsSub />} />
            <Route path="crm/leads" element={<CrmLeads />} />
            <Route path="crm/subscribers" element={<CrmSubscribers />} />
            <Route path="crm/bookings" element={<CrmBookingsSub />} />
            <Route path="crm/abandoned-carts" element={<CrmAbandonedCarts />} />
            <Route path="crm/chatbot" element={<CrmChatbot />} />

            {/* Security */}
            <Route path="security" element={<SecurityPage />} />

            {/* Analytics */}
            <Route path="analytics" element={<AnalyticsPage />} />

            {/* Branding */}
            <Route path="branding/overview" element={<BrandingOverview />} />
            <Route path="branding/logo-assets" element={<LogoAssets />} />
            <Route path="branding/business-card" element={<BusinessCard />} />
            <Route path="branding/social-media" element={<SocialMedia />} />
            <Route path="branding/social-publishing" element={<SocialPublishing />} />
            <Route path="branding/ad-campaigns" element={<AdCampaigns />} />
            <Route path="branding/link-in-bio" element={<LinkInBio />} />
            <Route path="branding/email-signature" element={<EmailSignature />} />
            <Route path="branding/invoices" element={<BrandedInvoices />} />

            {/* Plugins & Themes */}
            <Route path="plugins" element={<WordPressPlugins />} />
            <Route path="themes" element={<WordPressThemes />} />

            {/* Website Booster */}
            <Route path="booster/main" element={<BoosterMain />} />
            <Route path="booster/pages" element={<BoosterPages />} />
            <Route path="booster/image-optimizer" element={<ImageOptimizerPage />} />
            <Route path="booster/cloudflare" element={<BoosterCloudflare />} />
            <Route path="booster/settings" element={<BoosterSettings />} />

            {/* SEO Tools */}
            <Route path="seo/dashboard" element={<SeoPage />} />
            <Route path="seo/audit" element={<SeoAudit />} />
            <Route path="seo/suggestions" element={<SeoSuggestions />} />
            <Route path="seo/pages" element={<SeoPages />} />
            <Route path="seo/history" element={<SeoHistory />} />
          </Route>

          {/* Catch-all */}
          <Route path="*" element={<Navigate to="/" replace />} />
        </Routes>
      </AuthProvider>
    </BrowserRouter>
  );
}
