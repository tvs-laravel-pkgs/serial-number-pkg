<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SerialNumberCategoriesU2 extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::table('serial_number_categories', function (Blueprint $table) {
			$table->dropForeign('serial_number_categories_company_id_foreign');
			$table->dropUnique('serial_number_categories_company_id_name_unique');
			$table->dropUnique('serial_number_categories_company_id_short_name_unique');
			$table->dropColumn('company_id');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::table('serial_number_categories', function (Blueprint $table) {
			$table->unsignedInteger('company_id')->nullable()->after('id');
			$table->dropForeign('serial_number_categories_company_id_foreign');
			$table->unique(["company_id", "name"]);
			$table->unique(["company_id", "short_name"]);
		});
	}
}
