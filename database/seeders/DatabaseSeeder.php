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
            'rfc' => null,
            'password' => bcrypt('123456'),
            'type' => 'A',
        ]);

        User::factory()->create([
            'name' => 'Pagos Frutioro',
            'email' => 'proveedoresfrutioro@hotmail.com',
            'rfc' => 'GFR090108SM5',
            'password' => bcrypt('Fruoro01'),
            'type' => 'A',
        ]);

        User::factory()->create([
            'name' => 'Provider1',
            'email' => null,
            'rfc' => 'HPA0406153A6',
            'password' => bcrypt('1234'),
            'type' => 'P',
        ]);

        User::factory()->create([
            'name' => 'Provider2',
            'email' => null,
            'rfc' => 'RIVC910116Q75',
            'password' => bcrypt('1234'),
            'type' => 'P',
        ]);

        User::factory()->create([
            'name' => 'Provider3',
            'email' => null,
            'rfc' => 'CEO110827HA7',
            'password' => bcrypt('1234'),
            'type' => 'P',
        ]);

        $this->call([
            OwnerSeeder::class,
            ProviderSeeder::class,
            InvoiceSeeder::class,
        ]);
    }
}
