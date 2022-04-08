<?php

namespace AtyKlaxas\LegendaryFiesta\Debug\Render;

use AtyKlaxas\LegendaryFiesta\Debug\Config\Cell;
use PHPUnit\Framework\TestCase;

class CellTest extends TestCase
{

    public function testConstructEmpty()
    {
        $cell = new Cell();

        self::assertEquals(0, $cell->getLength());
        self::assertEquals('', $cell->export());
    }

    public function testStrValue()
    {
        $cell = new Cell('foo');

        self::assertEquals(3, $cell->getLength());
        self::assertEquals('foo', $cell->export());
    }

    public function testStrValueNbFormatPrm()
    {
        $cell = new Cell('bar');
        $cell
            ->setNumberFormatThousandsSeparator('_')
            ->setNumberFormatDecimalSeparator('-')
            ->setNumberFormatDecimals(20)
        ;

        self::assertEquals(3, $cell->getLength());
        self::assertEquals('bar', $cell->export());
    }

    public function testIntValueNbFormatPrm()
    {
        $cell = new Cell(1000);
        $cell
            ->setNumberFormatThousandsSeparator('_')
            ->setNumberFormatDecimalSeparator('-')
            ->setNumberFormatDecimals(20)
        ;

        self::assertEquals('1_000-00000000000000000000', $cell->export());
        self::assertEquals(26, $cell->getLength());
    }

    public function testStrValueStrPadLen10()
    {
        $cell = new Cell('baz');
        $cell
            ->setStrPadLength(10)
        ;

        self::assertEquals('baz       ', $cell->export());
        self::assertEquals(3, $cell->getLength());
    }

    public function testStrValueStrPadLeft()
    {
        $cell = new Cell('fiz');
        $cell
            ->setStrPadLength(10)
            ->setStrPadType(STR_PAD_LEFT)
        ;

        self::assertEquals('       fiz', $cell->export());
        self::assertEquals(3, $cell->getLength());
    }

    public function testStrValueStrPadChar()
    {
        $cell = new Cell('bazz');
        $cell
            ->setStrPadLength(10)
            ->setStrPadString('_')
        ;

        self::assertEquals('bazz______', $cell->export());
        self::assertEquals(4, $cell->getLength());
    }

    public function testStrValueStrPadOneChar()
    {
        $cell = new Cell('buzz');
        $cell
            ->setStrPadLength(10)
            ->setStrPadString('abc') // no effect
        ;

        self::assertEquals('buzz      ', $cell->export());
        self::assertEquals(4, $cell->getLength());
    }

    public function testSetConfig()
    {
        $cell = new Cell(1024);
        $cell->setConfig([
            'str_pad_length' => 20,
            'str_pad_string' => '_',
            'str_pad_type' => STR_PAD_BOTH,
            'number_format_decimals' => 5,
            'number_format_decimal_separator' => ',',
            'number_format_thousands_separator' => ' ',
        ]);

        self::assertEquals('____1 024,00000_____', $cell->export());
        self::assertEquals(11, $cell->getLength());
    }

    public function testSetConfigError()
    {
        $cell = new Cell(1024);
        $cell->setConfig([
            'str_pad_length' => 20,
            'str_pad_string' => '_',
            'str_pad_type' => STR_PAD_BOTH,
            'number_format_decimals' => 5,
            'number_format_decimal_separator' => ',',
            'number_format_thousands_separator' => ' ',
            'not_a_field' => true,
        ]);

        self::assertEquals('1024', $cell->export());
        self::assertEquals(4, $cell->getLength());
    }

}
