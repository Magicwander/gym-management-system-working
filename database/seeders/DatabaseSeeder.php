<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@hermesfitness.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
            'email_verified_at' => now(),
        ]);

        // Create Trainer Users
        User::create([
            'name' => 'Madhava Trainer',
            'email' => 'madhava@hermesfitness.com',
            'password' => bcrypt('password'),
            'role' => 'trainer',
            'email_verified_at' => now(),
            'specialization' => 'Weight Training',
            'experience_years' => 5,
            'certification' => 'NASM-CPT',
            'hourly_rate' => 50.00,
            'bio' => 'Experienced personal trainer specializing in weight training and muscle building.',
        ]);

        User::create([
            'name' => 'Sarah Johnson',
            'email' => 'sarah@hermesfitness.com',
            'password' => bcrypt('password'),
            'role' => 'trainer',
            'email_verified_at' => now(),
            'specialization' => 'Cardio & HIIT',
            'experience_years' => 3,
            'certification' => 'ACE-CPT',
            'hourly_rate' => 45.00,
            'bio' => 'Energetic trainer focused on cardio workouts and high-intensity interval training.',
        ]);

        // Create Member Users
        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
            'role' => 'member',
            'email_verified_at' => now(),
            'phone' => '555-0123',
            'date_of_birth' => '1990-05-15',
            'gender' => 'male',
            'address' => '123 Main St, City, State 12345',
        ]);

        User::create([
            'name' => 'Emily Smith',
            'email' => 'emily@example.com',
            'password' => bcrypt('password'),
            'role' => 'member',
            'email_verified_at' => now(),
            'phone' => '555-0125',
            'date_of_birth' => '1985-08-22',
            'gender' => 'female',
            'address' => '456 Oak Ave, City, State 12345',
        ]);

        // Create some exercises
        \App\Models\Exercise::create([
            'name' => 'Push-ups',
            'description' => 'Classic bodyweight exercise for chest, shoulders, and triceps',
            'category' => 'strength',
            'muscle_group' => 'chest',
            'difficulty_level' => 'beginner',
            'equipment_needed' => 'None',
            'instructions' => 'Start in plank position, lower body to ground, push back up',
            'duration_minutes' => 5,
            'calories_burned_per_minute' => 8,
            'is_active' => true,
        ]);

        \App\Models\Exercise::create([
            'name' => 'Squats',
            'description' => 'Fundamental lower body exercise',
            'category' => 'strength',
            'muscle_group' => 'legs',
            'difficulty_level' => 'beginner',
            'equipment_needed' => 'None',
            'instructions' => 'Stand with feet shoulder-width apart, lower hips back and down, return to standing',
            'duration_minutes' => 5,
            'calories_burned_per_minute' => 10,
            'is_active' => true,
        ]);

        \App\Models\Exercise::create([
            'name' => 'Deadlifts',
            'description' => 'Compound exercise for posterior chain',
            'category' => 'strength',
            'muscle_group' => 'back',
            'difficulty_level' => 'intermediate',
            'equipment_needed' => 'Barbell',
            'instructions' => 'Stand with feet hip-width apart, hinge at hips, lift barbell from ground',
            'duration_minutes' => 10,
            'calories_burned_per_minute' => 12,
            'is_active' => true,
        ]);
    }
}
