<?php

use LasseHaslev\LaravelPackageRouter\PackageRouter;
use Symfony\Component\HttpKernel\Exception\HttpException;

class TestRouter extends PackageRouter {}

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
            'as'=>'images.index',
            'uri'=>'images',
            'method'=>'get',
            'uses'=>'Controller@index',
        ] );

        $this->assertEquals( 1, count( $this->router->count() ) );
    }

    /** @test */
    public function add_method_returns_instance_of_object_for_binding() {
        $instance = $this->router->add( 'images.index', [
            'uri'=>'images',
            'method'=>'get',
            'uses'=>'Controller@index',
        ] );

        $this->assertInstanceOf( TestRouter::class, $instance );
    }

    /** @test */
    public function is_checking_that_we_have_all_things_we_need_when_adding_new_route() {
        $this->expectException( HttpException::class );
        $this->router->add( 'images.index', [
            'as'=>'images.index',
            'method'=>'get',
            'uses'=>'Controller@index',
        ] );
    }

    /** @test */
    public function is_getting_all_routes_when_calling_empty_routes() {
        $this->router->add( 'images.index', [
            'uri'=>'images',
            'method'=>'get',
            'uses'=>'Controller@index',
        ] );
        $this->router->add( 'blob.index', [
            'uri'=>'images',
            'method'=>'get',
            'uses'=>'Controller@index',
        ] );

        $this->assertEquals( 2, count( $this->router->routes() ) );

    }

    /** @test */
    public function can_filter_namespaces() {
        $this->router->add( 'images.index', [
            'uri'=>'images',
            'method'=>'get',
            'uses'=>'Controller@index',
        ] );
        $this->router->add( 'blob.index', [
            'uri'=>'images',
            'method'=>'get',
            'uses'=>'Controller@index',
        ] );


        $this->assertEquals( 1, count( $this->router->routes( 'images' ) ) );
    }

    /** @test */
    public function has_method_for_getting_counts() {
        $this->router->add( 'images.index', [
            'uri'=>'images',
            'method'=>'get',
            'uses'=>'Controller@index',
        ] );
        $this->router->add( 'blob.index', [
            'uri'=>'images',
            'method'=>'get',
            'uses'=>'Controller@index',
        ] );

        $this->assertEquals( 2, $this->router->count() );
    }

    /** @test */
    public function has_method_for_getting_filtered_counts() {
        $this->router->add( 'images.index', [
            'uri'=>'images',
            'method'=>'get',
            'uses'=>'Controller@index',
        ] );
        $this->router->add( 'images.show', [
            'uri'=>'images',
            'method'=>'get',
            'uses'=>'Controller@index',
        ] );
        $this->router->add( 'blob.index', [
            'uri'=>'images',
            'method'=>'get',
            'uses'=>'Controller@index',
        ] );

        $this->assertEquals( 2, $this->router->count( 'images' ) );
    }

    // Can get single route by name
    /** @test */
    public function can_get_single_route_name() {
        $this->router->add( 'images.index', [
            'uri'=>'images',
            'method'=>'get',
            'uses'=>'Controller@index',
        ] );
        $this->router->add( 'images.show', [
            'uri'=>'images',
            'method'=>'get',
            'uses'=>'Controller@index',
        ] );
        $this->router->add( 'blob.index', [
            'uri'=>'images',
            'method'=>'get',
            'uses'=>'Controller@index',
        ] );

        $this->assertEquals( 1, $this->router->count( 'images.show' ) );
    }

    // Route function returns object instead of array
    /** @test */
    public function route_function_returns_object_instead_of_array() {
        $this->router->add( 'images.index', [
            'uri'=>'images',
            'method'=>'get',
            'uses'=>'Controller@index',
        ] );
        $this->router->add( 'images.show', [
            'uri'=>'images',
            'method'=>'get',
            'uses'=>'TestController@index',
        ] );

        $this->assertEquals([
            'uri'=>'images',
            'method'=>'get',
            'uses'=>'TestController@index',
        ], $this->router->route( 'images.show' ));
    }
}
