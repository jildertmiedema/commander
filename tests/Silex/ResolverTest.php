<?php
namespace JildertMiedema\Commander\Silex;

use Mockery as m;
use Silex\Application;

class ResolverTest extends \PHPUnit_Framework_TestCase {

    private $app;
    private $resolver;

    protected function setUp()
    {
        parent::setUp();

        $this->app = new Application();
        $this->resolver = new Resolver($this->app);
    }


    protected function tearDown()
    {
        \Mockery::close();
        parent::tearDown();
    }


    public function testResolve()
    {
        require_once __DIR__ . '/../helpers/TestCommandHandler.php';

        $this->app['test.handler'] = new \TestCommandHandler();

        $object = $this->resolver->resolve('test.handler');

        $this->assertInstanceOf('TestCommandHandler', $object);
    }

    public function testCanResolveTrue()
    {
        require_once __DIR__ . '/../helpers/TestCommandHandler.php';

        $this->app['test.handler'] = new \TestCommandHandler();

        $result = $this->resolver->canResolve('test.handler');

        $this->assertTrue($result);
    }

    public function testCanResolveFalse()
    {
        require_once __DIR__ . '/../helpers/TestCommandHandler.php';

        $result = $this->resolver->canResolve('test.handler');

        $this->assertFalse($result);
    }
}
