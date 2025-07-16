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
            $table->unsignedBigInteger('user_id')->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->string('kind')->nullable()->index();
            $table->string('soundcloud_track_id', 255)->unique()->index();
            $table->string('urn')->unique()->nullable()->index();
            $table->bigInteger('duration')->default(0)->index();
            $table->boolean('commentable')->default(false);
            $table->bigInteger('comment_count')->default(0);
            $table->string('sharing')->nullable()->comment('public, private');
            $table->string('tag_lsit')->nullable()->index();
            $table->boolean('streamable')->default(false)->index();
            $table->string('embeddable_by')->nullable()->comment('all')->index();
            $table->text('purchase_url')->nullable()->index();
            $table->string('purchase_title')->nullable()->index();
            $table->string('genre')->nullable()->index();
            $table->string('title')->nullable()->index();
            $table->longText('description')->nullable();
            $table->string('label_name')->nullable()->index();
            $table->string('release')->nullable()->index();
            $table->string('key_signature')->nullable()->index();
            $table->string('isrc')->nullable()->index();
            $table->string('bpm')->nullable()->index();
            $table->string('release_year')->nullable()->index();
            $table->string('release_month')->nullable()->index();
            $table->string('license')->nullable()->index();
            $table->text('uri')->nullable()->index();

            $table->text('permalink_url')->nullable();
            $table->text('artwork_url')->nullable();
            $table->text('stream_url')->nullable();
            $table->text('download_url')->nullable();
            $table->text('waveform_url')->nullable();
            $table->string('available_country_codes')->nullable();
            $table->string('secret_uri')->nullable();
            $table->boolean('user_favorite')->default(false)->index();
            $table->bigInteger('user_playback_count')->default(0)->index();
            $table->bigInteger('playback_count')->default(0)->index();
            $table->bigInteger('download_count')->default(0)->index();
            $table->bigInteger('favoritings_count')->default(0)->index();
            $table->bigInteger('reposts_count')->default(0)->index();
            $table->boolean('downloadable')->default(false)->index();
            $table->string('access')->nullable()->index();
            $table->string('policy')->nullable();
            $table->string('monetization_model')->nullable();
            $table->string('metadata_artist')->nullable();
            $table->timestamp('created_at_soundcloud')->nullable();

            $table->string('type')->nullable()->index();

            // Author details
            $table->string('author_username')->nullable()->index();
            $table->bigInteger('author_soundcloud_id')->index();
            $table->string('author_soundcloud_urn')->index();
            $table->string('author_soundcloud_kind')->index();
            $table->string('author_soundcloud_permalink_url')->index();
            $table->string('author_soundcloud_permalink')->index();
            $table->string('author_soundcloud_uri')->index();

            $table->timestamp('last_sync_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $this->addMorphedAuditColumns($table);
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
