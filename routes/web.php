<?php

use Illuminate\Support\Facades\Route;

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

    (new \App\Admin\Controllers\ProjectController())->dynamicSQL();

    $po = request()->input('po');

    $headers = DB::table('dbo.Voltage CN$Purchase Header$437dbf0e-84ff-417a-965d-ed2bb9650972')->where('No_', $po)->first();
    $users = DB::table('dbo.Voltage CN$Purchase Line$437dbf0e-84ff-417a-965d-ed2bb9650972')->where('Document No_', $po)->get();

    $users = $users->where('Document Type', 1)->groupBy('Document No_')->map(function ($item){
        return $item->map(function ($res){
            return [
                'Document No_' => $res['Document No_'],
                'Line No_' => $res['Line No_'],
                'Description' => $res['Description'],
                'Type' => $res['Type'],
                'No_' => $res['No_'],
                'Variant Code' => $res['Variant Code'],
                'Quantity' => $res['Quantity'],
                'Unit of Measure' => $res['Unit of Measure'],
                'Direct Unit Cost' => $res['Direct Unit Cost'],
                'Unit Cost (LCY)' => $res['Unit Cost (LCY)'],
                'Amount' => $res['Amount'],
                'Amount Including VAT' => $res['Amount Including VAT'],
                'Quantity Received' => $res['Quantity Received'],
                'Quantity Invoiced' => $res['Quantity Invoiced'],
            ];
        });
    });
    $users = $users->first();

    return view('welcome', compact('users', 'headers'));
});
