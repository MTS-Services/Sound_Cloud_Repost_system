<?php

use App\Http\Traits\AuditColumnsTrait;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    use AuditColumnsTrait;
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->unsignedBigInteger('sort_order')->default(0);
            $this->addAdminAuditColumns($table);
        });
        Schema::table('permissions', function (Blueprint $table) {
            $table->unsignedBigInteger('sort_order')->default(0);
            $table->string('prefix')->index()->after('guard_name');
            $this->addAdminAuditColumns($table);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('sort_order');
            $table->dropColumn(['created_by', 'updated_by', 'deleted_by']);
        });
        Schema::table('permissions', function (Blueprint $table) {
            $table->dropColumn('sort_order');
            $table->dropColumn(['created_by', 'updated_by', 'deleted_by', 'prefix']);
        });
    }
};
