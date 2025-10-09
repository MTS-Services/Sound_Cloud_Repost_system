<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Http\Traits\AuditColumnsTrait;
use App\Models\Campaign;
use Illuminate\Database\Eloquent\SoftDeletes;

return new class extends Migration {
    use AuditColumnsTrait, SoftDeletes;
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sort_order')->default(0);
            $table->string('user_urn')->index();
            $table->unsignedBigInteger('music_id')->index();
            $table->string('music_type')->index();

            $table->boolean('commentable')->default(false);
            $table->boolean('likeable')->default(false);
            $table->integer('max_repost_last_24_h')->nullable();
            $table->integer('max_repost_per_day')->nullable();
            $table->string('target_genre')->nullable();
            $table->boolean('pro_feature')->default(false);
            $table->integer('like_count')->default(0);
            $table->integer('comment_count')->default(0);
            $table->integer('favorite_count')->default(0);
            $table->integer('followers_count')->default(0);

            $table->string('title');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('completed_reposts')->default(0);
            $table->decimal('momentum_price', 15, 2)->default(0.00);
            $table->decimal('budget_credits', 15, 2);
            $table->decimal('credits_spent', 15, 2)->default(0.00);
            $table->decimal('refund_credits', 15, 2)->default(0.00);
            $table->unsignedBigInteger('min_followers')->nullable()->index();
            $table->unsignedBigInteger('max_followers')->nullable()->index();
            $table->unsignedBigInteger('playback_count')->index()->default(0);
            $table->tinyInteger('status')->index()->default(Campaign::STATUS_OPEN);
            $table->timestamp('start_date')->index()->nullable();
            $table->timestamp('end_date')->index()->nullable();

            $table->boolean('is_featured')->default(Campaign::NOT_FEATURED);
            $table->timestamp('featured_at')->nullable();

            $table->boolean('is_boost')->default(false);
            $table->timestamp('boosted_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
            $this->addMorphedAuditColumns($table);

            $table->foreign('user_urn')->references('urn')->on('users')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
