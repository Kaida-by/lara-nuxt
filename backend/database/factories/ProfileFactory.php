<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Profile>
 */
class ProfileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => 'test name',
            'surname' => 'test surmane',
            'patronymic' => 'test patronymic',
            'user_id' => 1,
            'entity_type_id' => 3,
        ];
    }
}
