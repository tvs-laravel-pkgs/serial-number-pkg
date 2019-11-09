<?php

namespace Abs\SerialNumberPkg;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SerialNumberSegment extends Model {
	use SoftDeletes;
	protected $fillable = [
		'name',
		'data_type_id',
		'created_by_id',
	];
}
