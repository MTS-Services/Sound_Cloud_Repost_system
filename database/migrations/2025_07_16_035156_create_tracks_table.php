<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Http\Traits\AuditColumnsTrait;

return new class extends Migration
{
    use AuditColumnsTrait;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tracks', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->string('kind')->nullable();
            $table->string('soundcloud_track_id', 255)->unique();
            $table->string('urn')->unique()->nullable();
            $table->bigInteger('duration')->default(0);
            $table->boolean('commentable')->default(false);
            $table->bigInteger('comment_count')->default(0);
            $table->unsignedInteger('likes_count')->default(0);
            $table->string('sharing')->nullable()->comment('public, private');
            $table->boolean('streamable')->default(false);
            $table->string('embeddable_by')->nullable()->comment('all');
            $table->text('purchase_url')->nullable();
            $table->string('purchase_title')->nullable();
            $table->string('genre')->nullable();
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->string('label_name')->nullable();
            $table->string('release')->nullable();
            $table->string('key_signature')->nullable();
            $table->string('isrc')->nullable();
            $table->string('bpm')->nullable();
            $table->string('release_year')->nullable();
            $table->string('release_month')->nullable();
            $table->string('license')->nullable();
            $table->text('uri')->nullable();

            $table->text('permalink_url')->nullable();
            $table->text('artwork_url')->nullable();
            $table->text('stream_url')->nullable();
            $table->text('download_url')->nullable();
            $table->text('waveform_url')->nullable();
            $table->string('available_country_codes')->nullable();
            $table->string('secret_uri')->nullable();
            $table->boolean('user_favorite')->default(false);
            $table->bigInteger('user_playback_count')->default(0);
            $table->bigInteger('playback_count')->default(0);
            $table->bigInteger('download_count')->default(0);
            $table->bigInteger('favoritings_count')->default(0);
            $table->bigInteger('reposts_count')->default(0);
            $table->boolean('downloadable')->default(false);
            $table->string('access')->nullable();
            $table->string('policy')->nullable();
            $table->string('monetization_model')->nullable();
            $table->string('metadata_artist')->nullable();

            $table->boolean('is_public')->default(true);
            $table->boolean('is_streamable')->default(true);
            $table->boolean('is_downloadable')->default(false);
            $table->boolean('is_monetized')->default(false);
            $table->date('release_date')->nullable();

            $table->boolean('is_promotable')->default(true);
            $table->tinyInteger('promotion_priority')->default(1);

            $table->timestamp('created_at_soundcloud')->nullable();
            $table->timestamp('last_sync_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $this->addAdminAuditColumns($table);

            // Indexes
            $table->index('user_id');
            $table->index('soundcloud_track_id');
            $table->index('title');
            $table->index('genre');
            $table->index('is_public');
            $table->index('is_promotable');
            $table->index('playback_count');
            $table->index('created_at_soundcloud');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tracks');
    }
};
