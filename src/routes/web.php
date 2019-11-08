<?php

Route::group(['namespace' => 'Abs\SerialNumberPkg', 'middleware' => ['web', 'auth'], 'prefix' => 'serial-number-pkg'], function () {
	Route::get('/serial-number-segments/get-list', 'SerialNumberSegmentController@getSerialNumberSegmentList')->name('getSerialNumberSegmentList');
	Route::post('/serial-number-segment/save', 'TaxController@saveSerialNumberSegment')->name('saveSerialNumberSegment');

	Route::get('/serial-number-types/get-list', 'SerialNumberTypeController@getSerialNumberTypeList')->name('getSerialNumberTypeList');
	Route::get('/serial-number-types/add/{id?}', 'SerialNumberTypeController@getSerialNumberTypeForm')->name('getSerialNumberTypeForm');
	Route::post('/serial-number-type/save', 'SerialNumberTypeController@saveSerialNumberType')->name('saveSerialNumberType');
	Route::get('/serial-number-types/delete/{id}', 'SerialNumberTypeController@deleteSerialNumberType')->name('deleteSerialNumberType');
});