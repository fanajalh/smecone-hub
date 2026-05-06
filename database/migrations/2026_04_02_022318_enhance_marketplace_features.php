<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('marketplaces', function (Blueprint $table) {
            $table->enum('format', ['Fisik', 'Digital'])->default('Fisik')->after('type');
            $table->text('digital_link')->nullable()->after('format');
            $table->json('variants_config')->nullable()->after('digital_link');
        });

        Schema::table('carts', function (Blueprint $table) {
            $table->string('variant_selected')->nullable()->after('qty');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->string('variant_selected')->nullable()->after('qty');
        });
    }

    public function down(): void
    {
        Schema::table('marketplaces', function (Blueprint $table) {
            $table->dropColumn(['format', 'digital_link', 'variants_config']);
        });

        Schema::table('carts', function (Blueprint $table) {
            $table->dropColumn('variant_selected');
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn('variant_selected');
        });
    }
};
