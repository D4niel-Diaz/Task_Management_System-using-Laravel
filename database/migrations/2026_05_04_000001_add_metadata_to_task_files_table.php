<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// This migration is superseded by the consolidated task_files migration.
// It is kept as a no-op to avoid migration errors.
return new class extends Migration
{
    public function up(): void
    {
        // Already included in 2026_03_30_131313_create_task_files_table.php
    }

    public function down(): void
    {
        // No-op
    }
};
