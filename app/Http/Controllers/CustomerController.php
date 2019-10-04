<?php
namespace App\Http\Controllers;
use App\Models\CustomerModel;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CustomerController extends Controller
{
    public function fetchForWbAllocation(CustomerModel $CustomerModel)
    {
        return response()->json($CustomerModel->selectCustomers());
    }

    public function fetchForInvoicePrint(CustomerModel $CustomerModel)
    {
        return response()->json($CustomerModel->selectCustomersInvoicePrint());
    }
}
