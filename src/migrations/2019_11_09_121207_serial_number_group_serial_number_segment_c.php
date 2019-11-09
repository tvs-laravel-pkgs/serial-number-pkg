<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SerialNumberGroupSerialNumberSegmentC extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('serial_number_group_serial_number_segment', function (Blueprint $table) {
			$table->unsignedInteger('serial_number_type_id');
			$table->unsignedInteger('segment_id');
			$table->string('value', 20)->nullable();
			$table->unsignedMediumInteger('display_order')->default(9999);

			$table->foreign('serial_number_type_id', 'sng_snt_u')->references('id')->on('serial_number_types')->onDelete('CASCADE')->onUpdate('cascade');
			$table->foreign('segment_id', 'sng_su')->references('id')->on('serial_number_segments')->onDelete('CASCADE')->onUpdate('cascade');

			$table->unique(["serial_number_type_id", "segment_id"], 'sngsns_unique');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('serial_number_group_serial_number_segment');
	}
}
