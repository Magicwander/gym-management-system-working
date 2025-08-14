<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('specialization')->nullable()->after('fitness_goals');
            $table->integer('experience_years')->nullable()->after('specialization');
            $table->string('certification')->nullable()->after('experience_years');
            $table->decimal('hourly_rate', 8, 2)->nullable()->after('certification');
            $table->text('bio')->nullable()->after('hourly_rate');
            $table->string('profile_image')->nullable()->after('bio');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'specialization',
                'experience_years', 
                'certification',
                'hourly_rate',
                'bio',
                'profile_image'
            ]);
        });
    }
};