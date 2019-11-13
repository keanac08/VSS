<!DOCTYPE html>
<html>
<head>
    <title>Certificate of Quality Control</title>
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

            p{
                text-align: justify;
            }

            header {
                padding-bottom: .1cm;
                margin-bottom: 1cm;
                border-bottom: 2px solid #777;
                font-size: 11px;
            }

            main {
                margin-bottom: 1cm;
                margin-left: 1.2cm;
                margin-right: 1.2cm;
                font-size: 14px;
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
                font-size: 22px;
                font-weight: bold;
            }

            table.main-header{
                margin-bottom: .8cm;
            }

            table.main-header-2{
                font-size: 13px;
            }

            table.main-body{
                margin-bottom: 5cm;
            }

            table.main-body tr td,
            table.main-body tr th,
            table.main-footer tr td
            {
                padding: 2px;
                font-size: 13px;
                text-align: center;
            }

            table.main-footer tr td
            {
                padding: 0;
            }

            table.main-body thead th{
                text-align: center;
            }
            
            .page_break { page-break-before: always; }

        </style>
    </head>
<body>
    @php
        $ctr = 0;
    @endphp
    @foreach ($data as $row)
        
        @if ($ctr > 0)
            <div class="page_break"></div> 
        @endif
        @php
            $ctr++;
        @endphp
        
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
            <table class="main-header">
                <tr>
                    <td colspan="2" class="title">CERTIFICATE OF QUALITY CONTROL</td>
                </tr>
            </table>
            <table class="main-header-2">
                <tr>
                    <td width="100px">CS number</td>
                    <td width="15px">:</td>
                    <td>{{ $row->cs_number }}</td>
                </tr>
                <tr>
                    <td>Certificate No.</td>
                    <td>:</td>
                    <td>{{ $row->wb_number }}</td>
                </tr>
                <tr>
                    <td>Date</td>
                    <td>:</td>
                    <td>{{ date('F d, Y') }}</td>
                </tr>
            </table>

            <p>&nbsp;</p>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;This is to certify that the above Isuzu motor vehicle more particularly described below was manufactured in accordance with the safety and quality standards of Isuzu Philippines Corporation.</p>
            <p>&nbsp;</p>
            
            <table class="main-body" border="1">
                <tr>
                    <td width="220px">Model</td>
                    <td width="200px">Engine Number</td>
                    <td width="200px">Chassis Number</td>
                </tr>
                <tr>
                    <td>{{ $row->sales_model }}</td>
                    <td>{{ $row->engine_number }}</td>
                    <td>{{ $row->chassis_number }}</td>
                </tr>
            </table>
            
            <div>
                <img style="float: right;position: absolute;top: -80px" src="{{ asset('public/images/signature/mike-bernas-2.png') }}">
            </div>
            
            <table class="main-footer" border="0">
                <tr>
                    <td width="420px">&nbsp;</td>
                    <td>Miguel Nilo D. Bernas</td>
                </tr>
                <tr>
                    <td width="420px">&nbsp;</td>
                    <td>Department Head</td>
                </tr>
                <tr>
                    <td width="420px">&nbsp;</td>
                    <td>Quality Control</td>
                </tr>
            </table>
        </main> 
    @endforeach
</body>
</html>


