<?php

Route::group(['namespace' => 'Abs\SerialNumberPkg', 'middleware' => ['web', 'auth'], 'prefix' => 'serial-number-pkg'], function () {
	//SERIAL NUMBER SEGMENT
	Route::get('/serial-number-segments/get-list', 'SerialNumberSegmentController@getSerialNumberSegmentList')->name('getSerialNumberSegmentList');
	Route::post('/serial-number-segment/save', 'SerialNumberSegmentController@saveSerialNumberSegment')->name('saveSerialNumberSegment');
	Route::get('/serial-number-segments/add/{id?}', 'SerialNumberSegmentController@getSerialNumberSegmentForm')->name('getSerialNumberSegmentForm');
	Route::get('/serial-number-segments/delete/{id}', 'SerialNumberSegmentController@deleteSerialNumberSegment')->name('deleteSerialNumberSegment');
	//SERIAL NUMBER TYPE
	Route::get('/serial-number-types/get-list', 'SerialNumberTypeController@getSerialNumberTypeList')->name('getSerialNumberTypeList');
	Route::get('/serial-number-types/add/{id?}', 'SerialNumberTypeController@getSerialNumberTypeForm')->name('getSerialNumberTypeForm');
	Route::post('/serial-number-type/save', 'SerialNumberTypeController@saveSerialNumberType')->name('saveSerialNumberType');
	Route::get('/serial-number-types/delete/{id}', 'SerialNumberTypeController@deleteSerialNumberType')->name('deleteSerialNumberType');
	//SERIAL NUMBER GROUP
	Route::get('/serial-number-groups/get-list', 'SerialNumberGroupController@getSerialNumberGroupList')->name('getSerialNumberGroupList');
	Route::post('/serial-number-group/save', 'SerialNumberGroupController@saveSerialNumberGroup')->name('saveSerialNumberGroup');
	Route::get('/serial-number-groups/add/{id?}', 'SerialNumberGroupController@getSerialNumberGroupForm')->name('getSerialNumberGroupForm');
	Route::get('/serial-number-groups/delete/{id}', 'SerialNumberGroupController@deleteSerialNumberGroup')->name('deleteSerialNumberGroup');
	Route::get('/serial-number-groups/getBranch/{state_id}', 'SerialNumberGroupController@getBrancheBasedState')->name('getBrancheBasedState');
	Route::get('/serial-number-groups/get-segment/{id}', 'SerialNumberGroupController@getSegmentBasedId')->name('getSegmentBasedId');
});