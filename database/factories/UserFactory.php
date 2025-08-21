<?php
namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * User factory for generating test users with realistic data
 * 
 * - 2025-05-01 - Andrew CEO Festival WiFi Guys - info+developer@festivalwifiguys.com
 */
class UserFactory extends Factory
{
    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
            'phone' => $this->faker->phoneNumber(),
            'birthday' => $this->faker->date(),
            'gender' => $this->faker->randomElement(['male', 'female', 'non-binary', 'other', 'prefer-not-to-say']),
            'bio' => $this->faker->paragraph(),
            'social_media' => [
                'twitter' => '@' . $this->faker->userName(),
                'linkedin' => 'linkedin.com/in/' . $this->faker->userName(),
                'facebook' => 'facebook.com/' . $this->faker->userName(),
                'instagram' => 'instagram.com/' . $this->faker->userName(),
            ],
            'occupation' => $this->faker->jobTitle(),
            'mailing_address' => [
                'street' => $this->faker->streetAddress(),
                'city' => $this->faker->city(),
                'state' => $this->faker->stateAbbr(),
                'zip' => $this->faker->postcode(),
                'country' => $this->faker->countryCode(),
            ],
            'billing_address' => [
                'street' => $this->faker->streetAddress(),
                'city' => $this->faker->city(),
                'state' => $this->faker->stateAbbr(),
                'zip' => $this->faker->postcode(),
                'country' => $this->faker->countryCode(),
            ],
            'account_level' => $this->faker->randomElement(['user', 'guest', 'manager', 'admin']),
            'provider' => $this->faker->optional(0.3)->randomElement(['google', 'facebook', 'twitter']),
            'provider_id' => function (array $attributes) {
                return $attributes['provider'] ? $this->faker->uuid() : null;
            },
            'provider_token' => function (array $attributes) {
                return $attributes['provider'] ? Str::random(40) : null;
            },
            'provider_refresh_token' => function (array $attributes) {
                return $attributes['provider'] ? Str::random(40) : null;
            },
            'provider_avatar' => function (array $attributes) {
                return $attributes['provider'] ? $this->faker->imageUrl(200, 200, 'people') : null;
            },
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => function (array $attributes) {
                return $this->faker->dateTimeBetween($attributes['created_at'], 'now');
            },
        ];
    }
}
