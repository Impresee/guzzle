<?php
namespace ImpreseeGuzzleHttp\Tests\Exception;

use ImpreseeGuzzleHttp\Exception\SeekException;
use ImpreseeGuzzleHttp\Psr7;
use PHPUnit\Framework\TestCase;

class SeekExceptionTest extends TestCase
{
    public function testHasStream()
    {
        $s = Psr7\stream_for('foo');
        $e = new SeekException($s, 10);
        self::assertSame($s, $e->getStream());
        self::assertContains('10', $e->getMessage());
    }
}
