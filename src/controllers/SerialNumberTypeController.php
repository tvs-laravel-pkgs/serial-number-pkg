<?php

namespace Abs\SerialNumberPkg;
use Abs\SerialNumberPkg\SerialNumberType;
use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Validator;
use Yajra\Datatables\Datatables;

// use Validator;

class SerialNumberTypeController extends Controller {

	public function __construct() {
	}

	public function getSerialNumberTypeList() {
		$serial_number_type_list = SerialNumberType::withTrashed()
			->select(
				'id',
				'name',
				DB::raw('IF((serial_number_types.deleted_at) IS NULL,"Active","In Active") as status')
			)
			->where('serial_number_types.company_id', Auth::user()->company_id)
			->orderby('serial_number_types.id', 'desc');

		return Datatables::of($serial_number_type_list)
			->addColumn('status', function ($serial_number_type_list) {
				$status = $serial_number_type_list->status == 'Active' ? 'green' : 'red';
				return '<span class="status-indicator ' . $status . '"></span>' . $serial_number_type_list->status;
			})
			->addColumn('action', function ($serial_number_type_list) {
				$edit_img = asset('public/theme/img/table/cndn/edit.svg');
				$delete_img = asset('public/theme/img/table/cndn/delete.svg');
				return '
					<a href="#!/serial-number-pkg/serial-number-type/edit/' . $serial_number_type_list->id . '">
						<img src="' . $edit_img . '" alt="View" class="img-responsive">
					</a>
					<a href="javascript:;" data-toggle="modal" data-target="#delete_serial_number_type"
					onclick="angular.element(this).scope().deleteSerialNumberType(' . $serial_number_type_list->id . ')" dusk = "delete-btn" title="Delete">
					<img src="' . $delete_img . '" alt="delete" class="img-responsive">
					</a>
					';
			})
			->make(true);
	}

	public function getSerialNumberTypeForm($id = NULL) {
		if (!$id) {
			$serial_number_type = new SerialNumberType;
			$action = 'Add';
		} else {
			$serial_number_type = SerialNumberType::withTrashed()
				->find($id);
			$action = 'Edit';
		}
		$this->data['serial_number_type'] = $serial_number_type;
		$this->data['action'] = $action;

		return response()->json($this->data);
	}

	public function saveSerialNumberType(Request $request) {
		// dd($request->all());
		try {
			$error_messages = [
				'name.required' => "Serial Number Type is Required",
				'name.unique' => "Serial Number Type is already taken",
			];
			$validator = Validator::make($request->all(), [
				'name' => [
					'unique:serial_number_types,name,' . $request->id . ',id,company_id,' . Auth::user()->company_id,
					'required:true',
				],
			], $error_messages);
			if ($validator->fails()) {
				return response()->json(['success' => false, 'errors' => $validator->errors()->all()]);
			}

			DB::beginTransaction();
			if (empty($request->id)) {
				$serial_number_type = new SerialNumberType;
				$serial_number_type->created_by_id = Auth::user()->id;
				$serial_number_type->created_at = Carbon::now();
				$serial_number_type->updated_at = NULL;
			} else {
				$serial_number_type = SerialNumberType::withTrashed()->find($request->id);
				$serial_number_type->updated_by_id = Auth::user()->id;
				$serial_number_type->updated_at = Carbon::now();
			}
			$serial_number_type->company_id = Auth::user()->company_id;
			$serial_number_type->fill($request->all());
			if ($request->status == 'Inactive') {
				$serial_number_type->deleted_at = Carbon::now();
				$serial_number_type->deleted_by_id = Auth::user()->id;
			} else {
				$serial_number_type->deleted_by_id = NULL;
				$serial_number_type->deleted_at = NULL;
			}
			$serial_number_type->save();
			DB::commit();
			if (empty($request->id)) {
				return response()->json(['success' => true, 'message' => ['Serial Number Type Added Successfully']]);
			} else {
				return response()->json(['success' => true, 'message' => ['Serial Number Type Updated Successfully']]);
			}
		} catch (Exceprion $e) {
			DB::rollBack();
			return response()->json(['success' => false, 'errors' => ['Exception Error' => $e->getMessage()]]);
		}
	}
	public function deleteSerialNumberType($id) {
		$delete_status = SerialNumberType::where('id', $id)->forceDelete();
		if ($delete_status) {
			return response()->json(['success' => true]);
		}
	}
}
