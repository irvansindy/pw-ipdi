<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\FormatResponseJson;
use App\Http\Controllers\Controller;
use App\Models\MasterDialisis;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class DialisisController extends Controller
{
    public function index()
    {
        $dialisis = MasterDialisis::orderBy('id', 'desc')->get();

        return view('pages.admin.dialisis.index', compact('dialisis'));
    }

    public function fetch()
    {
        try {
            $dialisis = MasterDialisis::orderBy('id', 'desc')->get();

            return FormatResponseJson::success($dialisis, 'Data dialisis berhasil diambil');
        } catch (\Exception $e) {
            return FormatResponseJson::error(null, $e->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255', 'unique:master_dialises,name'],
            ], [
                'name.required' => 'Nama dialisis wajib diisi.',
                'name.unique' => 'Nama dialisis sudah tersedia.',
            ]);

            $dialisis = MasterDialisis::create([
                'name' => trim($request->name),
            ]);

            if ($request->expectsJson()) {
                return FormatResponseJson::success($dialisis, 'Data dialisis berhasil ditambahkan');
            }

            return redirect()->route('dialisis')->with('success', 'Data dialisis berhasil ditambahkan.');
        } catch (ValidationException $e) {
            if ($request->expectsJson()) {
                return FormatResponseJson::error(null, $e->errors(), 422);
            }

            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return FormatResponseJson::error(null, $e->getMessage(), 500);
            }

            return redirect()->back()->with('error', 'Gagal menambahkan data dialisis.')->withInput();
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'name' => ['required', 'string', 'max:255', 'unique:master_dialises,name,' . $id],
            ], [
                'name.required' => 'Nama dialisis wajib diisi.',
                'name.unique' => 'Nama dialisis sudah tersedia.',
            ]);

            $dialisis = MasterDialisis::findOrFail($id);
            $dialisis->update([
                'name' => trim($request->name),
            ]);

            if ($request->expectsJson()) {
                return FormatResponseJson::success($dialisis, 'Data dialisis berhasil diperbarui');
            }

            return redirect()->route('dialisis')->with('success', 'Data dialisis berhasil diperbarui.');
        } catch (ValidationException $e) {
            if ($request->expectsJson()) {
                return FormatResponseJson::error(null, $e->errors(), 422);
            }

            return redirect()->back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return FormatResponseJson::error(null, $e->getMessage(), 500);
            }

            return redirect()->back()->with('error', 'Gagal memperbarui data dialisis.')->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $dialisis = MasterDialisis::findOrFail($id);
            $dialisis->delete();

            return redirect()->route('dialisis')->with('success', 'Data dialisis berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data dialisis.');
        }
    }
}
