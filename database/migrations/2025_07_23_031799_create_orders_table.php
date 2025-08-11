<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Http\Traits\AuditColumnsTrait;
use App\Models\Order;
use Illuminate\Database\Eloquent\SoftDeletes;

return new class extends Migration {
    use AuditColumnsTrait, SoftDeletes;
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sort_order')->default(0);

            $table->string('user_urn')->index();
            $table->string('order_id')->unique();
            $table->unsignedBigInteger('source_id')->index()->comment('Credits table id or Plans Table Id');
            $table->string('source_type')->index()->comment('Credit Or Plan Model');
            $table->decimal('credits', 10, 2)->nullable();
            $table->decimal('amount', 10, 2)->default(0.00);
            $table->tinyInteger('status')->index()->default(Order::STATUS_PENDING);
            $table->tinyInteger('type')->index()->default(Order::TYPE_CREDIT)->comment(Order::TYPE_CREDIT . ': Credit' . Order::TYPE_PLAN . ': Plan');

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
        Schema::dropIfExists('orders');
    }
};
