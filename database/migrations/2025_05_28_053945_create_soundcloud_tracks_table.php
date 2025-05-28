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
        Schema::create('soundcloud_tracks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('soundcloud_track_id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('permalink');
            $table->string('permalink_url');
            $table->string('artwork_url')->nullable();
            $table->integer('duration')->nullable();
            $table->string('genre')->nullable();
            $table->json('tag_list')->nullable();
            $table->integer('playback_count')->default(0);
            $table->integer('likes_count')->default(0);
            $table->integer('reposts_count')->default(0);
            $table->integer('comment_count')->default(0);
            $table->json('track_data')->nullable(); // Store full track data
            $table->boolean('is_active')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $this->addMorphedAuditColumns($table);

            $table->unique(['user_id', 'soundcloud_track_id']);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');




        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soundcloud_tracks');
    }
};
