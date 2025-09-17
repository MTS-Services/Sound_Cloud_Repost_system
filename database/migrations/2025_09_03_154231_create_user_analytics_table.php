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

            $table->string('owner_user_urn');
            $table->foreign('owner_user_urn')->references('urn')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->string('act_user_urn');
            $table->foreign('act_user_urn')->references('urn')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->string('track_urn');
            $table->foreign('track_urn')->references('urn')->on('tracks')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('actionable_id')->index()->nullable();
            $table->string('actionable_type')->index()->nullable();

            $table->tinyInteger('type')->index();

            // ip address 
            $table->string('ip_address')->nullable();

            $table->string('genre')->nullable();

            $table->timestamps();
            $table->softDeletes();
            $this->addMorphedAuditColumns($table);

            // $table->unique(['act_user_urn', 'track_urn', 'type', 'ip_address'], 'user_analytics_unique_index');
        });


        //  Schema::create('user_analytics', function (Blueprint $table) {
        //     $table->id();

        //     $table->string('user_urn');
        //     $table->foreign('user_urn')->references('urn')->on('users')->onDelete('cascade')->onUpdate('cascade');

        //     $table->string('track_urn');
        //     $table->foreign('track_urn')->references('urn')->on('tracks')->onDelete('cascade')->onUpdate('cascade');

        //     $table->unsignedBigInteger('action_id');
        //     $table->string('action_type');

        //     $table->date('date');
        //     $table->string('genre');

        //     $table->unsignedBigInteger('total_requests')->default(0);
        //     $table->unsignedBigInteger('total_plays')->default(0);
        //     $table->unsignedBigInteger('total_followers')->default(0);
        //     $table->unsignedBigInteger('total_likes')->default(0);
        //     $table->unsignedBigInteger('total_reposts')->default(0);
        //     $table->unsignedBigInteger('total_comments')->default(0);
        //     $table->unsignedBigInteger('total_views')->default(0);

        //     $table->timestamps();
        //     $table->softDeletes();
        //     $this->addMorphedAuditColumns($table);

        //     $table->unique(['action_id', 'action_type', 'track_urn', 'user_urn', 'date'], 'user_analytics_unique_index');
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_analytics');
    }
};
