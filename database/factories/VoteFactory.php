<?php

namespace Database\Factories;

use App\Models\Idea;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Database\Eloquent\Factories\Factory;

class VoteFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Vote::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'idea_id' => Idea::factory(),
            'user_id' => User::factory(),
        ];
    }

    public function existing(int $maxIdea = 60, int $maxUser = 20)
    {
        return $this->state(function () use ($maxIdea, $maxUser) {
            return [
                'idea_id' => $this->faker->numberBetween(1, $maxIdea),
                'user_id' => $this->faker->numberBetween(1, $maxUser),
            ];
        });
    }
}
