<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('user_plans', function (Blueprint $table) {
            $table->string('stripe_subscription_id')->nullable()->after('order_id');
            $table->string('paypal_subscription_id')->nullable()->after('stripe_subscription_id');
            $table->boolean('auto_renew')->default(true)->after('status');
            $table->timestamp('next_billing_date')->nullable()->after('end_date');
            $table->string('billing_cycle')->default('monthly')->after('duration'); // 'monthly' or 'yearly'
            $table->timestamp('canceled_at')->nullable()->after('deleted_at');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->string('subscription_id')->nullable()->after('payment_intent_id');
            $table->boolean('is_recurring')->default(false)->after('status');
        });
        Schema::table('users', function (Blueprint $table) {
            $table->string('stripe_customer_id')->nullable()->after('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_plans', function (Blueprint $table) {
            $table->dropColumn([
                'stripe_subscription_id',
                'paypal_subscription_id',
                'auto_renew',
                'next_billing_date',
                'billing_cycle',
                'canceled_at'
            ]);
        });
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['subscription_id', 'is_recurring']);
        });
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['stripe_customer_id']);
        });
    }
};
