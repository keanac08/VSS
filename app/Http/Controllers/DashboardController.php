<?php
namespace App\Http\Controllers;
use App\Models\OrderModel;
use App\Models\InvoiceModel;
use App\Models\WbModel;
use Illuminate\Http\Request;

class DashboardController extends Controller
{

    public function __construct(OrderModel $OrderModel)
    {
       $this->OrderModel = $OrderModel;
    }

    public function fetchDashboardData()
    {
        return $this->OrderModel->selectDashboardData();
    }

    public function fetchInvoiceToday(InvoiceModel $InvoiceModel)
    {
        return $InvoiceModel->selectToday();
    }
   
    public function fetchPendingInvoice()
    {
        return $this->OrderModel->selectPendingInvoice();
    }

    public function fetchTagged()
    {
        return $this->OrderModel->selectTagged();
    }

    public function fetchWb(WbModel $WbModel)
    {
        return $WbModel->selectAvailableWb();
    }

    public function fetchDashboardMonthlySummary(InvoiceModel $InvoiceModel)
    {
        // echo '<pre>';
        // print_r( $InvoiceModel->selectDashboardMonthlySummary());
        // echo '</pre>';

        // $month = [];
        // $vehicle = [];
        // $fleet = [];

        foreach($InvoiceModel->selectDashboardMonthlySummary() as $row){
            $month[] = $row->month_name;
            $vehicle[] = $row->vehicle;
            $fleet[] = $row->fleet;
        }

        return array('month' => $month, 'vehicle' => $vehicle, 'fleet' => $fleet);
    }
}
