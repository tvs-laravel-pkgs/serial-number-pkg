<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SerialNumberGroupsC extends Migration {
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up() {
		Schema::create('serial_number_groups', function (Blueprint $table) {
			$table->increments('id');
			$table->unsignedInteger('company_id');
			$table->unsignedInteger('category_id');
			$table->unsignedInteger('fy_id');
			$table->unsignedInteger('state_id');
			$table->unsignedInteger('branch_id');
			$table->unsignedInteger('starting_number');
			$table->unsignedInteger('ending_number');
			$table->unsignedInteger('next_number');
			$table->unsignedInteger('created_by_id')->nullable();
			$table->unsignedInteger('updated_by_id')->nullable();
			$table->unsignedInteger('deleted_by_id')->nullable();
			$table->timestamps();
			$table->softDeletes();

			$table->foreign('company_id')->references('id')->on('companies')->onDelete('CASCADE')->onUpdate('cascade');
			$table->foreign('category_id')->references('id')->on('serial_number_categories')->onDelete('CASCADE')->onUpdate('cascade');
			$table->foreign('fy_id')->references('id')->on('financial_years')->onDelete('CASCADE')->onUpdate('cascade');
			// $table->foreign('state_id')->references('id')->on('states')->onDelete('CASCADE')->onUpdate('cascade');
			$table->foreign('branch_id')->references('id')->on('outlets')->onDelete('CASCADE')->onUpdate('cascade');
			$table->foreign('created_by_id')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');
			$table->foreign('updated_by_id')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');
			$table->foreign('deleted_by_id')->references('id')->on('users')->onDelete('SET NULL')->onUpdate('cascade');

			$table->unique(["company_id", "category_id", "fy_id", "state_id", "branch_id"], 'sng_unique');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down() {
		Schema::dropIfExists('serial_number_groups');
	}
}
