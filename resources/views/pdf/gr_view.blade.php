<!doctype html>
<head>
    <title>Document GR</title>

    <style>
        body{
            font-size: 11px;
        }

        .container{
            /*margin: 10px;*/
            padding: 0 20px 20px 20px;
            /*border: 1px solid black;*/
        }

        table, tr {
            border-collapse: collapse;
            vertical-align: middle;
        }

        td {
            border-collapse: collapse;
            vertical-align: middle;
            padding: 5px 3px;
        }

        .text-bold{
            font-weight: bold
        }
    </style>
</head>
<body>
<table width="100%" border="1">
    <tr align="center" class="text-bold">
        <td>No</td>
        <td>Link ID</td>
        <td>NE</td>
        <td>FE</td>
        <td>SOW</td>
        <td>Config Before Execution</td>
        <td>Config After Execution</td>
        <td>IP NMS</td>
        <td>RSL(NE-FE)</td>
    </tr>
    <tr align="center">
        <td>1</td>
        <td>{{ $detail->link_id }}</td>
        <td>{{ $detail->site_id_ne }}</td>
        <td>{{ $detail->site_id_fe }}</td>
        <td>{{ $detail->option_label }}</td>
        <td></td>
        <td></td>
        <td>{{ $detail->ip_ne }}/{{ $detail->ip_fe }}</td>
        <td></td>
    </tr>
</table>
<table width="100%" style="margin-top: 40px">
    <tr>
        <td width="50%">
            <div class="text-bold">Capture IP NE</div>
            <img src="{{ !empty($checklist->current_ip_ne) ? "http://10.0.5.37/".$checklist->current_ip_ne : asset('no-image.jpg') }}" alt="" width="95%">
        </td>
        <td width="50%">
            <div class="text-bold">Capture IP FE</div>
            <img src="{{ !empty($checklist->current_ip_fe) ? "http://10.0.5.37/".$checklist->current_ip_fe : asset('no-image.jpg') }}" alt="" width="95%">
        </td>
    </tr>
    <tr>
        <td width="50%">
            <div class="text-bold">Inventory IDU NE</div>
            <img src="{{ !empty($checklist->inventory_idu_ne) ? "http://10.0.5.37/".$checklist->inventory_idu_ne : asset('no-image.jpg') }}" alt="" width="95%">
        </td>
        <td width="50%">
            <div class="text-bold">Inventory IDU FE</div>
            <img src="{{ !empty($checklist->inventory_idu_fe) ? "http://10.0.5.37/".$checklist->inventory_idu_fe : asset('no-image.jpg') }}" alt="" width="95%">
        </td>
    </tr>
    <tr>
        <td width="50%">
            <div class="text-bold">Lisence Inventory NE</div>
            <img src="{{ !empty($checklist->lisence_inventory_ne) ? "http://10.0.5.37/".$checklist->lisence_inventory_ne : asset('no-image.jpg') }}" alt="" width="95%">
        </td>
        <td width="50%">
            <div class="text-bold">Lisence Inventory FE</div>
            <img src="{{ !empty($checklist->lisence_inventory_fe) ? "http://10.0.5.37/".$checklist->lisence_inventory_fe : asset('no-image.jpg') }}" alt="" width="95%">
        </td>
    </tr>
    <tr>
        <td width="50%">
            <div class="text-bold">Capture ODU Inventory NE</div>
            <img src="{{ !empty($checklist->inventory_odu_ne) ? "http://10.0.5.37/".$checklist->inventory_odu_ne : asset('no-image.jpg') }}" alt="" width="95%">
        </td>
        <td width="50%">
            <div class="text-bold">Capture ODU Inventory FE</div>
            <img src="{{ !empty($checklist->inventory_odu_fe) ? "http://10.0.5.37/".$checklist->inventory_odu_fe : asset('no-image.jpg') }}" alt="" width="95%">
        </td>
    </tr>
    <tr>
        <td width="50%">
            <div class="text-bold">RSL NE</div>
            <img src="{{ !empty($checklist->rsl_ne) ? "http://10.0.5.37/".$checklist->rsl_ne : asset('no-image.jpg') }}" alt="" width="95%">
        </td>
        <td width="50%">
            <div class="text-bold">RSL FE</div>
            <img src="{{ !empty($checklist->rsl_fe) ? "http://10.0.5.37/".$checklist->rsl_fe : asset('no-image.jpg') }}" alt="" width="95%">
        </td>
    </tr>
    <tr>
        <td width="50%">
            <div class="text-bold">Delivery Note</div>
            <img src="{{ !empty($checklist->dn_ne) ? "http://10.0.5.37/".$checklist->dn_ne : asset('no-image.jpg') }}" alt="" width="95%">
        </td>
        <td width="50%">
            <div class="text-bold">&nbsp;</div>
            <img src="{{ !empty($checklist->dn_fe) ? "http://10.0.5.37/".$checklist->dn_fe : asset('no-image.jpg') }}" alt="" width="95%">
        </td>
    </tr>
</table>
</body>
</html>
