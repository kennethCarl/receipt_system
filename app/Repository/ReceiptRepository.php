<?php


namespace App\Repository;

use App\Models\ReceiptItem;
use Illuminate\Validation\ValidationException;

class ReceiptRepository {
    private $unitPriceTax;
    private $unitPriceTaxMultiplier;
    public $receiptItems;
    public $errors;
    public function __construct(){
        $this->unitPriceTax = 11;
        $this->unitPriceTaxMultiplier = 0.1;
        $this->receiptItems = array();
        $this->errors = array();
    }

    public function addReceiptItem($itemName, $unitPrice, $qty, $isGSTIncluded){
        try {
            $receiptItem = new ReceiptItem($itemName, $unitPrice, $qty, $isGSTIncluded);
            \Illuminate\Support\Facades\Validator::validate((array)$receiptItem, array(
                "itemName" => ['required', 'string'],
                "unitPrice" => ['required', 'numeric', 'min:0.01'],
                "qty" => ['required', 'numeric', 'min:1'],
                "isGSTIncluded" => ['required', 'boolean'],
            ));

            $this->calculateTaxPerUnit($receiptItem);
            $this->calculateUnitPriceWithTax($receiptItem);
            $receiptItem->totalAmount = round($receiptItem->unitPriceWithTax * $receiptItem->qty, 2);
            $this->receiptItems[] = $receiptItem;
        }catch (ValidationException $validationException){
            $errorMessage = '';
            $errors = $validationException->validator->errors()->toArray();
            foreach ($errors as $error){
                $errorMessage = $errorMessage . implode(" ", $error);
            }
            $this->errors[] = $errorMessage;
        }
    }

    public function isPalindrome($itemName){
        $itemName = str_replace(' ', '', strtolower($itemName));
        return strrev($itemName) === $itemName;
    }

    public function calculateTaxPerUnit(&$receiptItem){
        if($this->isPalindrome($receiptItem->itemName)){
            $receiptItem->tax = 0;
        }else {
            if ($receiptItem->isGSTIncluded === true) {
                $receiptItem->tax = $receiptItem->unitPrice / $this->unitPriceTax;
            } else {
                $receiptItem->tax = $receiptItem->unitPrice * $this->unitPriceTaxMultiplier;
            }
        }
    }

    public function calculateUnitPriceWithTax(&$receiptItem){
        if($this->isPalindrome($receiptItem->itemName)){
            $receiptItem->unitPrice = 0;
            $receiptItem->unitPriceWithTax = 0;
        }else {
            if ($receiptItem->isGSTIncluded === false) {
                $receiptItem->unitPriceWithTax = $receiptItem->unitPrice + $receiptItem->tax;
            }else{
                $receiptItem->unitPriceWithTax = $receiptItem->unitPrice;
            }
        }
    }

    public function calculateGrandTotal(){
        $total = 0;
        foreach ($this->receiptItems as $receiptItem){
            $total += $receiptItem->totalAmount;
        }
        return round($total, 2);
    }

    public function calculateGST(){
        $total = 0;
        foreach ($this->receiptItems as $receiptItem){
            $total += $receiptItem->tax;
        }

        return round($total, 2);
    }
}
