<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Http\Traits\AuditColumnsTrait;
use App\Models\RepostRequest;
use Illuminate\Database\Eloquent\SoftDeletes;

return new class extends Migration
{
    use AuditColumnsTrait, SoftDeletes;
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('repost_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sort_order')->default(0);

            $table->string('requester_urn')->index();
            $table->string('target_user_urn')->index();
            $table->unsignedBigInteger('campaign_id')->nullable()->index();
            $table->string('track_urn')->nullable()->index();

            $table->decimal('credits_spent', 10, 2)->default(0.00);
            $table->tinyInteger('status')->default(RepostRequest::STATUS_PENDING)->index();
            $table->text('rejection_reason')->nullable();
            $table->timestamp('requested_at')->default(now())->index();
            $table->timestamp('expired_at')->nullable()->index();
            $table->timestamp('responded_at')->nullable()->index();
            $table->timestamp('completed_at')->nullable()->index();

            $table->boolean('following')->default(false);
            $table->string('comment_note')->nullable();
            $table->boolean('likeable')->default(false);
            
            $table->foreign('target_user_urn')->references('urn')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('requester_urn')->references('urn')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('track_urn')->references('urn')->on('tracks')->onDelete('cascade')->onUpdate('cascade');


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
        Schema::dropIfExists('repost_requests');
    }
};
