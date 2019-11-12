<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSerialNumberSegmentGroup extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('serial_number_groups', function (Blueprint $table) {
			$table->renameColumn('length', 'len')->change();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('serial_number_groups', function (Blueprint $table) {
			$table->renameColumn('len', 'length')->change();
		});
	}
}
