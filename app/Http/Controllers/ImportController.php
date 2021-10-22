<?php

namespace App\Http\Controllers;

use App\Imports\GeneratePdfImport;
use App\Imports\ToyotaSiswaImport;
use Illuminate\Http\Request;
use Excel;
use Illuminate\Support\Facades\Storage;

class ImportController extends Controller
{
    public function index(Request $request)
    {
        set_time_limit(-1);
        $path = public_path('pdf/');

        $url = "http://eatp.alita.id/report/index/Mjg4/MTA5";

//        $url = "https://media.neliti.com/media/publications/249244-none-837c3dfb.pdf";
//        $url = 'https://www.te.com/commerce/DocumentDelivery/DDEController?Action=srchrtrv&DocNm=5223955&DocType=Customer+Drawing&DocLang=English';

//        // Create a stream
//        $opts = [
//            "http" => [
//                "method" => "GET",
//                "header" => "Host: eatp.alita.id\r\n"
//                    . "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/94.0.4606.81 Safari/537.36\r\n"
//                    . "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9\r\n"
//                    . "Accept-Language: id-ID,id;q=0.9,en-US;q=0.8,en;q=0.7\r\n"
//                    . "Accept-Encoding: gzip, deflate\r\n"
//            ],
//        ];
//
//        $context = stream_context_create($opts);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        $data = curl_exec($ch);
        curl_close($ch);

        $data = file_put_contents(public_path('pdf').'/filename.pdf', $data);

        Storage::disk('public')->put('filename.pdf', $data);
        die();
        $this->validate($request, [
            'xls_file' => 'required'
        ]);

        $file = $request->file('xls_file');

        Excel::import(new GeneratePdfImport, $file);

        return back();
    }
}
