<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$sub_folder = env('SUB_FOLDER');
$index_route = $sub_folder;
if($sub_folder ==''){
    $index_route = '/';
}

$app->get($index_route, function () use ($app,$sub_folder) {
    return redirect($sub_folder.'/dashboard');
});





$app->group(['middleware' => 'auth'], function () use ($app,$sub_folder) {



    $app->get($sub_folder.'/dashboard', 'IndexController@dashboard');
    $app->post($sub_folder.'/current_threads', 'IndexController@getCurrentThreads');
    $app->get($sub_folder.'/tables', 'TablesController@index');
    $app->get($sub_folder.'/stats', 'StatsController@index');
    $app->post($sub_folder.'/tables', 'TablesController@optimizeTable');
    $app->get($sub_folder.'/tables/history', 'TablesController@history');
    $app->get($sub_folder.'/processes', 'ProcessesController@processes');
    $app->post($sub_folder.'/processes_list', 'ProcessesController@getProcesses');
    $app->get($sub_folder.'/recommendations', 'RecommendationsController@index');
    $app->get($sub_folder.'/settings', 'UsersController@settings');
    $app->post($sub_folder.'/settings', 'UsersController@settings');
});











$app->get($sub_folder.'/login', 'UsersController@login');
$app->get($sub_folder.'/logout', 'UsersController@logout');
$app->post($sub_folder.'/login', 'UsersController@login');
