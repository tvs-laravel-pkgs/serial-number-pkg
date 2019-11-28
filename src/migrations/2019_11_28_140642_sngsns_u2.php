<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SngsnsU2 extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		//

		Schema::table('serial_number_group_serial_number_segment', function (Blueprint $table) {
			$table->dropForeign('sng_snt_u');
			$table->dropForeign('sng_su');
			$table->dropUnique('sngsns_unique');
			$table->foreign('serial_number_group_id', 'sng_snt_u')->references('id')->on('serial_number_groups')->onDelete('CASCADE')->onUpdate('cascade');
			$table->foreign('segment_id', 'sng_su')->references('id')->on('serial_number_segments')->onDelete('CASCADE')->onUpdate('cascade');
		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('serial_number_group_serial_number_segment', function (Blueprint $table) {
			$table->unique(["serial_number_group_id", "segment_id"], 'sngsns_unique');
		});
		//
	}
}
