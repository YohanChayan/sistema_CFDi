<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        User::factory()->create([
            'name' => 'Administrador',
            'email' => 'administrador@frutioro.com',
            'password' => bcrypt('123456'),
        ]);

        $this->call([
            OwnerSeeder::class,
            ProviderSeeder::class,
        ]);
    }
}
