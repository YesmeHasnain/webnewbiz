<?php

namespace App\Http\Controllers\Api\Crm;

use App\Http\Controllers\Controller;
use App\Models\Calendar;
use App\Models\Booking;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CalendarController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $calendars = Calendar::forUser($request->user()->id)->get();
        return response()->json($calendars);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:255',
            'timezone'         => 'nullable|string|max:50',
            'booking_duration' => 'nullable|integer|min:5|max:480',
            'buffer_minutes'   => 'nullable|integer|min:0|max:120',
            'availability'     => 'nullable|array',
        ]);

        $calendar = Calendar::create(['user_id' => $request->user()->id, ...$validated]);
        return response()->json($calendar, 201);
    }

    public function bookings(Request $request): JsonResponse
    {
        $calendarIds = Calendar::forUser($request->user()->id)->pluck('id');
        $bookings = Booking::whereIn('calendar_id', $calendarIds)
            ->with('contact')
            ->orderBy('start_time')
            ->get();
        return response()->json($bookings);
    }

    public function storeBooking(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'calendar_id' => 'required|exists:calendars,id',
            'contact_id'  => 'nullable|exists:contacts,id',
            'start_time'  => 'required|date',
            'end_time'    => 'required|date|after:start_time',
            'notes'       => 'nullable|string|max:1000',
        ]);

        $booking = Booking::create($validated);
        return response()->json($booking->load('contact'), 201);
    }
}
