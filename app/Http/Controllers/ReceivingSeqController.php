<?php
namespace App\Http\Controllers;
use App\Models\ReceivingSeqModel;
use Illuminate\Http\Request;

class ReceivingSeqController extends Controller
{

    public function index(ReceivingSeqModel $receivingSeqModel)
    {
        $receivingSeqModel->createDelete();
    }

}
