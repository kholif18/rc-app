<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;



class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all()->map(function ($user) {
            $user->is_online = $user->last_activity && $user->last_activity->gt(Carbon::now()->subMinutes(5));
            return $user;
        });
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:admin,user',
        ]);

        $user = new User();
        $user->name = $validated['name'];
        $user->username = $validated['username'];
        $user->email = $validated['email'];
        $user->password = bcrypt($validated['password']);
        $user->role = $validated['role'];
        $user->avatar = 'avatar.png';

        $user->save();

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    use AuthorizesRequests;
    public function update(Request $request, User $user)
    {
        // Authorization check
        $this->authorize('update', $user);
        
        // Get authenticated user
        $currentUser = Auth::user();
                
        // Additional protection for admin/superadmin roles
        if ($currentUser->role === 'admin' && $currentUser->id !== $user->id && $user->role !== 'user') {
            abort(403, 'Anda hanya dapat mengedit user biasa atau diri Anda sendiri.');
        }

        // Validation rules
        $rules = [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|confirmed|min:6',
            'avatar' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ];

        // Role validation conditional
        $rules['role'] = [
            'required',
            Rule::in(['superadmin', 'admin', 'user']),
            function ($attribute, $value, $fail) use ($currentUser, $user) {
                if ($value === 'superadmin' && $currentUser->role !== 'superadmin') {
                    $fail('Hanya superadmin yang dapat menetapkan role superadmin.');
                }
                
                if ($user->id === $currentUser->id && $value !== $user->role) {
                    $fail('Anda tidak dapat mengubah role sendiri.');
                }
            }
        ];

        $validatedData = $request->validate($rules);
        // Update basic fields
        unset($validatedData['password']);
        $user->fill($validatedData);

        if ($request->filled('password')) {
            $user->password = bcrypt($request->input('password'));
        }

        // Only update role if authorized
        if ($currentUser->role === 'superadmin') {
            $user->role = $validatedData['role'];
        } elseif ($currentUser->role === 'admin') {
            if ($currentUser->id !== $user->id) {
                $user->role = 'user';
            } 
        }

        // Update password if provided
        if (!empty($validatedData['password'])) {
            $user->password = bcrypt($validatedData['password']);
        }

        // Handle avatar reset
        if ($request->input('reset_avatar') == 1) {
            if ($user->avatar && $user->avatar != 'avatar.png') {
                Storage::delete('public/avatars/' . $user->avatar);
            }
            $user->avatar = 'avatar.png';
        }

        // Handle new avatar upload
        if ($request->hasFile('avatar')) {
            if ($user->avatar && $user->avatar != 'avatar.png') {
                Storage::disk('public')->delete('avatars/' . $user->avatar);
            }

            $file = $request->file('avatar');
            $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('avatars', $filename, 'public');
            $user->avatar = $filename;
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Authorization check
        $this->authorize('delete', $user);

        // Tambahan proteksi untuk superadmin
        if ($user->role === 'superadmin' && $user->role !== 'superadmin') {
            abort(403, 'Hanya superadmin yang dapat menghapus superadmin');
        }

        // Hapus avatar jika bukan default
        if ($user->avatar && $user->avatar !== 'avatar.png') {
            Storage::delete('public/avatars/' . $user->avatar);
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dihapus');
    }
}
