<?php
Route::group(['namespace' => 'Abs\SerialNumberPkg\Api', 'middleware' => ['api']], function () {
	Route::group(['prefix' => 'serial-number-pkg/api'], function () {
		Route::group(['middleware' => ['auth:api']], function () {
		});
	});
});