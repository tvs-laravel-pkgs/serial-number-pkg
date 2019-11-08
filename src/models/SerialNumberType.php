<?php

namespace Abs\SerialNumberPkg;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SerialNumberType extends Model {
	use SoftDeletes;
	protected $fillable = [
		'name',
		'created_by_id',
	];
}
