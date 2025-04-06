<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $foreignKeys = DB::select("SELECT CONSTRAINT_NAME 
                                   FROM information_schema.KEY_COLUMN_USAGE 
                                   WHERE TABLE_NAME = 'model_has_roles' 
                                   AND COLUMN_NAME = 'model_id' 
                                   AND REFERENCED_TABLE_NAME IS NOT NULL");

        if (!empty($foreignKeys)) {
            Schema::table('model_has_roles', function (Blueprint $table) use ($foreignKeys) {
                foreach ($foreignKeys as $fk) {
                    $table->dropForeign($fk->CONSTRAINT_NAME);
                }
            });
        }

        Schema::table('model_has_roles', function (Blueprint $table) {
            if (Schema::hasColumn('model_has_roles', 'model_id')) {
                $table->dropColumn('model_id');
            }
            $table->uuid('model_id')->index();
        });
        

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('model_has_roles', function (Blueprint $table) {
            $table->dropColumn('model_id');
            $table->unsignedBigInteger('model_id')->index();
            $table->foreign('model_id')->references('id')->on('users')->onDelete('cascade');
        });


    }
};
