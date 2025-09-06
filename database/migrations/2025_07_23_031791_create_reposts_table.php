<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Http\Traits\AuditColumnsTrait;
use App\Models\Repost;
use Illuminate\Database\Eloquent\SoftDeletes;

return new class extends Migration
{
    use AuditColumnsTrait, SoftDeletes;
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reposts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sort_order')->default(0);

            $table->unsignedBigInteger('repost_request_id')->index()->nullable();
            $table->string('reposter_urn')->index();
            $table->string('track_owner_urn')->index();
            $table->unsignedBigInteger('campaign_id')->nullable()->index();
            $table->string('soundcloud_repost_id')->index()->nullable();
            $table->decimal('credits_earned', 10, 2)->default(0.00);
            $table->timestamp('reposted_at')->index();
            $table->integer('like_count')->default(0);
            $table->integer('comment_count')->default(0);
            $table->integer('followowers_count')->default(0);

            $table->foreign('repost_request_id')->references('id')->on('repost_requests')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('reposter_urn')->references('urn')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('track_owner_urn')->references('urn')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('reposts');
    }
};
