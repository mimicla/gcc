<?php
namespace Aura\Marshal;


/**
 * Test class for Data.
 * Generated by PHPUnit on 2011-11-26 at 14:22:58.
 */
class DataTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Data
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->data = new Data([
            'foo' => 'bar',
            'baz' => 'dib',
            'zim' => 'gir',
        ]);
    }
    
    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        parent::tearDown();
    }

    public function testOffsetExistsAndUnset()
    {
        $this->assertTrue($this->data->offsetExists('foo'));
        $this->data->offsetUnset('foo');
        $this->assertFalse($this->data->offsetExists('foo'));
    }

    public function testOffsetSetAndGet()
    {
        $this->data->offsetSet('irk', 'doom');
        $this->assertSame('doom', $this->data->offsetGet('irk'));
    }

    public function testCount()
    {
        $this->assertSame(3, $this->data->count());
    }

    public function testGetIterator()
    {
        $this->assertInstanceOf('Aura\Marshal\DataIterator', $this->data->getIterator());
    }
}
