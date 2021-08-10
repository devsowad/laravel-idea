<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\Idea;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Comment::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id'      => User::factory(),
            'idea_id'      => Idea::factory(),
            'body'         => $this->faker->paragraph(),
            'spam_reports' => $this->faker->numberBetween(0, 20),
        ];
    }

    public function existing()
    {
        return $this->state(function () {
            return [
                'user_id' => $this->faker->numberBetween(1, 20),
                'idea_id' => $this->faker->numberBetween(1, 60),
            ];
        });
    }
}
