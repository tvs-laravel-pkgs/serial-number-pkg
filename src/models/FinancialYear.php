<?php

namespace Abs\SerialNumberPkg;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FinancialYear extends Model {
	use SoftDeletes;
	protected $table = 'financial_years';
	protected $fillable = [
		'code',
		'from',
		'to',
		'created_by_id',
	];

	public static function getFinanceYearList() {
		return FinancialYear::select('code', 'id')->where('company_id', Auth::user()->company_id)->get();
	}

}