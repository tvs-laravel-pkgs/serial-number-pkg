<?php

namespace Abs\SerialNumberPkg;
use App\Company;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SerialNumberCategory extends Model {
	use SoftDeletes;
	protected $table = 'serial_number_categories';
	protected $fillable = [
		'name',
		'created_by_id',
	];

	public static function getCategoryList() {
		return SerialNumberCategory::select('name', 'id')->where('company_id', Auth::user()->company_id)->get();
	}

	public static function createFromCollection($records, $company = null) {
		foreach ($records as $key => $record_data) {
			try {
				if (!$record_data->company) {
					continue;
				}
				$record = self::createFromObject($record_data, $company);
			} catch (Exception $e) {
				dd($e);
			}
		}
	}

	public static function createFromObject($record_data, $company = null) {

		$errors = [];
		if (!$company) {
			$company = Company::where('code', $record_data->company)->first();
		}
		if (!$company) {
			dump('Invalid Company : ' . $record_data->company);
			return;
		}

		$admin = $company->admin();
		if (!$admin) {
			dump('Default Admin user not found');
			return;
		}

		if (count($errors) > 0) {
			dump($errors);
			return;
		}

		$record = self::firstOrNew([
			'company_id' => $company->id,
			'name' => $record_data->name,
		]);
		$record->short_name = $record_data->short_name;
		$record->created_by_id = $admin->id;
		$record->save();
		return $record;
	}
}
