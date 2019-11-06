<?php

Route::group(['namespace' => 'Abs\SerialNumberPkg', 'middleware' => ['web', 'auth'], 'prefix' => 'serial-number-pkg'], function () {
	Route::get('/serial-number-segments/get-list', 'SerialNumberSegmentController@getSerialNumberSegmentList')->name('getSerialNumberSegmentList');
	Route::get('/serial-number-segment/save', 'TaxController@saveSerialNumberSegment')->name('saveSerialNumberSegment');

	Route::get('/serial-number-types/get-list', 'SerialNumberTypeController@getSerialNumberTypeList')->name('getSerialNumberTypeList');
	Route::get('/serial-number-type/save', 'SerialNumberTypeController@saveSerialNumberType')->name('saveSerialNumberType');
});