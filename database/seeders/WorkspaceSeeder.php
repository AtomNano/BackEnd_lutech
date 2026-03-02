<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Workspace;
use Illuminate\Database\Seeder;

class WorkspaceSeeder extends Seeder
{
    /**
     * Buat satu workspace default "Kasir Lutech" untuk setiap user yang sudah ada.
     * Jalankan sekali setelah migration: php artisan db:seed --class=WorkspaceSeeder
     */
    public function run(): void
    {
        User::all()->each(function (User $user) {
            // Skip jika user sudah punya workspace
            if (Workspace::where('user_id', $user->id)->exists()) {
                return;
            }

            Workspace::create([
                'user_id' => $user->id,
                'name' => 'Kasir Lutech',
                'type' => 'business',
                'is_default' => true,
            ]);
        });

        $this->command->info('WorkspaceSeeder: workspace default dibuat untuk semua user.');
    }
}
