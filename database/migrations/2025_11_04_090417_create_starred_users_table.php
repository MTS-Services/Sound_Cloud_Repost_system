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
        Schema::create('starred_users', function (Blueprint $table) {
            $table->id();
            $table->string('follower_urn')->index();
            $table->string('starred_user_urn')->index();

            $table->foreign('follower_urn')->references('urn')->on('users')->onDelete('cascade');
            $table->foreign('starred_user_urn')->references('urn')->on('users')->onDelete('cascade');
            
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
        Schema::dropIfExists('starred_users');
    }
};
