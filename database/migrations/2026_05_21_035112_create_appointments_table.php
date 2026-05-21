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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pet_id')->constrained('pets')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // veterinarian
            $table->dateTime('scheduled_at');                 // Date and time of the appointment
            $table->enum('reason', [
                'consulta_general',
                'vacunacion',
                'cirugia',
                'revision_post_operatoria',
                'urgencia',
                'otro',
            ])->default('consulta_general');
            $table->text('notes')->nullable();               // Optional owner/vet notes
            $table->enum('status', [
                'pendiente',
                'confirmada',
                'completada',
                'cancelada',
            ])->default('pendiente');
            $table->boolean('reminder_sent')->default(false); // Whether reminder email was sent
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
