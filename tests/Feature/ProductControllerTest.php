<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Product;

class ProductControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $product = factory(App\Product::class)->create();

        // Call the dataTable method
        $response = $this->post('/management_product/dataTable');

        // Assert that the response status is 200
        $response->assertStatus(200);

        // Assert that the response contains the product data
        $response->assertSee($product->name);
    }
}
