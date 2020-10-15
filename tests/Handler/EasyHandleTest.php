<?php
namespace ImpreseeGuzzleHttp\Test\Handler;

use ImpreseeGuzzleHttp\Handler\EasyHandle;
use ImpreseeGuzzleHttp\Psr7;
use PHPUnit\Framework\TestCase;

/**
 * @covers \ImpreseeGuzzleHttp\Handler\EasyHandle
 */
class EasyHandleTest extends TestCase
{
    /**
     * @expectedException \BadMethodCallException
     * @expectedExceptionMessage The EasyHandle has been released
     */
    public function testEnsuresHandleExists()
    {
        $easy = new EasyHandle;
        unset($easy->handle);
        $easy->handle;
    }
}
