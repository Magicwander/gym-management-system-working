<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $members = User::where('role', 'member')
            ->with('memberships')
            ->paginate(10);

        return view('admin.members.index', compact('members'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.members.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'member';

        User::create($validated);

        return redirect()->route('admin.members.index')
            ->with('success', 'Member created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $member)
    {
        $member->load(['memberships', 'workouts.trainer']);
        return view('admin.members.show', compact('member'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $member)
    {
        return view('admin.members.edit', compact('member'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $member)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($member->id)],
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'string|min:8|confirmed',
            ]);
            $validated['password'] = Hash::make($request->password);
        }

        $member->update($validated);

        return redirect()->route('admin.members.index')
            ->with('success', 'Member updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $member)
    {
        // Check if member has active memberships
        $activeMemberships = $member->memberships()->where('status', 'active')->count();

        if ($activeMemberships > 0) {
            return redirect()->route('admin.members.index')
                ->with('error', 'Cannot delete member with active memberships. Please cancel memberships first.');
        }

        $memberName = $member->name;
        $member->delete();

        return redirect()->route('admin.members.index')
            ->with('success', "Member '{$memberName}' deleted successfully.");
    }

    /**
     * Toggle member status (activate/deactivate)
     */
    public function toggleStatus(Request $request, User $member)
    {
        $request->validate([
            'is_active' => 'required|boolean'
        ]);

        $member->update([
            'is_active' => $request->is_active
        ]);

        $status = $request->is_active ? 'activated' : 'deactivated';

        return redirect()->route('admin.members.index')
            ->with('success', "Member '{$member->name}' {$status} successfully.");
    }

    /**
     * Handle bulk actions on members
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,deactivate,delete',
            'member_ids' => 'required|json'
        ]);

        $memberIds = json_decode($request->member_ids);
        $action = $request->action;

        if (empty($memberIds)) {
            return redirect()->route('admin.members.index')
                ->with('error', 'No members selected.');
        }

        $members = User::whereIn('id', $memberIds)->where('role', 'member')->get();
        $count = $members->count();

        switch ($action) {
            case 'activate':
                $members->each(function ($member) {
                    $member->update(['is_active' => true]);
                });
                $message = "{$count} members activated successfully.";
                break;

            case 'deactivate':
                $members->each(function ($member) {
                    $member->update(['is_active' => false]);
                });
                $message = "{$count} members deactivated successfully.";
                break;

            case 'delete':
                // Check for active memberships
                $membersWithActiveMemberships = $members->filter(function ($member) {
                    return $member->memberships()->where('status', 'active')->exists();
                });

                if ($membersWithActiveMemberships->count() > 0) {
                    return redirect()->route('admin.members.index')
                        ->with('error', 'Cannot delete members with active memberships. Please cancel memberships first.');
                }

                $members->each(function ($member) {
                    $member->delete();
                });
                $message = "{$count} members deleted successfully.";
                break;
        }

        return redirect()->route('admin.members.index')
            ->with('success', $message);
    }

    /**
     * Export members to CSV
     */
    public function export()
    {
        $members = User::where('role', 'member')
            ->with('memberships')
            ->get();

        $filename = 'members_export_' . date('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function() use ($members) {
            $file = fopen('php://output', 'w');

            // CSV Headers
            fputcsv($file, [
                'ID',
                'Name',
                'Email',
                'Phone',
                'Gender',
                'Date of Birth',
                'Age',
                'Address',
                'Status',
                'Active Membership',
                'Membership Expires',
                'Joined Date'
            ]);

            // CSV Data
            foreach ($members as $member) {
                $activeMembership = $member->memberships()->where('status', 'active')->first();

                fputcsv($file, [
                    $member->id,
                    $member->name,
                    $member->email,
                    $member->phone ?? '',
                    $member->gender ?? '',
                    $member->date_of_birth ? $member->date_of_birth->format('Y-m-d') : '',
                    $member->date_of_birth ? $member->date_of_birth->age : '',
                    $member->address ?? '',
                    $member->is_active ? 'Active' : 'Inactive',
                    $activeMembership ? ucfirst($activeMembership->type) : 'None',
                    $activeMembership ? $activeMembership->end_date->format('Y-m-d') : '',
                    $member->created_at->format('Y-m-d H:i:s')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
