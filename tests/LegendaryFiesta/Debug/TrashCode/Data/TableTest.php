<?php

namespace LegendaryFiesta\Debug\Data;

use AtyKlaxas\LegendaryFiesta\Debug\TrashCode\Table2;
use PHPUnit\Framework\TestCase;

class TableTest extends TestCase
{

    public function testSetData()
    {
        $table = new Table2();

        self::assertTrue($table->setData([
            [
                'a' => 'b',
                'c' => 123,
                'd' => 1.2,
                'e' => true,
            ],
            [
                'a' => 'f',
                'c' => 321,
                'd' => 2.1,
                'e' => false,
            ],
        ]));

        self::assertFalse($table->setData([
            [
                'a' => (object) 'foo',
                'c' => [123, 456, 789],
            ],
        ]));

        self::assertFalse($table->setData([
            'a' => 'f',
            'c' => 321,
            'd' => 2.1,
            'e' => false,
        ]));
    }

}
