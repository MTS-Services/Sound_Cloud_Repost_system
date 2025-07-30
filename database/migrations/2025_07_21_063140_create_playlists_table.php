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
        Schema::create('playlists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sort_order')->default(0);
            $table->string('user_urn')->index();
            $table->unsignedBigInteger('duration')->nullable();
            $table->unsignedBigInteger('label_id')->nullable();
            $table->string('genre')->nullable();
            $table->integer('release_day')->nullable();
            $table->string('permalink')->nullable();
            $table->string('permalink_url')->nullable();
            $table->integer('release_month')->nullable();
            $table->integer('release_year')->nullable();
            $table->longText('description')->nullable();
            $table->string('uri')->nullable();
            $table->string('label_name')->nullable();
            $table->string('label')->nullable();
            $table->text('tag_list')->nullable();
            $table->integer('track_count')->nullable();
            $table->timestamp('last_modified')->nullable();
            $table->string('license')->nullable();

            $table->string('playlist_type')->nullable();
            $table->string('type')->nullable();
            $table->unsignedBigInteger('soundcloud_id');
            $table->string('soundcloud_urn')->unique();
            $table->boolean('downloadable')->nullable();
            $table->integer('likes_count')->default(0);
            $table->string('sharing')->nullable();
            $table->timestamp('soundcloud_created_at')->nullable();
            $table->string('release')->nullable();
            $table->text('tags')->nullable();
            $table->string('soundcloud_kind')->nullable();
            $table->string('title');
            $table->string('purchase_title')->nullable();
            $table->string('ean')->nullable();
            $table->boolean('streamable')->default(true);
            $table->string('embeddable_by')->nullable();
            $table->string('artwork_url')->nullable();
            $table->string('purchase_url')->nullable();
            $table->string('tracks_uri')->nullable();
            $table->string('secret_token')->nullable();
            $table->string('secret_uri')->nullable();

            $table->timestamps();
            $table->softDeletes();
            
            $this->addMorphedAuditColumns($table);

             $table->foreign('user_urn')->references('urn')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('playlists');
    }
};
