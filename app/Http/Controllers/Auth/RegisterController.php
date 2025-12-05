<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /**
     * Show the registration form.
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Handle registration request.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)],
            'user_type' => 'required|in:team_member,team_lead',
            'member_id' => 'required_if:user_type,team_member|nullable|string|unique:users',
            'employee_id' => 'required_if:user_type,team_lead|nullable|string|unique:users',
            'department' => 'nullable|string',
            'team' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => $request->user_type,
            'member_id' => $request->member_id,
            'employee_id' => $request->employee_id,
            'department' => $request->department,
            'team' => $request->team,
            'tahun_ajaran' => date('Y'),
        ]);

        // Assign default role based on user type
        $this->assignDefaultRole($user);

        Auth::login($user);

        return redirect()->route('dashboard');
    }

    /**
     * Assign default role to user.
     */
    protected function assignDefaultRole(User $user)
    {
        $roleMap = [
            'siswa' => 'siswa',
            'guru' => 'guru_pembimbing',
            'guru_penguji' => 'guru_penguji',
        ];

        if (isset($roleMap[$user->user_type])) {
            $user->roles()->attach(
                \App\Models\Role::where('name', $roleMap[$user->user_type])->first()
            );
        }
    }
}
