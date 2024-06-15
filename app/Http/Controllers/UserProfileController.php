<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\MasterProvinsi;
use App\Models\MasterKabupaten;
class UserProfileController extends Controller
{
    public function show()
    {
        $masterProvinsi = MasterProvinsi::all();
        $masterKabupaten = MasterKabupaten::all();
        return view('pages.user-profile', compact('masterProvinsi','masterKabupaten'));
    }

    public function update(Request $request)
    {
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

        auth()->user()->update([
            'username' => $request->get('username'),
            'firstname' => $request->get('firstname'),
            'lastname' => $request->get('lastname'),
            'email' => $request->get('email'),
            'number_phone' => $request->get('number_phone'),
            'last_edication' => $request->get('last_edication'),
            'nik' => $request->get('nik'),
            'nira_ppni' => $request->get('nira_ppni'),
            'nira_ipdi' => $request->get('nira_ipdi'),
            'pw_ipdi_provinsi' => $request->get('pw_ipdi_provinsi'),
            'pw_ipdi_kabupaten' => $request->get('pw_ipdi_kabupaten'),
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
}
