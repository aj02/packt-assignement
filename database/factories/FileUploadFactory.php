<?php

namespace Database\Factories;

use App\Models\FileUpload;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;

class FileUploadFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = FileUpload::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'email' => $this->faker->unique()->safeEmail(),
            'person_prefix' => Str::random(3),
            'first_name' => Str::random(10),
            'last_name' => Str::random(10),
            'active' => 1
        ];
    }

}
