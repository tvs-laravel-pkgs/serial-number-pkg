<?php

namespace Abs\SerialNumberPkg;
use App\Company;
use App\FinancialYear;
use App\Outlet;
use App\State;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SerialNumberGroup extends Model {
	use SoftDeletes;
	protected $fillable = [
		'company_id',
		'category_id',
		'fy_id',
		'state_id',
		'branch_id',
		'length',
		'starting_number',
		'ending_number',
		'next_number',
		'created_by_id',
	];

	public function segments() {
		return $this->belongsToMany('Abs\SerialNumberPkg\SerialNumberSegment', 'serial_number_group_serial_number_segment', 'serial_number_group_id', 'segment_id')->withPivot(['value', 'display_order'])->orderBy('display_order', 'asc');
	}

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

	public static function generateNumber($category_id, $fy_id = NULL, $state_id, $branch_id) {
		try {
			$response = array();
			$financial_year = FinancialYear::where('id', $fy_id)->first();
			if (!$financial_year) {
				$response['success'] = false;
				$response['error'] = 'No Serial number found';
				return $response;
			}

			$state = State::where('id', $state_id)->first();
			if (!$state) {
				$response['success'] = false;
				$response['error'] = 'No Serial number found';
				return $response;
			}

			$branch = Outlet::where('id', $branch_id)->first();
			if (!$branch) {
				$response['success'] = false;
				$response['error'] = 'No Serial number found';
				return $response;
			}

			$serial_number_group = self::where('category_id', $category_id)
				->where('fy_id', $fy_id)
				->where('state_id', $state_id)
				->where('branch_id', $branch_id)
				->where('company_id', Auth::user()->company_id)
				->first();
			if (!$serial_number_group) {
				$response['success'] = false;
				$response['error'] = 'No Serial number found';
				return $response;
			}

			//ADD DIGITS BEFORE NEXT NUMBER
			$number_format = sprintf("%0" . $serial_number_group->len . "d", $serial_number_group->next_number);

			//GENERATE NUMBER BASED ON SEGMENTS
			$number = '';
			if (count($serial_number_group->segments) > 0) {
				foreach ($serial_number_group->segments as $key => $segment) {
					if ($segment->data_type_id == 1140) {
						$number .= $segment->pivot->value;
					} else if ($segment->data_type_id == 1141) {
						$number .= $financial_year->code;
					} else if ($segment->data_type_id == 1142) {
						$number .= $state->code;
					} else if ($segment->data_type_id == 1143) {
						$number .= $branch->code;
					}
				}
			}
			//CONCATE
			$serial_number = $number . $number_format;

			//UPDATE SERIAL NUMBER GROUP NEXT NUMBER
			$serial_number_group->next_number = $serial_number_group->next_number + 1;
			$serial_number_group->save();

			$response['success'] = true;
			$response['number'] = $serial_number;
			return $response;

		} catch (Exception $e) {
			dd($e);
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

		$category = SerialNumberCategory::where('name', $record_data->category)->where('company_id', $company->id)->first();
		if (!$category) {
			$errors[] = 'Invalid category : ' . $record_data->category;
		}

		$fy = FinancialYear::where('code', $record_data->fy)->where('company_id', $company->id)->first();
		if (!$fy) {
			$errors[] = 'Invalid fy : ' . $record_data->fy;
		}

		$state = State::where('name', $record_data->state)->first();
		if (!$state) {
			$errors[] = 'Invalid state : ' . $record_data->state;
		}

		$outlet = Outlet::where('code', $record_data->outlet)->where('company_id', $company->id)->first();
		if (!$outlet) {
			$errors[] = 'Invalid outlet : ' . $record_data->outlet;
		}

		if (count($errors) > 0) {
			dump($errors);
			return;
		}

		$record = self::firstOrNew([
			'company_id' => $company->id,
			'category_id' => $category->id,
			'fy_id' => $fy->id,
			'state_id' => $state->id,
			'branch_id' => $outlet->id,
		]);
		$record->len = $record_data->length;
		$record->starting_number = $record_data->starting_number;
		$record->ending_number = $record_data->ending_number;
		$record->next_number = $record_data->next_number;
		$record->created_by_id = $admin->id;
		$record->save();
		return $record;
	}

	public static function mapSegments($records) {
		foreach ($records as $key => $record_data) {
			try {
				if (!$record_data->company) {
					continue;
				}
				$record = self::mapSegment($record_data);
			} catch (Exception $e) {
				dd($e);
			}
		}
	}

	public static function mapSegment($record_data) {

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

		$serial_number_groups = explode('/', $record_data->serial_number_group);
		$category = SerialNumberCategory::
			where('name', $serial_number_groups[0])->where('company_id', $company->id)->first();
		if (!$category) {
			$errors[] = 'Invalid category : ' . $serial_number_groups[0];
		}

		$fy = FinancialYear::where('code', $serial_number_groups[1])->where('company_id', $company->id)->first();
		if (!$fy) {
			$errors[] = 'Invalid fy : ' . $serial_number_groups[1];
		}

		$state = State::where('name', $serial_number_groups[2])->first();
		if (!$state) {
			$errors[] = 'Invalid state : ' . $serial_number_groups[2];
		}

		$outlet = Outlet::where('code', $serial_number_groups[3])->where('company_id', $company->id)->first();
		if (!$outlet) {
			$errors[] = 'Invalid outlet : ' . $serial_number_groups[3];
		}

		$record = self::where([
			'company_id' => $company->id,
			'category_id' => $category->id,
			'fy_id' => $fy->id,
			'state_id' => $state->id,
			'branch_id' => $outlet->id,
		])->first();
		if (!$record) {
			$errors[] = 'Invalid serial number group : ' . $record_data->serial_number_group;
		}

		$segment = SerialNumberSegment::where('name', $record_data->segment_name)->where('company_id', $company->id)->first();
		if (!$segment) {
			$errors[] = 'Invalid segment : ' . $record_data->segment_name;
		}

		if (count($errors) > 0) {
			dump($errors);
			return;
		}

		$record->segments()->syncWithoutDetaching([
			$segment->id => [
				'display_order' => $record_data->display_order,
				'value' => $record_data->value,
			],
		]);
		return $record;
	}
}
