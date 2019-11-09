<?php

namespace Abs\SerialNumberPkg;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SerialNumberCategory extends Model {
	use SoftDeletes;
	protected $table = 'serial_number_categories';
	protected $fillable = [
		'name',
		'created_by_id',
	];
}
