<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('aml_checks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('gender');
            $table->date('birthDate');
            $table->string('bvn')->nullable();
            $table->string('nin')->nullable();
            $table->string('passport_number')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();
            $table->text('residential_address')->nullable();
            $table->json('response')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('aml_checks');
    }
};
