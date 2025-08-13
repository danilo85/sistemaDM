<?php

namespace Database\Factories;

use App\Models\Cliente;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cliente>
 */
class ClienteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->company(),
            'contact_person' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => fake()->phoneNumber(),
            'is_complete' => true,
            'extrato_ativo' => fake()->boolean(70), // 70% chance de ser true
        ];
    }

    /**
     * Indicate that the client should have incomplete data.
     */
    public function incomplete(): static
    {
        return $this->state(function (array $attributes) {
            $incompleteFields = [];
            
            // Randomly remove email or phone to make it incomplete
            if (fake()->boolean(50)) {
                $incompleteFields['email'] = null;
            }
            
            if (fake()->boolean(50)) {
                $incompleteFields['phone'] = null;
            }
            
            // If both email and phone are removed, keep at least one
            if (!isset($incompleteFields['email']) && !isset($incompleteFields['phone'])) {
                if (fake()->boolean()) {
                    $incompleteFields['email'] = null;
                } else {
                    $incompleteFields['phone'] = null;
                }
            }
            
            $incompleteFields['is_complete'] = false;
            
            return $incompleteFields;
        });
    }

    /**
     * Indicate that the client should have only a name (most incomplete).
     */
    public function nameOnly(): static
    {
        return $this->state(fn (array $attributes) => [
            'contact_person' => null,
            'email' => null,
            'phone' => null,
            'is_complete' => false,
        ]);
    }
}