<?php

use App\Enums\HealthProfessionalStatus;
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
        Schema::create('health_professional_services', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(HealthProfessional::class)
                ->constrained('health_professionals')
                ->cascadeOnDelete();

            $table->foreignIdFor(Service::class)
                ->constrained('services')
                ->cascadeOnDelete();

            // Pivot fields
            $table->decimal('price', 10, 2)->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default(HealthProfessionalStatus::AVAILABLE->value);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('health_professional_services');
    }
};
