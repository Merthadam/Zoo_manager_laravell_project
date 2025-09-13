<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Animal;
use App\Models\Enclosure;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('animals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('species');
            $table->dateTime('born_at')->nullable();
            $table->boolean('is_predator')->default(false);
            $table->string('image_path')->nullable();
            $table->foreignId('enclosure_id')->nullable()->constrained()->nullOnDelete(); 
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('animals');
        $table->dropSoftDeletes();
    }
};
