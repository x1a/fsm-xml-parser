<?php

namespace Tests\X1a\Xml\FSM;

use PHPUnit\Framework\TestCase;

class ReaderTest extends TestCase
{

    public function testSample1()
    {
        $parser = new Sample1Parser();
        $reader = new Sample1Reader($parser);
        $fpath = __DIR__ . '/fixture/sample1.xml';
        $reader->read($fpath);
        $this->assertEquals('foobar', $reader->something);
        $this->assertEquals(['foo', 'bar', '', 'baz'], $reader->rows);
    }
}