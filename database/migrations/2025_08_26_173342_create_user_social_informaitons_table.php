<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Http\Traits\AuditColumnsTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

return new class extends Migration
{
    use AuditColumnsTrait, SoftDeletes;
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_social_informaitons', function (Blueprint $table) {
            $table->id();
            $table->string('user_urn')->index();

            $table->string('instagram')->nullable()->index();
            $table->string('twitter')->nullable()->index();
            $table->string('facebook')->nullable()->index();
            $table->string('youtube')->nullable()->index();
            $table->string('tiktok')->nullable()->index();
            $table->string('spotify')->nullable()->index();

            $table->foreign('user_urn')->references('urn')->on('users')->onDelete('cascade')->onUpdate('cascade');

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
        Schema::dropIfExists('user_social_informaitons');
    }
};
