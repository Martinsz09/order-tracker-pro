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
            CREATE TABLE jobs (
                id BIGSERIAL PRIMARY KEY,
                queue VARCHAR(255) NOT NULL,
                payload TEXT NOT NULL,
                attempts SMALLINT NOT NULL,
                reserved_at INTEGER,
                available_at INTEGER NOT NULL,
                created_at INTEGER NOT NULL
            )
        ');

        DB::statement('CREATE INDEX jobs_queue_index ON jobs(queue)');

        DB::statement('
            CREATE TABLE job_batches (
                id VARCHAR(255) PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                total_jobs INTEGER NOT NULL,
                pending_jobs INTEGER NOT NULL,
                failed_jobs INTEGER NOT NULL,
                failed_job_ids TEXT NOT NULL,
                options TEXT,
                cancelled_at INTEGER,
                created_at INTEGER NOT NULL,
                finished_at INTEGER
            )
        ');

        DB::statement('
            CREATE TABLE failed_jobs (
                id BIGSERIAL PRIMARY KEY,
                uuid VARCHAR(255) NOT NULL UNIQUE,
                connection VARCHAR(255) NOT NULL,
                queue VARCHAR(255) NOT NULL,
                payload TEXT NOT NULL,
                exception TEXT NOT NULL,
                failed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
            )
        ');

        DB::statement('CREATE INDEX failed_jobs_uuid_unique ON failed_jobs(uuid)');
        DB::statement('CREATE INDEX failed_jobs_connection_queue_failed_at ON failed_jobs(connection, queue, failed_at)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP TABLE IF EXISTS jobs CASCADE');
        DB::statement('DROP TABLE IF EXISTS job_batches CASCADE');
        DB::statement('DROP TABLE IF EXISTS failed_jobs CASCADE');
    }
};
