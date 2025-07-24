<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Http\Traits\AuditColumnsTrait;
use App\Models\UserCredit;
use Illuminate\Database\Eloquent\SoftDeletes;

return new class extends Migration
{
    use AuditColumnsTrait, SoftDeletes;
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_credits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sort_order')->default(0);
            $table->string('user_urn')->index();
            $table->unsignedBigInteger('transaction_id')->index();

            $table->tinyInteger('status')->index()->comment(UserCredit::STATUS_PENDING, UserCredit::STATUS_APPROVED, UserCredit::STATUS_REJECTED);
            $table->decimal('amount', 15, 2)->default(0);
            $table->decimal('credits', 15, 2)->default(0);

            $table->timestamps();
            $table->softDeletes();
            $this->addMorphedAuditColumns($table);

            // Optional Foreign Keys
            $table->foreign('user_urn')->references('urn')->on('users')->cascadeOnDelete();
            $table->foreign('transaction_id')->references('id')->on('credit_transactions')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_credits');
    }
};
