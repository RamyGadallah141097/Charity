<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'admin', 'middleware' => 'auth:admin'], function () {

    #### Home ####
    Route::get('/', 'HomeController@index')->name('adminHome');
    #### Admins ####
    Route::resource('admins', 'AdminController')->middleware('permission:admins.index');
    Route::POST('delete_admin', 'AdminController@delete')->name('delete_admin')->middleware('permission:delete_admin');
    Route::get('my_profile', 'AdminController@myProfile')->name('myProfile')->middleware('permission:myProfile');
    Route::post('changeRole', 'AdminController@changeRole')->name('changeRole')->middleware('permission:admins.update');
    Route::post('showChangeRole', 'AdminController@showChangeRole')->name('showChangeRole')->middleware('permission:admins.index');

    #### Users ####
    Route::get('users/{status}', 'UserController@index')->name('users.index')->middleware('permission:users.index');
    Route::get('users.create', 'UserController@create')->name('users.create')->middleware('permission:users.create');
    Route::POST('users.store', 'UserController@store')->name('users.store')->middleware('permission:users.store');
    Route::POST('delete_users', 'UserController@delete')->name('delete_users')->middleware('permission:delete_users');
    Route::POST('updateUserStatus', 'UserController@updateUserStatus')->name('updateUserStatus')->middleware('permission:updateUserStatus');
    Route::get('userDetails/{id}', 'UserController@userDetails')->name('userDetails')->middleware('permission:userDetails');
    Route::get('DonationDetails/{id}', 'UserController@DonationDetails')->name('DonationDetails')->middleware('permission:DonationDetails');
    //    الراوتس الخاصه بالمقترض
    Route::resource("borrowers", "BorrowerController");
    Route::get('getGuarantor', 'BorrowerController@getGuarantor')->name('getGuarantor');
    Route::POST('delete_borrowers', 'BorrowerController@delete')->name('delete_borrowers');
    //    Route::get('guarantorDetails/{id}', 'BorrowerController@delete')->name('guarantorDetails');

    #### Donors ####
    Route::middleware(['permission:donors.index'])->group(function () {
        Route::resource('donors', 'DonorController');
        Route::POST('delete_donors', 'DonorController@delete')->name('delete_donors')->middleware('permission:delete_donors');
        Route::POST('Donations_donors', 'DonationController@delete')->name('donations_delete')->middleware('permission:donations_delete');
        Route::resource('Donations', "DonationController");
        Route::get('/get_donor_phone/{id}', 'DonationController@get_donor_phone')->name("get_donor_phone")->middleware('permission:get_donor_phone');
        Route::get('/search-donor', 'DonationController@searchDonor')->name('search.donor')->middleware('permission:search.donor');
    });

    Route::get("lock/{lock}" , "DonationController@lock")->name("lock");

    #### Tasks ####
    Route::resource("tasks", "TaskController")->middleware('permission:tasks.index');
    Route::POST('delete_task', 'TaskController@delete')->name('delete_task')->middleware('permission:delete_task');
    //    the route of the task s
    Route::resource("tasks", "TaskController");
    Route::POST('delete_task', 'TaskController@delete')->name('delete_task');

    //القروض الحسنة
    Route::get("GoodLoansDonations", "GoodloansController@indexLoansDonations")->name("indexLoansDonations"); // التبرعات والمتبرعين
    Route::get('/getDonation', "GoodloansController@getDonors")->name('getDonors');

//    الراوتس الخاصه بالمقترض
    Route::resource("borrowers", "BorrowerController");
    Route::get('getGuarantor', 'BorrowerController@getGuarantor')->name('getGuarantor');
    Route::POST('delete_borrowers', 'BorrowerController@delete')->name('delete_borrowers');
    Route::get('/borrowers/{id}/media', 'BorrowerController@getMedia');


//    Route::get('guarantorDetails/{id}', 'BorrowerController@delete')->name('guarantorDetails');

    // القروضsearchDonor
    Route::get('indexLoans', 'loansController@indexLoans')->name('index.Loans');
    Route::get('createLoans', 'loansController@createLoans')->name('create.Loans');
    Route::get('searchBorrowers', 'loansController@searchBorrowers')->name('search.Borrowers');
    Route::post('storeLoans', 'loansController@storeLoans')->name('store.loans');
    Route::get('personLoans/{id}', 'loansController@personLoans')->name('person.loans');
    //الزكاة والصدقات
    Route::get("safer/CharityZakat", "SaferController@indexCharityZakat")->name("safer.CharityZakat"); //تبرعات الزكاة والصدقات
    //التبرعات العينية
    Route::get('safer/InKindDonations', 'SaferController@InKindDonations')->name('safer.InKindDonations'); //التبرعات العينية
    #### Subventions ####
    Route::resource('subventions', 'SubventionController');
    Route::get('showSubventions', 'SubventionController@showSubventions')->name('showSubventions');
    Route::get('showOneSubvention', 'SubventionController@showOneSubvention')->name('showOneSubvention');
    Route::POST('delete_subventions', 'SubventionController@delete')->name('delete_subventions');


    // اعانات القرض الحسن
    Route::get('SubventionsLoans/index', 'SubventionsLoansController@index')->name('SubventionsLoans.index');
    #### Safer ####

    #### Subventions ####
//    الاعانات الشهرية للمستفيدين
//    Route::resource('subventions', 'SubventionController')->middleware('permission:subventions.index');
//    Route::get('showSubventions', 'SubventionController@showSubventions')->name('showSubventions')->middleware('permission:showSubventions');
//    Route::POST('delete_subventions', 'SubventionController@delete')->name('delete_subventions')->middleware('permission:delete_subventions');
//    assets
    Route::resource("assets" , "AssetController");
    Route::get('assetsShow', 'AssetController@show')->name('assetsShow')->middleware('permission:showSubventions');
    Route::POST('assetsDelete', 'AssetController@delete')->name('assetsDelete')->middleware('permission:delete_subventions');



    #### Roles ####
    Route::resource("roles", "RulesController")->middleware('permission:roles.index');
    Route::post("Role_delete", "RulesController@delete")->name("Role_delete")->middleware('permission:Role_delete');




    #### Research ####
    Route::get('research', 'ResearchController@index')->name('research.index')->middleware('permission:research.index');
    Route::get('social_research/{user_id}', 'ResearchController@social_research')->name('social_research')->middleware('permission:social_research');
    Route::get('researchReceive', 'ResearchController@researchReceive')->name('research.receive')->middleware('permission:research.receive');

    #### Setting ####
    Route::get('setting', 'SettingController@index')->name('setting.index')->middleware(['permission:setting.index', 'admin']);
    Route::post('settingUpdate', 'SettingController@update')->name('settingUpdate')->middleware('permission:settingUpdate');

    #### Auth ####
    Route::get('logout', 'AuthController@logout')->name('admin.logout')->middleware('permission:admin.logout');
});

#### Login Actions ####
Route::group(['prefix' => 'admin'], function () {
    Route::get('login', 'AuthController@index')->name('admin.login');
    Route::POST('login', 'AuthController@login')->name('admin.login');
});

Route::get('/clear', function () {

    Artisan::call('cache:clear');
    Artisan::call('key:generate');
    Artisan::call('config:clear');
    Artisan::call('optimize:clear');
    return response()->json(['status' => 'success', 'code' => 1000000000]);
});
