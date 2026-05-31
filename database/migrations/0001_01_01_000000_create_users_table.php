<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Don't wrap this migration in a transaction
     */
    public $withinTransaction = false;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create users table without unique constraint
        DB::statement('
            CREATE TABLE users (
                id BIGSERIAL PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL,
                email_verified_at TIMESTAMP NULL,
                password VARCHAR(255) NOT NULL,
                remember_token VARCHAR(100),
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL
            )
        ');

        // Add unique constraint separately
        DB::statement('ALTER TABLE users ADD CONSTRAINT users_email_unique UNIQUE(email)');

        // Create password_reset_tokens table
        DB::statement('
            CREATE TABLE password_reset_tokens (
                email VARCHAR(255) PRIMARY KEY,
                token VARCHAR(255) NOT NULL,
                created_at TIMESTAMP NULL
            )
        ');

        // Create sessions table
        DB::statement('
            CREATE TABLE sessions (
                id VARCHAR(255) PRIMARY KEY,
                user_id BIGINT,
                ip_address VARCHAR(45),
                user_agent TEXT,
                payload TEXT NOT NULL,
                last_activity INTEGER NOT NULL
            )
        ');

        // Create indexes
        DB::statement('CREATE INDEX sessions_user_id_index ON sessions(user_id)');
        DB::statement('CREATE INDEX sessions_last_activity_index ON sessions(last_activity)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
