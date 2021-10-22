<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;

class GeneratePdfImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        set_time_limit(-1);
        foreach ($collection as $row)
        {
            if(!empty($row[0])){
                try {
                    $query = DB::table('t_trx_schedule')
                        ->where('visit_id', $row[0])
                        ->select('visit_id', 'visit_type')
                        ->first();

                    $url = 'http://10.0.5.37/report/index/'.base64_encode($query->visit_id).'/'.base64_encode($query->visit_type);

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_URL, $url);
                    $data = curl_exec($ch);
                    curl_close($ch);

                    $data = file_put_contents(public_path('pdf').'/'.date('Ymd_His').'_'.$query->visit_id.'_'.$query->visit_type.'.pdf', $data);
                }catch (\Exception $e){
                    DB::connection('mysql2')->table('pdf_error')->insert([
                        'visit_id' => $row[0],
                        'message' => $e->getMessage()
                    ]);
                }
            }
        }
    }
}
