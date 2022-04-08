<?php

namespace LegendaryFiesta\Debug\Config;

use AtyKlaxas\LegendaryFiesta\Debug\Config\Table;
use PHPUnit\Framework\TestCase;

class TableTest extends TestCase
{

    /*
     * Tests
     */

    public function testConstructEmpty()
    {
        $table = new Table();
    }

    /**
     * Test if proprieties returned in getConfigFields()
     * do not miss getter or setter
     * and do not miss property
     */
    public function testGetConfigFieldsExistGetterSetter()
    {
        $reflectionClass = new \ReflectionClass(Table::class);
        $to_not_check = ['CONFIG_FIELDS'];
        $variables_editable = [];
        $results = [];

        $properties = $reflectionClass->getProperties(\ReflectionProperty::IS_PRIVATE | \ReflectionProperty::IS_PROTECTED);

        foreach ($properties as $property) {
            if (in_array($property->getName(), $to_not_check)) {
                continue;
            }

            $var_in_snake_case = $property->getName();
            $var_in_pascal_case = implode('', array_map('ucfirst', array_map('strtolower', explode('_', $var_in_snake_case))));

            $variables_editable[$var_in_snake_case] = $var_in_snake_case;

            $getter = 'get' . $var_in_pascal_case;
            $istter = 'is' . $var_in_pascal_case;
            $setter = 'set' . $var_in_pascal_case;

            $results[$var_in_pascal_case] = [
                [$getter, method_exists(Table::class, $getter)],
                [$istter, method_exists(Table::class, $istter)],
                [$setter, method_exists(Table::class, $setter)],
            ];
        }

        $diff1 = array_diff(array_values($variables_editable), Table::getConfigFields());
        $diff2 = array_diff(Table::getConfigFields(), array_values($variables_editable));

        $message =
            'Error in properties from getConfigFields() !' . PHP_EOL .
            'Missing in return: ' . implode(' ', $diff1) . PHP_EOL .
            'Missing in class: ' . implode(' ', $diff2)
        ;

        $exept = array_values($variables_editable);
        sort($exept);

        $actual = Table::getConfigFields();
        sort($actual);

        self::assertEquals($exept, $actual, $message);

        foreach ($results as $a_result) {
            self::assertTrue($a_result[0][1] || $a_result[1][1], "Method " . $a_result[0][0] . "() or " . $a_result[1][0] . "() does not exist");
            self::assertTrue($a_result[2][1], "Method " . $a_result[2][0] . "() does not exist");
        }
    }

    public function testSetConfigTypeError()
    {
        $table = new Table();
        $config = $table->getConfig();

        $table->setConfig([
            'head_horizontal_separator' => 1234,
            'with_head' => 'test',
        ]);

        self::assertEquals($config, $table->getConfig());
    }

    public function testSetConfigNotField()
    {
        $table = new Table();
        $config = $table->getConfig();

        $table->setConfig([
            'not_a_field' => 1234,
        ]);

        self::assertEquals($config, $table->getConfig());
    }

}
