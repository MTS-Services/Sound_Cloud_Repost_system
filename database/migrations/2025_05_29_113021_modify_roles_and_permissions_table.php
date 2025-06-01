<?php

use App\Http\Traits\AuditColumnsTrait;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    use AuditColumnsTrait, SoftDeletes;
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->softDeletes();
            $this->addAdminAuditColumns($table);
        });
        Schema::table('permissions', function (Blueprint $table) {
            $table->string('prefix')->index()->after('guard_name');
            $table->softDeletes();
            $this->addAdminAuditColumns($table);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn(['created_by', 'updated_by', 'deleted_by']);
        });
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn(['created_by', 'updated_by', 'deleted_by', 'prefix']);
        });
    }
};
