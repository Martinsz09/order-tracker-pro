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
        DB::statement('
            CREATE TABLE orders (
                id BIGSERIAL PRIMARY KEY,
                user_id BIGINT NOT NULL,
                product_name VARCHAR(255) NOT NULL,
                tracking_code VARCHAR(255) NOT NULL UNIQUE,
                origin_address VARCHAR(255) NOT NULL,
                destination_address VARCHAR(255) NOT NULL,
                latitude_origem DECIMAL(10, 6),
                longitude_origem DECIMAL(10, 6),
                latitude_destino DECIMAL(10, 6),
                longitude_destino DECIMAL(10, 6),
                created_at TIMESTAMP NULL,
                updated_at TIMESTAMP NULL,
                CONSTRAINT orders_user_id_foreign FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE
            )
        ');

        DB::statement('CREATE INDEX orders_user_id_index ON orders(user_id)');
        DB::statement('CREATE INDEX orders_tracking_code_unique ON orders(tracking_code)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP TABLE IF EXISTS orders CASCADE');
    }
};