<?php

/** @var \Laravel\Lumen\Routing\Router $router */

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
use App\Http\Controllers;

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api'], function ($router) 
{
    $router->get('me', ['uses' => 'Auth\AuthController@me']);
});


$router->group(['prefix' => 'api'], function () use ($router) {

    

    /* AUTH LINKS */
    // $router->get('login/admin',  ['uses' => 'Auth\LoginController@adminLogin']);
    // $router->get('me',  ['middleware' => 'auth:api', 'uses' => 'Auth\AuthController@me']);
    $router->post('register/user',  ['uses' => 'Auth\AuthController@register']);
    $router->post('login/user',  ['uses' => 'Auth\AuthController@login']);
    $router->get('logout/user',  ['uses' => 'Auth\AuthController@logout']);
    // $router->post('/login/customer',  ['uses' => 'Auth\LoginController@custLogin']);
    // $router->post('/register/admin',  ['uses' => 'Auth\RegisterController@createAdmin']);
    // $router->post('/register/customer',  ['uses' => 'Auth\RegisterController@createCustomer']);


});


//     /* STORE CATEGORIES */
//     $router->get('category/store',  ['uses' => 'CatController@getAllStoreCat']);
//     $router->get('category/store/{id:[0-9]+}', ['uses' => 'CatController@getCatSingle']);
//     $router->post('category/store', ['uses' => 'CatController@createCat']);
//     $router->put('category/store/{id}', ['uses' => 'CatController@updateCat']);
//     $router->delete('category/store/{id:[0-9]+}', ['uses' => 'CatController@deleteCat']);
//     $router->get('category/store/{id:[0-9]+}/sub', ['uses' => 'CatController@catSub']); //get the sub of particular store cat


//     /* PRODUCT CATEGORIES */
//     $router->get('category/product',  ['uses' => 'CatController@getAllProductCat']); // get all prod cats
//     $router->get('category/product/{id}', ['uses' => 'CatController@getCatSingle']);
//     $router->post('category/product', ['uses' => 'CatController@createCat']);
//     $router->put('category/product/{id}', ['uses' => 'CatController@updateCat']);
//     $router->delete('category/product/{id:[0-9]+}', ['uses' => 'CatController@deleteCat']);
//     $router->get('category/product/{id:[0-9]+}/sub', ['uses' => 'CatController@getCatSub']); //get the sub of particular prod cat


//     /* PRODUCT SUB CATEGORIES */

//     $router->get('product/sub',  ['uses' => 'SubCatController@showAllProductSubCat']); //show all prod sub cat
//     $router->get('product/sub/{id}',  ['uses' => 'SubCatController@getSubCatSingle']); // get single sub category
//     $router->get('product/sub/{id}/cat',  ['uses' => 'SubCatController@getProductCategory']); //get the main cat of a sub
//     // categoy/sub/{id}/cat
//     // select from cat where id = $id
//     $router->post('product/sub', ['uses' => 'SubCatController@createSubCat']);
//     // $router->put('product/sub/{id}', ['uses' => 'CatController@createSubCat']);
//     $router->delete('category/product/{id:[0-9]+}', ['uses' => 'CatController@deleteSubCat']);
//     // $router->get('product/{id:[0-9]+}/sub/{id:[0-9]}',  ['uses' => 'SubCatController@getSubCatSingle']);


    
//     $router->post('login', ['uses' => 'Auth\LoginController@authenticate']);


//     $router->post('store/register', ['uses' => 'StoresController@createStore']);
//     $router->get('store',  ['uses' => 'StoresController@getAllStores'], ['middleware' => ['IsAdminMiddleware']]);
//     $router->get('store/{id}',  ['uses' => 'StoresController@getSingleStore']); //get single store details
//     $router->put('store/{id}',  ['uses' => 'StoresController@updateStore']);
//     $router->get('store/login', ['uses' => 'StoresController@login']);
//     $router->delete('store/{id}', ['uses' => 'StoresController@deleteStore']);
//     // $router->get('product/{id:[0-9]+}/cat', ['uses' => 'ProductsController@ProductBelongsTo']);

    
//     $router->get('product',  ['uses' => 'ProductsController@showAllProducts']);
//     $router->get('product/{id:[0-9]+}', ['uses' => 'ProductsController@showOneproduct']);
//     $router->post('product', ['uses' => 'ProductsController@createProduct']);
//     $router->put('product/{id}', ['uses' => 'ProductsController@updateproduct']);
//     $router->get('product/{id:[0-9]+}/cat', ['uses' => 'ProductsController@ProductBelongsTo']);
//     $router->delete('product/{id:[0-9]+}', ['uses' => 'ProductsController@deleteProduct']);
//   });


    // $router->get('prodcat',  ['uses' => 'ProdCatController@showAllProdCat']);
    // $router->get('prodcat/{id:[0-9]+}', ['uses' => 'ProdCatController@showOneProdCat']);
    // $router->get('prodcat/{id:[0-9]+}/sub', ['uses' => 'ProdCatController@prodCatHas']);
    // $router->post('prodcat', ['uses' => 'ProdCatController@createProdCat']);
    // $router->put('prodcat/{id}', ['uses' => 'ProdCatController@updateProdCat']);
    // $router->delete('prodcat/{id:[0-9]+}', ['uses' => 'ProdCatController@deleteProdCat']);


