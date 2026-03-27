<?php

namespace App\Http\Controllers\Api\Crm;

use App\Http\Controllers\Controller;
use App\Models\InvoiceCrm;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class InvoiceController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $invoices = InvoiceCrm::forUser($request->user()->id)
            ->with('contact')
            ->orderByDesc('created_at')
            ->get();
        return response()->json($invoices);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'contact_id' => 'nullable|exists:contacts,id',
            'due_date'   => 'nullable|date',
            'items'      => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantity'    => 'required|numeric|min:1',
            'items.*.price'       => 'required|numeric|min:0',
            'tax'        => 'nullable|numeric|min:0',
        ]);

        $subtotal = collect($validated['items'])->sum(fn($i) => $i['quantity'] * $i['price']);
        $tax = $validated['tax'] ?? 0;

        $invoice = InvoiceCrm::create([
            'user_id'    => $request->user()->id,
            'contact_id' => $validated['contact_id'] ?? null,
            'number'     => 'INV-' . strtoupper(Str::random(8)),
            'due_date'   => $validated['due_date'] ?? null,
            'items'      => $validated['items'],
            'subtotal'   => $subtotal,
            'tax'        => $tax,
            'total'      => $subtotal + $tax,
        ]);

        return response()->json($invoice->load('contact'), 201);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $invoice = InvoiceCrm::forUser($request->user()->id)->with('contact')->findOrFail($id);
        return response()->json($invoice);
    }

    public function markPaid(Request $request, int $id): JsonResponse
    {
        $invoice = InvoiceCrm::forUser($request->user()->id)->findOrFail($id);
        $invoice->update(['status' => 'paid', 'paid_at' => now()]);
        return response()->json($invoice->fresh());
    }
}
