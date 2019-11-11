<?php

namespace Abs\SerialNumberPkg;
use App\Company;
use App\Config;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SerialNumberSegment extends Model {
	use SoftDeletes;
	protected $fillable = [
		'name',
		'data_type_id',
		'created_by_id',
	];

	public static function createFromCollection($records) {
		foreach ($records as $key => $record_data) {
			try {
				if (!$record_data->company) {
					continue;
				}
				$record = self::createFromObject($record_data);
			} catch (Exception $e) {
				dd($e);
			}
		}
	}

	public static function createFromObject($record_data) {

		$errors = [];
		$company = Company::where('code', $record_data->company)->first();
		if (!$company) {
			dump('Invalid Company : ' . $record_data->company);
			return;
		}

		$admin = $company->admin();
		if (!$admin) {
			dump('Default Admin user not found');
			return;
		}

		$data_type = Config::where('name', $record_data->data_type)->where('config_type_id', 88)->first();
		if (!$data_type) {
			$errors[] = 'Invalid data type : ' . $record_data->data_type;
		}

		if (count($errors) > 0) {
			dump($errors);
			return;
		}

		$record = self::firstOrNew([
			'company_id' => $company->id,
			'name' => $record_data->segment_name,
		]);
		$record->data_type_id = $data_type->id;
		$record->created_by_id = $admin->id;
		$record->save();
		return $record;

	}
}
