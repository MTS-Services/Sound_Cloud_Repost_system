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
                CreditTransaction::CALCULATION_TYPE_DEBIT . ': Debit / Addition',
                CreditTransaction::CALCULATION_TYPE_CREDIT . ': Credit / Subtraction'
            );

            $table->unsignedBigInteger('source_id')->index();
            $table->string('source_type')->index();

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

            $table->text('description')->nullable();
            $table->json('metadata')->nullable();

            $table->timestamps();
            $table->softDeletes();
            $this->addMorphedAuditColumns($table);

            // Foreign key constraints (optional)
            $table->foreign('receiver_urn')->references('urn')->on('users')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('sender_urn')->references('urn')->on('users')->onDelete('cascade')->onUpdate('cascade');
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
