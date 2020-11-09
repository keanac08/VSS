<!DOCTYPE html>
<html>
<head>
    <title>Dealers Receiving Copy</title>
    <head>
        <style>
            /** 
                Set the margins of the page to 0, so the footer and the header
                can be of the full height and width !
             **/
            @page {
                margin: 0cm 0cm;

            }

            /** Define now the real margins of every page in the PDF **/
            body {
                font-family:'Calibri','Sans-serif','Arial';
                margin: 1cm;
                
            }

            header {
                padding-bottom: .1cm;
                margin-bottom: 3px;
                border-bottom: 2px solid #777;
                font-size: 11px;
            }

            main {
                margin-bottom: 2cm;
                font-size: 12px;
            }

            footer {
                font-size: 12px;
            }

            table {
                border-collapse: collapse;
                width: 100%;
            }

            header table tr td{
                padding: 0;
            }

            header table tr:first-child td{
                padding-bottom: 2px;
            }

            td.title{
                text-align: center;
                font-size: 17px;
                font-weight: bold;
            }

            table.main-header{
                margin-bottom: .8cm;
            }

            table.main-body tr td,
            table.main-body tr th
            {
                padding: 2px;;
                font-size: 11px;
            }

            table.main-body thead th{
                text-align: center;
            }
            
        </style>
    </head>
<body>

    <!-- Define header and footer blocks before your content -->
    <header>
        <table border="0">
            <tr>
                <td colspan="2"><img src="{{ asset('public/images/isuzu_logo.jpg') }}" /></td> 
            </tr>  
            <tr>
                <td colspan="2">Isuzu Philippines Corporation</td> 
            </tr>  
            <tr>
                <td colspan="2">114 Technology Avenue, Laguna Technopark Phase II, Biñan Laguna 4024</td> 
            </tr>  
            <tr>
                <td>Tel. No. (049) 541-0224 / Local 245 | Fax No. (043) 541-0347</td> 
                <td align="right">Vehicle Invoicing System</td>
            </tr>
        </table>
    </header>
    <main>
        {{-- main header --}}
        <table class="main-header">
            <tr>
                <td align="right" style="font-size: 11px;">Serial Number : {{ date('Ym') .  sprintf("- %03d", $serial) }}</td>
            </tr>
            <tr>
                <td class="title">INVOICE / DR / WB / CSR - RECEIVING REPORT</td>
            </tr>
            <tr>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td>{{ $data[0]->party_name . ' - ' . $data[0]->account_name}}</td>
            </tr>
            <tr>
                <td>{{ date('F d, Y') }}</td>
            </tr>
        </table>

        {{-- main body --}}
        <table border="1" class="main-body">
            <thead>
                <tr>
                    <th width="20px">#</th>
                    <th width="190px">Sales Model</th>
                    <th width="75px">CS Number</th>
                    <th width="100px">CSR Number</th>
                    <th width="75px">Invoice Date</th>
                    <th width="75px">WB Number</th>
                    <th width="140px">Fleet Customer</th>
                </tr>
            </thead>
            @php
            $cnt = 1;
            @endphp
            @foreach ($data as $row)
                <tr>
                    <td align="center">{{ $cnt }}</td>
                    <td>{{ $row->sales_model }}</td>
                    <td align="center">{{ $row->cs_number }}</td>
                    <td align="center">{{ $row->csr_number }}</td>
                    <td align="center">{{ $row->invoice_date }}</td>
                    <td align="center">{{ $row->wb_number }}</td>
                    <td>{{ $row->fleet_customer }}</td>
                </tr>
                @php
                    $cnt++;
                @endphp
            @endforeach
        </table>
    </main>

    <footer>
        <table border="0">
            <tr>
                <td colspan="2">Received original CSR and OR by : ____________________________</td>
            </tr>
            <tr>
                <td style="width:220px;">&nbsp;</td>
                <td style="font-size: 9px;">Signature Over Printed Name</td>
            </tr>
            <tr>
                <td colspan="2">Date : &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;__________________</td>
            </tr>
            <tr>
                <td colspan="2">Printed By : {{session('user.shortname')}}</td>
            </tr>
        </table>
    </footer>
    
</body>
</html>


