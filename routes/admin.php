<?php

use App\Http\Controllers\Admin\BorrowerController;
use App\Http\Controllers\Admin\InKindDisbursementController;
use App\Http\Controllers\Admin\ReferenceController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'admin', 'middleware' => 'auth:admin'], function () {

    #### Home ####
    Route::get('/', 'HomeController@index')->name('adminHome');
    #### Admins ####
    Route::resource('admins', 'AdminController')->middleware('permission:admins.index');
    Route::get('admins/setting', 'AdminController@show')->name("admins.setting");
    Route::POST('delete_admin', 'AdminController@delete')->name('delete_admin');
    Route::get('my_profile', 'AdminController@myProfile')->name('myProfile');
    Route::post('changeRole', 'AdminController@changeRole')->name('changeRole');
    Route::post('showChangeRole', 'AdminController@showChangeRole')->name('showChangeRole');

    Route::resource("adminSubscription", "AdminSubscriptionsController");
    Route::get('/get-subscription-price', function (Request $request) {
        $price = \App\Models\Setting::first()->adminSubscription ?? 0;
        return response()->json(['success' => true, 'price' => $price]);
    })->name('getSubscriptionPrice');


    Route::resource("SubscriptionFee", "SubscriptionFeeController");

    #### Users ####
    Route::get('users.create', 'UserController@create')->name('users.create');
    Route::get('users', 'UserController@index')->name('users.index')->middleware('permission:users.index');
    Route::get('users/{status}', 'UserController@index')->name('users.index.status')->middleware('permission:users.index');
    Route::get('users/{id}/edit', 'UserController@edit')->name('users.edit');
    Route::POST('users.store', 'UserController@store')->name('users.store');
    Route::put('/users/{id}/update', 'UserController@update')->name('users.update');
    Route::POST('delete_users', 'UserController@delete')->name('delete_users');
    Route::delete('/users/delete', [UserController::class, 'destroy'])->name('delete_users');

    Route::POST('updateUserStatus', 'UserController@updateUserStatus')->name('updateUserStatus');
    Route::post('users/toggle-monthly-subvention', 'UserController@toggleMonthlySubvention')->name('users.toggleMonthlySubvention');
    Route::get('userDetails/{id?}', 'UserController@userDetails')->name('userDetails');
    Route::get('searchNID/{id?}', 'UserController@searchNID')->name('user.searchNID');
    Route::get('DonationDetails/{id}', 'UserController@DonationDetails')->name('DonationDetails');
    Route::get('attachments/view', 'UserController@viewAttachment')->name('attachments.view');
    Route::get('users/export/excel', 'UserController@exportExcel')->name('users.export.excel');
    Route::post('users/import/excel', 'UserController@importExcel')->name('users.import.excel');

    #### print users ####
    Route::get('PrintUsersNew', 'UserController@PrintUsersNew')->name('PrintUsersNew');
    Route::get('PrintUsersAccepted', 'UserController@PrintUsersAccepted')->name('PrintUsersAccepted');
    Route::get('PrintUsersPennding', 'UserController@PrintUsersPennding')->name('PrintUsersPennding');
    Route::get('PrintUsersRefused', 'UserController@PrintUsersRefused')->name('PrintUsersRefused');



    //    الراوتس الخاصه بالمقترض

    Route::get('getGuarantor', 'BorrowerController@getGuarantor')->name('getGuarantor');
    Route::POST('delete_borrowers', 'BorrowerController@delete')->name('delete_borrowers');
    //    Route::get('guarantorDetails/{id}', 'BorrowerController@delete')->name('guarantorDetails');
    Route::get('searchBorrowers', 'loansController@searchBorrowers')->name('search.Borrowers');
    Route::get('search.BorrowerPhone', 'loansController@searchBorrowers')->name('search.BorrowerPhone');
    Route::get('borrowerDetails/{id}    ', 'BorrowerController@borrowerDetails')->name(name: 'borrowerDetails');

    //    الراوتس الخاصه بالمقترض
    Route::resource('borrowers', controller: "BorrowerController");
    //    Route::get('getGuarantor', 'BorrowerController@getGuarantor')->name('getGuarantor');
    //    Route::POST('delete_borrowers', 'BorrowerController@delete')->name('delete_borrowers');
    Route::get('/borrowers/{id}/media', 'BorrowerController@getMedia');

    #### Donors ####
    Route::middleware(['permission:donors.index'])->group(function () {
        Route::resource('donors', 'DonorController');
        Route::post("donor/returnDonationMoney", 'DonorController@returnDonationMoney')->name('donor.returnDonationMoney');
        Route::put('donors/{id}', 'DonorController@update
        ')->name('updateDonor');
        Route::POST('delete_donors', 'DonorController@delete')->name('delete_donors');
        Route::POST('Donations_donors', 'DonationController@delete')->name('donations_delete');
        Route::resource('Donations', "DonationController");
        Route::get('/get_donor_phone/{id}', 'DonationController@get_donor_phone')->name("get_donor_phone");
        Route::get('/search-donor', 'DonationController@searchDonor')->name('search.donor');
        Route::get('donorDetails/{id}', 'DonorController@donorDetails')->name(name: 'donorDetails');

        Route::get('PrintDonations', 'DonationController@PrintDonations')->name('PrintDonations');
    });

    #### Borrowers ####
    Route::middleware(['borrowers.index'])->group(function () {
        // Route::resource('borrowers', 'BorrowerController');
        Route::get('borrowers/{id}/media', 'BorrowerController@getMedia');
        Route::get('/get_borrower_phone/{id}', 'BorrowerController@get_borrower_phone')->name("get_borrower_phone");
    });

    Route::get("lock/{lock?}", "LockerLogController@index")->name("lock");
    Route::get('association-revenues', 'AssociationRevenueController@index')->name('association-revenues.index');
    Route::get('association-revenues/create', 'AssociationRevenueController@create')->name('association-revenues.create');
    Route::post('association-revenues/store', 'AssociationRevenueController@store')->name('association-revenues.store');
    Route::get('association-revenues/{associationRevenue}/edit', 'AssociationRevenueController@edit')->name('association-revenues.edit');
    Route::put('association-revenues/{associationRevenue}', 'AssociationRevenueController@update')->name('association-revenues.update');
    Route::post('association-revenues/delete', 'AssociationRevenueController@delete')->name('association-revenues.delete');

    Route::get('association-expenses', 'AssociationExpenseController@index')->name('association-expenses.index');
    Route::get('association-expenses/create', 'AssociationExpenseController@create')->name('association-expenses.create');
    Route::post('association-expenses/store', 'AssociationExpenseController@store')->name('association-expenses.store');
    Route::get('association-expenses/{associationExpense}/edit', 'AssociationExpenseController@edit')->name('association-expenses.edit');
    Route::put('association-expenses/{associationExpense}', 'AssociationExpenseController@update')->name('association-expenses.update');
    Route::post('association-expenses/delete', 'AssociationExpenseController@delete')->name('association-expenses.delete');

    #### Tasks ####
    Route::resource("tasks", "TaskController")->middleware('permission:tasks.index');
    Route::POST('delete_task', 'TaskController@delete')->name('delete_task');
    //    the route of the task s
    Route::resource("tasks", "TaskController");
    Route::POST('delete_task', 'TaskController@delete')->name('delete_task');

    //القروض الحسنة
    Route::get("GoodLoansDonations", "GoodloansController@indexLoansDonations")->name("indexLoansDonations"); // التبرعات والمتبرعين
    Route::get('/getDonation', "GoodloansController@getDonors")->name('getDonors');


    //    Route::get('guarantorDetails/{id}', 'BorrowerController@delete')->name('guarantorDetails');

    // القروضsearchDonor
    Route::get('indexLoans', 'loansController@indexLoans')->name('index.Loans');
    Route::get('createLoans', 'loansController@createLoans')->name('create.Loans');
    Route::post('storeLoans', 'loansController@storeLoans')->name('store.loans');
    Route::get('personLoans/{id}', 'loansController@personLoans')->name('person.loans');
    Route::get('person-loans/{id}', 'loansController@personLoans')->name('person.loans');
    Route::get('loans/{id}', 'loansController@checkout')->name('loan.checkout');
    Route::post('loans/pay/{id}', 'loansController@payLoan')->name('loan.pay');
    Route::get("loan/print", "loansController@printLoan")->name("printLoan");

    //الزكاة والصدقات
    Route::get("safer/CharityZakat", "SaferController@indexCharityZakat")->name("safer.CharityZakat"); //تبرعات الزكاة والصدقات
    //التبرعات العينية
    Route::get('safer/InKindDonations', 'SaferController@InKindDonations')->name('safer.InKindDonations'); //التبرعات العينية
    #### Subventions ####
    Route::resource('subventions', 'SubventionController');
    Route::get('showSubventions', 'SubventionController@showSubventions')->name('showSubventions');
    Route::get('showOneSubvention/{id}', 'SubventionController@showOneSubvention')->name('showOneSubvention');
    Route::POST('delete_subventions', 'SubventionController@delete')->name('delete_subventions');


    // اعانات القرض الحسن
    Route::get('SubventionsLoans/index', 'SubventionsLoansController@index')->name('SubventionsLoans.index');
    Route::get('SubventionsLoans/create', 'SubventionsLoansController@create')->name('SubventionsLoans.create');
    Route::post('SubventionsLoans/store', 'SubventionsLoansController@store')->name('SubventionsLoans.store');
    Route::get('SubventionsLoans/{subvention}/print-receipt', 'SubventionsLoansController@printReceipt')->name('SubventionsLoans.print-receipt');
    Route::get('in-kind-disbursements', [InKindDisbursementController::class, 'index'])->name('in-kind-disbursements.index');
    Route::get('in-kind-disbursements/create', [InKindDisbursementController::class, 'create'])->name('in-kind-disbursements.create');
    Route::post('in-kind-disbursements', [InKindDisbursementController::class, 'store'])->name('in-kind-disbursements.store');
    #### Safer ####

    #### Subventions ####
    //    الاعانات الشهرية للمستفيدين
    //    Route::resource('subventions', 'SubventionController')->middleware('permission:subventions.index');
    //    Route::get('showSubventions', 'SubventionController@showSubventions')->name('showSubventions')->middleware('permission:showSubventions');
    //    Route::POST('delete_subventions', 'SubventionController@delete')->name('delete_subventions')->middleware('permission:delete_subventions');
    //    assets
    Route::resource("assets", "AssetController");
    Route::get('assetsShow', 'AssetController@show')->name('assetsShow')->middleware('permission:subventions.index');
    Route::POST('assetsDelete', 'AssetController@delete')->name('assetsDelete');



    #### Roles ####
    Route::resource("roles", "RulesController")->middleware('permission:roles.index');
    Route::post("Role_delete", "RulesController@delete")->name("Role_delete");




    #### Research ####
    Route::get('research', 'ResearchController@index')->name('research.index')->middleware('permission:research.index');
    Route::get('social_research/{user_id}', 'ResearchController@social_research')->name('social_research');
    Route::get('researchReceive', 'ResearchController@researchReceive')->name('research.receive');

    #### Setting ####
    Route::get('setting', 'SettingController@index')->name('setting.index')->middleware(['permission:setting.index', 'admin']);
    Route::post('settingUpdate', 'SettingController@update')->name('settingUpdate');
    Route::get('references', [ReferenceController::class, 'dashboard'])->name('references.dashboard');
    Route::get('references/{type}', [ReferenceController::class, 'index'])->name('references.index');
    Route::get('references/{type}/create', [ReferenceController::class, 'create'])->name('references.create');
    Route::post('references/{type}', [ReferenceController::class, 'store'])->name('references.store');
    Route::get('references/{type}/{id}/edit', [ReferenceController::class, 'edit'])->name('references.edit');
    Route::put('references/{type}/{id}', [ReferenceController::class, 'update'])->name('references.update');
    Route::post('references/{type}/{id}/toggle-status', [ReferenceController::class, 'toggleStatus'])->name('references.toggle-status');
    Route::post('references/{type}/delete', [ReferenceController::class, 'delete'])->name('references.delete');
    Route::post('borrowerReviewModal', 'BorrowerController@storeReview')->name('BorrowerReview');
    #### Auth ####
    Route::get('logout', 'AuthController@logout')->name('admin.logout');
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


//the arrow charts routes
Route::get('/chart-data', 'UserController@getChartData');
Route::get('/dashboard', 'UserController@CartIndex');

Route::get('donors/chart-data', 'DonorController@getChartData');
Route::get('donors/dashboard', 'DonorController@CartIndex');
