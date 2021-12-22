<?php

namespace App\Http\Controllers;

use App\Imports\GeneratePdfGRPartial;
use App\Imports\GeneratePdfImport;
use App\Imports\ToyotaSiswaImport;
use Illuminate\Http\Request;
use Excel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ImportController extends Controller
{
    public function index(Request $request)
    {
        set_time_limit(-1);

        $this->validate($request, [
            'xls_file' => 'required'
        ]);

        $file = $request->file('xls_file');

//        $visit_id = 23180;
//        $data['detail'] = DB::table('t_trx_schedule')
//            ->where('visit_id', $visit_id)
//            ->join('t_mtr_site_link', 't_mtr_site_link.link_id_int', 't_trx_schedule.link_id')
//            ->join('t_mtr_checklist_options', 't_mtr_checklist_options.option_id', DB::raw('t_trx_schedule.visit_type::integer'))
//            ->select('visit_id', 'option_label', 't_mtr_site_link.site_id_ne', 't_mtr_site_link.site_id_fe', DB::raw("(SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '204') AS ip_ne"), DB::raw("(SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '209') AS ip_fe"))
//            ->first();
//
//        dd($data['detail']);die();

//        Excel::import(new GeneratePdfImport, $file);
        Excel::import(new GeneratePdfGRPartial, $file);

        return back()->withMessage('Import Success : '.date('Y-m-d H:i:s'));
    }
}
