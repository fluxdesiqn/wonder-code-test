<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/api/hospitals', 'HospitalController@index');
Route::get('/api/hospitals/names', 'HospitalController@names');
Route::get('/api/hospital/{id}', 'HospitalController@show');
Route::get('/api/hospital/{id}/specialists', 'HospitalController@showSpecialists');
Route::get('/api/hospital/{hospital_id}/specialist/{id}', 'HospitalController@showSpecialist');

Route::get('/api/specialists', 'SpecialistController@index');
Route::get('/api/specialist/{id}', 'SpecialistController@show');

Route::get('/{any}', 'SinglePageController@index')->where('any', '.*');