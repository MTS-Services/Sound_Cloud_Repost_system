<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Http\Traits\AuditColumnsTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

return new class extends Migration {
    use AuditColumnsTrait, SoftDeletes;
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('feature_relations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sort_order')->default(0);
            $table->unsignedBigInteger('package_id')->index();
            $table->unsignedBigInteger('feature_id')->index();
            $table->string('package_type')->index();
            $table->string('value');

            $table->foreign('feature_id')->references('id')->on('features')->onDelete('cascade')->onUpdate('cascade');

            $table->unique(['package_id', 'package_type', 'feature_id']);

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
        Schema::dropIfExists('feature_relaitons');
    }
};
