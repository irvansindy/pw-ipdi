<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\WithHeadings;
class ExportUser implements FromCollection,WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // 'username',
        // 'email',
        // 'nik',
        // 'nira_ppni',
        // 'nira_ipdi',
        // 'nama_perusahaan',
        // 'center_dialisis',
        // 'alamat_center_dialisis_perusahaan',
        // 'number_phone',
        // 'last_edication',
        // 'address',
        // 'city',
        // 'Provinsi',
        // 'country',
        // 'postal'
        $list_users = DB::select(
            'SELECT a.username, a.email, a.nik, a.nira_ppni, a.nira_ipdi, a.nama_perusahaan, a.center_dialisis, a.alamat_center_dialisis_perusahaan, a.number_phone, a.last_edication, a.address, a.city, b.name, a.country, a.postal
            FROM users a LEFT JOIN master_provinsi b ON a.pw_ipdi_provinsi = b.id
            LEFT JOIN model_has_roles c ON a.id = c.model_id
            LEFT JOIN roles d ON c.role_id = d.id
            WHERE d.name = "user"'
        );
        return collect($list_users);
    }
    public function headings(): array
    {
        return [
            'Nama Lengkap',
            'Email',
            'NIK',
            'NIRA PPNI',
            'NIRA IPDI',
            'Nama Perusahaan',
            'Center Dialisis',
            'Alamat Center Dialisis/Perusahaan',
            'No. Hp',
            'Pendidikan Terakhir',
            'Alamat Tempat Tinggal',
            'Kota',
            'Provinsi',
            'Negara',
            'Kode Pos',
        ];
    }
}
