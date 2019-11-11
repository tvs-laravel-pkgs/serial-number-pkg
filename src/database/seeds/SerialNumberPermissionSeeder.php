<?php
namespace Abs\SerialNumberPkg\Database\Seeds;

use App\Permission;
use Illuminate\Database\Seeder;

class SerialNumberPermissionSeeder extends Seeder {
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run() {
		$permissions = [
			//MASTER > SERIAL NUMBER SEGMENTS
			4400 => [
				'display_order' => 10,
				'parent_id' => 2,
				'name' => 'serial-number-segments',
				'display_name' => 'Serial Number Segments',
			],
			4401 => [
				'display_order' => 1,
				'parent_id' => 4400,
				'name' => 'add-serial-number-segment',
				'display_name' => 'Add',
			],
			4402 => [
				'display_order' => 2,
				'parent_id' => 4400,
				'name' => 'edit-serial-number-segment',
				'display_name' => 'Edit',
			],
			4403 => [
				'display_order' => 3,
				'parent_id' => 4400,
				'name' => 'delete-serial-number-segment',
				'display_name' => 'Delete',
			],

			//MASTER > SERIAL NUMBER GROUPS
			4420 => [
				'display_order' => 11,
				'parent_id' => 2,
				'name' => 'serial-number-groups',
				'display_name' => 'Serial Number Groups',
			],
			4421 => [
				'display_order' => 1,
				'parent_id' => 4420,
				'name' => 'add-serial-number-group',
				'display_name' => 'Add',
			],
			4422 => [
				'display_order' => 2,
				'parent_id' => 4420,
				'name' => 'edit-serial-number-group',
				'display_name' => 'Edit',
			],
			4423 => [
				'display_order' => 3,
				'parent_id' => 4420,
				'name' => 'delete-serial-number-group',
				'display_name' => 'Delete',
			],

		];

		foreach ($permissions as $permission_id => $permsion) {
			$permission = Permission::firstOrNew([
				'id' => $permission_id,
			]);
			$permission->fill($permsion);
			$permission->save();
		}
		//$this->call(RoleSeeder::class);

	}
}