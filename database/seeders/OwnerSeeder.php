<?php

namespace Database\Seeders;

use App\Models\Owner;
use Illuminate\Database\Seeder;

class OwnerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Owner::factory()->create([
            'rfc' => 'GFR090108SM5',
            'nombre' => 'GRUPO FRUTIORO SPR DE RL',
        ]);

        Owner::factory()->create([
            'rfc' => 'PPA180626CC4',
            'nombre' => 'PARADISE PAPAYA SPR DE RL',
        ]);

        Owner::factory()->create([
            'rfc' => 'FME100624J73',
            'nombre' => 'FRUTIORO MEXICO S.P.R. DE R.L.',
        ]);

        Owner::factory()->create([
            'rfc' => 'HPA0406153A6',
            'nombre' => 'HORTALIZAS LA PALMITA',
        ]);

        Owner::factory()->create([
            'rfc' => 'PCR150716ET3',
            'nombre' => 'PAPAYAS CAMPO REAL',
        ]);
    }
}
