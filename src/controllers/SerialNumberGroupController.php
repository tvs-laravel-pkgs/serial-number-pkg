<?php

namespace Abs\SerialNumberPkg;
use Abs\SerialNumberPkg\FinancialYear;
use Abs\SerialNumberPkg\SerialNumberCategory;
use Abs\SerialNumberPkg\SerialNumberGroup;
use Abs\SerialNumberPkg\SerialNumberSegment;
use App\Http\Controllers\Controller;
use App\Outlet;
use App\State;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Validator;
use Yajra\Datatables\Datatables;

class SerialNumberGroupController extends Controller {

	public function __construct() {
	}

	public function getSerialNumberGroupList() {
		$serial_number_group_list = SerialNumberGroup::withTrashed()
			->select(
				'serial_number_groups.id',
				'serial_number_categories.name as name',
				DB::raw('CONCAT(financial_years.from,"/",financial_years.to) as finance_year'),
				'states.name as state',
				'outlets.name as branch',
				'serial_number_groups.starting_number',
				'serial_number_groups.ending_number',
				'serial_number_groups.next_number',
				DB::raw('COUNT(sngsns.serial_number_group_id) as segment'),
				DB::raw('IF((serial_number_groups.deleted_at) IS NULL,"Active","In Active") as status')
			)
			->join('serial_number_categories', 'serial_number_categories.id', 'serial_number_groups.category_id')
			->join('financial_years', 'financial_years.id', 'serial_number_groups.fy_id')
			->join('states', 'states.id', 'serial_number_groups.state_id')
			->join('outlets', 'outlets.id', 'serial_number_groups.branch_id')
			->leftJoin('serial_number_group_serial_number_segment as sngsns', 'sngsns.serial_number_group_id', 'serial_number_groups.id')
			->where('serial_number_groups.company_id', Auth::user()->company_id)
			->orderby('serial_number_groups.id', 'desc')
			->groupby('serial_number_groups.id');

		return Datatables::of($serial_number_group_list)
			->addColumn('name', function ($serial_number_group_list) {
				$status = $serial_number_group_list->status == 'Active' ? 'green' : 'red';
				return '<span class="status-indicator ' . $status . '"></span>' . $serial_number_group_list->name;
			})
			->addColumn('action', function ($serial_number_group_list) {
				$edit_img = asset('public/theme/img/table/cndn/edit.svg');
				$delete_img = asset('public/theme/img/table/cndn/delete.svg');
				return '
					<a href="#!/serial-number-pkg/serial-number-group/edit/' . $serial_number_group_list->id . '">
						<img src="' . $edit_img . '" alt="View" class="img-responsive">
					</a>
					<a href="javascript:;" data-toggle="modal" data-target="#delete_serial_number_group"
					onclick="angular.element(this).scope().deleteSerialNumberType(' . $serial_number_group_list->id . ')" dusk = "delete-btn" title="Delete">
					<img src="' . $delete_img . '" alt="delete" class="img-responsive">
					</a>
					';
			})
			->make(true);
	}

	public function getSerialNumberGroupForm($id = NULL) {
		if (!$id) {
			$serial_number_group = new SerialNumberGroup;
			$action = 'Add';
		} else {
			$serial_number_group = SerialNumberGroup::withTrashed()->with([
				'segments',
			])->find($id);
			$action = 'Edit';
			$this->data['branch_list'] = Outlet::select('id', 'name')->where('state_id', $serial_number_group->state_id)->where('company_id', Auth::user()->company_id)->get();
		}
		$this->data['category_list'] = SerialNumberCategory::getCategoryList();
		$this->data['state_list'] = State::getStateList();
		$this->data['type_list'] = SerialNumberSegment::getSegmentList();
		$this->data['financial_year_list'] = FinancialYear::getFinanceYearList();
		$this->data['serial_number_group'] = $serial_number_group;
		$this->data['action'] = $action;

		return response()->json($this->data);
	}

	public function getBrancheBasedState($id) {
		$this->data['branch_list'] = State::getOutlet($id);

		return response()->json($this->data);
	}

	public function saveSerialNumberGroup(Request $request) {
		try {
			$error_messages = [
				'category_id.required' => 'Category Name is Required',
				'category_id.unique' => 'Category Name is already taken',
				'fy_id.required' => 'Fincncial Year is Required',
				'fy_id.unique' => 'Fincncial Year is already taken',
				'state_id.required' => 'State Name is Required',
				'state_id.unique' => 'State Name is already taken',
				'branch_id.required' => 'Branch Name is Required',
				'branch_id.unique' => 'Branch Name is already taken',
			];
			$validator = Validator::make($request->all(), [
				'category_id' => [
					'required',
					'unique:serial_number_groups,category_id,' . $request->id . ',id,company_id,' . Auth::user()->company_id . ',fy_id,' . $request->fy_id . ',state_id,' . $request->state_id . ',branch_id,' . $request->branch_id,
				],
				'fy_id' => [
					'required',
					'unique:serial_number_groups,fy_id,' . $request->id . ',id,company_id,' . Auth::user()->company_id . ',category_id,' . $request->category_id . ',state_id,' . $request->state_id . ',branch_id,' . $request->branch_id,
				],
				'state_id' => [
					'required',
					'unique:serial_number_groups,state_id,' . $request->id . ',id,company_id,' . Auth::user()->company_id . ',category_id,' . $request->category_id . ',fy_id,' . $request->fy_id . ',branch_id,' . $request->branch_id,
				],
				'branch_id' => [
					'required',
					'unique:serial_number_groups,branch_id,' . $request->id . ',id,company_id,' . Auth::user()->company_id . ',category_id,' . $request->category_id . ',state_id,' . $request->state_id . ',fy_id,' . $request->fy_id,
				],
			], $error_messages);
			if ($validator->fails()) {
				return response()->json(['success' => false, 'errors' => $validator->errors()->all()]);
			}

			DB::beginTransaction();
			if (!$request->id) {
				$serial_number_group = new SerialNumberGroup;
				$serial_number_group->created_by_id = Auth::user()->id;
				$serial_number_group->created_at = Carbon::now();
				$serial_number_group->updated_at = NULL;
			} else {
				$serial_number_group = SerialNumberGroup::withTrashed()->find($request->id);
				$serial_number_group->updated_by_id = Auth::user()->id;
				$serial_number_group->updated_at = Carbon::now();
			}
			$serial_number_group->company_id = Auth::user()->company_id;
			$serial_number_group->fill($request->all());
			if ($request->status == 'Inactive') {
				$serial_number_group->deleted_at = Carbon::now();
				$serial_number_group->deleted_by_id = Auth::user()->id;
			} else {
				$serial_number_group->deleted_by_id = NULL;
				$serial_number_group->deleted_at = NULL;
			}
			$serial_number_group->save();
			if (count($request->segment) > 0) {
				$serial_number_group->segments()->sync([]);
				foreach ($request->segment as $segments) {
					$serial_number_group->segments()->attach($segments['data_type_id'], [
						'value' => $segments['value'],
						'display_order' => $segments['display_order'],
					]);
				}
			}

			DB::commit();
			if (empty($request->id)) {
				return response()->json(['success' => true, 'message' => ['Serial Number Segment Added Successfully']]);
			} else {
				return response()->json(['success' => true, 'message' => ['Serial Number Segment Updated Successfully']]);
			}
		} catch (Exceprion $e) {
			DB::rollBack();
			return response()->json(['success' => false, 'errors' => ['Exception Error' => $e->getMessage()]]);
		}
	}
	public function deleteSerialNumberGroup($id) {
		$delete_status = SerialNumberGroup::where('id', $id)->forceDelete();
		if ($delete_status) {
			return response()->json(['success' => true]);
		}
	}
}
