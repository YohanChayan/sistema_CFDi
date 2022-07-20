<?php

namespace Database\Seeders;

use App\Models\Provider;
use Illuminate\Database\Seeder;

class ProviderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Provider::factory()->create([
            'rfc' => 'HPA0406153A6',
            'nombre' => 'HORTALIZAS LA PALMITA',
            'password' => '1234',
        ]);
    }
}
