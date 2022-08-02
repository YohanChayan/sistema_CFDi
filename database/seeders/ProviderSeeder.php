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
            'password' => bcrypt('1234'),
        ]);

        Provider::factory()->create([
            'rfc' => 'RIVC910116Q75',
            'nombre' => 'CRISTINA IRAIS RIVERO VAZQUEZ',
            'password' => bcrypt('1234'),
        ]);

        Provider::factory()->create([
            'rfc' => 'CEO110827HA7',
            'nombre' => 'COMERCIALIZADORA DE EMPAQUES DE OCCIDENTE S DE RL DE CV',
            'password' => bcrypt('1234'),
        ]);
    }
}
