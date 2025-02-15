<?php

use Illuminate\Support\Facades\Route;
use \Illuminate\Http\Request;

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

Route::get('/', function () {
    return redirect('admin/login');
});

Route::get('webView',function(Request $request){
    $validate = Validator::make($request->all(),[
        'type'=>'required|in:terms,privacy'
    ]);

    if ($validate->fails()){
        return 'type is missing';
    }

    $type = $request->type;
    $text = \App\Models\Setting::first("$request->type");
    $text = $text->$type;
    return view('webView',compact('text'));
});
