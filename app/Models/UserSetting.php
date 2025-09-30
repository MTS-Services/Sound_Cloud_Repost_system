<?php

namespace App\Models;

use App\Models\BaseModel;

class UserSetting extends BaseModel
{
    protected $table = 'user_settings';

    protected $fillable = [
        'user_urn',

        // Alerts (email)
        'em_new_repost',
        'em_repost_accepted',
        'em_repost_declined',
        'em_repost_expired',
        'em_campaign_summary',
        'em_free_boost',
        'em_feedback_campaign',
        'em_feedback_rated',
        'em_referrals',
        'em_reputation',
        'em_inactivity_warn',
        'em_marketing',
        'em_chart_entry',
        'em_mystery_box',
        'em_discussions',
        'em_competitions',

        // Alerts (push)
        'ps_new_repost',
        'ps_repost_accepted',
        'ps_repost_declined',
        'ps_repost_expired',
        'ps_campaign_summary',
        'ps_free_boost',
        'ps_feedback_campaign',
        'ps_feedback_rated',
        'ps_referrals',
        'ps_reputation',
        'ps_inactivity_warn',
        'ps_marketing',
        'ps_chart_entry',
        'ps_mystery_box',
        'ps_discussions',
        'ps_competitions',

        // My Requests
        'accept_repost',
        'block_mismatch_genre',

        // Additional Features
        'opt_mystery_box',
        'auto_boost',
        'enable_react',

        // Subscription
        'sub_plan',
        // Response Rate Reset
        'response_rate_reset',
    ];


    /**
     * Relation with User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeSelf($query)
    {
        return $query->where('user_urn', user()->urn);
    }
}
