<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasTable('menus')) {
            return;
        }

        DB::table('menus')->insert([
            [
                'name' => 'Dialisis',
                'url' => '/dialisis',
                'icon' => '<i class="fas fa-hospital text-dark text-sm opacity-10"></i>',
                'permission' => null,
                'type' => 'link',
                'parent_id' => null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('menus')) {
            DB::table('menus')->where('url', '/dialisis')->delete();
        }
    }
};
