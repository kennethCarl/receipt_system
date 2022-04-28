<?php

namespace Tests\Feature;

use App\Repository\ReceiptRepository;
use PHPUnit\Framework\TestCase;
use Tests\CreatesApplication;

class ReceiptTest extends TestCase
{
    use CreatesApplication;
    /**
     * A basic test example.
     *
     * @return void
     */

    public $receiptRepository;
    function __construct()
    {
        parent::__construct();
    }

    public function setUp(): void {
        parent::setUp();
        $this->receiptRepository = new ReceiptRepository();
    }

    public function test_validate_item_name_empty(){
        $this->receiptRepository->addReceiptItem("", 1.15, 4, true);
        $this->assertTrue(count($this->receiptRepository->errors) > 0);
    }

    public function test_validate_item_unit_price_zero(){
        $this->receiptRepository->addReceiptItem("Test", 0 , 4, true);
        $this->assertTrue(count($this->receiptRepository->errors) > 0);
    }

    public function test_validate_item_unit_price_string(){
        $this->receiptRepository->addReceiptItem("Test", "test" , 4, true);
        $this->assertTrue(count($this->receiptRepository->errors) > 0);
    }

    public function test_validate_item_qty_zero(){
        $this->receiptRepository->addReceiptItem("Test", 0 , 4, true);
        $this->assertTrue(count($this->receiptRepository->errors) > 0);
    }

    public function test_validate_item_qty_string(){
        $this->receiptRepository->addReceiptItem("Test", "test" , 4, true);
        $this->assertTrue(count($this->receiptRepository->errors) > 0);
    }

    public function test_validate_item_gdp_empty(){
        $this->receiptRepository->addReceiptItem("Test", 1 , 4, "");
        $this->assertTrue(count($this->receiptRepository->errors) > 0);
    }

    public function test_validate_item_gdp_non_boolean(){
        $this->receiptRepository->addReceiptItem("Test", 1 , 4, 4);
        $this->assertTrue(count($this->receiptRepository->errors) > 0);
    }

    public function test_item_tax_calculation_gt_included()
    {
        $this->receiptRepository->addReceiptItem("Bouncy Ball", 1.15, 4, true);
        $this->assertTrue(round($this->receiptRepository->receiptItems[0]->tax, 4) === .1045);
    }

    public function test_item_total_amount_calculation_gt_included()
    {
        $this->receiptRepository->addReceiptItem("Bouncy Ball", 1.15, 4, true);
        $this->assertTrue(round($this->receiptRepository->receiptItems[0]->totalAmount, 4) === 4.6000);
    }

    public function test_item_tax_calculation_gt_not_included()
    {
        $this->receiptRepository->addReceiptItem("In-store assist hrs", 25.30, 2, false);
        $this->assertTrue(round($this->receiptRepository->receiptItems[0]->tax, 4) === 2.5300);
    }

    public function test_item_total_amount_calculation_gt_not_included()
    {
        $this->receiptRepository->addReceiptItem("In-store assist hrs", 25.30, 2, false);
        $this->assertTrue($this->receiptRepository->receiptItems[0]->totalAmount === 55.66);
    }

    public function test_total_amount_calculation(){
        $this->receiptRepository->addReceiptItem("Bouncy Ball", 1.15, 4, true);
        $this->receiptRepository->addReceiptItem("Doll's House", 213.99, 1, true);
        $this->receiptRepository->addReceiptItem("In-store assist hrs", 25.30, 2, false);
        $this->receiptRepository->addReceiptItem("Race car", 1000, 1, true);
        $this->assertTrue($this->receiptRepository->calculateGrandTotal() === 274.25);
    }

    public function test_total_gdp_calculation(){
        $this->receiptRepository->addReceiptItem("Bouncy Ball", 1.15, 4, true);
        $this->receiptRepository->addReceiptItem("Doll's House", 213.99, 1, true);
        $this->receiptRepository->addReceiptItem("In-store assist hrs", 25.30, 2, false);
        $this->receiptRepository->addReceiptItem("Race car", 1000, 1, true);
        $this->assertTrue($this->receiptRepository->calculateGST() === 22.09);
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }
}
