<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 * Products resursam jābūt laukiem: name, quantity, description,
 * expiration_date un status. Esošā migrācija netiek mainīta —
 * šī migrācija pievieno trūkstošos laukus un noņem lieko 'price'.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->date('expiration_date')->nullable()->after('quantity');
            $table->string('status')->default('active')->after('expiration_date');
        });

        if (Schema::hasColumn('products', 'price')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('price');
            });
        }
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('price', 10, 2)->default(0);
        });

        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['expiration_date', 'status']);
        });
    }
};
