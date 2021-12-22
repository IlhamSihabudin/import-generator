<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use PDF;

class GeneratePdfGRPartial implements ToCollection
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
                    $visit_id = $row[0];
                    $data = [];
                    $data['detail'] = DB::table('t_trx_schedule')
                        ->where('visit_id', $visit_id)
                        ->join('t_mtr_site_link', 't_mtr_site_link.link_id_int', 't_trx_schedule.link_id')
                        ->join('t_mtr_checklist_options', 't_mtr_checklist_options.option_id', DB::raw('t_trx_schedule.visit_type::integer'))
                        ->select('visit_id', 't_mtr_site_link.link_id', 'option_label', 't_mtr_site_link.site_id_ne', 't_mtr_site_link.site_id_fe', DB::raw("(SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '204') AS ip_ne"), DB::raw("(SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '209') AS ip_fe"))
                        ->first();

                    $data['checklist'] = DB::table('t_trx_checklist_result_photo')->select(
                        DB::raw("(SELECT photo_name FROM t_trx_checklist_result_photo WHERE visit_id = '".$visit_id."' AND checklist_id = '3816') AS current_ip_ne"),
                        DB::raw("(SELECT photo_name FROM t_trx_checklist_result_photo WHERE visit_id = '".$visit_id."' AND checklist_id = '257') AS current_ip_fe"),
                        DB::raw("(SELECT photo_name FROM t_trx_checklist_result_photo WHERE visit_id = '".$visit_id."' AND checklist_id = '3858') AS inventory_idu_ne"),
                        DB::raw("(SELECT photo_name FROM t_trx_checklist_result_photo WHERE visit_id = '".$visit_id."' AND checklist_id = '278') AS inventory_idu_fe"),
                        DB::raw("(SELECT photo_name FROM t_trx_checklist_result_photo WHERE visit_id = '".$visit_id."' AND checklist_id = '3660') AS lisence_inventory_ne"),
                        DB::raw("(SELECT photo_name FROM t_trx_checklist_result_photo WHERE visit_id = '".$visit_id."' AND checklist_id = '3543') AS lisence_inventory_fe"),
                        DB::raw("(SELECT photo_name FROM t_trx_checklist_result_photo WHERE visit_id = '".$visit_id."' AND checklist_id = '3860') AS inventory_odu_ne"),
                        DB::raw("(SELECT photo_name FROM t_trx_checklist_result_photo WHERE visit_id = '".$visit_id."' AND checklist_id = '279') AS inventory_odu_fe"),
                        DB::raw("(SELECT photo_name FROM t_trx_checklist_result_photo WHERE visit_id = '".$visit_id."' AND checklist_id = '3850') AS rsl_ne"),
                        DB::raw("(SELECT photo_name FROM t_trx_checklist_result_photo WHERE visit_id = '".$visit_id."' AND checklist_id = '273') AS rsl_fe"),
                        DB::raw("(SELECT photo_name FROM t_trx_checklist_result_photo WHERE visit_id = '".$visit_id."' AND checklist_id = '3654') AS dn_ne"),
                        DB::raw("(SELECT photo_name FROM t_trx_checklist_result_photo WHERE visit_id = '".$visit_id."' AND checklist_id = '3636') AS dn_fe")
                    )->first();

//                    dd(!empty($data['checklist']->lisence_inventory_ne) ? "http://10.0.5.37/".$data['checklist']->inventory_idu_fe : asset('no-image.png'));
                    $pdf = PDF::loadView('pdf.gr_view', $data);
//                    $pdf->download($data['detail']->site_id_ne.'_'.$data['detail']->site_id_fe.'.pdf');
                    file_put_contents(public_path('pdf_gr').'/'.$data['detail']->visit_id.'_'.$data['detail']->site_id_ne.'--'.$data['detail']->site_id_fe.'.pdf', $pdf->download()->getOriginalContent());
                }catch (\Exception $e){
                    dd($e);
                    DB::connection('mysql2')->table('pdf_error')->insert([
                        'visit_id' => $row[0],
                        'message' => $e->getMessage()
                    ]);
                }
            }
        }
    }
}
