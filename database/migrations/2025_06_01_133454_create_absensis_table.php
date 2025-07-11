<?php

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
        Schema::create('absensis', function (Blueprint $table) {
            $table->id();
            
 $table->foreignId('karyawan_id')->constrained('karyawans')->onDelete('cascade');
            $table->string('name');    
            $table->string('tipe'); 
            $table->string('foto');   
            $table->string('lokasi');   
            $table->string('jam');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensis');
    }
};
