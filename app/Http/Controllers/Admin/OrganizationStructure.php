<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Helpers\FormatResponseJson;
use Illuminate\Support\Facades\DB;
use App\Models\Organization;

class OrganizationStructure extends Controller
{
    public function index()
    {
        return view('pages.admin.organization.index');
    }
    public function fetchOrganization()
    {
        try {
            $organization = Organization::all();
            return FormatResponseJson::success($organization,'data fetched successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 404);
        }
    }

    public function upload(Request $request)
    {
        try {
            $file = $request->file('input_organization');
            $file_name = time().'.'.$file->getClientOriginalExtension();
            $file->move(public_path('uploads/video'), $file_name);
            Organization::create([
                'video' => $file_name,
                'desc' => '',
                'additional_files' => $file->getClientOriginalName(),
            ]);
            return FormatResponseJson::success('success','successfully');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, 'Gagal melakukan upload bukti transaksi', 500);
        }
    }
}
