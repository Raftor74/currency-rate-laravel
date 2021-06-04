<?php

namespace Tests\Feature\Api;

use App\Models\Currency;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CurrencyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_show_currency_list()
    {
        Currency::factory(20)->create();

        $response = $this->json('GET', route('api.currency.list'));

        $response->assertStatus(200);
    }

    /** @test */
    public function user_can_show_currency_detail()
    {
        $currency = Currency::factory()->create();

        $response = $this->json('GET', route('api.currency.show', $currency));

        $response->assertStatus(200);
    }

    /** @test */
    public function currency_list_has_correct_structure()
    {
        Currency::factory(20)->create();

        $response = $this->json('GET', route('api.currency.list'));

        $response->assertJsonCount(20, 'data');
        $response->assertJsonStructure([
            'data' => ['*' => $this->getExpectedListItemJsonStructure()],
        ]);
    }

    /** @test */
    public function currency_detail_has_correct_structure()
    {
        $currency = Currency::factory()->create();

        $response = $this->json('GET', route('api.currency.show', $currency));

        $response->assertJsonStructure([
            'data' => $this->getExpectedDetailItemJsonStructure(),
        ]);
    }

    protected function getExpectedListItemJsonStructure(): array
    {
        return [
            'id',
            'char_code',
            'name',
            'rate',
            'relevant_on',
        ];
    }

    protected function getExpectedDetailItemJsonStructure(): array
    {
        return [
            'id',
            'char_code',
            'name',
            'rate',
            'relevant_on',
        ];
    }
}