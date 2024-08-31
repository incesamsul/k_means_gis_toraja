<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WilayahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('wilayah')->insert([
            ['id' => 1, 'nama_wilayah' => 'SOPAI', 'lokasi' => 'Sopai', 'koordinat' => NULL],
            ['id' => 2, 'nama_wilayah' => 'SOPAI', 'lokasi' => 'Langda', 'koordinat' => NULL],
            ['id' => 3, 'nama_wilayah' => 'SOPAI', 'lokasi' => 'Salu Sarre', 'koordinat' => NULL],
            ['id' => 4, 'nama_wilayah' => 'SOPAI', 'lokasi' => 'Tombang Langda', 'koordinat' => NULL],
            ['id' => 5, 'nama_wilayah' => 'SOPAI', 'lokasi' => 'Marante', 'koordinat' => NULL],
            ['id' => 6, 'nama_wilayah' => 'KESU', 'lokasi' => 'Angin-angin', 'koordinat' => '[[-6.20306206826986,106.84433258360674],[-6.212746726121194,106.8544615061776],[-6.21398396032824,106.84055569722437]]'],
            ['id' => 7, 'nama_wilayah' => 'KESU', 'lokasi' => 'Tallu Lolo', 'koordinat' => NULL],
            ['id' => 8, 'nama_wilayah' => 'KESU', 'lokasi' => 'Tadongkon', 'koordinat' => NULL],
            ['id' => 9, 'nama_wilayah' => 'KESU', 'lokasi' => 'Sangbua\'', 'koordinat' => NULL],
            ['id' => 10, 'nama_wilayah' => 'KESU', 'lokasi' => 'Rinding Batu', 'koordinat' => NULL],
            ['id' => 11, 'nama_wilayah' => 'SANGGALANGI', 'lokasi' => 'Sanggalangi', 'koordinat' => NULL],
            ['id' => 12, 'nama_wilayah' => 'SANGGALANGI', 'lokasi' => 'La\'bo\'', 'koordinat' => NULL],
            ['id' => 13, 'nama_wilayah' => 'SANGGALANGI', 'lokasi' => 'Tallung Penanian', 'koordinat' => NULL],
            ['id' => 14, 'nama_wilayah' => 'SANGGALANGI', 'lokasi' => 'Tandung La\'bo\'', 'koordinat' => NULL],
            ['id' => 15, 'nama_wilayah' => 'SANGGALANGI', 'lokasi' => 'Paepalean', 'koordinat' => NULL],
            ['id' => 16, 'nama_wilayah' => 'SANGGALANGI', 'lokasi' => 'Kambata', 'koordinat' => NULL],
            ['id' => 17, 'nama_wilayah' => 'SANGGALANGI', 'lokasi' => 'Tandung Bua', 'koordinat' => NULL],
            ['id' => 18, 'nama_wilayah' => 'SANGGALANGI', 'lokasi' => 'Lappak', 'koordinat' => NULL],
            ['id' => 19, 'nama_wilayah' => 'SANGGALANGI', 'lokasi' => 'Tandung Bua', 'koordinat' => NULL],
            ['id' => 20, 'nama_wilayah' => 'SANGGALANGI', 'lokasi' => 'Rinding Batu', 'koordinat' => NULL],
            ['id' => 21, 'nama_wilayah' => 'SANGGALANGI', 'lokasi' => 'Baringin', 'koordinat' => NULL],
            ['id' => 22, 'nama_wilayah' => 'SANGGALANGI', 'lokasi' => 'Pakolo', 'koordinat' => NULL],
            ['id' => 23, 'nama_wilayah' => 'SANGGALANGI', 'lokasi' => 'Tandung Bua', 'koordinat' => NULL],
            ['id' => 24, 'nama_wilayah' => 'SANGGALANGI', 'lokasi' => 'Bau Bau', 'koordinat' => NULL],
            ['id' => 25, 'nama_wilayah' => 'SANGGALANGI', 'lokasi' => 'Saluak', 'koordinat' => NULL],
            ['id' => 26, 'nama_wilayah' => 'SANGGALANGI', 'lokasi' => 'Sanggalangi', 'koordinat' => NULL],
            ['id' => 27, 'nama_wilayah' => 'SANGGALANGI', 'lokasi' => 'Kondong', 'koordinat' => NULL],
            ['id' => 28, 'nama_wilayah' => 'SANGGALANGI', 'lokasi' => 'Salubata', 'koordinat' => NULL],
            ['id' => 29, 'nama_wilayah' => 'SANGGALANGI', 'lokasi' => 'La\'bo\'', 'koordinat' => NULL],
            ['id' => 30, 'nama_wilayah' => 'SANGGALANGI', 'lokasi' => 'Tandung La\'bo\'', 'koordinat' => NULL],
        ]);
    }
}
