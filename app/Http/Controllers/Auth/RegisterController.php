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
            'user_type' => 'required|in:siswa,guru,guru_penguji',
            'nisn' => 'required_if:user_type,siswa|nullable|string|unique:users',
            'nip' => 'required_if:user_type,guru,guru_penguji|nullable|string|unique:users',
            'jurusan' => 'required_if:user_type,siswa|nullable|string',
            'kelas' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'user_type' => $request->user_type,
            'nisn' => $request->nisn,
            'nip' => $request->nip,
            'jurusan' => $request->jurusan,
            'kelas' => $request->kelas,
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
