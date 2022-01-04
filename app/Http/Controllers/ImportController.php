<?php

namespace App\Http\Controllers;

use App\Exports\SummaryExport;
use App\Imports\GeneratePdfGRPartial;
use App\Imports\GeneratePdfImport;
use App\Imports\SummaryPCQCImport;
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

        Excel::import(new GeneratePdfGRPartial, $file);

        return back()->withMessage('Import Success : '.date('Y-m-d H:i:s'));
    }

    public function excel(Request $request)
    {
        set_time_limit(-1);

        $this->validate($request, [
            'xls_file' => 'required'
        ]);

        $file = $request->file('xls_file');

        Excel::import(new SummaryPCQCImport(), $file);

        return back()->withMessage('Import Excel Success : '.date('Y-m-d H:i:s'));
    }
}
