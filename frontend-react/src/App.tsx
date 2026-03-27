import { BrowserRouter, Routes, Route, Navigate } from 'react-router-dom';
import { AuthProvider } from './contexts/AuthContext';
import AuthGuard from './guards/AuthGuard';
import GuestGuard from './guards/GuestGuard';

import Home from './pages/Home';
import Login from './pages/Login';
import Register from './pages/Register';
import Dashboard from './pages/Dashboard';
import BuilderWizard from './pages/BuilderWizard';
import BuildProgress from './pages/BuildProgress';
import WebsiteLayout from './pages/website/WebsiteLayout';
import WebsiteManage from './pages/website/WebsiteManage';
import ComingSoon from './pages/website/ComingSoon';
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
import { lazy, Suspense } from 'react';
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
          <Route path="/builder" element={<AuthGuard><BuilderWizard /></AuthGuard>} />
          <Route path="/builder/progress/:id" element={<AuthGuard><BuildProgress /></AuthGuard>} />

          {/* Code Builder (Lovable-style) */}
          <Route path="/code-builder" element={<AuthGuard><Suspense fallback={null}><CodeBuilder /></Suspense></AuthGuard>} />
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

          {/* Website management */}
          <Route path="/websites/:id" element={<AuthGuard><WebsiteLayout /></AuthGuard>}>
            <Route index element={<Navigate to="manage" replace />} />
            <Route path="manage" element={<WebsiteManage />} />
            <Route path="ai-builder" element={<AIBuilder />} />
            <Route path="ai-editor" element={<AiEditor />} />

            {/* Ecommerce */}
            <Route path="ecommerce/orders" element={<WooOrders />} />
            <Route path="ecommerce/products" element={<WooProducts />} />
            <Route path="ecommerce/customers" element={<ComingSoon />} />
            <Route path="ecommerce/emails" element={<ComingSoon />} />
            <Route path="ecommerce/settings" element={<ComingSoon />} />

            {/* Domains & Backups */}
            <Route path="domains" element={<ComingSoon />} />
            <Route path="backups" element={<BackupsPage />} />

            {/* CRM */}
            <Route path="crm/dashboard" element={<ComingSoon />} />
            <Route path="crm/campaigns" element={<ComingSoon />} />
            <Route path="crm/leads" element={<ComingSoon />} />
            <Route path="crm/subscribers" element={<ComingSoon />} />
            <Route path="crm/bookings" element={<ComingSoon />} />
            <Route path="crm/abandoned-carts" element={<ComingSoon />} />
            <Route path="crm/chatbot" element={<ComingSoon />} />

            {/* Security */}
            <Route path="security" element={<SecurityPage />} />

            {/* Analytics */}
            <Route path="analytics" element={<AnalyticsPage />} />

            {/* Branding */}
            <Route path="branding/overview" element={<ComingSoon />} />
            <Route path="branding/logo-assets" element={<LogoAssets />} />
            <Route path="branding/business-card" element={<ComingSoon />} />
            <Route path="branding/social-media" element={<ComingSoon />} />
            <Route path="branding/social-publishing" element={<ComingSoon />} />
            <Route path="branding/ad-campaigns" element={<ComingSoon />} />
            <Route path="branding/link-in-bio" element={<ComingSoon />} />
            <Route path="branding/email-signature" element={<ComingSoon />} />
            <Route path="branding/invoices" element={<ComingSoon />} />

            {/* Plugins & Themes */}
            <Route path="plugins" element={<WordPressPlugins />} />
            <Route path="themes" element={<WordPressThemes />} />

            {/* Website Booster */}
            <Route path="booster/main" element={<BoosterMain />} />
            <Route path="booster/pages" element={<ComingSoon />} />
            <Route path="booster/image-optimizer" element={<ImageOptimizerPage />} />
            <Route path="booster/cloudflare" element={<ComingSoon />} />
            <Route path="booster/settings" element={<ComingSoon />} />

            {/* SEO Tools */}
            <Route path="seo/dashboard" element={<SeoPage />} />
            <Route path="seo/audit" element={<ComingSoon />} />
            <Route path="seo/suggestions" element={<ComingSoon />} />
            <Route path="seo/pages" element={<ComingSoon />} />
            <Route path="seo/history" element={<ComingSoon />} />
          </Route>

          {/* Catch-all */}
          <Route path="*" element={<Navigate to="/" replace />} />
        </Routes>
      </AuthProvider>
    </BrowserRouter>
  );
}
