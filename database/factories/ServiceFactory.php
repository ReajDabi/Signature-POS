<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Branch;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Service> */
class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition(): array
    {
        return [
            'branch_id' => null,
            'name' => fake()->unique()->words(2, true),
            'category' => 'Hair',
            'price' => '350.00',
            'duration_min' => 45,
            'is_active' => true,
        ];
    }

    public function forBranch(Branch|int $branch): static
    {
        return $this->state(['branch_id' => $branch instanceof Branch ? $branch->id : $branch]);
    }
}
