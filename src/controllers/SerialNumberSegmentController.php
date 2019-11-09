<?php

namespace Abs\SerialNumberPkg;
use Abs\SerialNumberPkg\SerialNumberSegment;
use App\Http\Controllers\Controller;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Validator;
use Yajra\Datatables\Datatables;

// use Validator;

class SerialNumberSegmentController extends Controller {

	public function __construct() {
	}

	public function getSerialNumberSegmentList() {
		$serial_number_segment_list = SerialNumberSegment::withTrashed()
			->select(
				'id',
				'name',
				DB::raw('IF((serial_number_segments.deleted_at) IS NULL,"Active","In Active") as status')
			)
			->where('serial_number_segments.company_id', Auth::user()->company_id)
			->orderby('serial_number_segments.id', 'desc')
			->get();
		dd($serial_number_segment_list);
		return Datatables::of($serial_number_segment_list)
			->addColumn('status', function ($serial_number_segment_list) {
				$status = $serial_number_segment_list->status == 'Active' ? 'green' : 'red';
				return '<span class="status-indicator ' . $status . '"></span>' . $serial_number_segment_list->status;
			})
			->addColumn('action', function ($serial_number_segment_list) {
				$edit_img = asset('public/theme/img/table/cndn/edit.svg');
				$delete_img = asset('public/theme/img/table/cndn/delete.svg');
				return '
					<a href="#!/serial-number-pkg/serial-number-type/edit/' . $serial_number_segment_list->id . '">
						<img src="' . $edit_img . '" alt="View" class="img-responsive">
					</a>
					<a href="javascript:;" data-toggle="modal" data-target="#delete_serial_number_segment"
					onclick="angular.element(this).scope().deleteSerialNumberType(' . $serial_number_segment_list->id . ')" dusk = "delete-btn" title="Delete">
					<img src="' . $delete_img . '" alt="delete" class="img-responsive">
					</a>
					';
			})
			->make(true);
	}

	public function getSerialNumberSegmentForm($id = NULL) {
		if (!$id) {
			$serial_number_segment = new SerialNumberSegment;
			$action = 'Add';
		} else {
			$serial_number_segment = SerialNumberSegment::withTrashed()
				->find($id);
			$action = 'Edit';
		}
		$this->data['type_list'] = [];
		$this->data['serial_number_segment'] = $serial_number_segment;
		$this->data['action'] = $action;

		return response()->json($this->data);
	}

	public function saveSerialNumberSegment(Request $request) {
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
				$serial_number_segment = new SerialNumberSegment;
				$serial_number_segment->created_by_id = Auth::user()->id;
				$serial_number_segment->created_at = Carbon::now();
				$serial_number_segment->updated_at = NULL;
			} else {
				$serial_number_segment = SerialNumberSegment::withTrashed()->find($request->id);
				$serial_number_segment->updated_by_id = Auth::user()->id;
				$serial_number_segment->updated_at = Carbon::now();
			}
			$serial_number_segment->company_id = Auth::user()->company_id;
			$serial_number_segment->fill($request->all());
			if ($request->status == 'Inactive') {
				$serial_number_segment->deleted_at = Carbon::now();
				$serial_number_segment->deleted_by_id = Auth::user()->id;
			} else {
				$serial_number_segment->deleted_by_id = NULL;
				$serial_number_segment->deleted_at = NULL;
			}
			$serial_number_segment->save();
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
	public function deleteSerialNumberSegment($id) {
		$delete_status = SerialNumberSegment::where('id', $id)->forceDelete();
		if ($delete_status) {
			return response()->json(['success' => true]);
		}
	}
}
