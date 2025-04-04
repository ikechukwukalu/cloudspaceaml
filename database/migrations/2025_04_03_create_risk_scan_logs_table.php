<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('risk_scan_logs', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('bvn')->nullable();
            $table->string('nin')->nullable();
            $table->enum('risk_level', ['low', 'medium', 'high'])->default('low');
            $table->unsignedInteger('match_count')->default(0);
            $table->json('summary')->nullable(); // basic metadata
            $table->timestamp('scanned_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('risk_scan_logs');
    }
};
