<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin\Member;
use App\Models\Admin\Trainer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AccountController extends Controller
{
    // Member Management
    public function members()
    {
        $members = Member::latest()->paginate(15);
        return view('admin.members.index', compact('members'));
    }
    
    public function createMember()
    {
        return view('admin.members.create');
    }
    
    public function storeMember(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string',
            'emergency_contact' => 'nullable|string|max:255',
            'emergency_phone' => 'nullable|string|max:20',
            'medical_conditions' => 'nullable|string',
            'fitness_goals' => 'nullable|string',
        ]);
        
        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'member';
        $validated['is_active'] = true;
        
        Member::create($validated);
        
        return redirect()->route('admin.accounts.members.index')
            ->with('success', 'Member created successfully.');
    }
    
    public function showMember(Member $member)
    {
        $member->load(['bookings.trainer', 'payments']);
        return view('admin.members.show', compact('member'));
    }
    
    public function editMember(Member $member)
    {
        return view('admin.members.edit', compact('member'));
    }
    
    public function updateMember(Request $request, Member $member)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($member->id)],
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string',
            'emergency_contact' => 'nullable|string|max:255',
            'emergency_phone' => 'nullable|string|max:20',
            'medical_conditions' => 'nullable|string',
            'fitness_goals' => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        
        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8|confirmed']);
            $validated['password'] = Hash::make($request->password);
        }
        
        $member->update($validated);
        
        return redirect()->route('admin.accounts.members.index')
            ->with('success', 'Member updated successfully.');
    }
    
    public function destroyMember(Member $member)
    {
        $member->delete();
        return redirect()->route('admin.accounts.members.index')
            ->with('success', 'Member deleted successfully.');
    }
    
    // Trainer Management
    public function trainers()
    {
        $trainers = Trainer::latest()->paginate(15);
        return view('admin.trainers.index', compact('trainers'));
    }
    
    public function createTrainer()
    {
        return view('admin.trainers.create');
    }
    
    public function storeTrainer(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string',
            'specialization' => 'nullable|string',
            'experience_years' => 'nullable|integer|min:0',
            'certification' => 'nullable|string',
            'hourly_rate' => 'nullable|numeric|min:0',
        ]);
        
        $validated['password'] = Hash::make($validated['password']);
        $validated['role'] = 'trainer';
        $validated['is_active'] = true;
        
        Trainer::create($validated);
        
        return redirect()->route('admin.accounts.trainers.index')
            ->with('success', 'Trainer created successfully.');
    }
    
    public function showTrainer(Trainer $trainer)
    {
        return view('admin.trainers.show', compact('trainer'));
    }
    
    public function editTrainer(Trainer $trainer)
    {
        return view('admin.trainers.edit', compact('trainer'));
    }
    
    public function updateTrainer(Request $request, Trainer $trainer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($trainer->id)],
            'phone' => 'nullable|string|max:20',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'address' => 'nullable|string',
            'specialization' => 'nullable|string',
            'experience_years' => 'nullable|integer|min:0',
            'certification' => 'nullable|string',
            'hourly_rate' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);
        
        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8|confirmed']);
            $validated['password'] = Hash::make($request->password);
        }
        
        $trainer->update($validated);
        
        return redirect()->route('admin.accounts.trainers.index')
            ->with('success', 'Trainer updated successfully.');
    }
    
    public function destroyTrainer(Trainer $trainer)
    {
        $trainer->delete();
        return redirect()->route('admin.accounts.trainers.index')
            ->with('success', 'Trainer deleted successfully.');
    }
    
    public function toggleStatus(Request $request, User $user)
    {
        $user->update(['is_active' => !$user->is_active]);
        
        $status = $user->is_active ? 'activated' : 'deactivated';
        return back()->with('success', "User {$status} successfully.");
    }
}