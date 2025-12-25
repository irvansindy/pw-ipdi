<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('roles')->insert([
            [
                'id' => 1,
                'name' => 'superadmin',
                'guard_name' => 'web',
                'created_at' => '2024-06-11 16:25:55',
                'updated_at' => '2024-06-11 16:25:55'
            ],
            [
                'id' => 2,
                'name' => 'admin',
                'guard_name' => 'web',
                'created_at' => '2024-06-11 16:25:55',
                'updated_at' => '2024-06-11 16:25:55'
            ],
            [
                'id' => 3,
                'name' => 'user',
                'guard_name' => 'web',
                'created_at' => '2024-06-11 16:25:55',
                'updated_at' => '2024-06-11 16:25:55'
            ],
            [
                'id' => 4,
                'name' => 'admin-verificator',
                'guard_name' => 'web',
                'created_at' => '2025-06-02 22:23:53',
                'updated_at' => '2025-06-02 22:23:53'
            ],
            [
                'id' => 5,
                'name' => 'ketua',
                'guard_name' => 'web',
                'created_at' => '2025-06-02 22:24:01',
                'updated_at' => '2025-06-02 22:24:01'
            ],
            [
                'id' => 6,
                'name' => 'sekretaris',
                'guard_name' => 'web',
                'created_at' => '2025-06-02 22:24:09',
                'updated_at' => '2025-06-02 22:24:09'
            ],
            [
                'id' => 7,
                'name' => 'bendahara',
                'guard_name' => 'web',
                'created_at' => '2025-06-02 22:24:16',
                'updated_at' => '2025-06-02 22:24:16'
            ],
            [
                'id' => 8,
                'name' => 'admin-organisasi',
                'guard_name' => 'web',
                'created_at' => '2025-06-02 22:24:30',
                'updated_at' => '2025-06-02 22:24:30'
            ],
            [
                'id' => 9,
                'name' => 'admin-diklat',
                'guard_name' => 'web',
                'created_at' => '2025-06-02 22:24:38',
                'updated_at' => '2025-06-02 22:24:38'
            ],
            [
                'id' => 10,
                'name' => 'infokom',
                'guard_name' => 'web',
                'created_at' => '2025-06-02 22:24:47',
                'updated_at' => '2025-06-02 22:24:47'
            ]
        ]);

        DB::table('permissions')->insert([
            [
                'id' => 1,
                'name' => 'dashboard',
                'guard_name' => 'web',
                'created_at' => '2025-06-02 22:23:08',
                'updated_at' => '2025-06-02 22:23:08'
            ],
            [
                'id' => 2,
                'name' => 'users',
                'guard_name' => 'web',
                'created_at' => '2025-06-02 22:26:54',
                'updated_at' => '2025-06-02 22:26:54'
            ],
            [
                'id' => 3,
                'name' => 'roles',
                'guard_name' => 'web',
                'created_at' => '2025-06-02 22:27:19',
                'updated_at' => '2025-06-02 22:27:19'
            ],
            [
                'id' => 4,
                'name' => 'vision-mision',
                'guard_name' => 'web',
                'created_at' => '2025-06-02 22:27:49',
                'updated_at' => '2025-06-02 22:27:49'
            ],
            [
                'id' => 5,
                'name' => 'history',
                'guard_name' => 'web',
                'created_at' => '2025-06-02 22:35:08',
                'updated_at' => '2025-06-02 22:35:08'
            ],
            [
                'id' => 6,
                'name' => 'organization',
                'guard_name' => 'web',
                'created_at' => '2025-06-02 22:35:37',
                'updated_at' => '2025-06-02 22:35:37'
            ],
            [
                'id' => 7,
                'name' => 'news-management',
                'guard_name' => 'web',
                'created_at' => '2025-06-02 22:36:01',
                'updated_at' => '2025-06-02 22:36:01'
            ],
            [
                'id' => 8,
                'name' => 'iuran',
                'guard_name' => 'web',
                'created_at' => '2025-06-02 22:36:25',
                'updated_at' => '2025-06-02 22:36:25'
            ],
            [
                'id' => 9,
                'name' => 'transaction',
                'guard_name' => 'web',
                'created_at' => '2025-06-02 22:36:58',
                'updated_at' => '2025-06-02 22:36:58'
            ],
            [
                'id' => 10,
                'name' => 'attendance-audience',
                'guard_name' => 'web',
                'created_at' => '2025-06-02 22:38:02',
                'updated_at' => '2025-06-02 22:38:02'
            ],
            [
                'id' => 11,
                'name' => 'menu-permission',
                'guard_name' => 'web',
                'created_at' => '2025-06-02 22:38:32',
                'updated_at' => '2025-06-02 22:38:32'
            ]
        ]);

        DB::table('role_has_permissions')->insert([
            [
                'permission_id' => 1,
                'role_id' => 1
            ],
            [
                'permission_id' => 2,
                'role_id' => 1
            ],
            [
                'permission_id' => 3,
                'role_id' => 1
            ],
            [
                'permission_id' => 4,
                'role_id' => 1
            ],
            [
                'permission_id' => 5,
                'role_id' => 1
            ],
            [
                'permission_id' => 6,
                'role_id' => 1
            ],
            [
                'permission_id' => 7,
                'role_id' => 1
            ],
            [
                'permission_id' => 8,
                'role_id' => 1
            ],
            [
                'permission_id' => 9,
                'role_id' => 1
            ],
            [
                'permission_id' => 10,
                'role_id' => 1
            ],
            [
                'permission_id' => 11,
                'role_id' => 1
            ],
            [
                'permission_id' => 1,
                'role_id' => 2
            ],
            [
                'permission_id' => 2,
                'role_id' => 2
            ],
            [
                'permission_id' => 9,
                'role_id' => 2
            ],
            [
                'permission_id' => 1,
                'role_id' => 4
            ],
            [
                'permission_id' => 9,
                'role_id' => 4
            ],
            [
                'permission_id' => 10,
                'role_id' => 4
            ],
            [
                'permission_id' => 1,
                'role_id' => 5
            ],
            [
                'permission_id' => 2,
                'role_id' => 5
            ],
            [
                'permission_id' => 4,
                'role_id' => 5
            ],
            [
                'permission_id' => 5,
                'role_id' => 5
            ],
            [
                'permission_id' => 6,
                'role_id' => 5
            ],
            [
                'permission_id' => 1,
                'role_id' => 6
            ],
            [
                'permission_id' => 2,
                'role_id' => 6
            ],
            [
                'permission_id' => 4,
                'role_id' => 6
            ],
            [
                'permission_id' => 5,
                'role_id' => 6
            ],
            [
                'permission_id' => 6,
                'role_id' => 6
            ],
            [
                'permission_id' => 1,
                'role_id' => 7
            ],
            [
                'permission_id' => 2,
                'role_id' => 7
            ],
            [
                'permission_id' => 8,
                'role_id' => 7
            ],
            [
                'permission_id' => 1,
                'role_id' => 8
            ],
            [
                'permission_id' => 6,
                'role_id' => 8
            ],
            [
                'permission_id' => 1,
                'role_id' => 9
            ],
            [
                'permission_id' => 1,
                'role_id' => 10
            ],
            [
                'permission_id' => 2,
                'role_id' => 10
            ],
            [
                'permission_id' => 7,
                'role_id' => 10
            ]
        ]);
    }
}
