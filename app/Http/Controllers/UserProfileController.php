<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\MasterProvinsi;
use App\Models\MasterKabupaten;
use App\Models\MasterDialisis;
use Illuminate\Support\Facades\Storage;
class UserProfileController extends Controller
{
    public function show()
    {
        $masterProvinsi = MasterProvinsi::all();
        $masterKabupaten = MasterKabupaten::all();
        $masterDialisis = MasterDialisis::all();
        return view('pages.user-profile', compact('masterProvinsi','masterKabupaten', 'masterDialisis'));
    }

    public function update(Request $request)
    {
        // dd($request->photo_profile);
        $attributes = $request->validate([
            'username' => ['required','max:255', 'min:2'],
            'firstname' => ['max:100'],
            'lastname' => ['max:100'],
            'email' => ['required', 'email', 'max:255',  Rule::unique('users')->ignore(auth()->user()->id),],
            'address' => ['max:100'],
            'city' => ['max:100'],
            'country' => ['max:100'],
            'postal' => ['max:100'],
            'about' => ['max:255']
        ]);

        if ($request->hasFile('photo_profile')) {
            $user = auth()->user(); // Assuming the user is authenticated
            $file = $request->file('photo_profile');
            $path = $file->store('profile_photos', 'public');

            // Optionally, delete old photo if exists
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            $user->profile = $path;
            $user->save();
        }
        // dd($request->file('sertifikat_file'));
        if ($request->hasFile('sertifikat_file')) {
            $user = auth()->user(); // Assuming the user is authenticated
            $file = $request->file('sertifikat_file');
            $path = $file->store('sertifikat_user', 'public');

            // Optionally, delete old photo if exists
            if ($user->sertifikat_file) {
                Storage::disk('public')->delete($user->sertifikat_file);
            }

            $user->sertifikat_file = 'storage/'.$path;
            $user->save();
        }

        if ($request->sertifikat_start_date != null || $request->sertifikat_start_date != '') {
            auth()->user()->update([
                'sertifikat_start_date' => $request->sertifikat_start_date
            ]);
        }
        if ($request->sertifikat_end_date != null || $request->sertifikat_end_date != '') {
            auth()->user()->update([
                'sertifikat_end_date' => $request->sertifikat_end_date
            ]);
        }

        auth()->user()->update([
            'username' => $request->get('username'),
            'firstname' => $request->get('firstname'),
            'lastname' => $request->get('lastname'),
            'jenis_kelamin' => $request->get('jenis_kelamin'),
            'email' => $request->get('email'),
            'number_phone' => $request->get('number_phone'),
            'last_edication' => $request->get('last_edication'),
            'nik' => $request->get('nik'),
            'nira_ppni' => $request->get('nira_ppni'),
            'nira_ipdi' => $request->get('nira_ipdi'),
            'pw_ipdi_provinsi' => $request->get('pw_ipdi_provinsi'),
            'pw_ipdi_kabupaten' => $request->get('pw_ipdi_kabupaten') == 'hidden' ? null : $request->get('pw_ipdi_kabupaten'),
            'nama_perusahaan' => $request->get('nama_perusahaan'),
            'center_dialisis' => $request->get('center_dialisis'),
            'alamat_center_dialisis_perusahaan' => $request->get('alamat_center_dialisis_perusahaan'),
            'address' => $request->get('address'),
            'city' => $request->get('city'),
            'country' => $request->get('country'),
            'postal' => $request->get('postal'),
            'about' => $request->get('about')
        ]);
        return back()->with('succes', 'Profile succesfully updated');
    }

    public function updatePhotoProfile(Request $request)
    {
        $request->validate([
            'profile_pic' => 'required|image|mimes:jpeg,jpg,png|max:1024',
        ]);

        if ($request->hasFile('profile_pic')) {
            $user = auth()->user(); // Assuming the user is authenticated
            $file = $request->file('profile_pic');
            $path = $file->store('profile_photos', 'public');

            // Optionally, delete old photo if exists
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            $user->profile = $path;
            $user->save();

            return response()->json(['message' => 'Profile photo updated successfully'], 200);
        }

        return response()->json(['message' => 'Failed to upload profile photo'], 400);
    }
    public function resetPassword()
    {
        
    }
}