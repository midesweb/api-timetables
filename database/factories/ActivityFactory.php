<?php

namespace Database\Factories;

use App\Models\Timetable;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Activity>
 */
class ActivityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'timetable_id' => Timetable::factory(), // crea también un horario si no se pasa uno
            'day' => $this->faker->numberBetween(1, 7),
            'start_time' => $this->faker->time('H:i'),
            'duration' => $this->faker->numberBetween(30, 180), // entre 30 minutos y 3 horas
            'info' => $this->faker->sentence(8),
            'is_available' => $this->faker->boolean(80), // 80% de probabilidad de estar disponible
        ];
    }

    public function forUser($user): static
    {
        return $this->for(
            \App\Models\Timetable::factory()->for($user),
            'timetable'
        );
    }

}
