<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\WebsiteController;
use App\Http\Controllers\Api\BuilderController;
use App\Http\Controllers\Api\WpPluginController;
use App\Http\Controllers\Api\WpThemeController;
use App\Http\Controllers\Api\WpOverviewController;
use App\Http\Controllers\Api\BackupController;
use App\Http\Controllers\Api\DomainController;
use App\Http\Controllers\Api\WooCommerceController;
use App\Http\Controllers\Api\BrandingController;
use App\Http\Controllers\Api\WpBuilderController;
use App\Http\Controllers\Api\AiChatController;
use App\Http\Controllers\Api\AiCopilotController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\BillingController;
use App\Http\Controllers\Api\AppController;
use App\Http\Controllers\Api\DeployController;
use App\Http\Controllers\Api\ConverterController;
use App\Http\Controllers\Api\StoreSubmissionController;
use App\Http\Controllers\Api\AnalyticsController;
use App\Http\Controllers\Api\IntegrationController;
use App\Http\Controllers\Api\FigmaController;
use App\Http\Controllers\Api\StripeWebhookController;
use App\Http\Controllers\Api\Crm\ContactController;
use App\Http\Controllers\Api\Crm\PipelineController;
use App\Http\Controllers\Api\Crm\CampaignController;
use App\Http\Controllers\Api\Crm\SequenceController;
use App\Http\Controllers\Api\Crm\WorkflowController;
use App\Http\Controllers\Api\Crm\CalendarController;
use App\Http\Controllers\Api\Crm\InvoiceController as CrmInvoiceController;
use App\Http\Controllers\Api\Crm\ConversationController;
use Illuminate\Support\Facades\Route;

// Public auth routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login']);

// Project preview (no auth — served in iframe)
Route::get('/projects/{id}/preview/{path?}', [ProjectController::class, 'preview'])
    ->where('path', '.*');

// Public analytics tracking (no auth — called from deployed sites)
Route::post('/track', [AnalyticsController::class, 'track']);

// Stripe webhook (no auth — verified by signature)
Route::post('/billing/webhook', [StripeWebhookController::class, 'handle']);

// Public builder routes (no auth needed)
Route::post('/builder/analyze', [BuilderController::class, 'analyze']);
Route::post('/builder/analyze-questions', [BuilderController::class, 'analyzeWithQuestions']);
Route::post('/builder/summarize', [BuilderController::class, 'summarize']);
Route::get('/builder/layouts', [BuilderController::class, 'layouts']);
Route::post('/builder/enhance-prompt', [BuilderController::class, 'enhancePrompt']);
Route::post('/builder/site-plan', [BuilderController::class, 'sitePlan']);
Route::post('/builder/generate-page-sections', [BuilderController::class, 'generatePageSections']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/auth/me', [AuthController::class, 'me']);

    // Code Builder Projects
    Route::get('/projects', [ProjectController::class, 'index']);
    Route::post('/projects', [ProjectController::class, 'store']);
    Route::get('/projects/{id}', [ProjectController::class, 'show']);
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy']);
    Route::get('/projects/{id}/files', [ProjectController::class, 'readFile']);
    Route::put('/projects/{id}/files', [ProjectController::class, 'writeFile']);
    Route::delete('/projects/{id}/files', [ProjectController::class, 'deleteFile']);
    Route::post('/projects/{id}/chat', [ProjectController::class, 'chat']);
    Route::get('/projects/{id}/stream', [ProjectController::class, 'stream']);
    Route::get('/projects/{id}/messages', [ProjectController::class, 'messages']);
    Route::post('/projects/{id}/files/create', [ProjectController::class, 'createFile']);
    Route::post('/projects/{id}/files/rename', [ProjectController::class, 'renameFile']);
    Route::post('/projects/{id}/terminal', [ProjectController::class, 'terminal']);
    Route::post('/projects/{id}/git', [ProjectController::class, 'git']);
    Route::get('/projects/{id}/search', [ProjectController::class, 'search']);

    // Deploy & Hosting
    Route::get('/deployments', [DeployController::class, 'index']);
    Route::post('/deployments', [DeployController::class, 'deploy']);
    Route::get('/deployments/{id}', [DeployController::class, 'show']);
    Route::post('/deployments/{id}/domain', [DeployController::class, 'addDomain']);
    Route::post('/deployments/{id}/stop', [DeployController::class, 'stop']);
    Route::post('/deployments/{id}/redeploy', [DeployController::class, 'redeploy']);
    Route::get('/deployments/{id}/logs', [DeployController::class, 'logs']);
    Route::post('/deployments/{id}/email', [DeployController::class, 'setupEmail']);

    // Universal Converter
    Route::get('/conversions', [ConverterController::class, 'index']);
    Route::post('/conversions', [ConverterController::class, 'convert']);
    Route::get('/conversions/{id}', [ConverterController::class, 'show']);

    // App Store Submissions
    Route::get('/submissions', [StoreSubmissionController::class, 'index']);
    Route::post('/submissions', [StoreSubmissionController::class, 'submit']);
    Route::get('/submissions/{id}', [StoreSubmissionController::class, 'show']);

    // Unified Analytics
    Route::get('/analytics/overview', [AnalyticsController::class, 'overview']);

    // Integrations (Shopify, Squarespace)
    Route::get('/integrations', [IntegrationController::class, 'index']);
    Route::post('/integrations', [IntegrationController::class, 'connect']);
    Route::post('/integrations/{id}/disconnect', [IntegrationController::class, 'disconnect']);
    Route::delete('/integrations/{id}', [IntegrationController::class, 'destroy']);

    // Billing & Credits
    Route::get('/billing/overview', [BillingController::class, 'overview']);
    Route::get('/billing/plans', [BillingController::class, 'plans']);
    Route::get('/billing/packages', [BillingController::class, 'packages']);
    Route::get('/billing/transactions', [BillingController::class, 'transactions']);
    Route::get('/billing/invoices', [BillingController::class, 'invoices']);
    Route::post('/billing/purchase-credits', [BillingController::class, 'purchaseCredits']);
    Route::post('/billing/subscribe', [BillingController::class, 'subscribe']);

    // App Builder
    Route::get('/apps', [AppController::class, 'index']);
    Route::post('/apps', [AppController::class, 'store']);
    Route::get('/apps/{id}', [AppController::class, 'show']);
    Route::delete('/apps/{id}', [AppController::class, 'destroy']);
    Route::get('/apps/{id}/files', [AppController::class, 'readFile']);
    Route::put('/apps/{id}/files', [AppController::class, 'writeFile']);
    Route::post('/apps/{id}/chat', [AppController::class, 'chat']);
    Route::get('/apps/{id}/stream', [AppController::class, 'stream']);
    Route::get('/apps/{id}/messages', [AppController::class, 'messages']);

    // Websites
    Route::get('/websites', [WebsiteController::class, 'index']);
    Route::post('/websites/generate', [WebsiteController::class, 'generate']);
    Route::get('/websites/{id}', [WebsiteController::class, 'show']);
    Route::get('/websites/{id}/status', [WebsiteController::class, 'status']);
    Route::post('/websites/{id}/rebuild', [WebsiteController::class, 'rebuild']);
    Route::delete('/websites/{id}', [WebsiteController::class, 'destroy']);

    // Website Management APIs
    Route::prefix('websites/{websiteId}')->group(function () {
        // Overview & Site Info
        Route::get('/overview', [WpOverviewController::class, 'index']);
        Route::get('/updates', [WpOverviewController::class, 'updates']);
        Route::post('/cache/clear', [WpOverviewController::class, 'clearCache']);
        Route::get('/wp-pages', [WpOverviewController::class, 'pages']);
        Route::get('/options', [WpOverviewController::class, 'options']);
        Route::put('/options', [WpOverviewController::class, 'updateOptions']);

        // Plugins
        Route::get('/plugins', [WpPluginController::class, 'index']);
        Route::post('/plugins/activate', [WpPluginController::class, 'activate']);
        Route::post('/plugins/deactivate', [WpPluginController::class, 'deactivate']);
        Route::post('/plugins/install', [WpPluginController::class, 'install']);
        Route::post('/plugins/update', [WpPluginController::class, 'update']);
        Route::delete('/plugins', [WpPluginController::class, 'destroy']);

        // Themes
        Route::get('/themes', [WpThemeController::class, 'index']);
        Route::post('/themes/activate', [WpThemeController::class, 'activate']);
        Route::post('/themes/install', [WpThemeController::class, 'install']);
        Route::post('/themes/update', [WpThemeController::class, 'update']);
        Route::delete('/themes', [WpThemeController::class, 'destroy']);

        // Backups
        Route::get('/backups', [BackupController::class, 'index']);
        Route::post('/backups', [BackupController::class, 'store']);
        Route::get('/backups/{backupId}/download', [BackupController::class, 'download']);
        Route::post('/backups/{backupId}/restore', [BackupController::class, 'restore']);
        Route::delete('/backups/{backupId}', [BackupController::class, 'destroy']);

        // Domains
        Route::get('/domains', [DomainController::class, 'index']);
        Route::post('/domains', [DomainController::class, 'store']);
        Route::delete('/domains/{domainId}', [DomainController::class, 'destroy']);

        // Branding / Logo
        Route::get('/branding/logo', [BrandingController::class, 'getLogo']);
        Route::post('/branding/logo', [BrandingController::class, 'uploadLogo']);
        Route::delete('/branding/logo', [BrandingController::class, 'removeLogo']);
        Route::post('/branding/logo/generate', [BrandingController::class, 'generateLogo']);

        // WooCommerce
        Route::get('/woo/products', [WooCommerceController::class, 'products']);
        Route::get('/woo/products/{productId}', [WooCommerceController::class, 'showProduct']);
        Route::post('/woo/products', [WooCommerceController::class, 'createProduct']);
        Route::put('/woo/products/{productId}', [WooCommerceController::class, 'updateProduct']);
        Route::delete('/woo/products/{productId}', [WooCommerceController::class, 'deleteProduct']);
        Route::get('/woo/orders', [WooCommerceController::class, 'orders']);
        Route::get('/woo/categories', [WooCommerceController::class, 'categories']);

        // AI Chat Assistant (legacy)
        Route::post('/ai-chat', [AiChatController::class, 'chat']);
        Route::get('/ai-chat/suggestions', [AiChatController::class, 'suggestions']);

        // AI Copilot (premium)
        Route::prefix('copilot')->group(function () {
            Route::post('/chat', [AiCopilotController::class, 'chat']);
            Route::get('/suggestions', [AiCopilotController::class, 'suggestions']);
            Route::get('/sessions', [AiCopilotController::class, 'sessions']);
            Route::get('/session/{sessionId}', [AiCopilotController::class, 'session']);
            Route::post('/undo/{actionId}', [AiCopilotController::class, 'undo']);
        });

        // WebNewBiz Builder Plugin
        Route::prefix('wnb')->group(function () {
            Route::get('/dashboard', [WpBuilderController::class, 'dashboard']);
            Route::get('/analytics', [WpBuilderController::class, 'analytics']);

            Route::get('/performance', [WpBuilderController::class, 'performanceGet']);
            Route::post('/performance', [WpBuilderController::class, 'performanceSave']);

            Route::get('/cache', [WpBuilderController::class, 'cacheStats']);
            Route::post('/cache/purge', [WpBuilderController::class, 'cachePurge']);
            Route::post('/cache/settings', [WpBuilderController::class, 'cacheSettings']);

            Route::get('/security', [WpBuilderController::class, 'securityGet']);
            Route::post('/security', [WpBuilderController::class, 'securitySave']);

            Route::get('/backups', [WpBuilderController::class, 'backupList']);
            Route::post('/backups', [WpBuilderController::class, 'backupCreate']);
            Route::delete('/backups/{backupId}', [WpBuilderController::class, 'backupDelete']);
            Route::post('/backups/{backupId}/restore', [WpBuilderController::class, 'backupRestore']);

            Route::get('/database', [WpBuilderController::class, 'databaseStats']);
            Route::post('/database/cleanup', [WpBuilderController::class, 'databaseCleanup']);
            Route::post('/database/optimize', [WpBuilderController::class, 'databaseOptimize']);

            Route::get('/maintenance', [WpBuilderController::class, 'maintenanceGet']);
            Route::post('/maintenance/toggle', [WpBuilderController::class, 'maintenanceToggle']);
            Route::post('/maintenance/settings', [WpBuilderController::class, 'maintenanceSave']);

            Route::get('/images', [WpBuilderController::class, 'imagesStats']);
            Route::post('/images/optimize', [WpBuilderController::class, 'imagesOptimize']);
            Route::post('/images/settings', [WpBuilderController::class, 'imagesSettings']);

            Route::get('/seo', [WpBuilderController::class, 'seoGet']);
            Route::post('/seo', [WpBuilderController::class, 'seoSave']);
            Route::post('/seo/redirects', [WpBuilderController::class, 'seoRedirectAdd']);
            Route::delete('/seo/redirects', [WpBuilderController::class, 'seoRedirectDelete']);
            Route::post('/seo/sitemap', [WpBuilderController::class, 'seoSitemap']);
            Route::post('/seo/robots', [WpBuilderController::class, 'seoRobots']);

            Route::post('/ai/generate', [WpBuilderController::class, 'aiGenerate']);
            Route::get('/ai/history', [WpBuilderController::class, 'aiHistory']);
            Route::post('/ai/history/clear', [WpBuilderController::class, 'aiHistory']);
        });
    });

    // ═══ FIGMA PLUGIN ═══
    Route::post('/figma/generate-design', [FigmaController::class, 'generateDesign']);
    Route::post('/figma/image-to-design', [FigmaController::class, 'imageToDesign']);
    Route::post('/figma/export-to-code', [FigmaController::class, 'exportToCode']);
    Route::get('/figma/projects', [FigmaController::class, 'projects']);

    // ═══ CRM ROUTES ═══
    Route::prefix('crm')->group(function () {
        // Contacts
        Route::get('/contacts', [ContactController::class, 'index']);
        Route::post('/contacts', [ContactController::class, 'store']);
        Route::get('/contacts/{id}', [ContactController::class, 'show']);
        Route::put('/contacts/{id}', [ContactController::class, 'update']);
        Route::delete('/contacts/{id}', [ContactController::class, 'destroy']);

        // Pipelines & Deals
        Route::get('/pipelines', [PipelineController::class, 'index']);
        Route::post('/pipelines', [PipelineController::class, 'store']);
        Route::get('/deals', [PipelineController::class, 'deals']);
        Route::post('/deals', [PipelineController::class, 'storeDeal']);
        Route::put('/deals/{id}', [PipelineController::class, 'updateDeal']);
        Route::put('/deals/{id}/stage', [PipelineController::class, 'updateDealStage']);

        // Email Campaigns
        Route::get('/campaigns', [CampaignController::class, 'index']);
        Route::post('/campaigns', [CampaignController::class, 'store']);
        Route::post('/campaigns/{id}/send', [CampaignController::class, 'send']);
        Route::get('/campaigns/{id}/stats', [CampaignController::class, 'stats']);

        // Sequences
        Route::get('/sequences', [SequenceController::class, 'index']);
        Route::post('/sequences', [SequenceController::class, 'store']);
        Route::put('/sequences/{id}', [SequenceController::class, 'update']);

        // Workflows
        Route::get('/workflows', [WorkflowController::class, 'index']);
        Route::post('/workflows', [WorkflowController::class, 'store']);
        Route::put('/workflows/{id}', [WorkflowController::class, 'update']);
        Route::post('/workflows/{id}/activate', [WorkflowController::class, 'activate']);

        // Calendar & Bookings
        Route::get('/calendars', [CalendarController::class, 'index']);
        Route::post('/calendars', [CalendarController::class, 'store']);
        Route::get('/bookings', [CalendarController::class, 'bookings']);
        Route::post('/bookings', [CalendarController::class, 'storeBooking']);

        // Invoices
        Route::get('/invoices', [CrmInvoiceController::class, 'index']);
        Route::post('/invoices', [CrmInvoiceController::class, 'store']);
        Route::get('/invoices/{id}', [CrmInvoiceController::class, 'show']);
        Route::post('/invoices/{id}/paid', [CrmInvoiceController::class, 'markPaid']);

        // Conversations
        Route::get('/conversations', [ConversationController::class, 'index']);
        Route::get('/conversations/{id}/messages', [ConversationController::class, 'messages']);
        Route::post('/conversations/{id}/messages', [ConversationController::class, 'sendMessage']);
    });
});
