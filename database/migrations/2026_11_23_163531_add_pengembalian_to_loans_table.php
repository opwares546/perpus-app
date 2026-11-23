<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->date('tanggal_kembali')->nullable()->after('tanggal_kembali_rencana');
            $table->unsignedInteger('denda')->default(0)->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropColumn(['tanggal_kembali', 'denda']);
        });
    }
};