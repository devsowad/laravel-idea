<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\Idea;
use App\Models\Status;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class IdeaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Idea::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->words(2, true);
        return [
            'user_id'      => User::factory(),
            'category_id'  => Category::factory(),
            'status_id'    => Status::factory(),
            'title'        => $title,
            'slug'         => Str::slug($title),
            'description'  => $this->faker->paragraphs(5, true),
            'spam_reports' => rand(0, 10),
        ];
    }

    public function existing()
    {
        return $this->state(function () {
            return [
                'user_id'     => rand(1, 20),
                'category_id' => rand(1, 5),
                'status_id'   => rand(1, 5),
            ];
        });
    }

    public function shortDescription()
    {
        return $this->state(fn() => ['description' => $this->faker->paragraphs(1, true)]);
    }
}
