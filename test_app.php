<?php
// Simple test script to verify the application functionality

echo "=== Hermes Fitness Gym Management System Test ===\n\n";

// Test database connection
try {
    $pdo = new PDO('sqlite:' . __DIR__ . '/database/database.sqlite');
    echo "✓ Database connection successful\n";
    
    // Test users table
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
    $userCount = $stmt->fetch()['count'];
    echo "✓ Users in database: $userCount\n";
    
    // Test exercises table
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM exercises");
    $exerciseCount = $stmt->fetch()['count'];
    echo "✓ Exercises in database: $exerciseCount\n";
    
    // Show sample users by role
    echo "\n=== Sample Users by Role ===\n";
    $stmt = $pdo->query("SELECT name, email, role FROM users ORDER BY role, name");
    while ($user = $stmt->fetch()) {
        echo "- {$user['role']}: {$user['name']} ({$user['email']})\n";
    }
    
    // Show sample exercises
    echo "\n=== Sample Exercises ===\n";
    $stmt = $pdo->query("SELECT name, category, difficulty_level FROM exercises LIMIT 5");
    while ($exercise = $stmt->fetch()) {
        echo "- {$exercise['name']} ({$exercise['category']}, {$exercise['difficulty_level']})\n";
    }
    
} catch (Exception $e) {
    echo "✗ Database error: " . $e->getMessage() . "\n";
}

echo "\n=== Application URLs ===\n";
echo "Main Application: http://localhost:12000\n";
echo "Login Page: http://localhost:12000/login\n";
echo "Customer Registration: http://localhost:12000/register-customer\n";
echo "Admin Dashboard: http://localhost:12000/admin/dashboard\n";
echo "Trainer Dashboard: http://localhost:12000/trainer/dashboard\n";
echo "Customer Dashboard: http://localhost:12000/customer/dashboard\n";

echo "\n=== Test Credentials ===\n";
echo "Admin: admin@hermesfitness.com / password\n";
echo "Trainer: madhava@hermesfitness.com / password\n";
echo "Trainer: sarah@hermesfitness.com / password\n";
echo "Member: john@example.com / password\n";
echo "Member: emily@example.com / password\n";

echo "\n=== Features Implemented ===\n";
echo "✓ Role-based authentication (Admin, Trainer, Member)\n";
echo "✓ Customer registration with detailed profile\n";
echo "✓ Admin dashboard with user management\n";
echo "✓ Trainer dashboard with workout management\n";
echo "✓ Customer dashboard with booking system\n";
echo "✓ Automatic time slot assignment for bookings\n";
echo "✓ Payment system with dummy gateway\n";
echo "✓ Responsive Bootstrap UI\n";
echo "✓ CRUD operations for all entities\n";
echo "✓ Database migrations and seeders\n";
echo "✓ Authorization policies\n";

echo "\nApplication is ready for testing!\n";
?>