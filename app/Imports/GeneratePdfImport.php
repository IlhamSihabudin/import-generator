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
                        ->join('t_mtr_site_link', 't_mtr_site_link.link_id_int', 't_trx_schedule.link_id')
                        ->select('visit_id', 't_trx_schedule.visit_type as visit_type', 't_mtr_site_link.site_id_ne as site_id_ne', 't_mtr_site_link.site_id_fe as site_id_fe')
                        ->first();

                    $url = 'http://10.0.5.37/report/index/'.strtr(rtrim(base64_encode($query->visit_id), '='), '+/', '-_').'/'.strtr(rtrim(base64_encode($query->visit_type), '='), '+/', '-_');

                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_HEADER, 0);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt($ch, CURLOPT_URL, $url);
                    $data = curl_exec($ch);
                    curl_close($ch);

                    $data = file_put_contents(public_path('pdf').'/'.date('Y-m-d_His').'_'.$query->visit_id.'_'.$query->site_id_ne.'_'.$query->site_id_fe.'.pdf', $data);
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
