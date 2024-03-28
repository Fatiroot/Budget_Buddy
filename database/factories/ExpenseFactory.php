<?php

namespace Database\Factories;

use App\Models\Expense;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseFactory extends Factory
{

public function definition(){
    return ['name' => $this->faker->word,'description' => $this->faker->sentence,'price' => $this->faker->randomFloat(2, 1, 1000),'user_id' => \App\Models\User::factory(),];}
}
