<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Http\Traits\AuditColumnsTrait;
use App\Models\Feature;
use Illuminate\Database\Eloquent\SoftDeletes;

return new class extends Migration
{
    use AuditColumnsTrait, SoftDeletes;
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('features', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sort_order')->default(0);
            $table->unsignedBigInteger('feature_category_id')->index();

            $table->string('name')->nullable()->unique();
            $table->tinyInteger('key')->unique();
            $table->tinyInteger('type')->default(Feature::TYPE_STRING)->comment(Feature::TYPE_STRING .': string', Feature::TYPE_BOOLEAN .': boolean');
            
            $table->foreign('feature_category_id')->references('id')->on('feature_categories')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('features');
    }
};
