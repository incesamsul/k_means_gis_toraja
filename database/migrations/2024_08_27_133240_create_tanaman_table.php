<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTanamanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
// INSERT INTO `report` (`id`, `wilayah`, `luas_lahan`, `produksi`, `produktivitas`, `jenis_hortikultura`, `klasifikasi`, `saran`, `lokasi`, `persentase`) VALUES

        Schema::create('tanaman', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_wilayah');
            $table->string('luas_lahan');
            $table->string('produksi');
            $table->string('produktivitas');
            $table->string('jenis_horikultura');
            $table->string('persentase');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tanaman');
    }
}
