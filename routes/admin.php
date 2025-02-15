<?php

use Illuminate\Support\Facades\Route;



Route::group(['prefix'=>'admin','middleware'=>'auth:admin'],function (){

    #### Home ####
    Route::get('/', 'HomeController@index')->name('adminHome');


    #### Admins ####
    Route::resource('admins','AdminController');
    Route::POST('delete_admin','AdminController@delete')->name('delete_admin');
    Route::get('my_profile','AdminController@myProfile')->name('myProfile');

    #### users ####
//    Route::resource('users','UserController');
    Route::get('users/{status}','UserController@index')->name('users.index');
    Route::get('users.create','UserController@create')->name('users.create');
    Route::POST('users.store','UserController@store')->name('users.store');
    Route::POST('delete_users','UserController@delete')->name('delete_users');
    Route::POST('updateUserStatus','UserController@updateUserStatus')->name('updateUserStatus');
    Route::get('userDetails/{id}','UserController@userDetails')->name('userDetails');

    #### Donors ####
    Route::resource('donors','DonorController');
    Route::POST('delete_donors','DonorController@delete')->name('delete_donors');

    #### Subventions ####
    Route::resource('subventions','SubventionController');
    Route::get('showSubventions','SubventionController@showSubventions')->name('showSubventions');
    Route::POST('delete_subventions','SubventionController@delete')->name('delete_subventions');

    #### Research ####
    Route::get('research','ResearchController@index')->name('research.index');
    Route::get('social_research/{user_id}','ResearchController@social_research')->name('social_research');
    Route::get('researchReceive','ResearchController@researchReceive')->name('research.receive');


    #### Setting ####
    Route::get('setting','SettingController@index')->name('setting.index');
    Route::post('settingUpdate','SettingController@update')->name('settingUpdate');



    #### Auth ####
    Route::get('logout', 'AuthController@logout')->name('admin.logout');
});

#### Login Actions ####
Route::group(['prefix'=>'admin'],function (){
    Route::get('login', 'AuthController@index')->name('admin.login');
    Route::POST('login', 'AuthController@login')->name('admin.login');
});

#### Clear Cache ####
Route::get('/clear/route', function (){
    \Artisan::call('optimize:clear');
    return 'done';
});
