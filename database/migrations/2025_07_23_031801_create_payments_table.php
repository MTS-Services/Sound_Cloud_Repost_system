<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Http\Traits\AuditColumnsTrait;
use App\Models\Payment;
use Illuminate\Database\Eloquent\SoftDeletes;

return new class extends Migration
{
    use AuditColumnsTrait, SoftDeletes;
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sort_order')->default(0);

            $table->unsignedBigInteger('user_urn');
            $table->unsignedBigInteger('credit_transaction_id')->nullable();

            $table->string('payment_method')->nullable();
            $table->tinyInteger('payment_gateway');
            $table->string('payment_provider_id')->index();
            $table->decimal('amount', 10, 2);
            $table->string('currency', 3)->default('USD');
            $table->decimal('credits_purchased', 10, 2);
            $table->decimal('exchange_rate', 10, 6);
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

            // Optionally, add foreign key constraint if applicable:
            // $table->foreign('user_urn')->references('urn')->on('users')->cascadeOnDelete();


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
        Schema::dropIfExists('payments');
    }
};
