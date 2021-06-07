<?php

namespace Tests\Feature\Api;

use App\Models\Currency;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CurrencyTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function unauthorized_user_cannot_show_currency_list()
    {
        Currency::factory(20)->create();

        $response = $this->json('GET', $this->getCurrencyListRouteUrl());

        $response->assertStatus(401);
    }

    /** @test */
    public function unauthorized_user_cannot_show_currency_detail()
    {
        $currency = Currency::factory()->create();

        $response = $this->json('GET', $this->getCurrencyDetailRouteUrl($currency->id));

        $response->assertStatus(401);
    }

    /** @test */
    public function authorized_user_can_show_currency_list()
    {
        Sanctum::actingAs(User::factory()->create());
        Currency::factory(20)->create();

        $response = $this->json('GET', $this->getCurrencyListRouteUrl());

        $response->assertStatus(200);
    }

    /** @test */
    public function authorized_user_can_show_currency_detail()
    {
        Sanctum::actingAs(User::factory()->create());
        $currency = Currency::factory()->create();

        $response = $this->json('GET', $this->getCurrencyDetailRouteUrl($currency->id));

        $response->assertStatus(200);
    }

    /** @test */
    public function non_existent_currency_raise_404()
    {
        Sanctum::actingAs(User::factory()->create());
        $response = $this->json('GET', $this->getCurrencyDetailRouteUrl(999));

        $response->assertStatus(404);
    }

    /** @test */
    public function currency_list_has_correct_structure()
    {
        Sanctum::actingAs(User::factory()->create());
        Currency::factory(20)->create();

        $response = $this->json('GET', $this->getCurrencyListRouteUrl(), ['perPage' => 50]);

        $response->assertJsonCount(20, 'data');
        $response->assertJsonStructure([
            'data' => ['*' => $this->getExpectedListItemJsonStructure()],
        ]);
    }

    /** @test */
    public function currency_list_has_correct_pagination()
    {
        $perPage = 5;
        $page = 2;
        $currencyItemsCount = 20;
        Sanctum::actingAs(User::factory()->create());
        Currency::factory($currencyItemsCount)->create();

        $response = $this->json('GET', $this->getCurrencyListRouteUrl(), ['perPage' => $perPage, 'page' => $page]);

        $response->assertJsonCount($perPage, 'data');
        $response->assertJsonPath('meta.current_page', $page);
        $response->assertJsonPath('meta.per_page', $perPage);
        $response->assertJsonPath('meta.total', $currencyItemsCount);
    }

    /** @test */
    public function currency_detail_has_correct_structure()
    {
        Sanctum::actingAs(User::factory()->create());
        $currency = Currency::factory()->create();

        $response = $this->json('GET', $this->getCurrencyDetailRouteUrl($currency->id));

        $response->assertJsonStructure([
            'data' => $this->getExpectedDetailItemJsonStructure(),
        ]);
    }

    public function getCurrencyListRouteUrl(): string
    {
        return '/api/currencies';
    }

    public function getCurrencyDetailRouteUrl(int $currencyId): string
    {
        return '/api/currency/' . $currencyId;
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
