<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Exercise;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@hermesfitness.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '+94712369851',
            'gender' => 'male',
            'address' => '505/D, Sudarshana Mw, Katunayaka',
            'is_active' => true,
        ]);

        // Create trainer users
        User::create([
            'name' => 'Coach Madhava',
            'email' => 'madhava@hermesfitness.com',
            'password' => Hash::make('password'),
            'role' => 'trainer',
            'phone' => '+94712369852',
            'gender' => 'male',
            'address' => 'Colombo, Sri Lanka',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Coach Yohan',
            'email' => 'yohan@hermesfitness.com',
            'password' => Hash::make('password'),
            'role' => 'trainer',
            'phone' => '+94712369853',
            'gender' => 'male',
            'address' => 'Gampaha, Sri Lanka',
            'is_active' => true,
        ]);

        User::create([
            'name' => 'Coach Arun',
            'email' => 'arun@hermesfitness.com',
            'password' => Hash::make('password'),
            'role' => 'trainer',
            'phone' => '+94712369854',
            'gender' => 'male',
            'address' => 'Negombo, Sri Lanka',
            'is_active' => true,
        ]);

        // Create sample member
        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => Hash::make('password'),
            'role' => 'member',
            'phone' => '+94712369855',
            'gender' => 'male',
            'date_of_birth' => '1990-01-01',
            'address' => 'Colombo, Sri Lanka',
            'is_active' => true,
        ]);

        // Create sample exercises
        $exercises = [
            [
                'name' => 'Push-ups',
                'description' => 'Classic bodyweight exercise for chest, shoulders, and triceps',
                'category' => 'strength',
                'muscle_group' => 'chest',
                'difficulty_level' => 'beginner',
                'equipment_needed' => 'None',
                'instructions' => 'Start in plank position, lower body to ground, push back up',
                'duration_minutes' => 10,
                'calories_burned_per_minute' => 8,
            ],
            [
                'name' => 'Squats',
                'description' => 'Lower body exercise targeting quads, glutes, and hamstrings',
                'category' => 'strength',
                'muscle_group' => 'legs',
                'difficulty_level' => 'beginner',
                'equipment_needed' => 'None',
                'instructions' => 'Stand with feet shoulder-width apart, lower hips back and down, return to standing',
                'duration_minutes' => 15,
                'calories_burned_per_minute' => 10,
            ],
            [
                'name' => 'Treadmill Running',
                'description' => 'Cardiovascular exercise for endurance and weight loss',
                'category' => 'cardio',
                'muscle_group' => 'full_body',
                'difficulty_level' => 'intermediate',
                'equipment_needed' => 'Treadmill',
                'instructions' => 'Start with warm-up walk, gradually increase speed to running pace',
                'duration_minutes' => 30,
                'calories_burned_per_minute' => 12,
            ],
            [
                'name' => 'Deadlifts',
                'description' => 'Compound exercise for posterior chain strength',
                'category' => 'strength',
                'muscle_group' => 'back',
                'difficulty_level' => 'advanced',
                'equipment_needed' => 'Barbell, Weight plates',
                'instructions' => 'Stand with feet hip-width apart, grip bar, lift by extending hips and knees',
                'duration_minutes' => 20,
                'calories_burned_per_minute' => 15,
            ],
            [
                'name' => 'Plank',
                'description' => 'Core strengthening isometric exercise',
                'category' => 'strength',
                'muscle_group' => 'core',
                'difficulty_level' => 'beginner',
                'equipment_needed' => 'None',
                'instructions' => 'Hold body in straight line from head to heels, engage core',
                'duration_minutes' => 5,
                'calories_burned_per_minute' => 5,
            ],
        ];

        foreach ($exercises as $exercise) {
            Exercise::create($exercise);
        }
    }
}
