<?php
/**
 *  Disallow access to root
 */
Route::get('/', function () {
    return App::abort(403); // Forbidden
});


/**
 *  API endpoints
 */
Route::group([ 'middleware' => 'api', 'prefix' => '/api' ], function() {
    Route::post('/auth', 'AuthController@authenticate');
    Route::post('/up', 'UploadController@upload');
});


/**
 *  Pages (registration, etc.)
 */
Route::group([ 'prefix' => '/page' ], function() {

});


/**
 *  Upload routes
 */
Route::get('/{alias}/{protectAlias}', 'UploadController@get');