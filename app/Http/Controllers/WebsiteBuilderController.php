<?php

namespace App\Http\Controllers;

use App\Jobs\ProvisionWebsiteJob;
use App\Models\Server;
use App\Models\Website;
use App\Services\AIContentService;
use App\Services\ThemeMatcherService;
use App\Services\WebsiteBuilderService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class WebsiteBuilderController extends Controller
{
    public function __construct(
        private WebsiteBuilderService $builderService,
    ) {}

    public function index()
    {
        // Redirect to home page — the AI modal now handles everything
        return redirect()->route('home');

        $user = auth()->user();
        $plan = $user->currentPlan();
        $websiteCount = $user->websites()->count();
        $maxWebsites = $plan->max_websites ?? 0;

        $businessTypes = [
            'Restaurant', 'E-commerce', 'Portfolio', 'Blog', 'Agency',
            'SaaS', 'Nonprofit', 'Consulting', 'Healthcare', 'Education',
            'Real Estate', 'Fitness', 'Photography', 'Legal', 'Finance',
            'Technology', 'Travel', 'Fashion', 'Food & Beverage', 'Automotive',
            'Construction', 'Entertainment', 'Marketing', 'Architecture',
            'Interior Design', 'Beauty & Spa', 'Pet Services', 'Music',
            'Sports', 'Other',
        ];

        // Premium layouts for the layout picker
        $matcher = app(ThemeMatcherService::class);
        $layouts = $matcher->getLayouts();

        return view('builder.index', compact('plan', 'websiteCount', 'maxWebsites', 'businessTypes', 'layouts'));
    }

    public function generate(Request $request)
    {
        $user = auth()->user();

        if (!$user->canCreateWebsite()) {
            $message = 'You\'ve reached your plan\'s website limit. Please upgrade to create more websites.';
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $message], 422);
            }
            return back()->with('error', $message)->withInput();
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'business_type' => 'required|string|max:100',
            'prompt' => 'nullable|string|max:2000',
            'style' => 'nullable|string|max:100',
            'theme' => 'nullable|string|max:100',
            'pages_structure' => 'nullable|string|max:10000',
        ]);

        $validated['email'] = $user->email;

        // Parse pages structure from the builder
        if (!empty($validated['pages_structure'])) {
            $pagesStructure = json_decode($validated['pages_structure'], true);
            if (is_array($pagesStructure)) {
                $validated['pages_structure'] = $pagesStructure;
            } else {
                unset($validated['pages_structure']);
            }
        }

        // Step 1: Find a server with capacity
        $server = Server::active()->withCapacity()->orderBy('current_websites')->first();
        if (!$server) {
            $message = 'No servers available. Please try again later.';
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => false, 'message' => $message], 422);
            }
            return back()->with('error', $message)->withInput();
        }

        // Step 2: Create website record
        $subdomain = Str::slug($validated['name']) . '-' . Str::random(4);

        // theme = layout slug (noir, ivory, azure, etc.) or 'auto'
        $theme = $validated['theme'] ?? 'auto';
        $style = $validated['style'] ?? $theme;

        $website = Website::create([
            'user_id' => $user->id,
            'server_id' => $server->id,
            'name' => $validated['name'],
            'subdomain' => strtolower($subdomain),
            'status' => 'provisioning',
            'ai_prompt' => $validated['prompt'] ?? null,
            'ai_business_type' => $validated['business_type'] ?? null,
            'ai_style' => $style,
            'ai_theme' => $theme,
            'wp_admin_email' => $validated['email'],
        ]);

        // Step 3: Dispatch provisioning job
        ProvisionWebsiteJob::dispatch($website, $validated);
        $this->ensureQueueWorkerRunning();

        // Step 4: Redirect to progress page
        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'redirect' => route('builder.status', $website),
            ]);
        }

        return redirect()->route('builder.status', $website);
    }

    public function enhance(Request $request)
    {
        $request->validate([
            'description' => 'required|string|min:10|max:2000',
            'business_name' => 'nullable|string|max:200',
            'business_type' => 'nullable|string|max:100',
        ]);

        $aiService = app(\App\Services\AIContentService::class);

        $businessContext = '';
        if ($request->business_name) {
            $businessContext .= "Business name: {$request->business_name}. ";
        }
        if ($request->business_type) {
            $businessContext .= "Industry: {$request->business_type}. ";
        }

        $prompt = "You are a website content expert. The user is building a website and wrote this brief description:\n\n\"{$request->description}\"\n\n{$businessContext}\n\nEnhance and expand this into a detailed, compelling website description (max 900 characters). Keep the user's intent, add specifics about pages needed, features, target audience, and unique selling points. Write in first person as the business owner. Return ONLY the enhanced text, nothing else.";

        $result = $aiService->generateContent($prompt);

        if ($result['success']) {
            $enhanced = trim($result['data']);
            $enhanced = mb_substr($enhanced, 0, 1000);
            return response()->json(['success' => true, 'enhanced' => $enhanced]);
        }

        return response()->json(['success' => false, 'message' => $result['message'] ?? 'Enhancement failed'], 422);
    }

    public function planSite(Request $request)
    {
        $request->validate([
            'website_type' => 'required|string|in:business,ecommerce',
            'business_name' => 'required|string|max:255',
            'business_type' => 'required|string|max:100',
            'description' => 'required|string|min:10|max:2000',
            'style' => 'nullable|string|max:50',
        ]);

        $aiService = app(AIContentService::class);
        $matcher = app(ThemeMatcherService::class);

        $description = $request->description;
        $websiteType = $request->website_type;

        if ($websiteType === 'ecommerce') {
            $description .= ' Needs online store with product catalog and checkout.';
        }

        // Theme matching
        $styleHint = $request->style ?? 'auto';
        $layouts = config('layouts', []);
        if ($styleHint !== 'auto' && isset($layouts[$styleHint])) {
            $theme = $styleHint;
        } else {
            $theme = $matcher->match($request->business_type, $description);
        }

        $aiPrompt = <<<PROMPT
You are a website planning assistant. Create a build plan for this business.

Business: "{$request->business_name}"
Type: {$request->business_type}
Website Type: {$websiteType}
Description: {$request->description}

Return JSON:
{
  "summary": "2-3 sentence description of the website that will be built",
  "features": ["Feature 1", "Feature 2", ...up to 8 key features],
  "pages": [
    {"name": "Home", "description": "Main landing page with hero and key info", "enabled": true},
    {"name": "About", "description": "Company story and team", "enabled": true}
  ]
}

Rules:
- Include 4-7 pages based on business type
- Always include Home and Contact pages
- For e-commerce include Shop page
- Features should be specific to this business type
- Summary should be engaging and specific
Return ONLY valid JSON.
PROMPT;

        $result = $aiService->generateContent($aiPrompt, 'Return only valid JSON.');

        $summary = "A professional {$request->business_type} website for {$request->business_name} with custom design, responsive layout, and optimized performance.";
        $features = ['Responsive Design', 'Contact Form', 'SEO Optimized', 'Fast Loading', 'Mobile Friendly'];
        $defaultPages = [
            ['name' => 'Home', 'description' => 'Main landing page', 'enabled' => true],
            ['name' => 'About', 'description' => 'About your business', 'enabled' => true],
            ['name' => 'Services', 'description' => 'Services you offer', 'enabled' => true],
            ['name' => 'Contact', 'description' => 'Contact information and form', 'enabled' => true],
        ];

        if ($websiteType === 'ecommerce') {
            $features = array_merge($features, ['Online Store', 'Shopping Cart', 'Secure Checkout']);
            $defaultPages[] = ['name' => 'Shop', 'description' => 'Product catalog', 'enabled' => true];
        }

        $pages = $defaultPages;

        if ($result['success']) {
            $data = $this->extractJsonFromAI($result['data']);
            if ($data) {
                $summary = $data['summary'] ?? $summary;
                $features = $data['features'] ?? $features;
                if (!empty($data['pages'])) {
                    $pages = array_map(function ($p) {
                        if (is_string($p)) return ['name' => $p, 'description' => '', 'enabled' => true];
                        return [
                            'name' => $p['name'] ?? 'Page',
                            'description' => $p['description'] ?? '',
                            'enabled' => $p['enabled'] ?? true,
                        ];
                    }, $data['pages']);
                }
            }
        }

        return response()->json([
            'success' => true,
            'summary' => $summary,
            'features' => array_values(array_slice($features, 0, 8)),
            'pages' => $pages,
            'theme' => $theme,
        ]);
    }

    public function status(Website $website)
    {
        abort_if($website->user_id !== auth()->id() && !auth()->user()->isAdmin(), 403);

        if (request()->wantsJson()) {
            return response()->json([
                'status' => $website->status,
                'url' => $website->url,
                'screenshot' => $website->screenshot_path,
            ]);
        }
        return view('builder.progress', compact('website'));
    }

    public function complete(Website $website)
    {
        abort_if($website->user_id !== auth()->id() && !auth()->user()->isAdmin(), 403);

        if ($website->status !== 'active') {
            return redirect()->route('builder.status', $website);
        }

        $website->load(['domains', 'server']);

        return view('builder.complete', compact('website'));
    }

    public function retry(Website $website)
    {
        abort_if($website->user_id !== auth()->id() && !auth()->user()->isAdmin(), 403);
        abort_if($website->status !== 'error', 422, 'Only failed websites can be retried.');

        $website->update(['status' => 'provisioning']);

        $params = [
            'name' => $website->name,
            'business_type' => $website->ai_business_type ?? 'general',
            'prompt' => $website->ai_prompt,
            'style' => $website->ai_style ?? 'auto',
            'theme' => $website->ai_theme ?? 'auto',
            'email' => $website->wp_admin_email ?? auth()->user()->email,
        ];

        ProvisionWebsiteJob::dispatch($website, $params);
        $this->ensureQueueWorkerRunning();

        return redirect()->route('builder.status', $website)->with('success', 'Build restarted! We\'re trying again.');
    }

    /**
     * AI-powered analysis: extract business info and generate Yes/No questions.
     */
    public function analyze(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string|min:10|max:2000',
        ]);

        $prompt = $request->prompt;
        $aiService = app(AIContentService::class);

        $layoutList = collect(config('layouts', []))->map(fn($cfg, $slug) =>
            "{$slug}: {$cfg['name']} ({$cfg['style']})"
        )->join(', ');

        $aiPrompt = <<<PROMPT
You are a website planning assistant for WebNewBiz. Analyze this business description and generate smart Yes/No questions to refine the website plan.

User's description: "{$prompt}"

Available design styles: {$layoutList}

Return a JSON object with:
1. "business_name": Extract the business/brand name from the description (best guess, max 60 chars)
2. "business_type": Categorize into one of: Restaurant, E-commerce, Portfolio, Blog, Agency, SaaS, Nonprofit, Consulting, Healthcare, Education, Real Estate, Fitness, Photography, Legal, Finance, Technology, Travel, Fashion, Beauty & Spa, Construction, Entertainment, Other
3. "questions": Array of exactly 5 objects, each with:
   - "id": unique short id (e.g., "ecommerce", "booking", "gallery")
   - "question": A clear Yes/No question (max 80 chars)
   - "context": Brief explanation (max 50 chars)
4. "suggested_style": One of the available design style slugs that best fits this business

Rules for questions:
- Only Yes/No questions — no open-ended
- Make questions contextual to the business described
- Always include: whether they need online selling/payments
- Always include: whether they want a contact form or booking
- Include 1-2 industry-specific questions
- Include a design preference question (e.g., "Do you prefer a dark, modern look?")
- Do NOT ask things already obvious from the description

Return ONLY valid JSON, no markdown.
PROMPT;

        $result = $aiService->generateContent($aiPrompt, 'You are a JSON-only responder. Return only valid JSON.');

        if ($result['success']) {
            $data = $this->extractJsonFromAI($result['data']);
            if ($data && !empty($data['questions'])) {
                return response()->json([
                    'success' => true,
                    'business_name' => $data['business_name'] ?? $this->guessBusinessName($prompt),
                    'business_type' => $data['business_type'] ?? 'Other',
                    'questions' => array_slice($data['questions'], 0, 6),
                    'suggested_style' => $data['suggested_style'] ?? 'azure',
                ]);
            }
        }

        // Fallback: return hardcoded questions
        return response()->json([
            'success' => true,
            'business_name' => $this->guessBusinessName($prompt),
            'business_type' => 'Other',
            'questions' => [
                ['id' => 'ecommerce', 'question' => 'Do you want to sell products online?', 'context' => 'Adds an online store to your site'],
                ['id' => 'booking', 'question' => 'Do you need appointment or booking features?', 'context' => 'Adds a booking/scheduling system'],
                ['id' => 'gallery', 'question' => 'Do you want a photo or work gallery?', 'context' => 'Showcase your work visually'],
                ['id' => 'dark_theme', 'question' => 'Do you prefer a dark, modern look?', 'context' => 'Affects overall design theme'],
                ['id' => 'testimonials', 'question' => 'Do you want customer reviews on your site?', 'context' => 'Adds a testimonials section'],
            ],
            'suggested_style' => 'azure',
        ]);
    }

    /**
     * AI-powered summary: generate build plan from answers.
     */
    public function summarize(Request $request)
    {
        $request->validate([
            'prompt' => 'required|string|max:2000',
            'business_name' => 'required|string|max:255',
            'business_type' => 'required|string|max:100',
            'answers' => 'required|array',
        ]);

        $prompt = $request->prompt;
        $businessName = $request->business_name;
        $businessType = $request->business_type;
        $answers = $request->answers;

        // Build readable answers
        $answerText = collect($answers)->map(function ($val, $key) {
            $label = str_replace('_', ' ', ucfirst($key));
            return "{$label}: " . ($val ? 'Yes' : 'No');
        })->join("\n");

        // Enrich prompt for theme matching
        $enrichedPrompt = $prompt;
        if (!empty($answers['ecommerce'])) $enrichedPrompt .= ' Needs online store with product catalog and payments.';
        if (!empty($answers['booking'])) $enrichedPrompt .= ' Needs appointment booking system.';
        if (!empty($answers['gallery'])) $enrichedPrompt .= ' Needs photo gallery showcase.';
        if (!empty($answers['dark_theme'])) $enrichedPrompt .= ' Prefers dark modern design.';

        // Match theme
        $matcher = app(ThemeMatcherService::class);
        $theme = $matcher->match($businessType, $enrichedPrompt);

        // If user wants dark theme but matched light, bias to dark
        if (!empty($answers['dark_theme'])) {
            $layouts = config('layouts', []);
            if (isset($layouts[$theme]) && ($layouts[$theme]['style'] ?? '') !== 'dark') {
                $darkLayouts = ['noir', 'ember', 'royal'];
                $theme = $matcher->keywordFallback($businessType, $enrichedPrompt . ' dark modern');
                if (!in_array($theme, $darkLayouts)) $theme = 'noir';
            }
        }

        $aiService = app(AIContentService::class);

        $aiPrompt = <<<PROMPT
You are a website planning assistant for WebNewBiz. Based on the user's description and preferences, create a build summary.

Business: "{$businessName}"
Type: {$businessType}
Description: {$prompt}
User Preferences:
{$answerText}

Return JSON:
{
  "summary": "2-3 sentence description of what WebNewBiz will build for this business",
  "features": ["Feature 1", "Feature 2", ...max 6 features that will be included],
  "pages": ["Home", "About", ...list of pages that will be created]
}

Return ONLY valid JSON, no markdown.
PROMPT;

        $result = $aiService->generateContent($aiPrompt, 'You are a JSON-only responder. Return only valid JSON.');

        $summary = 'WebNewBiz will create a professional website for your business.';
        $features = ['Responsive Design', 'Contact Form', 'SEO Optimized'];
        $pages = ['Home', 'About', 'Services', 'Contact'];

        if ($result['success']) {
            $data = $this->extractJsonFromAI($result['data']);
            if ($data) {
                $summary = $data['summary'] ?? $summary;
                $features = $data['features'] ?? $features;
                $pages = $data['pages'] ?? $pages;
            }
        }

        return response()->json([
            'success' => true,
            'summary' => $summary,
            'features' => $features,
            'pages' => $pages,
            'theme' => $theme,
            'business_name' => $businessName,
            'business_type' => $businessType,
        ]);
    }

    /**
     * Extract JSON from AI response text.
     */
    private function extractJsonFromAI(string $raw): ?array
    {
        $content = trim($raw);
        $content = preg_replace('/^```(?:json|JSON)?\s*\n?/m', '', $content);
        $content = preg_replace('/\n?```\s*$/m', '', $content);
        $content = trim($content);

        $parsed = json_decode($content, true);
        if (is_array($parsed) && !empty($parsed)) return $parsed;

        $first = strpos($content, '{');
        $last = strrpos($content, '}');
        if ($first !== false && $last !== false && $last > $first) {
            $json = substr($content, $first, $last - $first + 1);
            $parsed = json_decode($json, true);
            if (is_array($parsed) && !empty($parsed)) return $parsed;

            $cleaned = preg_replace('/,\s*([\]}])/m', '$1', $json);
            $parsed = json_decode($cleaned, true);
            if (is_array($parsed) && !empty($parsed)) return $parsed;
        }

        return null;
    }

    /**
     * Best-effort business name extraction from prompt.
     */
    private function guessBusinessName(string $prompt): string
    {
        // Look for quoted names
        if (preg_match('/"([^"]+)"/', $prompt, $m)) return $m[1];
        if (preg_match("/called\s+([A-Z][A-Za-z\s&']+)/", $prompt, $m)) return trim($m[1]);
        if (preg_match("/named\s+([A-Z][A-Za-z\s&']+)/", $prompt, $m)) return trim($m[1]);
        if (preg_match("/for\s+([A-Z][A-Za-z\s&']+)/", $prompt, $m)) return trim($m[1]);

        // Fallback: first few words
        $words = explode(' ', $prompt);
        return implode(' ', array_slice($words, 0, 3));
    }

    private function ensureQueueWorkerRunning(): void
    {
        if (config('queue.default') === 'sync') {
            return;
        }

        try {
            $lockFile = storage_path('app/queue-worker.lock');
            $isRunning = false;

            if (file_exists($lockFile)) {
                $pid = (int) file_get_contents($lockFile);
                if ($pid > 0 && PHP_OS_FAMILY === 'Windows') {
                    exec("tasklist /FI \"PID eq {$pid}\" 2>NUL", $output);
                    $isRunning = count($output) > 1 && strpos(implode('', $output), (string) $pid) !== false;
                }
            }

            if (!$isRunning) {
                $artisan = base_path('artisan');
                $php = PHP_BINARY ?: 'php';
                $logFile = storage_path('logs/queue-worker.log');

                if (PHP_OS_FAMILY === 'Windows') {
                    $cmd = "start /B {$php} {$artisan} queue:work --stop-when-empty --timeout=600 > \"{$logFile}\" 2>&1";
                    pclose(popen($cmd, 'r'));
                } else {
                    $cmd = "{$php} {$artisan} queue:work --stop-when-empty --timeout=600 > \"{$logFile}\" 2>&1 &";
                    exec($cmd);
                }
            }
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning("Queue worker auto-start failed: {$e->getMessage()}");
        }
    }
}
