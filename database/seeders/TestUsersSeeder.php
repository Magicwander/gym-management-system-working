<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Exercise;
use Illuminate\Support\Facades\Hash;

class TestUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::updateOrCreate(
            ['email' => 'admin@hermesfitness.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'phone' => '+1234567890',
                'gender' => 'male',
                'address' => '123 Admin Street, City',
                'is_active' => true,
            ]
        );

        // Create Trainer User
        User::updateOrCreate(
            ['email' => 'trainer@hermesfitness.com'],
            [
                'name' => 'John Trainer',
                'password' => Hash::make('password'),
                'role' => 'trainer',
                'phone' => '+1234567891',
                'gender' => 'male',
                'address' => '456 Trainer Avenue, City',
                'is_active' => true,
            ]
        );

        // Create Test Members
        $members = [
            [
                'name' => 'Alice Johnson',
                'email' => 'alice@example.com',
                'phone' => '+1234567892',
                'gender' => 'female',
                'date_of_birth' => '1990-05-15',
                'address' => '789 Member Lane, City',
            ],
            [
                'name' => 'Bob Smith',
                'email' => 'bob@example.com',
                'phone' => '+1234567893',
                'gender' => 'male',
                'date_of_birth' => '1985-08-22',
                'address' => '321 Fitness Road, City',
            ],
            [
                'name' => 'Carol Davis',
                'email' => 'carol@example.com',
                'phone' => '+1234567894',
                'gender' => 'female',
                'date_of_birth' => '1992-12-03',
                'address' => '654 Health Street, City',
            ],
        ];

        foreach ($members as $memberData) {
            User::updateOrCreate(
                ['email' => $memberData['email']],
                array_merge($memberData, [
                    'password' => Hash::make('password'),
                    'role' => 'member',
                    'is_active' => true,
                ])
            );
        }

        // Create Sample Exercises
        $exercises = [
            [
                'name' => 'Push-ups',
                'description' => 'Classic upper body exercise targeting chest, shoulders, and triceps',
                'category' => 'strength',
                'muscle_groups' => 'Chest, Shoulders, Triceps',
                'equipment' => 'None',
                'difficulty' => 'beginner',
                'duration' => 15,
                'instructions' => 'Start in plank position, lower body to ground, push back up',
                'is_active' => true,
            ],
            [
                'name' => 'Squats',
                'description' => 'Lower body exercise targeting legs and glutes',
                'category' => 'strength',
                'muscle_groups' => 'Quadriceps, Glutes, Hamstrings',
                'equipment' => 'None',
                'difficulty' => 'beginner',
                'duration' => 20,
                'instructions' => 'Stand with feet shoulder-width apart, lower hips back and down, return to standing',
                'is_active' => true,
            ],
            [
                'name' => 'Running',
                'description' => 'Cardiovascular exercise for endurance and heart health',
                'category' => 'cardio',
                'muscle_groups' => 'Full Body',
                'equipment' => 'Treadmill or outdoor space',
                'difficulty' => 'intermediate',
                'duration' => 30,
                'instructions' => 'Maintain steady pace, focus on breathing and form',
                'is_active' => true,
            ],
            [
                'name' => 'Yoga Flow',
                'description' => 'Flexibility and mindfulness exercise',
                'category' => 'flexibility',
                'muscle_groups' => 'Full Body',
                'equipment' => 'Yoga mat',
                'difficulty' => 'beginner',
                'duration' => 45,
                'instructions' => 'Flow through poses focusing on breath and alignment',
                'is_active' => true,
            ],
            [
                'name' => 'Deadlifts',
                'description' => 'Compound strength exercise',
                'category' => 'strength',
                'muscle_groups' => 'Hamstrings, Glutes, Back',
                'equipment' => 'Barbell',
                'difficulty' => 'advanced',
                'duration' => 25,
                'instructions' => 'Lift barbell from ground to hip level with proper form',
                'is_active' => true,
            ],
        ];

        foreach ($exercises as $exerciseData) {
            Exercise::updateOrCreate(
                ['name' => $exerciseData['name']],
                $exerciseData
            );
        }

        echo "Test users and exercises created successfully!\n";
        echo "Login credentials:\n";
        echo "Admin: admin@hermesfitness.com / password\n";
        echo "Trainer: trainer@hermesfitness.com / password\n";
        echo "Members: alice@example.com, bob@example.com, carol@example.com / password\n";
    }
}
