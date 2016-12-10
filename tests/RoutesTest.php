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
            ] );
    }

    // Check if we returns routes when calling function routes

    /** @test */
    public function check_route_exists_after_calling_route_function() {
        $this->router->route( 'images.index' );
        $route = route( 'images.index' );

        $this->assertEquals( url( 'images' ), route( 'images.index' ) );
    }
    // Check if we can add routes to route group
}
