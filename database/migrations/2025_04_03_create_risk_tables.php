<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('risk_scan_results', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('bvn')->nullable();
            $table->string('nin')->nullable();
            $table->string('other_identifiable_code')->nullable();
            $table->string('other_identifiable_type')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('gender')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->longText('address')->nullable();
            $table->string('website')->nullable();
            $table->enum('risk_level', ['low', 'medium', 'high'])->default('low');
            $table->timestamp('scanned_at')->useCurrent();
            $table->timestamps();
        });

        Schema::create('risk_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('risk_scan_result_id')->constrained('risk_scan_results')->onDelete('cascade');
            $table->string('match_hash')->unique()->nullable();
            $table->string('source');
            $table->string('match_type');
            $table->text('source_url')->nullable();
            $table->text('description')->nullable();
            $table->longText('response_payload')->nullable();
            $table->unsignedTinyInteger('confidence')->default(50);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('risk_matches');
        Schema::dropIfExists('risk_scan_results');
    }
};
