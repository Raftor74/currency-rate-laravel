<?php

namespace Database\Factories;

use App\Models\Currency;
use Illuminate\Database\Eloquent\Factories\Factory;

class CurrencyFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Currency::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'char_code' => $this->faker->currencyCode,
            'name' => 'Test currency',
            'rate' => $this->faker->randomFloat(2, 1, 100),
            'relevant_on' => now()
        ];
    }
}
