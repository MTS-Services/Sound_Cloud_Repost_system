<?php

use App\Http\Traits\AuditColumnsTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    use SoftDeletes, AuditColumnsTrait;
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {

            $table->id();
            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->tinyInteger('status')->default(1)->comment('1 = Active, 2 = Inactive, 3 = Banned');

            // SoundCloud specific fields
            $table->string('soundcloud_id')->unique()->nullable();
            $table->string('soundcloud_username')->nullable();
            $table->string('soundcloud_avatar')->nullable();
            $table->integer('soundcloud_track_count')->default(0);
            $table->integer('soundcloud_followings_count')->default(0);
            $table->integer('soundcloud_followers_count')->default(0);
            $table->string('soundcloud_access_token')->nullable();
            $table->string('soundcloud_refresh_token')->nullable();
            $table->timestamp('soundcloud_token_expires_at')->nullable();
            $table->timestamp('last_sync_at')->nullable();

            // Credit system
            $table->integer('credits')->default(0);

            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
            $this->addMorphedAuditColumns($table);

            // $table->id();
            // $table->string('name');
            // $table->string('email')->unique();
            // $table->timestamp('email_verified_at')->nullable();
            // $table->string('password');
            // $table->rememberToken();
            // $table->timestamps();
            // $table->softDeletes();
            // $this->addMorphedAuditColumns($table);

        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
