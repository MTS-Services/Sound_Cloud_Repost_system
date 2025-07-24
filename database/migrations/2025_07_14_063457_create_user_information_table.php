<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Http\Traits\AuditColumnsTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

return new class extends Migration
{
    use AuditColumnsTrait, SoftDeletes;
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_information', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('sort_order')->default(0)->unsigned();

            $table->string('user_urn')->unique();
            $table->foreign('user_urn')->references('urn')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->string('first_name')->index();
            $table->string('last_name')->nullable()->index();
            $table->string('full_name')->nullable()->index();
            $table->string('username')->nullable()->index();

            $table->unsignedBigInteger('soundcloud_id')->unique();
            $table->string('soundcloud_urn')->unique();
            $table->string('soundcloud_kind');
            $table->string('soundcloud_permalink_url')->unique();
            $table->string('soundcloud_permalink')->unique();
            $table->string('soundcloud_uri')->unique();

            $table->timestamp('soundcloud_created_at');
            $table->timestamp('soundcloud_last_modified')->nullable();

            $table->text('description')->nullable();
            $table->string('country')->nullable()->index();
            $table->string('city')->nullable()->index();

            $table->bigInteger('track_count')->default(0)->unsigned();
            $table->bigInteger('public_favorites_count')->default(0)->unsigned();
            $table->bigInteger('reposts_count')->default(0)->unsigned();
            $table->bigInteger('followers_count')->default(0)->unsigned();
            $table->bigInteger('following_count')->default(0)->unsigned();

            $table->string('plan');
            $table->string('myspace_name')->nullable();
            $table->string('discogs_name')->nullable();
            $table->string('website_title')->nullable();
            $table->string('website')->nullable();

            $table->boolean('online')->default(false)->index();
            $table->bigInteger('comments_count')->default(0)->unsigned();
            $table->bigInteger('like_count')->default(0)->unsigned();
            $table->bigInteger('playlist_count')->default(0)->unsigned();
            $table->bigInteger('private_playlist_count')->default(0)->unsigned();
            $table->bigInteger('private_tracks_count')->default(0)->unsigned();

            $table->boolean('primary_email_confirmed')->default(false)->index();
            $table->string('local')->nullable();
            $table->bigInteger('upload_seconds_left')->nullable();

            $table->timestamps();
            $table->softDeletes();
            $this->addMorphedAuditColumns($table);           


            $table->index(['followers_count']);
            $table->index(['track_count']);
            $table->index(['created_at']);

            $table->index(['soundcloud_id', 'country'], 'idx_soundcloud_country');
            $table->index(['city', 'country'], 'idx_city_country');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_information');
    }
};
