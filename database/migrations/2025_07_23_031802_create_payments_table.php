<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Http\Traits\AuditColumnsTrait;
use App\Models\Payment;
use Illuminate\Database\Eloquent\SoftDeletes;

return new class extends Migration {
    use AuditColumnsTrait, SoftDeletes;
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sort_order')->default(0);
            $table->string('name')->nullable();
            $table->string('email_address')->nullable();
            $table->string('address')->nullable();
            $table->string('postal_code')->nullable();
            $table->string('reference')->unique()->nullable();
            $table->string('user_urn');
            $table->unsignedBigInteger('order_id');


            $table->string('payment_method')->nullable();
            $table->tinyInteger('payment_gateway');
            $table->string('payment_provider_id')->index()->nullable();
            $table->decimal('amount', 10, 2)->default(0.00);
            $table->string('currency', 3)->default('USD');
            $table->decimal('credits_purchased', 10, 2)->nullable();
            $table->decimal('exchange_rate', 10, 6)->nullable();
            $table->enum('status', [
                'requires_payment_method',
                'requires_confirmation',
                'requires_action',
                'processing',
                'succeeded',
                'canceled'
            ])->default('requires_payment_method');

            $table->string('payment_intent_id')->nullable();
            $table->text('receipt_url')->nullable();
            $table->text('failure_reason')->nullable();
            $table->json('metadata')->nullable();

            $table->timestamp('processed_at')->index()->nullable();

            $table->foreign('order_id')->references('id')->on('orders')->cascadeOnDelete();
            $table->foreign('user_urn')->references('urn')->on('users')->cascadeOnDelete();


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
        Schema::dropIfExists('payments');
    }
};
