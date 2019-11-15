<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterSerialNumberSegmentGroupAddNull extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('serial_number_groups', function (Blueprint $table) {
			$table->unsignedInteger('fy_id')->nullable()->change();
			$table->unsignedInteger('state_id')->nullable()->change();
			$table->unsignedInteger('branch_id')->nullable()->change();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('serial_number_groups', function (Blueprint $table) {
			$table->unsignedInteger('fy_id')->change();
			$table->unsignedInteger('state_id')->change();
			$table->unsignedInteger('branch_id')->change();
		});
	}
}
