<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->text(20),
        ];
    }

    /**
     * Define the model's with parent_id state.
     *
     * @param $categoryId
     * @return CategoryFactory
     */
    public function withParent($categoryId): CategoryFactory
    {
        return $this->state([
            'parent_id' => $categoryId,
        ]);
    }
}
