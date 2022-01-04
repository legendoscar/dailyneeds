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
return [
        'API Documentation URL => https://documenter.getpostman.com/view/6959988/UVJeFFxk',
        'API Server URL => https://dailyneedsng.herokuapp.com/'
    ];
});

$router->group(['prefix' => 'api'], function () use ($router) {

    
    $router->get('user/profile', ['uses' => 'UserProfileController@profile']);
    

    
    //     /* STORE CATEGORIES */
        $router->get('category/store',  ['uses' => 'CatController@getAllStoreCat']);
        $router->post('category/store', ['uses' => 'CatController@createCat']);
        $router->get('category/store/{id:[0-9]+}', ['uses' => 'CatController@getCatSingle']);
        $router->put('category/store/{id}', ['uses' => 'CatController@updateCat']);
        $router->delete('category/store/{id:[0-9]+}', ['uses' => 'CatController@deleteCatPerm']);
        $router->get('category/store/{id:[0-9]+}/sub', ['uses' => 'CatController@catSub']); //get the sub of particular store cat


        
        /* PRODUCT CATEGORIES */
        $router->get('category/product',  ['uses' => 'CatController@getAllProductCat']); // get all prod cats
        $router->post('category/product', ['uses' => 'CatController@createCat']);
        $router->get('category/product/{id}', ['uses' => 'CatController@getCatSingle']);
        $router->put('category/product/{id}', ['uses' => 'CatController@updateCat']);
        $router->delete('category/product/{id:[0-9]+}', ['uses' => 'CatController@deleteCat']);
        
        
        
        /* PRODUCT SUB CATEGORIES */
        // get the sub cat of a product cat
        $router->get('category/product/{id:[0-9]+}/sub', ['uses' => 'CatController@getCatSub']); //get the subCat of single prod cat
        $router->post('category/sub/product', ['uses' => 'SubCatController@createSubCat']); //create sub cat
        // $router->get('category/sub/product/sub',  ['uses' => 'SubCatController@showAllProductSubCat']); //show all prod sub cat
        $router->get('category/sub/product/{id}',  ['uses' => 'SubCatController@getSubCatSingle']); // get single sub category
        $router->get('product/sub/{id}/cat',  ['uses' => 'SubCatController@getProductCategory']); //get the main cat of a sub
        $router->put('category/sub/product/{id}',  ['uses' => 'SubCatController@updateSubCat']); // update single sub category
        $router->delete('category/sub/product/{id}',  ['uses' => 'SubCatController@deleteSubCat']); // delete single sub category
        
        
        /* PRODUCT  */
        $router->get('product',  ['uses' => 'ProductsController@showAllProducts']);
        $router->get('product/{id:[0-9]+}', ['uses' => 'ProductsController@showOneProduct']);
        $router->get('product/store/{id:[0-9]+}', ['uses' => 'ProductsController@showStoreProducts']); //show products owned by stored
        $router->post('product', ['uses' => 'ProductsController@createProduct']);
        $router->put('product/{id}', ['uses' => 'ProductsController@updateproduct']);
        $router->delete('product/{id:[0-9]+}', ['uses' => 'ProductsController@deleteProduct']);
        
        
        /* FAVORITE  */
        // $router->get('product',  ['uses' => 'ProductsController@showAllProducts']);
        // $router->get('product/{id:[0-9]+}', ['uses' => 'ProductsController@showOneProduct']);
        // $router->get('product/store/{id:[0-9]+}', ['uses' => 'ProductsController@showStoreProducts']); //show products owned by stored
        $router->post('product/{id}/favorites', ['uses' => 'FavoritesController@store']);
        // $router->put('product/{id}', ['uses' => 'ProductsController@updateproduct']);
        // $router->delete('product/{id:[0-9]+}', ['uses' => 'ProductsController@deleteProduct']);

        
        $router->get('product/{id:[0-9]+}/cat', ['uses' => 'ProductsController@ProductBelongsTo']);
        
        
        /* LOCATIONS */
        $router->get('location',  ['uses' => 'LocationsController@showAllLocations']);
        $router->get('location/{id:[0-9]+}', ['uses' => 'LocationsController@showOneLocation']);
        $router->post('location', ['uses' => 'LocationsController@createLocation']);
        $router->delete('location/{id:[0-9]+}', ['uses' => 'LocationsController@deleteLocation']);
        $router->put('location/{id}', ['uses' => 'LocationsController@updateLocation']);
       
       
        /* USER ADDRESS */
        $router->get('useraddress',  ['uses' => 'UserAddressController@showAllUserAddress']);
        $router->get('useraddress/{id:[0-9]+}', ['uses' => 'UserAddressController@showOneUserAddress']);
        $router->post('useraddress', ['uses' => 'UserAddressController@createUserAddress']);
        $router->delete('useraddress/{id:[0-9]+}', ['uses' => 'UserAddressController@deleteUserAddress']);
        $router->put('useraddress/{id}', ['uses' => 'UserAddressController@updateUserAddress']);
        
        
        /* ORDER ITEMS */
        $router->get('orderitem',  ['uses' => 'OrderItemsController@showAllOrderItems']);
        $router->get('orderitem/{id:[0-9]+}', ['uses' => 'OrderItemsController@showOneOrderItem']);
        $router->post('orderitem', ['uses' => 'OrderItemsController@createOrderItem']);
        $router->delete('orderitem/{id:[0-9]+}', ['uses' => 'OrderItemsController@deleteOrderItem']);
        $router->put('orderitem/{id}', ['uses' => 'OrderItemsController@updateOrderItem']);
        
        
        /* ORDERS */
        $router->get('order',  ['uses' => 'OrdersController@showAllOrders']);
        $router->get('order/{id:[0-9]+}', ['uses' => 'OrdersController@showOneOrder']);
        $router->post('order', ['uses' => 'OrdersController@createOrder']);
        $router->delete('order/{id:[0-9]+}', ['uses' => 'OrdersController@deleteOrder']);
        // $router->put('product/{id}', ['uses' => 'ProductsController@updateproduct']);
        // // $router->get('product/{id:[0-9]+}/cat', ['uses' => 'ProductsController@ProductBelongsTo']);

        });
// });




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



