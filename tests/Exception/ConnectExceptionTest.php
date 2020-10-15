<?php
namespace ImpreseeGuzzleHttp\Tests\Exception;

use ImpreseeGuzzleHttp\Exception\ConnectException;
use ImpreseeGuzzleHttp\Psr7\Request;
use PHPUnit\Framework\TestCase;

/**
 * @covers \ImpreseeGuzzleHttp\Exception\ConnectException
 */
class ConnectExceptionTest extends TestCase
{
    public function testHasNoResponse()
    {
        $req = new Request('GET', '/');
        $prev = new \Exception();
        $e = new ConnectException('foo', $req, $prev, ['foo' => 'bar']);
        self::assertSame($req, $e->getRequest());
        self::assertNull($e->getResponse());
        self::assertFalse($e->hasResponse());
        self::assertSame('foo', $e->getMessage());
        self::assertSame('bar', $e->getHandlerContext()['foo']);
        self::assertSame($prev, $e->getPrevious());
    }
}
