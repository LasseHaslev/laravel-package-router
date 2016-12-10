<?php

/**
 * Class RoutesTest
 * @author Lasse S. Haslev
 */
class RoutesTest extends TestCase
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
        $this->router = TestRouter::get()
            ->add( 'images.index', [
                'as'=>'images.index',
                'uri'=>'images',
                'uses'=>'Controller@index',
                'middleware'=>null,
            ] )
            ->add( 'images.show', [
                'as'=>'images.show',
                'uri'=>'images/{image}',
                'uses'=>'Controller@show',
                'middleware'=>null,
            ] )
            ->add( 'users.index', [
                'as'=>'users.index',
                'uri'=>'users',
                'uses'=>'Controller@index',
                'middleware'=>null,
            ] );
    }

    /** @test */
    public function check_route_exists_after_calling_route_function() {
        $this->router->route( 'images.index' );

        $this->assertTrue( Route::has( 'images.index' ) );
        $this->assertFalse( Route::has( 'images.show' ) );
        $this->assertEquals( url( 'images' ), route( 'images.index' ) );
    }

    // Check if we returns routes when calling function routes
    /** @test */
    public function check_if_routes_exists_after_calling_routes_function() {
        $this->router->routes();

        $this->assertTrue( Route::has( 'images.index' ) );
        $this->assertTrue( Route::has( 'images.show' ) );
        $this->assertTrue( Route::has( 'users.index' ) );
        $this->assertEquals( url( 'images' ), route( 'images.index' ) );
        $this->assertEquals( url( 'users' ), route( 'users.index' ) );
    }

    // Check if we can add routes to route group
    /** @test */
    public function can_add_routes_to_route_group() {
        $router = app( 'router' );
        $router->group( [ 'prefix'=>'backend' ], function ($router)
        {
            TestRouter::get()->routes();
        } );
        $this->assertTrue( Route::has( 'images.index' ) );
        $this->assertTrue( Route::has( 'images.show' ) );
        $this->assertTrue( Route::has( 'users.index' ) );

        $this->assertEquals( url( 'backend/images' ), route( 'images.index' ) );
        $this->assertEquals( url( 'backend/users' ), route( 'users.index' ) );
    }
}
