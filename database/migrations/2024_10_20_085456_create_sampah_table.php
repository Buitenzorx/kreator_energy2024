<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('sampah', function (Blueprint $table) {
        $table->id();
        $table->enum('kategori', ['organik', 'anorganik', 'logam']);
        $table->float('jarak'); // Jarak dari sensor ultrasonik
        $table->float('tegangan')->nullable(); // Tegangan dari INA219
        $table->float('arus')->nullable(); // Arus dari INA219
        $table->float('daya')->nullable(); // Arus dari INA219
        $table->timestamps(); // Waktu pencatatan data
    });
}



    public function down()
    {
        Schema::dropIfExists('sampah');
    }

};
