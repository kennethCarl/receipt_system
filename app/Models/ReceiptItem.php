<?php


namespace App\Models;


class ReceiptItem {
    public $itemName;
    public $unitPrice;
    public $unitPriceWithTax;
    public $qty;
    public $totalAmount;
    public $isGSTIncluded;
    public $tax;

    public function __construct($itemName, $unitPrice, $qty, $isGSTIncluded) {
        $this->itemName = $itemName;
        $this->unitPrice = $unitPrice;
        $this->unitPriceWithTax = 0;
        $this->qty = $qty;
        $this->isGSTIncluded = $isGSTIncluded;
        $this->totalAmount = 0;
        $this->tax = 0;
    }
}
