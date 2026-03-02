<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    // database/seeders/DatabaseSeeder.php
        public function run(): void {
    \App\Models\User::factory()->create([
        'name' => 'Admin Lutech',
        'email' => 'admin@lutech.com',
        'role' => 'admin',
    ]);
    \App\Models\Customer::factory(10)->hasTickets(3)->create(); // Magic method dari Factory
}

}
