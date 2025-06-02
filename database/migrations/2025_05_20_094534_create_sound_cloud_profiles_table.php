<?php

use App\Http\Traits\AuditColumnsTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    use SoftDeletes, AuditColumnsTrait;
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sound_cloud_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('soundcloud_url')->nullable();
            $table->text('description')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->json('genres')->nullable();
            $table->boolean('is_pro')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->timestamps();
            $table->softDeletes();
            $this->addMorphedAuditColumns($table);

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sound_cloud_profiles');
    }
};
