<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MitraSeeder extends Seeder
{
    public function run()
    {
        DB::table('mitras')->insert([
            [
                'nama_usaha'   => 'Tambak Udang',
                'nama_pemilik' => 'Rangga Ridwan Nova',
                'no_hp'        => '6283894100924',
                'nik'          => '4545645645646545',
                'jenis_usaha'  => 'Jasa',
                'alamat_usaha' => 'Subang',
                'sku'          => 'dokumen_mitra/sku/contoh1.pdf',
                'dusun'        => 'Subang',
                'status'       => 'aktif',
                'user_id'      => 7, // Sesuaikan dengan id user yang ada
            ],
            [
                'nama_usaha'   => 'Peyek',
                'nama_pemilik' => 'Rangga',
                'no_hp'        => '6285185296225',
                'nik'          => '1311425263432432',
                'jenis_usaha'  => 'Produk',
                'alamat_usaha' => 'Subang, Subang, Subang',
                'sku'          => 'dokumen_mitra/sku/contoh2.pdf',
                'dusun'        => 'Subang',
                'status'       => 'aktif',
                'user_id'      => 9,
            ],
        ]);
    }
}
