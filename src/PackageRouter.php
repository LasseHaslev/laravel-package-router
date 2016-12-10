<?php

namespace LasseHaslev\LaravelPackageRouter;

use Illuminate\Support\Facades\Route;
use LasseHaslev\LaravelImage\Http\Controllers\ImagesController;
use LasseHaslev\UniversalObjects\Object as UniversalObject;

/**
 * Class Routing
 * @author Lasse S. Haslev
 */
class PackageRouter extends UniversalObject
{
    protected $routes = [];

    /**
     * Get all routes of this object
     * If you set a namespace we will filter it
     *
     * @return array
     */
    public function routes( string $namespace = null )
    {
        return $this->getOnlyRoutesWithNamespace( $namespace );
    }

    /**
     * Get single item of route by namespace
     *
     * @return array
     */
    public function route( string $namespace )
    {
        return array_first( $this->routes( $namespace ) );
    }


    /**
     * Get the number of routes registered
     *
     * @return void
     */
    public function count( $namespace = null )
    {
        return count( $this->routes( $namespace ) );
    }


    /**
     * Add new object
     *
     * @return $this
     */
    public function add(string $routeName, array $routeObject)
    {
        $this->routes[ $routeName ] = $routeObject;
        return $this;
    }

    /**
     * Get only routes with namespace
     *
     * @return array
     */
    protected function getOnlyRoutesWithNamespace($namespace)
    {
        if ( ! $namespace ) {
            return $this->routes;
        }

        $routeKeys = $this->routeKeysInNamespace($namespace);

        $routes = $this->getRoutesByKeys($routeKeys);
        return $routes;
    }

    /**
     * Get all routes by array with keys
     *
     * @return array
     */
    protected function getRoutesByKeys($routeKeys)
    {
        $routes = [];
        foreach ( $this->routes as $key=>$value ) {
            if ( in_array( $key, $routeKeys ) ) {
                $routes[ $key ] = $value;
            }
        }
        return $routes;
    }

    /*
     * Find all array keys in routes that starts with namespace
     *
     * @return array
     */
    protected function routeKeysInNamespace($namespace)
    {
        $routeKeys = array_where( array_keys( $this->routes ), function ($value, $key) use ( $namespace )
        {
            return substr( $value, 0, strlen( $namespace ) ) === $namespace;
        } );
        return $routeKeys;
    }

    /**
     * Build a route from route object
     *
     * @return void
     */
    public function buildRoute( $name, $object )
    {
        $router = app('router');
        $router->match( $object[ 'method' ], $object )->name( $name );
    }


}
