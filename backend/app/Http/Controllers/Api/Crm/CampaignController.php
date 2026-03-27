<?php

namespace App\Http\Controllers\Api\Crm;

use App\Http\Controllers\Controller;
use App\Models\EmailCampaign;
use App\Models\Contact;
use App\Models\EmailSend;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CampaignController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $campaigns = EmailCampaign::forUser($request->user()->id)->orderByDesc('created_at')->get();
        return response()->json($campaigns);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:255',
            'subject'   => 'required|string|max:500',
            'body_html' => 'required|string',
        ]);

        $campaign = EmailCampaign::create(['user_id' => $request->user()->id, 'status' => 'draft', ...$validated]);
        return response()->json($campaign, 201);
    }

    public function send(Request $request, int $id): JsonResponse
    {
        $campaign = EmailCampaign::forUser($request->user()->id)->findOrFail($id);
        $contacts = Contact::forUser($request->user()->id)->whereNotNull('email')->get();

        $sent = 0;
        foreach ($contacts as $contact) {
            EmailSend::create([
                'campaign_id' => $campaign->id,
                'contact_id'  => $contact->id,
                'status'      => 'sent',
            ]);
            // In production: dispatch email job via SES/SendGrid
            $sent++;
        }

        $campaign->update([
            'status'  => 'sent',
            'sent_at' => now(),
            'stats'   => ['total' => $sent, 'opened' => 0, 'clicked' => 0],
        ]);

        return response()->json(['message' => "Campaign sent to {$sent} contacts.", 'campaign' => $campaign->fresh()]);
    }

    public function stats(Request $request, int $id): JsonResponse
    {
        $campaign = EmailCampaign::forUser($request->user()->id)->findOrFail($id);
        $total = EmailSend::where('campaign_id', $id)->count();
        $opened = EmailSend::where('campaign_id', $id)->whereNotNull('opened_at')->count();
        $clicked = EmailSend::where('campaign_id', $id)->whereNotNull('clicked_at')->count();

        return response()->json([
            'total' => $total, 'opened' => $opened, 'clicked' => $clicked,
            'open_rate' => $total > 0 ? round(($opened / $total) * 100, 1) : 0,
            'click_rate' => $total > 0 ? round(($clicked / $total) * 100, 1) : 0,
        ]);
    }
}
