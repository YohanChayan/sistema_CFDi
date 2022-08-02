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
            'rfc' => 'admin1NN1',
            'email' => 'administrador@frutioro.com',
            'password' => bcrypt('123456'),
            'type' => 'A',
        ]);

        User::factory()->create([
            'name' => 'Provider1',
            'rfc' => 'provider1NN1',
            'password' => bcrypt('123456'),
            'type' => 'P',
        ]);

        $this->call([
            OwnerSeeder::class,
            ProviderSeeder::class,
            InvoiceSeeder::class,
        ]);
    }
}
