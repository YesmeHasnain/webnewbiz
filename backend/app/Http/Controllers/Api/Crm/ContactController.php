<?php

namespace App\Http\Controllers\Api\Crm;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Contact::forUser($request->user()->id);

        if ($request->has('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('first_name', 'like', "%{$s}%")
                  ->orWhere('last_name', 'like', "%{$s}%")
                  ->orWhere('email', 'like', "%{$s}%")
                  ->orWhere('company', 'like', "%{$s}%");
            });
        }

        if ($request->has('status')) $query->where('status', $request->status);
        if ($request->has('tag')) $query->whereJsonContains('tags', $request->tag);

        $contacts = $query->orderByDesc('updated_at')->paginate(50);
        return response()->json($contacts);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'first_name'    => 'required|string|max:255',
            'last_name'     => 'nullable|string|max:255',
            'email'         => 'nullable|email|max:255',
            'phone'         => 'nullable|string|max:50',
            'company'       => 'nullable|string|max:255',
            'title'         => 'nullable|string|max:255',
            'source'        => 'nullable|string|max:50',
            'tags'          => 'nullable|array',
            'custom_fields' => 'nullable|array',
        ]);

        $contact = Contact::create(['user_id' => $request->user()->id, ...$validated]);
        return response()->json($contact, 201);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $contact = Contact::forUser($request->user()->id)->with(['deals', 'bookings'])->findOrFail($id);
        return response()->json($contact);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $contact = Contact::forUser($request->user()->id)->findOrFail($id);
        $contact->update($request->only([
            'first_name', 'last_name', 'email', 'phone', 'company', 'title', 'status', 'tags', 'custom_fields',
        ]));
        return response()->json($contact->fresh());
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        Contact::forUser($request->user()->id)->findOrFail($id)->delete();
        return response()->json(['message' => 'Contact deleted.']);
    }
}
