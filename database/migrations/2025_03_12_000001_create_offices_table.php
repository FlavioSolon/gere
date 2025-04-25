<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('cnpj')->unique();
            $table->string('razao_social');
            $table->string('certificate_path')->nullable();
            $table->binary('certificate_data')->nullable();
            $table->text('certificate_password')->nullable();
            $table->integer('balance')->default(0);
            $table->integer('subscription_cnpjs')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offices');
    }
};
