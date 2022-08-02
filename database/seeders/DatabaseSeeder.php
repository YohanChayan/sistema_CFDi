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
        //Usuario 1
        User::factory()->create([
            'name' => 'Administrador',
            'rfc' => 'rfc1',
            'email' => 'administrador@frutioro.com',
            'password' => bcrypt('123456'),
            'type' => 'A',
        ]);

        //Usuario 2
        User::factory()->create([
            'name' => 'Pagos Frutioro',
            'rfc' => 'GFR090108SM5',
            'email' => 'proveedoresfrutioro@hotmail.com',
            'password' => bcrypt('Fruoro01'),
            'type' => 'A',
        ]);

        //Usuario 3
        User::factory()->create([
            'name' => 'Provider1',
            'rfc' => 'HPA0406153A6',
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
