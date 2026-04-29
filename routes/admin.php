<?php

use App\Http\Controllers\Admin\BorrowerController;
use App\Http\Controllers\Admin\CaseResearchController;
use App\Http\Controllers\Admin\InKindDisbursementController;
use App\Http\Controllers\Admin\ReferenceController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'admin', 'middleware' => 'auth:admin'], function () {

    #### Home ####
    Route::get('/', 'HomeController@index')->name('adminHome');
    #### Admins ####
    Route::get('admins', 'AdminController@index')->name('admins.index')->middleware('permission:admins.index');
    Route::get('admins/create', 'AdminController@create')->name('admins.create')->middleware('permission:admins.create');
    Route::post('admins', 'AdminController@store')->name('admins.store')->middleware('permission:admins.create');
    Route::get('admins/{admin}/edit', 'AdminController@edit')->name('admins.edit')->middleware('permission:admins.edit');
    Route::put('admins/{admin}', 'AdminController@update')->name('admins.update')->middleware('permission:admins.edit');
    Route::delete('admins/{admin}', 'AdminController@destroy')->name('admins.destroy')->middleware('permission:delete_admin');
    Route::get('admins/setting', 'AdminController@show')->name("admins.setting");
    Route::POST('delete_admin', 'AdminController@delete')->name('delete_admin')->middleware('permission:delete_admin');
    Route::get('my_profile', 'AdminController@myProfile')->name('myProfile');
    Route::post('changeRole', 'AdminController@changeRole')->name('changeRole');
    Route::post('showChangeRole', 'AdminController@showChangeRole')->name('showChangeRole');

    Route::resource("adminSubscription", "AdminSubscriptionsController")->middleware('permission:subscription.index');
    Route::get('/get-subscription-price', function (Request $request) {
        $price = \App\Models\Setting::first()->adminSubscription ?? 0;
        return response()->json(['success' => true, 'price' => $price]);
    })->name('getSubscriptionPrice');


    Route::resource("SubscriptionFee", "SubscriptionFeeController")->middleware('permission:subscription.index');

    #### Users ####
    Route::get('users.create', 'UserController@create')->name('users.create')->middleware('permission:users.create');
    Route::get('users', 'UserController@index')->name('users.index')->middleware('permission:users.index');
    Route::get('users/{status}', 'UserController@index')->name('users.index.status')->middleware('permission:users.index');
    Route::get('users/{id}/edit', 'UserController@edit')->name('users.edit')->middleware('permission:users.edit');
    Route::POST('users.store', 'UserController@store')->name('users.store')->middleware('permission:users.create');
    Route::put('/users/{id}/update', 'UserController@update')->name('users.update')->middleware('permission:users.edit');
    Route::delete('/users/delete', [UserController::class, 'destroy'])->name('delete_users')->middleware('permission:delete_users');

    Route::POST('updateUserStatus', 'UserController@updateUserStatus')->name('updateUserStatus')->middleware('permission:updateUserStatus');
    Route::post('users/toggle-monthly-subvention', 'UserController@toggleMonthlySubvention')->name('users.toggleMonthlySubvention')->middleware('permission:updateUserStatus');
    Route::get('userDetails/{id?}', 'UserController@userDetails')->name('userDetails')->middleware('permission:userDetails');
    Route::get('searchNID/{id?}', 'UserController@searchNID')->name('user.searchNID')->middleware('permission:users.index');
    Route::get('DonationDetails/{id}', 'UserController@DonationDetails')->name('DonationDetails')->middleware('permission:DonationDetails');
    Route::get('attachments/view', 'UserController@viewAttachment')->name('attachments.view');
    Route::get('users/export/excel', 'UserController@exportExcel')->name('users.export.excel')->middleware('permission:users.index');
    Route::post('users/import/excel', 'UserController@importExcel')->name('users.import.excel')->middleware('permission:users.create');

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
    Route::get('donors', 'DonorController@index')->name('donors.index')->middleware('permission:donors.index');
    Route::get('donors/create', 'DonorController@create')->name('donors.create')->middleware('permission:donors.create');
    Route::post('donors', 'DonorController@store')->name('donors.store')->middleware('permission:donors.create');
    Route::get('donors/{donor}/edit', 'DonorController@edit')->name('donors.edit')->middleware('permission:donors.edit');
    Route::put('donors/{donor}', 'DonorController@update')->name('donors.update')->middleware('permission:donors.edit');
    Route::delete('donors/{donor}', 'DonorController@destroy')->name('donors.destroy')->middleware('permission:delete_donors');
    Route::post("donor/returnDonationMoney", 'DonorController@returnDonationMoney')->name('donor.returnDonationMoney')->middleware('permission:donors.edit');
    Route::put('donors/{id}', 'DonorController@update
        ')->name('updateDonor')->middleware('permission:donors.edit');
    Route::POST('delete_donors', 'DonorController@delete')->name('delete_donors')->middleware('permission:delete_donors');
    Route::POST('Donations_donors', 'DonationController@delete')->name('donations_delete')->middleware('permission:donations_delete');
    Route::get('Donations', 'DonationController@index')->name('Donations.index')->middleware('permission:Donations.index');
    Route::get('Donations/create', 'DonationController@create')->name('Donations.create')->middleware('permission:Donations.create');
    Route::post('Donations', 'DonationController@store')->name('Donations.store')->middleware('permission:Donations.create');
    Route::get('Donations/{Donation}/edit', 'DonationController@edit')->name('Donations.edit')->middleware('permission:Donations.edit');
    Route::put('Donations/{Donation}', 'DonationController@update')->name('Donations.update')->middleware('permission:Donations.edit');
    Route::delete('Donations/{Donation}', 'DonationController@destroy')->name('Donations.destroy')->middleware('permission:donations_delete');
    Route::get('/get_donor_phone/{id}', 'DonationController@get_donor_phone')->name("get_donor_phone")->middleware('permission:get_donor_phone');
    Route::get('/search-donor', 'DonationController@searchDonor')->name('search.donor')->middleware('permission:search.donor');
    Route::get('donorDetails/{id}', 'DonorController@donorDetails')->name(name: 'donorDetails')->middleware('permission:donors.index');

    Route::get('PrintDonations', 'DonationController@PrintDonations')->name('PrintDonations')->middleware('permission:Donations.index');

    #### Borrowers ####
    Route::middleware(['borrowers.index'])->group(function () {
        // Route::resource('borrowers', 'BorrowerController');
        Route::get('borrowers/{id}/media', 'BorrowerController@getMedia');
        Route::get('/get_borrower_phone/{id}', 'BorrowerController@get_borrower_phone')->name("get_borrower_phone");
    });

    Route::get("lock/{lock?}", "LockerLogController@index")->name("lock")->middleware('permission:lock.index');
    Route::get('association-revenues', 'AssociationRevenueController@index')->name('association-revenues.index')->middleware('permission:association.revenues.index');
    Route::get('association-revenues/create', 'AssociationRevenueController@create')->name('association-revenues.create')->middleware('permission:association.revenues.create');
    Route::post('association-revenues/store', 'AssociationRevenueController@store')->name('association-revenues.store')->middleware('permission:association.revenues.create');
    Route::get('association-revenues/{associationRevenue}/edit', 'AssociationRevenueController@edit')->name('association-revenues.edit')->middleware('permission:association.revenues.edit');
    Route::put('association-revenues/{associationRevenue}', 'AssociationRevenueController@update')->name('association-revenues.update')->middleware('permission:association.revenues.edit');
    Route::post('association-revenues/delete', 'AssociationRevenueController@delete')->name('association-revenues.delete')->middleware('permission:association.revenues.delete');

    Route::get('association-expenses', 'AssociationExpenseController@index')->name('association-expenses.index')->middleware('permission:association.expenses.index');
    Route::get('association-expenses/create', 'AssociationExpenseController@create')->name('association-expenses.create')->middleware('permission:association.expenses.create');
    Route::post('association-expenses/store', 'AssociationExpenseController@store')->name('association-expenses.store')->middleware('permission:association.expenses.create');
    Route::get('association-expenses/{associationExpense}/edit', 'AssociationExpenseController@edit')->name('association-expenses.edit')->middleware('permission:association.expenses.edit');
    Route::put('association-expenses/{associationExpense}', 'AssociationExpenseController@update')->name('association-expenses.update')->middleware('permission:association.expenses.edit');
    Route::post('association-expenses/delete', 'AssociationExpenseController@delete')->name('association-expenses.delete')->middleware('permission:association.expenses.delete');

    #### Tasks ####
    Route::get('tasks', 'TaskController@index')->name('tasks.index')->middleware('permission:tasks.index');
    Route::get('tasks/create', 'TaskController@create')->name('tasks.create')->middleware('permission:tasks.create');
    Route::post('tasks', 'TaskController@store')->name('tasks.store')->middleware('permission:tasks.create');
    Route::get('tasks/{task}/edit', 'TaskController@edit')->name('tasks.edit')->middleware('permission:tasks.edit');
    Route::put('tasks/{task}', 'TaskController@update')->name('tasks.update')->middleware('permission:tasks.edit');
    Route::delete('tasks/{task}', 'TaskController@destroy')->name('tasks.destroy')->middleware('permission:delete_task');
    Route::POST('delete_task', 'TaskController@delete')->name('delete_task')->middleware('permission:delete_task');

    //القروض الحسنة
    Route::get("GoodLoansDonations", "GoodloansController@indexLoansDonations")->name("indexLoansDonations")->middleware('permission:goodLoans.index'); // التبرعات والمتبرعين
    Route::get('/getDonation', "GoodloansController@getDonors")->name('getDonors')->middleware('permission:goodLoans.index');


    //    Route::get('guarantorDetails/{id}', 'BorrowerController@delete')->name('guarantorDetails');

    // القروضsearchDonor
    Route::get('indexLoans', 'loansController@indexLoans')->name('index.Loans')->middleware('permission:goodLoans.index');
    Route::get('createLoans', 'loansController@createLoans')->name('create.Loans')->middleware('permission:goodLoans.create');
    Route::post('storeLoans', 'loansController@storeLoans')->name('store.loans')->middleware('permission:goodLoans.create');
    Route::get('personLoans/{id}', 'loansController@personLoans')->name('person.loans')->middleware('permission:goodLoans.index');
    Route::get('person-loans/{id}', 'loansController@personLoans')->name('person.loans')->middleware('permission:goodLoans.index');
    Route::get('loans/{id}', 'loansController@checkout')->name('loan.checkout')->middleware('permission:goodLoans.edit');
    Route::post('loans/pay/{id}', 'loansController@payLoan')->name('loan.pay')->middleware('permission:goodLoans.edit');
    Route::post('loans/delete', 'loansController@delete')->name('delete_loans')->middleware('permission:delete_goodLoans');
    Route::get("loan/print", "loansController@printLoan")->name("printLoan")->middleware('permission:goodLoans.index');

    //الزكاة والصدقات
    Route::get("safer/CharityZakat", "SaferController@indexCharityZakat")->name("safer.CharityZakat")->middleware('permission:zakat.index'); //تبرعات الزكاة والصدقات
    //التبرعات العينية
    Route::get('safer/InKindDonations', 'SaferController@InKindDonations')->name('safer.InKindDonations')->middleware('permission:safer.InKindDonations'); //التبرعات العينية
    #### Subventions ####
    Route::get('subventions', 'SubventionController@index')->name('subventions.index')->middleware('permission:subventions.index');
    Route::get('subventions/create', 'SubventionController@create')->name('subventions.create')->middleware('permission:subventions.create');
    Route::post('subventions', 'SubventionController@store')->name('subventions.store')->middleware('permission:subventions.create');
    Route::get('subventions/{subvention}/edit', 'SubventionController@edit')->name('subventions.edit')->middleware('permission:subventions.edit');
    Route::put('subventions/{subvention}', 'SubventionController@update')->name('subventions.update')->middleware('permission:subventions.edit');
    Route::delete('subventions/{subvention}', 'SubventionController@destroy')->name('subventions.destroy')->middleware('permission:delete_subventions');
    Route::get('showSubventions', 'SubventionController@showSubventions')->name('showSubventions')->middleware('permission:showSubventions');
    Route::get('showOneSubvention/{id}', 'SubventionController@showOneSubvention')->name('showOneSubvention');
    Route::POST('delete_subventions', 'SubventionController@delete')->name('delete_subventions')->middleware('permission:delete_subventions');


    // اعانات القرض الحسن
    Route::get('SubventionsLoans/index', 'SubventionsLoansController@index')->name('SubventionsLoans.index')->middleware('permission:SubventionsLoans.index');
    Route::get('SubventionsLoans/create', 'SubventionsLoansController@create')->name('SubventionsLoans.create')->middleware('permission:SubventionsLoans.create');
    Route::post('SubventionsLoans/store', 'SubventionsLoansController@store')->name('SubventionsLoans.store')->middleware('permission:SubventionsLoans.create');
    Route::post('SubventionsLoans/delete', 'SubventionsLoansController@delete')->name('SubventionsLoans.delete')->middleware('permission:SubventionsLoans.delete');
    Route::get('SubventionsLoans/print-selected', 'SubventionsLoansController@showSubventions')->name('SubventionsLoans.print-selected');
    Route::get('SubventionsLoans/{subvention}/print-receipt', 'SubventionsLoansController@printReceipt')->name('SubventionsLoans.print-receipt');
    Route::get('in-kind-disbursements', [InKindDisbursementController::class, 'index'])->name('in-kind-disbursements.index')->middleware('permission:in-kind-disbursements.index');
    Route::get('in-kind-disbursements/create', [InKindDisbursementController::class, 'create'])->name('in-kind-disbursements.create')->middleware('permission:in-kind-disbursements.create');
    Route::post('in-kind-disbursements/store', [InKindDisbursementController::class, 'store'])->name('in-kind-disbursements.store')->middleware('permission:in-kind-disbursements.create');
    Route::post('in-kind-disbursements/delete', [InKindDisbursementController::class, 'delete'])->name('in-kind-disbursements.delete')->middleware('permission:in-kind-disbursements.delete');
    #### Safer ####

    #### Subventions ####
    //    الاعانات الشهرية للمستفيدين
    //    Route::resource('subventions', 'SubventionController')->middleware('permission:subventions.index');
    //    Route::get('showSubventions', 'SubventionController@showSubventions')->name('showSubventions')->middleware('permission:showSubventions');
    //    Route::POST('delete_subventions', 'SubventionController@delete')->name('delete_subventions')->middleware('permission:delete_subventions');
    //    assets
    Route::get('assets', 'AssetController@index')->name('assets.index')->middleware('permission:assets.index');
    Route::get('assets/create', 'AssetController@create')->name('assets.create')->middleware('permission:assets.create');
    Route::post('assets', 'AssetController@store')->name('assets.store')->middleware('permission:assets.create');
    Route::get('assets/{asset}/edit', 'AssetController@edit')->name('assets.edit')->middleware('permission:assets.edit');
    Route::put('assets/{asset}', 'AssetController@update')->name('assets.update')->middleware('permission:assets.edit');
    Route::delete('assets/{asset}', 'AssetController@destroy')->name('assets.destroy')->middleware('permission:delete_assets');
    Route::get('assetsShow', 'AssetController@show')->name('assetsShow')->middleware('permission:assets.index');
    Route::POST('assetsDelete', 'AssetController@delete')->name('assetsDelete')->middleware('permission:delete_assets');



    #### Roles ####
    Route::get('roles', 'RulesController@index')->name('roles.index')->middleware('permission:roles.index');
    Route::get('roles/create', 'RulesController@create')->name('roles.create')->middleware('permission:roles.create');
    Route::post('roles', 'RulesController@store')->name('roles.store')->middleware('permission:roles.create');
    Route::get('roles/{role}/edit', 'RulesController@edit')->name('roles.edit')->middleware('permission:roles.edit');
    Route::put('roles/{role}', 'RulesController@update')->name('roles.update')->middleware('permission:roles.edit');
    Route::delete('roles/{role}', 'RulesController@destroy')->name('roles.destroy')->middleware('permission:Role_delete');
    Route::post("Role_delete", "RulesController@delete")->name("Role_delete")->middleware('permission:Role_delete');




    #### Research ####
    Route::get('research', 'ResearchController@index')->name('research.index')->middleware('permission:research.index');
    Route::get('social_research/{user_id}', 'ResearchController@social_research')->name('social_research')->middleware('permission:research.index');
    Route::get('researchReceive', 'ResearchController@researchReceive')->name('research.receive')->middleware('permission:research.index');
    Route::get('case-research', [CaseResearchController::class, 'index'])->name('case-research.index')->middleware('permission:research.index');
    Route::get('case-research/create', [CaseResearchController::class, 'create'])->name('case-research.create')->middleware('permission:case-research.create');
    Route::post('case-research/store', [CaseResearchController::class, 'store'])->name('case-research.store')->middleware('permission:case-research.create');
    Route::get('case-research/{id}/edit', [CaseResearchController::class, 'edit'])->name('case-research.edit')->middleware('permission:case-research.edit');
    Route::get('case-research/{id}/attachments/{index}', [CaseResearchController::class, 'attachment'])->name('case-research.attachment')->middleware('permission:research.index');
    Route::put('case-research/{id}', [CaseResearchController::class, 'update'])->name('case-research.update')->middleware('permission:case-research.edit');
    Route::delete('case-research/delete', [CaseResearchController::class, 'destroy'])->name('case-research.delete')->middleware('permission:case-research.delete');
    Route::get('case-research/workload', [CaseResearchController::class, 'workload'])->name('case-research.workload')->middleware('permission:case-research.workload.index');
    Route::get('case-research/researchers', [CaseResearchController::class, 'researchers'])->name('case-research.researchers')->middleware('permission:case-research.researchers.index');
    Route::post('case-research/researchers', [CaseResearchController::class, 'storeResearcher'])->name('case-research.researchers.store')->middleware('permission:case-research.manage-researchers');

    #### Setting ####
    Route::get('setting', 'SettingController@index')->name('setting.index')->middleware(['permission:setting.index', 'admin']);
    Route::post('settingUpdate', 'SettingController@update')->name('settingUpdate')->middleware('permission:setting.index');
    Route::get('references', [ReferenceController::class, 'dashboard'])->name('references.dashboard')->middleware('permission:references.dashboard');
    Route::get('references/{type}', [ReferenceController::class, 'index'])->name('references.index')->middleware('permission:references.index');
    Route::get('references/{type}/create', [ReferenceController::class, 'create'])->name('references.create')->middleware('permission:references.create');
    Route::post('references/{type}', [ReferenceController::class, 'store'])->name('references.store')->middleware('permission:references.create');
    Route::get('references/{type}/{id}/edit', [ReferenceController::class, 'edit'])->name('references.edit')->middleware('permission:references.edit');
    Route::put('references/{type}/{id}', [ReferenceController::class, 'update'])->name('references.update')->middleware('permission:references.edit');
    Route::post('references/{type}/{id}/toggle-status', [ReferenceController::class, 'toggleStatus'])->name('references.toggle-status')->middleware('permission:references.edit');
    Route::post('references/{type}/delete', [ReferenceController::class, 'delete'])->name('references.delete')->middleware('permission:references.delete');
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
