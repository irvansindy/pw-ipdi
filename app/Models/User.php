<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Models\Role;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
// use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, SoftDeletes;
    protected $table = "users";
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'firstname',
        'lastname',
        'profile',
        'jenis_kelamin',
        'email',
        'number_phone',
        'last_edication',
        'nik',
        'nira_ppni',
        'nira_ipdi',
        'pw_ipdi_provinsi',
        'pw_ipdi_kabupaten',
        'nama_perusahaan',
        'center_dialisis',
        'alamat_center_dialisis_perusahaan',
        'password',
        'address',
        'city',
        'country',
        'postal',
        'about',
        'status',
        'sertifikat_file',
        'sertifikat_start_date',
        'sertifikat_end_date',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Always encrypt the password when it is updated.
     *
     * @param $value
    * @return string
    */
    public function setPasswordAttribute($value) : void
    {
        $this->attributes['password'] = bcrypt($value);
    }
    public function dialisis() {
        return $this->hasOne(MasterDialisis::class, 'id', 'center_dialisis');
    }
    public function iuranForDashboard(){
        return $this->hasOne(IuranTransactionDetail::class, 'user_id', 'id');
    }

    public function hasCompleteBiodata(): bool
    {
        $requiredFields = [
            'username',
            'email',
            'jenis_kelamin',
            'number_phone',
            'nik',
            'nira_ppni',
            'nira_ipdi',
            // 'pw_ipdi_provinsi',
            // 'pw_ipdi_kabupaten',
            // 'nama_perusahaan',
            'center_dialisis',
            'alamat_center_dialisis_perusahaan',
            // 'address',
            // 'city',
            // 'country',
            // 'postal',
        ];

        foreach ($requiredFields as $field) {
            if (!filled($this->{$field})) {
                return false;
            }
        }

        return true;
    }
}
