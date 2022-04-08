<?php

namespace AtyKlaxas\LegendaryFiesta\Debug\Render;

use PHPUnit\Framework\TestCase;

class TableTest extends TestCase
{

    const DEFAULT_TITLE = 'Point \\ Metric';
    const DEFAULT_TABLE = [
        'point 1' => ['head 1' => 1, 'head 2' => 2],
        'point 2' => ['head 2' => 3, 'head 3' => 4],
    ];
    const DEFAULT_TABLE_2 = [
        'point 1' => ['head 1' => 1, 'head 2' => 2                ],
        'point 2' => [               'head 2' => 3, 'head 3' => 4 ],
        'point 3' => ['head 1' => 5, 'head 2' => 4, 'head 3' => 7 ],
        'point 4' => ['head 1' => 5, 'head 2' => 9, 'head 3' => 12],
    ];
    const DEFAULT_CONFIG = [
        'head_horizontal_separator' => '=',
        'body_horizontal_separator' => '=',
        'tail_horizontal_separator' => '=',
        'head_vertical_separator' => '|',
        'body_vertical_separator' => '|',
        'tail_vertical_separator' => '|',
        'with_space_betwen_val_n_separators' => true,
        'with_body_center_horizontal_separators' => false,
        'with_head' => true,
        'with_r_l_border_separator' => false,
        'ftcs' => [],
        'texts' => [
            'title' => 'Point \ Metric',
            'tail_cnt' => 'Effectif',
            'tail_sum' => 'Total',
            'tail_min' => 'Minimum',
            'tail_max' => 'Maximum',
            'tail_avg' => 'Moyenne',
            'tail_var' => 'Variance',
            'tail_med' => 'Médiane'
        ],
    ];

    private function generateTable(array $config = [], bool $use_default_2 = false): Table1
    {
        $Table = new Table1($use_default_2 ? self::DEFAULT_TABLE_2 : self::DEFAULT_TABLE);

        // export default config
        if (false) {
            $conf = $Table->getConfig();

            var_dump($conf);

            foreach ($conf as $conf => $val) {
                if (is_array($val)) {
                    if (empty($val)) {
                        $val = '[]';
                    } else {
                        $newval = [];

                        foreach ($val as $key => $item) {
                            $newval[] = "'$key' => '$item'";
                        }

                        $val = '[' . PHP_EOL . implode(',' . PHP_EOL, $newval) . PHP_EOL . ']';
                    }
                } elseif (is_bool($val)) {
                    $val = ($val ? 'true' : 'false');
                } else {
                    $val = "'$val'";
                }

                echo "'$conf' => $val," . PHP_EOL;
            }
        }

        $res = $Table->setConfig(self::DEFAULT_CONFIG);

        if ($res === false) {
            throw new \Exception('Error ! generateTable() => setConfig(DEFAULT) Fail !');
        }

        $res = $Table->setConfig($config);

        if ($res === false) {
            throw new \Exception('Error ! generateTable() => setConfig($config) Fail !');
        }

        return $Table;
    }

    /*
     * Tests
     */

    public function testConstructEmpty()
    {
        $table = new Table1();

        self::assertEquals('', $table->export());
    }

    public function testDefault()
    {
        $table = $this->generateTable();

        $text = self::DEFAULT_TITLE;

        $exepted =
            '=========================================' . PHP_EOL .
            $text .       ' | head 1 | head 2 | head 3' . PHP_EOL .
            '=========================================' . PHP_EOL .
            'point 1        | 1      | 2      |       ' . PHP_EOL .
            'point 2        |        | 3      | 4     ' . PHP_EOL .
            '=========================================' . PHP_EOL
        ;

        self::assertEquals($exepted, $table->export());
    }

    public function testChangeTitle()
    {
        $table = $this->generateTable();

        $text = self::DEFAULT_TITLE;

        $exepted =
            '=========================================' . PHP_EOL .
            $text .       ' | head 1 | head 2 | head 3' . PHP_EOL .
            '=========================================' . PHP_EOL .
            'point 1        | 1      | 2      |       ' . PHP_EOL .
            'point 2        |        | 3      | 4     ' . PHP_EOL .
            '=========================================' . PHP_EOL
        ;

        self::assertEquals($exepted, $table->export());

        $table->setTextTitle('test');

        $exepted =
            '==================================' . PHP_EOL .
            'test    | head 1 | head 2 | head 3' . PHP_EOL .
            '==================================' . PHP_EOL .
            'point 1 | 1      | 2      |       ' . PHP_EOL .
            'point 2 |        | 3      | 4     ' . PHP_EOL .
            '==================================' . PHP_EOL
        ;

        self::assertEquals($exepted, $table->export());
    }

    public function testNoHead()
    {
        $table = $this->generateTable([
            'with_head' => false,
        ]);

        $exepted =
            '=========================================' . PHP_EOL .
            'point 1        | 1      | 2      |       ' . PHP_EOL .
            'point 2        |        | 3      | 4     ' . PHP_EOL .
            '=========================================' . PHP_EOL
        ;

        self::assertEquals($exepted, $table->export());
    }

    public function testNoHeadCustomBody()
    {
        $table = $this->generateTable([
            'with_head' => false,
            'body_horizontal_separator' => '-',
        ]);

        $exepted =
            '-----------------------------------------' . PHP_EOL .
            'point 1        | 1      | 2      |       ' . PHP_EOL .
            '-----------------------------------------' . PHP_EOL .
            'point 2        |        | 3      | 4     ' . PHP_EOL .
            '-----------------------------------------' . PHP_EOL
        ;

        self::assertEquals($exepted, $table->export());
    }

    public function testNoHeadCustomBodyNoCenter()
    {
        $table = $this->generateTable([
            'with_head' => false,
            'body_horizontal_separator' => '-',
            'with_body_center_horizontal_separator' => false,
        ]);

        $exepted =
            '-----------------------------------------' . PHP_EOL .
            'point 1        | 1      | 2      |       ' . PHP_EOL .
            'point 2        |        | 3      | 4     ' . PHP_EOL .
            '-----------------------------------------' . PHP_EOL
        ;

        self::assertEquals($exepted, $table->export());
    }

    public function testWithTail()
    {
        $table = $this->generateTable([
            'with_head' => false,
        ]);

        $exepted =
            '=========================================' . PHP_EOL .
            'point 1        | 1      | 2      |       ' . PHP_EOL .
            'point 2        |        | 3      | 4     ' . PHP_EOL .
            '=========================================' . PHP_EOL
        ;

        self::assertEquals($exepted, $table->export());
    }

    /*
     * Test the horizontal separators
     */

    public function testHeadHorizSep()
    {
        $table = $this->generateTable([
            'head_horizontal_separator' => '-',
        ]);

        $text = self::DEFAULT_TITLE;

        $exepted =
            '-----------------------------------------' . PHP_EOL .
            $text .       ' | head 1 | head 2 | head 3' . PHP_EOL .
            '-----------------------------------------' . PHP_EOL .
            'point 1        | 1      | 2      |       ' . PHP_EOL .
            'point 2        |        | 3      | 4     ' . PHP_EOL .
            '=========================================' . PHP_EOL
        ;

        self::assertEquals($exepted, $table->export());
    }

    public function testBodyHorizSep()
    {
        $table = $this->generateTable([
            'body_horizontal_separator' => '-',
        ]);

        $text = self::DEFAULT_TITLE;

        $exepted =
            '=========================================' . PHP_EOL .
            $text .       ' | head 1 | head 2 | head 3' . PHP_EOL .
            '=========================================' . PHP_EOL .
            'point 1        | 1      | 2      |       ' . PHP_EOL .
            '-----------------------------------------' . PHP_EOL .
            'point 2        |        | 3      | 4     ' . PHP_EOL .
            '-----------------------------------------' . PHP_EOL
        ;

        self::assertEquals($exepted, $table->export());
    }

    public function testBodyHorizSepNoCenter()
    {
        $table = $this->generateTable([
            'body_horizontal_separator' => '-',
            'with_body_center_horizontal_separators' => false,
        ]);

        $text = self::DEFAULT_TITLE;

        $exepted =
            '=========================================' . PHP_EOL .
            $text .       ' | head 1 | head 2 | head 3' . PHP_EOL .
            '=========================================' . PHP_EOL .
            'point 1        | 1      | 2      |       ' . PHP_EOL .
            'point 2        |        | 3      | 4     ' . PHP_EOL .
            '-----------------------------------------' . PHP_EOL
        ;

        self::assertEquals($exepted, $table->export());
    }

    public function testTailHorizSep()
    {
        $table = $this->generateTable([
            'tail_horizontal_separator' => '-',
        ]);

        $text = self::DEFAULT_TITLE;

        $exepted =
            '=========================================' . PHP_EOL .
            $text .       ' | head 1 | head 2 | head 3' . PHP_EOL .
            '=========================================' . PHP_EOL .
            'point 1        | 1      | 2      |       ' . PHP_EOL .
            'point 2        |        | 3      | 4     ' . PHP_EOL .
            '=========================================' . PHP_EOL
        ;

        self::assertEquals($exepted, $table->export());
    }

    public function testTailHorizSepWithTail()
    {
        $table = $this->generateTable([
            'tail_horizontal_separator' => '-',
            'with_total_line_at_the_end' => true,
        ]);

        $text = self::DEFAULT_TITLE;

        $exepted =
            '=========================================' . PHP_EOL .
            $text .       ' | head 1 | head 2 | head 3' . PHP_EOL .
            '=========================================' . PHP_EOL .
            'point 1        | 1      | 2      |       ' . PHP_EOL .
            'point 2        |        | 3      | 4     ' . PHP_EOL .
            '-----------------------------------------' . PHP_EOL .
            'Total          | 1      | 5      | 4     ' . PHP_EOL .
            '-----------------------------------------' . PHP_EOL
        ;

        self::assertEquals($exepted, $table->export());
    }

    /*
     * Test the vertical separators
     */

    public function testHeadVertSep()
    {
        $table = $this->generateTable([
            'head_vertical_separator' => 'h',
        ]);

        $text = self::DEFAULT_TITLE;

        $exepted =
            '=========================================' . PHP_EOL .
            $text .       ' h head 1 h head 2 h head 3' . PHP_EOL .
            '=========================================' . PHP_EOL .
            'point 1        | 1      | 2      |       ' . PHP_EOL .
            'point 2        |        | 3      | 4     ' . PHP_EOL .
            '=========================================' . PHP_EOL
        ;

        self::assertEquals($exepted, $table->export());
    }

    public function testBodyVertSep()
    {
        $table = $this->generateTable([
            'body_vertical_separator' => 'b',
        ]);

        $text = self::DEFAULT_TITLE;

        $exepted =
            '=========================================' . PHP_EOL .
            $text .       ' | head 1 | head 2 | head 3' . PHP_EOL .
            '=========================================' . PHP_EOL .
            'point 1        b 1      b 2      b       ' . PHP_EOL .
            'point 2        b        b 3      b 4     ' . PHP_EOL .
            '=========================================' . PHP_EOL
        ;

        self::assertEquals($exepted, $table->export());
    }

    public function testTailVertSepWithTail()
    {
        $table = $this->generateTable([
            'tail_vertical_separator' => 't',
            'with_total_line_at_the_end' => true,
        ]);

        $text = self::DEFAULT_TITLE;

        $exepted =
            '=========================================' . PHP_EOL .
            $text .       ' | head 1 | head 2 | head 3' . PHP_EOL .
            '=========================================' . PHP_EOL .
            'point 1        | 1      | 2      |       ' . PHP_EOL .
            'point 2        |        | 3      | 4     ' . PHP_EOL .
            '=========================================' . PHP_EOL .
            'Total          t 1      t 5      t 4     ' . PHP_EOL .
            '=========================================' . PHP_EOL
        ;

        self::assertEquals($exepted, $table->export());
    }

    /*
     * Test functions
     */

    public function testTailFtcSum()
    {
        $table = $this->generateTable([
            'tail_ftcs' => ['sum'],
        ]);

        $text = self::DEFAULT_TITLE;

        $exepted =
            '=========================================' . PHP_EOL .
            $text .       ' | head 1 | head 2 | head 3' . PHP_EOL .
            '=========================================' . PHP_EOL .
            'point 1        | 1      | 2      |       ' . PHP_EOL .
            'point 2        |        | 3      | 4     ' . PHP_EOL .
            '=========================================' . PHP_EOL .
            'Total          | 1      | 5      | 4     ' . PHP_EOL .
            '=========================================' . PHP_EOL
        ;

        self::assertEquals($exepted, $table->export());
    }

    public function testTailFtcSumCustomTitle()
    {
        $table = $this->generateTable([
            'tail_ftcs' => ['sum'],
            'tail_ftcs_names' => [
                'sum' => 'Totals afa',
            ],
        ]);

        $text = self::DEFAULT_TITLE;

        $exepted =
            '=========================================' . PHP_EOL .
            $text .       ' | head 1 | head 2 | head 3' . PHP_EOL .
            '=========================================' . PHP_EOL .
            'point 1        | 1      | 2      |       ' . PHP_EOL .
            'point 2        |        | 3      | 4     ' . PHP_EOL .
            '=========================================' . PHP_EOL .
            'Totals afa     | 1      | 5      | 4     ' . PHP_EOL .
            '=========================================' . PHP_EOL
        ;

        self::assertEquals($exepted, $table->export());
    }

}