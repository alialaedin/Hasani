<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('mobile', 20);
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        $admin = [
            'name' => 'admin',
            'mobile' => '09368917169',
            'password' => bcrypt(123456)
        ];

        User::create($admin);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
