<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\UserRole;
use App\Models\Branch;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

/** @extends Factory<User> */
class UserFactory extends Factory
{
    protected $model = User::class;

    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'branch_id' => null,
            'is_active' => true,
        ];
    }

    public function unverified(): static
    {
        return $this->state(['email_verified_at' => null]);
    }

    public function inactive(): static
    {
        return $this->state(['is_active' => false]);
    }

    public function hq(): static
    {
        return $this->afterCreating(fn (User $user) => $user->assignRole($this->roleName(UserRole::HqAdmin)));
    }

    public function manager(Branch|int|null $branch = null): static
    {
        return $this->branchUser(UserRole::BranchManager, $branch);
    }

    public function cashier(Branch|int|null $branch = null): static
    {
        return $this->branchUser(UserRole::BranchCashier, $branch);
    }

    private function branchUser(UserRole $role, Branch|int|null $branch): static
    {
        return $this
            ->state(fn () => ['branch_id' => $branch instanceof Branch ? $branch->id : ($branch ?? Branch::factory())])
            ->afterCreating(fn (User $user) => $user->assignRole($this->roleName($role)));
    }

    private function roleName(UserRole $role): string
    {
        return Role::findOrCreate($role->value, 'web')->name;
    }
}
