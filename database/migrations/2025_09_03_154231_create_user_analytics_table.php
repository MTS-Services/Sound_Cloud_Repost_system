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
        Schema::create('user_analytics', function (Blueprint $table) {
            $table->id();

            $table->string('user_urn');
            $table->foreign('user_urn')->references('urn')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->string('track_urn');
            $table->foreign('track_urn')->references('urn')->on('tracks')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('campaign_id')->nullable();
            $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade')->onUpdate('cascade');

            $table->date('date');
            $table->string('genre');

            $table->unsignedBigInteger('total_requests')->default(0);
            $table->unsignedBigInteger('total_plays')->default(0);
            $table->unsignedBigInteger('total_followers')->default(0);
            $table->unsignedBigInteger('total_likes')->default(0);
            $table->unsignedBigInteger('total_reposts')->default(0);
            $table->unsignedBigInteger('total_comments')->default(0);
            $table->unsignedBigInteger('total_views')->default(0);            

            $table->timestamps();
            $table->softDeletes();
            $this->addMorphedAuditColumns($table);

            $table->unique(['campaign_id', 'track_urn', 'user_urn', 'date'], 'user_analytics_unique_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_analytics');
    }
};
