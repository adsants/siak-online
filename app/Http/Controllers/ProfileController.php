<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;


class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        $user = $request->user();
        return view('auth.profile_edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'          => ['required','string','max:255'],
            // 'umur'        => HAPUS dari validasi
            'alamat'        => ['nullable','string','max:255'],
            'pekerjaan'     => ['nullable','string','max:255'],
            'tgl_lahir'     => ['nullable','date','before:today'],
            'jenis_kelamin' => ['required', Rule::in(['L','P'])],
            'photo'         => ['nullable','image','mimes:jpg,jpeg,png,webp','max:2048'],
            'password'      => ['nullable','min:4','confirmed'],
        ],[],[
            'name'          => 'Nama',
            'alamat'        => 'Alamat',
            'pekerjaan'     => 'Pekerjaan',
            'tgl_lahir'     => 'Tanggal Lahir',
            'jenis_kelamin' => 'Jenis Kelamin',
            'photo'         => 'Foto',
            'password'      => 'Password',
        ]);

        // Upload photo
        if ($request->hasFile('photo')) {
            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }
            $ext = $request->file('photo')->getClientOriginalExtension();
            $filename = 'user-'.$user->id.'-'.Str::random(8).'.'.$ext;
            $path = $request->file('photo')->storeAs('photos', $filename, 'public');
            if (!Storage::disk('public')->exists($path)) {
                return back()->withErrors(['photo' => 'Gagal menyimpan foto. Coba lagi.']);
            }
            $validated['photo'] = $path;
        }

        // Password opsional
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        // === Hitung umur otomatis dari tgl_lahir ===
        if (!empty($validated['tgl_lahir'])) {
            // Carbon otomatis menghitung umur per hari ini
            $validated['umur'] = Carbon::parse($validated['tgl_lahir'])->age;
            // (Opsional) validasi umur wajar:
            if ($validated['umur'] < 0 || $validated['umur'] > 120) {
                return back()->withErrors(['tgl_lahir' => 'Tanggal lahir tidak valid.']);
            }
        } else {
            // Jika tanggal lahir dikosongkan, kosongkan umur juga
            $validated['umur'] = null;
        }

        $user->update($validated);

        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui.');
    }
}
