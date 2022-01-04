<?php

namespace App\Imports;

use App\Exports\SummaryExport;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Excel;

class SummaryPCQCImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        set_time_limit(-1);
        $head = [
            'No', //fill
            'EWO/1/NBWO', //fill
            'EWO-2',
            'Work Order Type',
            'Project Type',
            'Description',
            'Company Name',
            'Installer Name', //fill
            'Contact Number',
            'Area', //fill
            'Link', //fill
            'Visit ID', //fill
            'Site & IDU Indeks NE', //fill
            'NE Code',
            'NE Name', //fill
            'IP of NE', //fill
            'Subnet Mask NE', //fill
            'Gateway NE', //fill
            'Gateway Type NE',
            'Site & IDU Indeks FE', //fill
            'FE Code',
            'FE Name',
            'IP of FE', //fill
            'Subnet Mask FE', //fill
            'Gateway FE', //fill
            'Gateway Type FE',
            'Date',
            'Name',
            'Status',
            'IDU Type @NE',
            'Modem Slot @NE',
            'Electrical Port Status NE',
            'Clock Sync. @NE',
            'S/N IDU @NE', //fill
            'S/N Modem @NE',
            'RXL on Link Budget (NE)', //fill
            'RXL on Actual (NE)', //fill
            'IDU Type @FE',
            'Modem Slot @FE',
            'Electrical Port Status FE',
            'Clock Sync. @FE',
            'S/N IDU @FE', //fill
            'S/N Modem @FE',
            'RXL on Link Budget (FE)', //fill
            'RXL on Actual (FE)', //fill
            'ETH Bandwidth [Mbps]', //fill
            'TX Power', //fill
            'Sub Band', //fill
            'Channel Spacing (MHz)', //fill
            'Modulation', //fill
            'Frek. NE 1', //fill
            'Frek. NE 2', //fill
            'Frek. NE 3', //fill
            'Frek. NE 4', //fill
            'Frek. FE 1', //fill
            'Frek. FE 2', //fill
            'Frek. FE 3', //fill
            'Frek. FE 4', //fill
            'Config', //fill
            'Firmware Version NE | FE',
            'DN',
            'LB',
            'Lat NE', //fill
            'Long NE', //fill
            'Lat FE', //fill
            'Long FE', //fill
            'Tower Leg NE|FE', //fill
            'Azimuth (°) NE|FE', //fill
            'Height NE (m)', //fill
            'Feeder Length NE (m)', //fill
            'Height FE (m)', //fill
            'Feeder Length FE (m)', //fill
            'SN ODU NE', //fill
            'RAU Weight NE (kg)', //fill
            'SN ODU FE', //fill
            'RAU Weight FE (kg)', //fill
            'SN Ant NE', //fill
            'SN Ant FE', //fill
            'Ant Type NE', //fill
            'Merk ANT NE', //fill
            'ANT Gain NE', //fill
            'Ant Type FE', //fill
            'Merk ANT FE', //fill
            'ANT Gain FE', //fill
            'Ant Diameter NE', //fill
            'Ant Diameter FE', //fill
            'Polarization', //fill
            'DN',
            'LB'
        ];
        foreach ($collection as $key => $row)
        {
            if(!empty($row[0])){
                try {
                    $visit_id = $row[0];
                    $datas = [];

                    $datas[] = DB::table('t_trx_schedule')
                        ->where('t_trx_schedule.visit_id', $visit_id)
                        ->leftJoin('t_trx_schedule_sign', 't_trx_schedule.visit_id', 't_trx_schedule_sign.visit_id')
                        ->leftJoin('t_mtr_region', 't_trx_schedule.region_id', 't_mtr_region.region_id')
                        ->leftJoin('t_trx_po_link', 't_trx_po_link.link_id_int', 't_trx_schedule.link_id')
                        ->leftJoin('t_mtr_site_link', 't_mtr_site_link.link_id_int', 't_trx_schedule.link_id')
                        ->leftJoin('t_mtr_checklist_options', 't_mtr_checklist_options.option_id', DB::raw('t_trx_schedule.visit_type::integer'))
                        ->select(
                            DB::raw( $key+1 . ' as no'),
                            't_trx_po_link.ewo_no',
                            DB::raw('NULL AS ewo_2'),
                            DB::raw('NULL AS wo_type'),
                            DB::raw('NULL AS project_type'),
                            DB::raw('NULL AS desc'),
                            DB::raw('NULL AS company_name'),
                            DB::raw('(SELECT usr.user_name FROM t_trx_schedule_sign AS sch LEFT JOIN t_mtr_user AS usr ON sch.user_id = usr.user_id WHERE sign_type = 1 AND visit_id = '.$visit_id.' ORDER BY sch.created_on DESC LIMIT 1) AS installer_name'),
                            DB::raw('NULL AS contact_number'),
                            DB::raw('t_mtr_region.region_code'),
                            't_mtr_site_link.link_id',
                            't_trx_schedule.visit_id',
                            't_mtr_site_link.site_id_ne',
                            DB::raw('NULL AS ne_code'),
                            DB::raw("(SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '388') AS ne_name"),
                            DB::raw("(SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '204') AS ip_ne"),
                            DB::raw("(SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '391') AS subnet_ne"),
                            DB::raw("(SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '392') AS geteway_ne"),
                            DB::raw('NULL AS gateway_type_ne'),
                            't_mtr_site_link.site_id_fe',
                            DB::raw('NULL AS fe_code'),
                            DB::raw("(SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '393') AS fe_name"),
                            DB::raw("(SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '209') AS ip_fe"),
                            DB::raw("(SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '210') AS subnet_fe"),
                            DB::raw("(SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '397') AS gateway_fe"),
                            DB::raw('NULL AS gateway_type_fe'),
                            DB::raw('NULL AS date'),
                            DB::raw('NULL AS name'),
                            DB::raw('NULL AS status'),
                            DB::raw('NULL AS idu_type_ne'),
                            DB::raw('NULL AS modem_slot_ne'),
                            DB::raw('NULL AS port_status_ne'),
                            DB::raw('NULL AS clock_sync_ne'),
                            DB::raw("(SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '117') AS sn_idu_ne"),
                            DB::raw('NULL AS sn_modem_ne'),
                            DB::raw("(SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '369') AS rxl_link_budget_ne"),
                            DB::raw("CONCAT(
                                (SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '187'),
                                ';',
                                (SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '373'),
                                ';',
                                (SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '188'),
                                ';',
                                (SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '374'),
                                ';'
                                )
                                AS rxl_on_actual_ne"),
                            DB::raw('NULL AS idu_type_fe'),
                            DB::raw('NULL AS modem_slot_fe'),
                            DB::raw('NULL AS port_status_fe'),
                            DB::raw('NULL AS clock_sync_fe'),
                            DB::raw("(SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '129') AS sn_idu_fe"),
                            DB::raw('NULL AS sn_modem_fe'),
                            DB::raw("(SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '371') AS rxl_link_budget_fe"),
                            DB::raw("CONCAT(
                                (SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '375'),
                                ';',
                                (SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '189'),
                                ';',
                                (SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '190'),
                                ';',
                                (SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '376'),
                                ';'
                                )
                                AS rxl_on_actual_fe"),
                            DB::raw("(SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '167') AS eth_bandwidth"),
                            DB::raw("CONCAT(
                                (SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '191'),
                                ';',
                                (SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '377'),
                                ';',
                                (SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '377'),
                                ';',
                                (SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '192'),
                                ';',
                                (SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '193'),
                                ';',
                                (SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '379'),
                                ';',
                                (SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '194'),
                                ';',
                                (SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '380'),
                                ';'
                                )
                                AS tx_power"),
                            DB::raw("CONCAT(
                                (SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '153'),
                                ';',
                                (SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '154'),
                                ';',
                                (SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '169'),
                                ';',
                                (SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '170'),
                                ';'
                                )
                                AS sub_band"),
                            DB::raw("CONCAT(
                                (SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '159'),
                                ';',
                                (SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '160'),
                                ';'
                                )
                                AS channel_spacing"),
                            DB::raw("CONCAT(
                                (SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '163'),
                                ';',
                                (SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '164'),
                                ';'
                                )
                                AS modulation"),
                            DB::raw("CONCAT(
                                (SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '155'),
                                ';',
                                (SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '156'),
                                ';'
                                )
                                AS frek_ne1"),
                            DB::raw("CONCAT(
                                (SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '155'),
                                ';',
                                (SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '156'),
                                ';'
                                )
                                AS frek_ne2"),
                            DB::raw("CONCAT(
                                (SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '155'),
                                ';',
                                (SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '156'),
                                ';'
                                )
                                AS frek_ne3"),
                            DB::raw("CONCAT(
                                (SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '155'),
                                ';',
                                (SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '156'),
                                ';'
                                )
                                AS frek_ne4"),
                            DB::raw("CONCAT(
                                (SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '171'),
                                ';',
                                (SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '172'),
                                ';'
                                )
                                AS frek_fe1"),
                            DB::raw("CONCAT(
                                (SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '171'),
                                ';',
                                (SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '172'),
                                ';'
                                )
                                AS frek_fe2"),
                            DB::raw("CONCAT(
                                (SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '171'),
                                ';',
                                (SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '172'),
                                ';'
                                )
                                AS frek_fe3"),
                            DB::raw("CONCAT(
                                (SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '171'),
                                ';',
                                (SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '172'),
                                ';'
                                )
                                AS frek_fe4"),
                            DB::raw("(SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '195') AS config"),
                            DB::raw('NULL AS firmware_version'),
                            DB::raw('NULL AS dn'),
                            DB::raw('NULL AS lb'),
                            DB::raw("(SELECT latitude FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '3973') AS lat_ne"),
                            DB::raw("(SELECT longitude FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '3973') AS long_ne"),
                            DB::raw("(SELECT latitude FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '3974') AS lat_fe"),
                            DB::raw("(SELECT longitude FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '3974') AS long_fe"),
                            DB::raw("CONCAT(
                                (SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '3047'),
                                ';',
                                (SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '3064'),
                                ';'
                                )
                                AS tower_leg"),
                            DB::raw("CONCAT(
                                (SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '3222'),
                                ';',
                                (SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '3228'),
                                ';'
                                )
                                AS azimuth"),
                            DB::raw("(SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '3212') AS height_ne"),
                            DB::raw("(SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '3045') AS feeder_ne"),
                            DB::raw("(SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '3246') AS height_fe"),
                            DB::raw("(SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '3062') AS feeder_fe"),
                            DB::raw("CONCAT(
                                (SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '119'),
                                ';',
                                (SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '121'),
                                ';'
                                )
                                AS sn_odu_ne"),
                            DB::raw("(SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '3040') AS rau_weight_ne"),
                            DB::raw("CONCAT(
                                (SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '131'),
                                ';',
                                (SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '133'),
                                ';'
                                )
                                AS sn_odu_fe"),
                            DB::raw("(SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '3057') AS rau_weight_fe"),
                            DB::raw("(SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '125') AS sn_ant_ne"),
                            DB::raw("(SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '137') AS sn_ant_fe"),
                            DB::raw("(SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '124') AS ant_type_ne"),
                            DB::raw("(SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '140') AS merk_ant_ne"),
                            DB::raw("(SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '3213') AS ant_gain_ne"),
                            DB::raw("(SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '136') AS ant_type_fe"),
                            DB::raw("(SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '144') AS merk_ant_fe"),
                            DB::raw("(SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '3245') AS ant_gain_fe"),
                            DB::raw("(SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '141') AS ant_diameter_ne"),
                            DB::raw("(SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '145') AS ant_diameter_fe"),
                            DB::raw("CONCAT(
                                (SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '143'),
                                ';',
                                (SELECT checklist_result FROM t_trx_checklist_result WHERE visit_id = ".$visit_id." AND checklist_id = '147'),
                                ';'
                                )
                                AS polarization"),
                            DB::raw('NULL AS dn'),
                            DB::raw('NULL AS lb')
                        )->first();
                }catch (\Exception $e){
                    DB::connection('mysql2')->table('pdf_error')->insert([
                        'visit_id' => $row[0],
                        'message' => $e->getMessage()
                    ]);
                    dd($e);
                }
            }
        }

        array_unshift($datas, $head);

        $export = new SummaryExport($datas);

        Excel::store($export, date('Ymd').'_summary_pcqc_export.xlsx');
    }
}
