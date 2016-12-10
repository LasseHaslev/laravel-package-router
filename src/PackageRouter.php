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
        $routeObjects = $this->getOnlyRoutesWithNamespace( $namespace );

        foreach( $routeObjects as $routeObject ) {
            $this->buildRoute( $routeObject );
        }

        return $routeObjects;
    }

    /**
     * Get single item of route by namespace
     *
     * @return array
     */
    public function route( string $namespace )
    {
        $routeObject = array_first( $this->routes( $namespace ) );
        $this->buildRoute( $routeObject );
        return $routeObject;
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

        $this->validateRouteObject($routeObject);

        $this->routes[ $routeName ] = $routeObject;
        return $this;
    }

    protected function validateRouteObject(array $routeObject)
    {
        $validations = [
            'uri',
            'uses',
        ];
        foreach( $validations as $validation ) {
            if ( ! array_key_exists( $validation, $routeObject ) ) {
                $properties = join(' and ', array_filter(array_merge(array(join(', ', array_slice($validations, 0, -1))), array_slice($validations, -1)), 'strlen'));
                abort( 500, sprintf( 'Your route array need to include at least %s keys.', $properties ) );
            }
        }
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
    public function buildRoute( $object )
    {
        $router = app('router');
        $method = isset( $object[ 'method' ] ) ? $object[ 'method' ] : 'get';
        $router->match( $method, $object['uri'], $object );
    }


}
