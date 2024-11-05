<?php

use App\Enums\EventStatus;
use App\Models\User;
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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->index()->constrained();
            $table->string('name');
            $table->text('description');
            $table->string('status')->default(EventStatus::Draft);
            $table->string('country');
            $table->string('address');
            $table->string('city');
            $table->string('postal_code');
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->string('cover_image')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
