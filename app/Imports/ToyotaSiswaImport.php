<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class ToyotaSiswaImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row)
        {
            if (!DB::table('internship')->where('no_induk', $row[0])->exists()){
                DB::table('internship')
                    ->insert([
                        'no_induk' => $row[0],
                        'sekolah_id' => $row[1],
                        'nama' => $row[2],
                        'kategori' => $row[3],
                        'kelas' => $row[4],
                        'unit' => $row[5],
                        'email1' => $row[6],
                        'email2' => @$row[7],
                        'email3' => @$row[8]
                    ]);
            }
        }
    }
}
