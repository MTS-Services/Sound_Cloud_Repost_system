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

            $table->string('receiver_urn')->index();
            $table->string('sender_urn')->index()->nullable();
            $table->tinyInteger('calculation_type')->comment(
                CreditTransaction::CALCULATION_TYPE_ADDITION . ': Addition',
                CreditTransaction::CALCULATION_TYPE_SUBTRACTION . ': Subtraction'
            );
            $table->unsignedBigInteger('campaign_id')->index()->nullable();
            $table->unsignedBigInteger('repost_request_id')->index()->nullable();

            $table->tinyInteger('transaction_type')->comment(
                CreditTransaction::TYPE_EARN . ': Earn',
                CreditTransaction::TYPE_SPEND . ': Spend',
                CreditTransaction::TYPE_REFUND . ': Refund',
                CreditTransaction::TYPE_PURCHASE . ': Purchase',
                CreditTransaction::TYPE_PENALTY . ': Penalty',
                CreditTransaction::TYPE_BONUS . ': Bonus'
            );
            $table->decimal('amount', 15, 2);
            $table->decimal('credits', 15, 2);
            $table->decimal('balance_before', 10, 2)->nullable();
            $table->decimal('balance_after', 10, 2)->nullable();

            $table->text('description')->nullable();
            $table->json('metadata')->nullable();

            $table->timestamps();
            $table->softDeletes();
            $this->addMorphedAuditColumns($table);

            // Foreign key constraints (optional)
            $table->foreign('receiver_urn')->references('urn')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('sender_urn')->references('urn')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('repost_request_id')->references('id')->on('repost_requests')->onDelete('cascade')->onUpdate('cascade');
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
