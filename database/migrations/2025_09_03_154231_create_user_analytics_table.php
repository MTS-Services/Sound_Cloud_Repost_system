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
        Schema::create('user_analytics', function (Blueprint $table) {
            $table->id();

            $table->string('user_urn');
            $table->foreign('user_urn')->references('urn')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->string('response_user_urn');
            $table->foreign('response_user_urn')->references('urn')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('source_id');
            $table->string('source_type');

            $table->date('date');
            $table->string('genre');

            $table->unsignedBigInteger('total_plays')->default(0);
            $table->unsignedBigInteger('total_followes')->default(0);
            $table->unsignedBigInteger('total_likes')->default(0);
            $table->unsignedBigInteger('total_reposts')->default(0);
            $table->unsignedBigInteger('total_comments')->default(0);
            $table->unsignedBigInteger('total_views')->default(0);

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
        Schema::dropIfExists('user_analytics');
    }
};
