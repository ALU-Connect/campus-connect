<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function show(User $user)
    {
        $user->load([
            'petitions' => function($query) {
                $query->latest()->take(5);
            },
            'createdEvents' => function($query) {
                $query->where('event_date', '>=', now())->latest()->take(5);
            }
        ]);

        $stats = [
            'petitions_created' => $user->petitions()->count(),
            'events_created' => $user->createdEvents()->count(),
            'total_votes' => $user->petitions()->sum('upvote_count'),
        ];

        return Inertia::render('Profile/Show', [
            'profileUser' => $user,
            'stats' => $stats,
        ]);
    }

    public function edit()
    {
        return Inertia::render('Profile/Edit', [
            'user' => auth()->user(),
        ]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'bio' => 'nullable|string|max:500',
            'profile_picture' => 'nullable|image|max:2048',
        ]);

        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old picture if exists
            if ($user->profile_picture) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            
            $validated['profile_picture'] = $request->file('profile_picture')
                ->store('profiles', 'public');
        }

        $user->update($validated);

        return redirect()->route('profile.show', $user)
            ->with('success', 'Profile updated successfully!');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = auth()->user();

        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password updated successfully!');
    }
}
