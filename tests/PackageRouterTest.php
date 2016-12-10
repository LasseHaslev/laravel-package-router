<?php

use LasseHaslev\LaravelPackageRouter\PackageRouter;

class TestRouter extends PackageRouter {

}

/**
 * Class PackageRouterTest
 * @author Lasse S. Haslev
 */
class PackageRouterTest extends TestCase
{
    /**
     * @var mixed
     */
    protected $router;

    /**
     * Set up for all tests
     *
     * @return void
     */
    public function setUp()
    {
        parent::setUp();
        TestRouter::destroyAll();
        $this->router = TestRouter::get();
    }

    // Is global object
    /** @test */
    public function is_global_object() {
        $this->assertInstanceOf( LasseHaslev\UniversalObjects\Object::class, TestRouter::get() );
    }
    // Can add routes
    /** @test */
    public function add_routes_to_router() {
        $this->router->add( 'images.index', [
            'uri'=>'images',
            'method'=>'get',
            'function'=>'index',
            'uses'=>'SomethingSomething',
        ] );

        $this->assertEquals( 1, count( $this->router->count() ) );
    }

    /** @test */
    public function add_method_returns_instance_of_object_for_binding() {
        $instance = $this->router->add( 'images.index', [
            'uri'=>'images',
            'method'=>'get',
            'function'=>'index',
            'uses'=>'SomethingSomething',
        ] );

        $this->assertInstanceOf( TestRouter::class, $instance );
    }

    /** @test */
    public function can_filter_namespaces() {
        $this->router->add( 'images.index', [
            'uri'=>'images',
            'method'=>'get',
            'function'=>'index',
            'uses'=>'SomethingSomething',
        ] );
        $this->router->add( 'blob.index', [
            'uri'=>'images',
            'method'=>'get',
            'function'=>'index',
            'uses'=>'SomethingSomething',
        ] );


        $this->assertEquals( 1, count( $this->router->routes( 'images' ) ) );
    }

    /** @test */
    public function has_method_for_getting_counts() {
        $this->router->add( 'images.index', [
            'uri'=>'images',
            'method'=>'get',
            'function'=>'index',
            'uses'=>'SomethingSomething',
        ] );
        $this->router->add( 'blob.index', [
            'uri'=>'images',
            'method'=>'get',
            'function'=>'index',
            'uses'=>'SomethingSomething',
        ] );

        $this->assertEquals( 1, $this->router->count( 'images' ) );
    }

    // Can group routes

    // Can get single route by name
    // Can get all route in namespace
    // Is getting all routes when calling empty routes
    // Is getting group when calling routes with group name
}
