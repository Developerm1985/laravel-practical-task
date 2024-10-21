<?php

namespace Tests\Unit;

use App\Jobs\ProcessProductImage;
use App\Models\Product;
use App\Services\SpreadsheetService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;

class SpreadsheetServiceTest extends TestCase
{
    use RefreshDatabase;

    protected SpreadsheetService $spreadsheetService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->spreadsheetService = new SpreadsheetService();
        Bus::fake();
    }

    public function testProcessSpreadsheetWithValidData()
    {
        $filePath = 'path/to/file.csv';
        $mockData = [
            ['product_code' => 'P001', 'quantity' => 10],
            ['product_code' => 'P002', 'quantity' => 5],
        ];

        app()->instance('importer', new class($mockData) {
            private $data;
            public function __construct($data) { $this->data = $data; }
            public function import($filePath) { return $this->data; }
        });

        $this->spreadsheetService->processSpreadsheet($filePath);

        $this->assertCount(2, Product::all());
        Bus::assertDispatched(ProcessProductImage::class, 2);
    }

    public function testProcessSpreadsheetWithInvalidData()
    {
        $filePath = 'path/to/file.csv';
        $mockData = [
            ['product_code' => 'P001', 'quantity' => 10],
            ['product_code' => '', 'quantity' => 5],
        ];

        app()->instance('importer', new class($mockData) {
            private $data;
            public function __construct($data) { $this->data = $data; }
            public function import($filePath) { return $this->data; }
        });

        $this->spreadsheetService->processSpreadsheet($filePath);

        $this->assertCount(1, Product::all());
        Bus::assertDispatched(ProcessProductImage::class, 1);
    }

    public function testProcessSpreadsheetHandlesUniqueValidation()
    {
        Product::create(['code' => 'P001', 'quantity' => 10]);

        $filePath = 'path/to/file.csv';
        $mockData = [
            ['product_code' => 'P001', 'quantity' => 5],
        ];

        app()->instance('importer', new class($mockData) {
            private $data;
            public function __construct($data) { $this->data = $data; }
            public function import($filePath) { return $this->data; }
        });

        $this->spreadsheetService->processSpreadsheet($filePath);

        $this->assertCount(1, Product::all());
        Bus::assertNotDispatched(ProcessProductImage::class);
    }
}

