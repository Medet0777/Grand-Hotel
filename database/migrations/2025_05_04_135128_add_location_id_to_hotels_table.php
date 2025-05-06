<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Models\Location;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Удаляем старую колонку location
        Schema::table('hotels', function (Blueprint $table) {
            if (Schema::hasColumn('hotels', 'location')) {
                $table->dropColumn('location');
            }

            // 2. Добавляем location_id как nullable (без foreign key пока)
            $table->unsignedBigInteger('location_id')->nullable()->after('id');
        });

        // 3. Создаем default location
        $defaultLocation = Location::firstOrCreate([
            'name' => 'Unknown Location',
            'latitude' => 0,
            'longitude' => 0,
        ]);

        // 4. Обновляем все записи hotel
        DB::table('hotels')->update(['location_id' => $defaultLocation->id]);

        // 5. Делаем колонку NOT NULL и добавляем внешний ключ
        Schema::table('hotels', function (Blueprint $table) {
            $table->unsignedBigInteger('location_id')->nullable(false)->change();
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            $table->dropForeign(['location_id']);
            $table->dropColumn('location_id');

            if (!Schema::hasColumn('hotels', 'location')) {
                $table->string('location');
            }
        });
    }
};
