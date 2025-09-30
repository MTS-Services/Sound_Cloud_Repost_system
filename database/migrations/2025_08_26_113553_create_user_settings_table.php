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
        Schema::create('user_settings', function (Blueprint $table) {
            $table->id();
            $table->string('user_urn')->index();

            // Alerts (email)
            $table->boolean('em_new_repost')->default(true);
            $table->boolean('em_repost_accepted')->default(true);
            $table->boolean('em_repost_declined')->default(true);
            $table->boolean('em_repost_expired')->default(true);
            $table->boolean('em_campaign_summary')->default(true);
            $table->boolean('em_free_boost')->default(true);
            $table->boolean('em_feedback_campaign')->default(true);
            $table->boolean('em_feedback_rated')->default(true);
            $table->boolean('em_referrals')->default(true);
            $table->boolean('em_reputation')->default(true);
            $table->boolean('em_inactivity_warn')->default(true);
            $table->boolean('em_marketing')->default(false);
            $table->boolean('em_chart_entry')->default(false);
            $table->boolean('em_mystery_box')->default(true);
            $table->boolean('em_discussions')->default(true);
            $table->boolean('em_competitions')->default(true);

            // Alerts (push)
            $table->boolean('ps_new_repost')->default(false);
            $table->boolean('ps_repost_accepted')->default(false);
            $table->boolean('ps_repost_declined')->default(false);
            $table->boolean('ps_repost_expired')->default(false);
            $table->boolean('ps_campaign_summary')->default(false);
            $table->boolean('ps_free_boost')->default(false);
            $table->boolean('ps_feedback_campaign')->default(false);
            $table->boolean('ps_feedback_rated')->default(false);
            $table->boolean('ps_referrals')->default(false);
            $table->boolean('ps_reputation')->default(false);
            $table->boolean('ps_inactivity_warn')->default(false);
            $table->boolean('ps_marketing')->default(false);
            $table->boolean('ps_chart_entry')->default(false);
            $table->boolean('ps_mystery_box')->default(false);
            $table->boolean('ps_discussions')->default(false);
            $table->boolean('ps_competitions')->default(false);

            // My Requests
            $table->boolean('accept_repost')->default(false);
            $table->boolean('block_mismatch_genre')->default(false);

            // Additional Features
            $table->boolean('opt_mystery_box')->default(false);
            $table->boolean('auto_boost')->default(false);
            $table->boolean('enable_react')->default(true);

            // Subscription
            $table->string('sub_plan')->default('Free Forever Plan');

            // Reset Response Rate
            $table->timestamp('response_rate_reset')->nullable();

            $table->foreign('user_urn')
                ->references('urn')->on('users')
                ->onDelete('cascade')->onUpdate('cascade');

            $table->timestamps();
            $table->softDeletes();
            $this->addAdminAuditColumns($table);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_settings');
    }
};
