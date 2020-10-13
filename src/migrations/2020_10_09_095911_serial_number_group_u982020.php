<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SerialNumberGroupU982020 extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('serial_number_groups', function (Blueprint $table) {
			$table->integer('sbu_id')->nullable()->after('branch_id');

			$table->foreign('sbu_id')->references('id')->on('sbus')->onDelete('CASCADE')->onUpdate('cascade');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('serial_number_groups', function (Blueprint $table) {
			$table->dropForeign('serial_number_groups_sbu_id_foreign');

			$table->dropColumn('sbu_id');
		});

	}
}
