<?php

namespace LegendaryFiesta\Debug\Helper;

use AtyKlaxas\LegendaryFiesta\Debug\Helper\NamingHelper;
use PHPUnit\Framework\TestCase;

class NamingHelperTest extends TestCase
{

    public function testConvertAllInOneManual()
    {
        $cases = [
            'pascal' => 'MotDeTest',
            'camel' => 'motDeTest',
            'shout' => 'MOT_DE_TEST',
            'snake' => 'mot_de_test',
        ];

        foreach ($cases as $caseFrom => $valueFrom) {
            foreach ($cases as $caseTo => $valueTo) {
                if ($caseFrom === $caseTo) {
                    continue;
                }

                $const_from = strtoupper($caseFrom) . '_CASE';
                $const_to = strtoupper($caseTo) . '_CASE';

                $valueToActual = NamingHelper::convert_manual(
                    constant(NamingHelper::class . '::' . $const_from),
                    constant(NamingHelper::class . '::' . $const_to),
                    $valueFrom
                );

                self::assertEquals($valueTo, $valueToActual, 'error while converting ' . $caseFrom . ' to ' . $caseTo . ' manually');
            }
        }
    }

    public function testConvertAllInOneAuto()
    {
        $cases = [
            'pascal' => 'MotDeTest',
            'camel' => 'motDeTest',
            'shout' => 'MOT_DE_TEST',
            'snake' => 'mot_de_test',
        ];

        foreach ($cases as $caseFrom => $valueFrom) {
            foreach ($cases as $caseTo => $valueTo) {
                if ($caseFrom === $caseTo) {
                    continue;
                }

                $valueToActual = NamingHelper::convert_auto(
                    constant(NamingHelper::class . '::' . strtoupper($caseTo) . '_CASE'),
                    $valueFrom
                );

                self::assertEquals($valueTo, $valueToActual, 'error while converting ' . $caseFrom . ' to ' . $caseTo . ' automatically');
            }
        }
    }

}
