<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Throwable;

class ProfileController extends Controller
{
    /**
     * Display the admin profile management page.
     */
    public function index(): View
    {
        /** @var User $user */
        $user = Auth::user();

        return view('admin.profile.index', [
            'user' => $user,
        ]);
    }

    /**
     * Update admin profile information (Name, Email, Avatar).
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
        ]);

        try {
            DB::transaction(function () use ($user, $validated, $request) {
                if ($request->hasFile('avatar')) {
                    if ($user->avatar && ! str_starts_with($user->avatar, 'http') && Storage::disk('public')->exists($user->avatar)) {
                        Storage::disk('public')->delete($user->avatar);
                    }
                    $validated['avatar'] = $request->file('avatar')->store('avatars', 'public');
                }

                $user->update($validated);
            });

            return back()->with('success', 'Profile information updated successfully!');
        } catch (Throwable $e) {
            Log::error('Failed to update admin profile #'.$user->id.': '.$e->getMessage(), ['exception' => $e]);

            return back()->withInput()->with('error', 'Failed to update profile. Please try again.');
        }
    }

    /**
     * Update admin account password.
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        /** @var User $user */
        $user = Auth::user();

        $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        try {
            if (! Hash::check($request->string('current_password')->toString(), $user->password)) {
                return back()->with('error', 'Your current password does not match our records.');
            }

            DB::transaction(function () use ($user, $request) {
                $user->password = Hash::make($request->string('password')->toString());
                $user->save();
            });

            return back()->with('success', 'Password updated successfully!');
        } catch (Throwable $e) {
            Log::error('Failed to update admin password #'.$user->id.': '.$e->getMessage(), ['exception' => $e]);

            return back()->with('error', 'Failed to update password. Please try again.');
        }
    }
}
