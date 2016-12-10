# lassehaslev/laravel-package-router
> Your package routes made REALLY simple!

## Install
Run ```composer require lassehaslev/laravel-package-router```

## Usage
This is a [global/universal](https://github.com/LasseHaslev/UniversalObjects) object to handle your routes.

Even tough this package is made to handle routes in packages, it is no trouble using it wherever you want.

#### Register you routes
```php
// It is recommended that you extend the class
class MyRouter extends LasseHaslev\LaravelPackageRouter\PackageRouter {}

// Create new router
$router = MyRouter::create();

// Add route to router
$router->add( 'users.index', [
    'uri'=>'users',
    'as'=>'users.index',
    'uses'=>'Controller@index',
] )
// You can also chain add
->app( 'users.update', [
    'uri'=>'users/{user}',
    'method'=>'put',
    'uses'=>'Controller@index',
] );

->add( 'images.index', [
    'uri'=>'images',
    'uses'=>'Controller@index',
] )
->add( 'images.store', [
    'uri'=>'images',
    'method'=>'post',
    'uses'=>'Controller@store',
] )
```

#### Register routes
Note that you can get the reference of the router only by calling ```MyRouter::get()``` even though you created it another place. 
This is because it extends [LasseHaslev\UniversalObjects\Object](https://github.com/LasseHaslev/UniversalObjects).
```php
// Usually in your routes/web.php
$myRouter = MyRouter::get();

$myRouter->route( 'images.index' ); // "/images"
Route::group([ 'prefix'=>'backend', 'middleware'=>'auth' ], function( $router ) use ( $myRouter ) {
    $myRouter->routes( 'users' ); // "/backend/users" and "/backend/users/{user}"
    $myRouter->route( 'images.store' ); // "/backend/images"
});
```

#### Api
```php
// Get the router
$router = MyRouter::create(); // MyRouter::get();

// Add route
$router->add( $reference, [
    'uri'=>'users',
    'method'=>'get',
    'as'=>'users.index',
    'uses'=>'Controller@index',
    'middleware'=>null,
] );

// Get all routes
$router->routes(); // Set routes for users.index, images.index and images.show

// Get all routes under images namespace
$router->routes( 'images' ); // Set routes for images.index and images.show

// Get single route
$router->route( 'images.index' );
```

## Development
``` bash
# Install dependencies
composer install

# Install dependencies for automatic tests
yarn

# Run one time
npm run test

# Automaticly run test on changes
npm run dev
```
