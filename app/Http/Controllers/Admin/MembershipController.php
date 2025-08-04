<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Membership;
use App\Models\User;

class MembershipController extends Controller
{
    public function index()
    {
        $memberships = Membership::with('user')
            ->latest()
            ->paginate(10);

        return view('admin.memberships.index', compact('memberships'));
    }

    public function create()
    {
        $members = User::where('role', 'member')->where('is_active', true)->get();
        return view('admin.memberships.create', compact('members'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:platinum,gold,silver',
            'duration' => 'required|in:3_months,6_months,1_year',
            'price' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        // Calculate end date based on duration
        $startDate = \Carbon\Carbon::parse($validated['start_date']);
        switch ($validated['duration']) {
            case '3_months':
                $endDate = $startDate->copy()->addMonths(3);
                break;
            case '6_months':
                $endDate = $startDate->copy()->addMonths(6);
                break;
            case '1_year':
                $endDate = $startDate->copy()->addYear();
                break;
        }

        $validated['end_date'] = $endDate;
        $validated['status'] = 'active';

        Membership::create($validated);

        return redirect()->route('admin.memberships.index')
            ->with('success', 'Membership created successfully.');
    }

    public function show(Membership $membership)
    {
        $membership->load('user');
        return view('admin.memberships.show', compact('membership'));
    }

    public function edit(Membership $membership)
    {
        $members = User::where('role', 'member')->where('is_active', true)->get();
        return view('admin.memberships.edit', compact('membership', 'members'));
    }

    public function update(Request $request, Membership $membership)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'type' => 'required|in:platinum,gold,silver',
            'duration' => 'required|in:3_months,6_months,1_year',
            'price' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'status' => 'required|in:active,expired,cancelled',
            'notes' => 'nullable|string',
        ]);

        // Recalculate end date if start date or duration changed
        if ($request->start_date != $membership->start_date || $request->duration != $membership->duration) {
            $startDate = \Carbon\Carbon::parse($validated['start_date']);
            switch ($validated['duration']) {
                case '3_months':
                    $endDate = $startDate->copy()->addMonths(3);
                    break;
                case '6_months':
                    $endDate = $startDate->copy()->addMonths(6);
                    break;
                case '1_year':
                    $endDate = $startDate->copy()->addYear();
                    break;
            }
            $validated['end_date'] = $endDate;
        }

        $membership->update($validated);

        return redirect()->route('admin.memberships.index')
            ->with('success', 'Membership updated successfully.');
    }

    public function destroy(Membership $membership)
    {
        $membership->delete();

        return redirect()->route('admin.memberships.index')
            ->with('success', 'Membership deleted successfully.');
    }
}
