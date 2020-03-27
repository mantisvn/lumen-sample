<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class WagerTest extends TestCase
{
    /**
     * Test create wager selling_price validate failed.
     *
     * @return void
     */
    public function testPlaceWagerValidateFailSellingPrice()
    {
        $data = [
            "total_wager_value" => 50,
            "odds" => 100,
            "selling_percentage" => 50,
            "selling_price" => 10, //selling_price must be greater than 25
        ];

        $response = $this->post('/wagers', $data);
        $response->assertResponseStatus(400);
        $response->seeJson(['error' => 'validation_failed']);
    }

    /**
     * Test create wager: validate input value
     *
     * @return void
     */
    public function testPlaceWagerValidateInputValue()
    {
        $data = [
            "total_wager_value" => "no number",
            "odds" => 0,
            "selling_percentage" => 200,
            "selling_price" => 100,
        ];

        $response = $this->post('/wagers', $data);
        
        $response->assertResponseStatus(400);
        $response->seeJson(['error' => 'validation_failed']);
    }

    /**
     * Test create wager
     *
     * @return void
     */
    public function testPlaceWager()
    {
        $data = [
            "total_wager_value" => 50,
            "odds" => 110,
            "selling_percentage" => 50,
            "selling_price" => 30,
        ];

        $response = $this->post('/wagers', $data);
        $response->assertResponseStatus(201);
    }

    /**
     * Test list of wagers
     */
    public function testWagerList() {
        $response = $this->get('/wagers');
        $response->assertResponseStatus(200);
    }
}
