<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SerialNumberGroupU1282020 extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('serial_number_groups', function (Blueprint $table) {
			$table->unsignedInteger('business_id')->nullable()->after('sbu_id');

			$table->foreign('business_id')->references('id')->on('businesses')->onDelete('CASCADE')->onUpdate('cascade');

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('serial_number_groups', function (Blueprint $table) {
			$table->dropForeign('serial_number_groups_business_id_foreign');

			$table->dropColumn('business_id');
		});
	}
}
