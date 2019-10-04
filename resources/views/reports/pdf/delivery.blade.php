<!DOCTYPE html>
<html>
<head>
    <title>Delivery Receipt</title>
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
                font-family: Helvetica;
                margin: 0 .5cm 0 1cm;
                /* border: 1px solid #ccc; */
            }

            header{
                
            }

            table {
                border-collapse: collapse;
                width: 100%;
            }

            table tr td{
                padding: 0;
            }

            header .header-table-1{
                text-align: center;
            }
            
            header .header-table-2{
               font-size: 9pt;
            }

            header .header-table-2 tr:first-child td{
               font-size: 5pt;
            }

            header .header-div-1{
                height: 125pt;
                border:1px solid transparent;
            }
            
            header .header-div-2{
                height: 114pt;
                border:1px solid transparent;
            }

            main .main-div-1{
                height: 220pt;
                border:1px solid transparent;
            }

            main .main-div-2{
                height: 133pt;
                border:1px solid transparent;
            }

            main .main-div-3,
            main .main-div-4{
                height: 62pt;
                border:1px solid transparent;
            }

            main .main-div-5{
                height: 57pt;
                border:1px solid transparent;
            }

            main .main-table-1,
            main .main-table-2{
                font-size: 9pt;
            }

            main .main-table-4,
            main .main-table-5{
                font-size: 8pt;
                padding: 2pt;
            }

            main .main-table-6{
                font-size: 7pt;
            }

            main table.main-table-2 tr td table.main-sub-table-2 tr td:nth-child(2){
                text-align: right;
                padding-top: 1pt;
                padding-right: 3pt;
            }

            main .main-table-1 tr td{
                padding: 0;
            }

            main .main-table-1 tr:first-child td{
                /* border: 1px solid #ccc; */
                padding: 3pt 0;
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
            <div class="header-div-1">
                <table class="header-table-1" border="0">
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2" style="font-size: 3pt;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width="525px">&nbsp;</td>
                        <td style="font-size: 11pt;"><strong>DELIVERY RECEIPT (VEHICLE)</strong></td>
                    </tr>
                    <tr>
                        <td width="525px">&nbsp;</td>
                        <td style="font-size: 13pt;"><span style="font-size: 9pt;">No.</span> <strong>{{ $row->dr_number }}</strong></td>
                    </tr>
                </table>
            </div>
            <div class="header-div-2">
                <table border="0" class="header-table-2">
                    <tr>
                        <td width="10px">&nbsp;</td>
                        <td width="20px">&nbsp;</td>
                        <td width="430px">&nbsp;</td>
                        <td width="80px">&nbsp;</td>
                        <td width="75px">&nbsp;</td>
                        <td width="115px">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="3">&nbsp;</td>
                        <td>Date</td>
                        <td colspan="2">{{ longDate($row->trx_date) }}</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td colspan="2" style="font-size: 8pt;">SOLD TO</td>
                        <td colspan="3">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                        <td style="font-size: 11pt;"><strong>{{ $row->party_name }}</strong></td>
                        <td colspan="2">With Orig Copy of CSR</td>
                        <td>{{ $row->csr_number }}</td>
                        
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                        <td>{{ $row->address1 . ' ' . $row->address2 . ' ' . $row->address3 }}</td>
                        <td colspan="2">CSR(OR)</td>
                        <td>{{ $row->csr_or_number }}</td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                        <td>TIN# {{ $row->tax_reference }}</td>
                        <td colspan="3">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                        <td>Business Style :{{ $row->class_code . ' - ' . $row->business_style }}</td>
                        <td colspan="3">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="6">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="6">&nbsp;</td>
                    </tr>
                </table>
            </div>
        </header>
        <main>
            <div class="main-div-1">
                <table class="main-table-1" border="0">
                    <tr>
                        <td width="55px">Item No.</td>
                        <td width="90px">Reference</td>
                        <td colspan="2">Description</td>
                        <td colspan="3" width="250px">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="7" style="font-size: 8pt;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td align="center">1</td>
                        <td>CS No.: </td>
                        <td width="110px">Model</td>
                        <td>{{ $row->sales_model }}</td>
                        <td colspan="3">&nbsp;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td rowspan="2"><strong style="font-size: 15pt;">{{ $row->cs_number }}</strong></td>
                        <td>Lot No.</td>
                        <td>{{ $row->lot_number }}</td>
                        <td colspan="3">&nbsp;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>Serial No.</td>
                        <td>{{ $row->chassis_number }}</td>
                        <td colspan="3">&nbsp;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>WB No.:</td>
                        <td>Engine</td>
                        <td>{{ $row->engine_type . '-' . $row->engine_no }}</td>
                        <td colspan="3">&nbsp;</td>
                    </tr>
                    <tr>
                        <td>&nbsp;</td>
                        <td>{{ $row->wb_number }}</td>
                        <td>Color</td>
                        <td>{{ $row->body_color }}</td>
                        <td colspan="3">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                        <td>GVW</td>
                        <td>{{ $row->gvw }}</td>
                        <td colspan="3">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                        <td>Fuel</td>
                        <td>{{ $row->fuel }}</td>
                        <td colspan="3">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                        <td>Key No.</td>
                        <td>{{ $row->key_no }}</td>
                        <td colspan="3">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                        <td>Trim Color</td>
                        <td>&nbsp;</td>
                        <td colspan="3">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                        <td>Tire Brand/Size</td>
                        <td>{{ $row->tire_specs }}</td>
                        <td colspan="3">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                        <td>Battery</td>
                        <td>{{ $row->battery }}</td>
                        <td colspan="3">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                        <td>Displacement</td>
                        <td>{{ $row->displacement }}</td>
                        <td colspan="3">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="2">&nbsp;</td>
                        <td>Year Model</td>
                        <td>{{ $row->year_model }}</td>
                        <td colspan="3">&nbsp;</td>
                    </tr>
                </table>
            </div>
            <div class="main-div-2">
                <table class="main-table-2" border="0">
                    <tr>
                        <td colspan="3" style="font-size: 5pt;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width="170pt" style="font-size: 7pt;padding: 2pt;">{!! nl2br(e($row->items1)) !!}</td>
                        <td width="160pt" style="font-size: 7pt;padding: 2pt;">{!! nl2br(e($row->items2)) !!}</td>
                        <td width="190pt" colspan="2">&nbsp;</td>
                    </tr>
                </table>
            </div>
            <div class="main-div-3">
                @if($row->fleet_name != NULL)
                <table class="main-table-3">
                    <tr>
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="font-size: 7pt;">Dealers Fleet Account:</td>
                    </tr>
                    <tr>
                        <td style="font-size: 10pt;"><strong style="margin-left: 10pt;">{{ $row->fleet_name }}</strong></td>
                    </tr>
                </table>
                @endif
            </div>
            <div class="main-div-4">
                <table class="main-table-4">
                    <tr>
                        <td><p style="text-align: justify;">Purchaser hereby expressly agrees that any action arising out or in condition with this invoice shall be instituted in the proper court of Province of Laguna, Philippines, and that in case of litigation purchaser shall pay as attorney’s fees an amount equivalent to 25% of the total sum due which attorney’s fees shall however, in no case be less than P5.00. Any account not paid within 90 days from the due date shall bear interest at the rate of 4% per month except as otherwise expressly stipulated herein. This sale is governed by the agreement on basic terms and conditions of the sales contract between the parties.</p></td>
                    </tr>
                </table>
            </div>
            <div class="main-div-5">
                <table class="main-table-5" border="0">
                    <tr>
                        <td width="25%">Prepared by:</td>
                        <td width="25%">Checked by:</td>
                        <td width="25%">Approved by:</td>
                        <td width="25%">Released by:</td>
                    </tr>
                    <tr>
                        <td colspan="4">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="4">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="4">&nbsp;</td>
                    </tr>
                    <tr>
                        <td width="25%"><strong>DESIDERIO, CHRIS M.</strong></td>
                        <td width="25%"><strong>VILLARICO, MARVIN M.</strong></td>
                        <td width="25%"><strong>TORRES, DENNIS L.</strong></td>
                        <td width="25%">&nbsp;</td>
                    </tr>
                </table>
            </div>
            <div class="main-div-6">
                <table class="main-table-6" border="0">
                    <tr>
                        <td colspan="6"><strong>Received the above merchandise in good order and condition, subject to the terms and conditions of the Sales Contract.</strong> </td>
                    </tr>
                    <tr>
                        <td colspan="6" style="font-size: 2pt">&nbsp;</td>
                    </tr>
                    <tr>
                        <td style="width: 170px;"></td>
                        <td style="width: 85px;">&nbsp;</td>
                        <td style="width: 110px;"></td>
                        <td style="width: 40px;">&nbsp;</td>
                        <td style="width: 130px;">Valid Until</td>
                        <td style="width: 155px;">December 31, 2021</td>
                    </tr>
                    <tr>
                        <td colspan="4"></td>
                        <td>BIR PERMIT TO USE NO.:</td>
                        <td>1701_0124_PTU_CAS_000056</td>
                    </tr>
                    <tr>
                        <td colspan="4"></td>
                        <td>Date issued:</td>
                        <td>January 3, 2017</td>
                    </tr>
                    <tr>
                        <td style="border-bottom: 1px solid black;"></td>
                        <td>&nbsp;</td>
                        <td style="border-bottom: 1px solid black;"></td>
                        <td>&nbsp;</td>
                        <td>Valid until</td>
                        <td>December 31, 2021</td>
                    </tr>
                    <tr>
                        <td>Dealer Representative</td>
                        <td>&nbsp;</td>
                        <td>Date</td>
                        <td>&nbsp;</td>
                        <td>Document series range:</td>
                        <td>40300000001-40399999999</td>
                    </tr>
                    <tr>
                        <td colspan="6" style="font-size: 2pt">&nbsp;</td>
                    </tr>
                    <tr>
                        <td colspan="6" align="center"><strong>THIS DELIVERY RECEIPT SHALL BE VALID FOR FIVE (5) YEARS FROM THE DATE OF THE PERMIT TO USE</strong></td>
                    </tr>
                </table>
            </div>
        </main> 
    @endforeach
</body>
</html>


