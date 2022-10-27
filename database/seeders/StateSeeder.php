<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('states')->insert([
            ['id'=>'1','name'=>'Aguascalientes','created_at'=>'2019-01-21 23:56:13','updated_at'=>'2019-01-21 23:56:13'],
            ['id'=>'2','name'=>'Baja California','created_at'=>'2019-01-21 23:56:13','updated_at'=>'2019-01-21 23:56:13'],
            ['id'=>'3','name'=>'Baja California Sur','created_at'=>'2019-01-21 23:56:13','updated_at'=>'2019-01-21 23:56:13'],
            ['id'=>'4','name'=>'Campeche','created_at'=>'2019-01-21 23:56:13','updated_at'=>'2019-01-21 23:56:13'],
            ['id'=>'5','name'=>'Coahuila','created_at'=>'2019-01-21 23:56:13','updated_at'=>'2019-01-21 23:56:13'],
            ['id'=>'6','name'=>'Colima','created_at'=>'2019-01-21 23:56:13','updated_at'=>'2019-01-21 23:56:13'],
            ['id'=>'7','name'=>'Chiapas','created_at'=>'2019-01-21 23:56:13','updated_at'=>'2019-01-21 23:56:13'],
            ['id'=>'8','name'=>'Chihuahua','created_at'=>'2019-01-21 23:56:13','updated_at'=>'2019-01-21 23:56:13'],
            ['id'=>'9','name'=>'Ciudad de México','created_at'=>'2019-01-21 23:56:13','updated_at'=>'2019-01-21 23:56:13'],
            ['id'=>'10','name'=>'Durango','created_at'=>'2019-01-21 23:56:13','updated_at'=>'2019-01-21 23:56:13'],
            ['id'=>'11','name'=>'Guanajuato','created_at'=>'2019-01-21 23:56:13','updated_at'=>'2019-01-21 23:56:13'],
            ['id'=>'12','name'=>'Guerrero','created_at'=>'2019-01-21 23:56:13','updated_at'=>'2019-01-21 23:56:13'],
            ['id'=>'13','name'=>'Hidalgo','created_at'=>'2019-01-21 23:56:13','updated_at'=>'2019-01-21 23:56:13'],
            ['id'=>'14','name'=>'Jalisco','created_at'=>'2019-01-21 23:56:13','updated_at'=>'2019-01-21 23:56:13'],
            ['id'=>'15','name'=>'México','created_at'=>'2019-01-21 23:56:13','updated_at'=>'2019-01-21 23:56:13'],
            ['id'=>'16','name'=>'Michoacán','created_at'=>'2019-01-21 23:56:13','updated_at'=>'2019-01-21 23:56:13'],
            ['id'=>'17','name'=>'Morelos','created_at'=>'2019-01-21 23:56:13','updated_at'=>'2019-01-21 23:56:13'],
            ['id'=>'18','name'=>'Nayarit','created_at'=>'2019-01-21 23:56:13','updated_at'=>'2019-01-21 23:56:13'],
            ['id'=>'19','name'=>'Nuevo León','created_at'=>'2019-01-21 23:56:13','updated_at'=>'2019-01-21 23:56:13'],
            ['id'=>'20','name'=>'Oaxaca','created_at'=>'2019-01-21 23:56:13','updated_at'=>'2019-01-21 23:56:13'],
            ['id'=>'21','name'=>'Puebla','created_at'=>'2019-01-21 23:56:13','updated_at'=>'2019-01-21 23:56:13'],
            ['id'=>'22','name'=>'Querétaro','created_at'=>'2019-01-21 23:56:13','updated_at'=>'2019-01-21 23:56:13'],
            ['id'=>'23','name'=>'Quintana Roo','created_at'=>'2019-01-21 23:56:13','updated_at'=>'2019-01-21 23:56:13'],
            ['id'=>'24','name'=>'San Luis Potosí','created_at'=>'2019-01-21 23:56:13','updated_at'=>'2019-01-21 23:56:13'],
            ['id'=>'25','name'=>'Sinaloa','created_at'=>'2019-01-21 23:56:13','updated_at'=>'2019-01-21 23:56:13'],
            ['id'=>'26','name'=>'Sonora','created_at'=>'2019-01-21 23:56:13','updated_at'=>'2019-01-21 23:56:13'],
            ['id'=>'27','name'=>'Tabasco','created_at'=>'2019-01-21 23:56:13','updated_at'=>'2019-01-21 23:56:13'],
            ['id'=>'28','name'=>'Tamaulipas','created_at'=>'2019-01-21 23:56:13','updated_at'=>'2019-01-21 23:56:13'],
            ['id'=>'29','name'=>'Tlaxcala','created_at'=>'2019-01-21 23:56:13','updated_at'=>'2019-01-21 23:56:13'],
            ['id'=>'30','name'=>'Veracruz','created_at'=>'2019-01-21 23:56:13','updated_at'=>'2019-01-21 23:56:13'],
            ['id'=>'31','name'=>'Yucatán','created_at'=>'2019-01-21 23:56:13','updated_at'=>'2019-01-21 23:56:13'],
            ['id'=>'32','name'=>'Zacatecas','created_at'=>'2019-01-21 23:56:13','updated_at'=>'2019-01-21 23:56:13']
        ]);
    }
}
