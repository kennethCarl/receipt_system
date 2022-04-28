<?php

namespace App\Http\Controllers;

use App\Repository\ReceiptRepository;
use Illuminate\Support\Facades\View;

class ReceiptController extends Controller
{
    private $receiptRepository;

    public function __construct(ReceiptRepository $receiptRepository) {
        $this->receiptRepository = $receiptRepository;
    }

    public function index(){
        $this->receiptRepository->addReceiptItem("Bouncy Ball", 1.15, 4, true);
        $this->receiptRepository->addReceiptItem("Doll's House", 213.99, 1, true);
        $this->receiptRepository->addReceiptItem("In-store assist hrs", 25.30, 2, false);
        $this->receiptRepository->addReceiptItem("Race car", 1000, 1, true);

        $grandTotal = 0;
        $gst = 0;

        if(count($this->receiptRepository->errors) === 0){
            $grandTotal = $this->receiptRepository->calculateGrandTotal();
            $gst = $this->receiptRepository->calculateGST();
        }

        return View::make('receipt', array(
            "receipt_items" =>  $this->receiptRepository->receiptItems,
            'grand_total' => $grandTotal,
            "gst" => $gst,
            "errors" => $this->receiptRepository->errors
        ));
    }
}
