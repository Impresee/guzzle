<?php
namespace ImpreseeGuzzleHttp\Test;

use ImpreseeGuzzleHttp;
use PHPUnit\Framework\TestCase;

class FunctionsTest extends TestCase
{
    public function testExpandsTemplate()
    {
        self::assertSame(
            'foo/123',
            ImpreseeGuzzleHttp\wsee_uri_template('foo/{bar}', ['bar' => '123'])
        );
    }
    public function noBodyProvider()
    {
        return [['get'], ['head'], ['delete']];
    }

    public function testProvidesDefaultUserAgent()
    {
        $ua = ImpreseeGuzzleHttp\wsee_default_user_agent();
        self::assertRegExp('#^ImpreseeGuzzleHttp/.+ curl/.+ PHP/.+$#', $ua);
    }

    public function typeProvider()
    {
        return [
            ['foo', 'string(3) "foo"'],
            [true, 'bool(true)'],
            [false, 'bool(false)'],
            [10, 'int(10)'],
            [1.0, 'float(1)'],
            [new StrClass(), 'object(ImpreseeGuzzleHttp\Test\StrClass)'],
            [['foo'], 'array(1)']
        ];
    }
    /**
     * @dataProvider typeProvider
     */
    public function testDescribesType($input, $output)
    {
        self::assertSame($output, ImpreseeGuzzleHttp\wsee_describe_type($input));
    }

    public function testParsesHeadersFromLines()
    {
        $lines = ['Foo: bar', 'Foo: baz', 'Abc: 123', 'Def: a, b'];
        self::assertSame([
            'Foo' => ['bar', 'baz'],
            'Abc' => ['123'],
            'Def' => ['a, b'],
        ], ImpreseeGuzzleHttp\wsee_headers_from_lines($lines));
    }

    public function testParsesHeadersFromLinesWithMultipleLines()
    {
        $lines = ['Foo: bar', 'Foo: baz', 'Foo: 123'];
        self::assertSame([
            'Foo' => ['bar', 'baz', '123'],
        ], ImpreseeGuzzleHttp\wsee_headers_from_lines($lines));
    }

    public function testReturnsDebugResource()
    {
        self::assertInternalType('resource', ImpreseeGuzzleHttp\wsee_debug_resource());
    }

    public function testProvidesDefaultCaBundler()
    {
        self::assertFileExists(ImpreseeGuzzleHttp\wsee_default_ca_bundle());
    }

    public function noProxyProvider()
    {
        return [
            ['mit.edu', ['.mit.edu'], false],
            ['foo.mit.edu', ['.mit.edu'], true],
            ['mit.edu', ['mit.edu'], true],
            ['mit.edu', ['baz', 'mit.edu'], true],
            ['mit.edu', ['', '', 'mit.edu'], true],
            ['mit.edu', ['baz', '*'], true],
        ];
    }

    /**
     * @dataProvider noproxyProvider
     */
    public function testChecksNoProxyList($host, $list, $result)
    {
        self::assertSame(
            $result,
            \ImpreseeGuzzleHttp\wsee_is_host_in_noproxy($host, $list)
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testEnsuresNoProxyCheckHostIsSet()
    {
        \ImpreseeGuzzleHttp\wsee_is_host_in_noproxy('', []);
    }

    public function testEncodesJson()
    {
        self::assertSame('true', \ImpreseeGuzzleHttp\wsee_json_encode(true));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testEncodesJsonAndThrowsOnError()
    {
        \ImpreseeGuzzleHttp\wsee_json_encode("\x99");
    }

    public function testDecodesJson()
    {
        self::assertTrue(\ImpreseeGuzzleHttp\wsee_json_decode('true'));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testDecodesJsonAndThrowsOnError()
    {
        \ImpreseeGuzzleHttp\wsee_json_decode('{{]]');
    }
}

final class StrClass
{
    public function __toString()
    {
        return 'foo';
    }
}
