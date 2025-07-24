<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Http\Traits\AuditColumnsTrait;
use App\Models\CreditTransaction;
use Illuminate\Database\Eloquent\SoftDeletes;

return new class extends Migration
{
    use AuditColumnsTrait, SoftDeletes;
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('credit_transactions', function (Blueprint $table) {
            $table->id();

            $table->string('receiver_id')->index(); 
            $table->string('sender_id')->index()->nullable();

            $table->unsignedBigInteger('campaign_id')->index()->nullable();
            $table->unsignedBigInteger('repost_request_id')->index()->nullable();

            $table->tinyInteger('transaction_type')->comment(
                CreditTransaction::TYPE_EARN .': Earn',
                CreditTransaction::TYPE_SPEND .': Spend',
                CreditTransaction::TYPE_REFUND .': Refund',
                CreditTransaction::TYPE_PURCHASE .': Purchase',
                CreditTransaction::TYPE_PENALTY .': Penalty',
                CreditTransaction::TYPE_BONUS .': Bonus');
            $table->decimal('amount', 15, 2);
            $table->decimal('credits', 15, 2);
            $table->decimal('balance_before', 10, 2);
            $table->decimal('balance_after', 10, 2);

            $table->text('description')->nullable();
            $table->json('metadata')->nullable();

            $table->timestamps();
            $table->softDeletes();
            $this->addMorphedAuditColumns($table);

            // Foreign key constraints (optional)
            $table->foreign('receiver_id')->references('urn')->on('users')->cascadeOnDelete();
            $table->foreign('sender_id')->references('urn')->on('users')->nullOnDelete();
            $table->foreign('campaign_id')->references('id')->on('campaigns')->nullOnDelete();
            // $table->foreign('repost_request_id')->references('id')->on('repost_requests')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_transactions');
    }
};
