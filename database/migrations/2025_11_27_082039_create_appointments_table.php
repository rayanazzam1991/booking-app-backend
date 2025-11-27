<?php

use App\Models\HealthProfessional;
use App\Models\Service;
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

            $table->foreignIdFor(Service::class)
                ->constrained('services')
                ->onDelete('cascade');

            $table->foreignIdFor(HealthProfessional::class)
                ->constrained('health_professionals')
                ->onDelete('cascade');

            $table->dateTime('scheduled_at');

            $table->string('customer_email');

            $table->softDeletes();
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
