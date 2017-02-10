<?php 
/**
 * ModuleAlias: news
 * ModuleName: news
 * Description: Route of module news.This bellow have 3 type route: normal rotue, admin route, api route
 * to use, you have to uncommnet it
 * @author: noname
 * @version: 1.0
 * @package: PhambinhCMS
 */

// Route::group(['module' => 'news', 'namespace' => 'Phambinh\News\Http\Controllers', 'middleware' => ['web'], 'prefix' => 'news'], function() {

// });

Route::group(['module' => 'news', 'namespace' => 'Phambinh\News\Http\Controllers\Admin', 'middleware' => ['web'], 'prefix' => 'admin/news'], function () {
    Route::get('/', 'NewsController@index')->name('admin.news.index');
    Route::get('create', 'NewsController@create')->name('admin.news.create');
    Route::post('/', 'NewsController@store')->name('admin.news.store');
    Route::get('{id}', 'NewsController@show')->name('admin.news.show');
    Route::get('{id}/edit', 'NewsController@edit')->name('admin.news.edit');
    Route::put('{id}', 'NewsController@update')->name('admin.news.update');
    Route::put('{id}/disable', 'NewsController@disable')->name('admin.news.disable');
    Route::put('{id}/enable', 'NewsController@enable')->name('admin.news.enable');
    Route::delete('{id}', 'NewsController@destroy')->name('admin.news.destroy');

    Route::get('category/', 'CategoryController@index')->name('admin.news.category.index');
    Route::get('category/create', 'CategoryController@create')->name('admin.news.category.create');
    Route::post('category/', 'CategoryController@store')->name('admin.news.category.store');
    Route::get('category/{id}', 'CategoryController@show')->name('admin.news.category.show');
    Route::get('category/{id}/edit', 'CategoryController@edit')->name('admin.news.category.edit');
    Route::put('category/{id}', 'CategoryController@update')->name('admin.news.category.update');
    Route::put('category/{category}/disable', 'CategoryController@disable');
    Route::put('category/{category}/enable', 'CategoryController@disable');
    Route::delete('category/{id}', 'CategoryController@destroy')->name('admin.news.category.destroy');
});

Route::group(['module' => 'news', 'namespace' => 'Phambinh\News\Http\Controllers\Admin', 'middleware' => ['web'], 'prefix' => 'api/v1/news'], function () {
    Route::get('/', 'NewsController@index')->name('api.v1.news.index');
});
