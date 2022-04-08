<?php

namespace AtyKlaxas\LegendaryFiesta\Debug\TrashCode;

use function pointerAssignMax;
use function see;

class Table1 {

    /*
     * Cells array
     */

    protected $head_array = [];
    protected $body_array = [];
    protected $tail_array = [];

    /*
     * Display Vars
     */

    protected $columns_width = [];
    protected $texts = [];
    protected $tail_functions = [];

    /*
     * Methods
     */

    /**
     * Constructor
     *
     * @param mixed $data 2 dimension array [point => metric => value]
     */
    public function __construct(?array $points = null)
    {
        if (!empty($points)) {
            $this->buildCellArray($points);
        }
    }

    /**
     * @param array|null $points Data, here to be clear 'points' mean 'lines', 'points' for point where data added
     * @return bool
     */
    protected function buildCellArray(?array $points = null): bool
    {
        $this->head_array = [];
        $this->body_array = [];
        $this->tail_array = [];

        if (empty($points)) {
            return false;
        }

        $this->head_array[0] = new Cell($this->getText('title'));

        foreach (['cnt', 'sum', 'min', 'max', 'avg', 'var', 'med'] as $ftc) {
            $this->tail_array[$ftc][0] = new Cell($this->getText('tail_' . $ftc));
        }

        $keys_points = array_keys($points);
        $count_point = count($keys_points);

        for ($i=0; $i<$count_point; $i++) {
            $point = $keys_points[$i];
            $heads = $points[$point];

            $keys_heads = array_keys($heads);
            $count_heads = count($keys_heads);

            for ($j=0; $j<$count_heads; $j++) {
                $head = $keys_heads[$j];
                $this->head_array[$head] = new Cell($head);
            }
        }

        $heads_arr = array_keys($this->head_array);

        for ($i=0; $i<$count_point; $i++) {
            $point = $keys_points[$i];
            $heads = $points[$point];

            foreach ($heads_arr as $head) {
                if (!isset($this->body_array[$i])) {
                    $this->body_array[$i] = [];
                }

                $this->body_array[$i][$head] = new Cell($head === 0 ? $point : ($heads[$head] ?? ''));
            }
        }

        foreach ($heads_arr as $head) {
            if ($head === 0) {
                continue;
            }

            $columns = array_column($this->body_array, $head);

            $values = array_map(static function($cell) {
                /** @var Cell $cell */
                return $cell->getValue();
            }, $columns);

            $clean = array_values(array_filter($values));
            sort($clean);


            $minimum = min($clean);
            $maximum = max($clean);
            $effectif = count($clean);

            $mediane_index = (int) floor($effectif / 2);
            $mediane = $clean[$mediane_index];
            $somme = 0;
            $somme_carre = 0;

            if (($effectif % 2) === 0) {
                $mediane = ($mediane + $clean[$mediane_index - 1]) / 2;
            }

            foreach ($clean as $item) {
                $somme += $item;
                $somme_carre += $item * $item;
            }

            $moyenne = $somme / $effectif;
            $variance = ($somme_carre / $effectif) - ($moyenne * $moyenne);

            $this->tail_array['cnt'][$head] = new Cell($effectif);
            $this->tail_array['sum'][$head] = new Cell($somme);
            $this->tail_array['min'][$head] = new Cell($minimum);
            $this->tail_array['max'][$head] = new Cell($maximum);
            $this->tail_array['avg'][$head] = new Cell($moyenne);
            $this->tail_array['var'][$head] = new Cell($variance);
            $this->tail_array['med'][$head] = new Cell($mediane);
        }

        $this->buildWidth();

        return true;
    }

    protected function buildWidth()
    {
        $this->columns_width = [];

        foreach ($this->head_array as $head => $cell) {
            /** @var Cell $cell */
            pointerAssignMax($this->columns_width[$head], $cell->getLength());
        }

        foreach ($this->body_array as $line => $cells) {
            foreach ($cells as $head => $cell) {
                /** @var Cell $cell */
                pointerAssignMax($this->columns_width[$head], $cell->getLength());
            }
        }

        foreach ($this->tail_array as $ftc => $cells) {
            foreach ($cells as $head => $cell) {
                /** @var Cell $cell */
                pointerAssignMax($this->columns_width[$head], $cell->getLength());
            }
        }

        foreach ($this->head_array as $head => $cell) {
            $column_width = $this->columns_width[$head];

            /** @var Cell $cell */
            $cell = &$this->head_array[$head];
            $cell->setStrPadLength($column_width);
            unset($cell);

            foreach (array_keys($this->body_array) as $cell_key) {
                /** @var Cell $cell */
                $cell = &$this->body_array[$cell_key][$head];
                $cell->setStrPadLength($column_width);
                unset($cell);
            }

            foreach (array_keys($this->tail_array) as $cell_key) {
                /** @var Cell $cell */
                $cell = &$this->tail_array[$cell_key][$head];
                $cell->setStrPadLength($column_width);
                unset($cell);
            }
        }
    }

    protected function getWidth()
    {
        $nb_separator = count($this->columns_width) - 1;
        $width_separator = $nb_separator;

        if ($this->isWithSpaceBetwenValNSeparators()) {
            $width_separator += $nb_separator * 2;
        }

        if ($this->isWithRLBorderSeparator()) {
            $width_separator += 2;
        }

        if ($this->isWithSpaceBetwenValNSeparators() && $this->isWithRLBorderSeparator()) {
            $width_separator += 2;
        }

        return array_sum($this->columns_width) + $width_separator;
    }

    /**
     * Export the generated string
     *
     * @return string
     */
    public function export(): string
    {
        if (empty($this->head_array) || empty($this->body_array) || empty($this->tail_array)) {
            return '';
        }

        $head = [];
        $body = [];
        $tail = [];

        /*
         * Export cells
         */

        foreach ($this->head_array as $cell) {
            /** @var Cell $cell */
            $head[] = $cell->export();
        }

        foreach ($this->body_array as $line => $cells) {
            foreach ($cells as $cell) {
                /** @var Cell $cell */
                $body[$line][] = $cell->export();
            }
        }

        foreach ($this->tail_array as $ftc => $cells) {
            // refactor
            /*
                if (
                    ($this->isWithTailTotal() && $ftc !== 'avg') ||
                    ($this->isWithTailAverage() && $ftc !== 'avg') ||
                ) {
                    continue;
                }
            */

            foreach ($cells as $cell) {
                if (!in_array($ftc, $this->getTailFunctions())) {
                    continue;
                }

                /** @var Cell $cell */
                $tail[$ftc][] = $cell->export();
            }
        }

        /*
         * Implodes
         */

        $head_separator = $this->getHeadVerticalSeparator();
        $body_separator = $this->getBodyVerticalSeparator();
        $tail_separator = $this->getTailVerticalSeparator();

        if ($this->isWithSpaceBetwenValNSeparators()) {
            $head_separator_fi = " $head_separator ";
            $body_separator_fi = " $body_separator ";
            $tail_separator_fi = " $tail_separator ";
        } else {
            $head_separator_fi = $head_separator;
            $body_separator_fi = $body_separator;
            $tail_separator_fi = $tail_separator;
        }

        $head = implode($head_separator_fi, $head);
        $body = array_map('implode', array_fill(0, count($body), $body_separator_fi), $body);
        $tail = array_map('implode', array_fill(0, count($tail), $tail_separator_fi), $tail);

        if ($this->isWithRLBorderSeparator()) {
            $head = $head_separator . ' ' . $head . ' ' . $head_separator;

            foreach ($body as $k => $v) {
                $body[$k] = $body_separator . ' ' . $body[$k] . ' ' . $body_separator;
            }

            foreach ($tail as $k => $v) {
                $tail[$k] = $tail_separator . ' ' . $tail[$k] . ' ' . $tail_separator;
            }
        }

        $width = $this->getWidth();
        $table = [];

        $head_separator_h = $this->getHeadHorizontalSeparator();
        $body_separator_h = $this->getBodyHorizontalSeparator();
        $tail_separator_h = $this->getTailHorizontalSeparator();

        if (!empty($head)) {
            if (!empty($head_separator_h)) {
                $table[] = str_repeat($head_separator_h, $width);
            }

            $table[] = $head;

            if (!empty($head_separator_h)) {
                $table[] = str_repeat($head_separator_h, $width);
            }
        }

        foreach ($body as $item) {
            $table[] = $item;

            if ($this->isWithBodyCenterHorizontalSeparators() && !empty($body_separator_h)) {
                $table[] = str_repeat($body_separator_h, $width);
            }
        }

        if ($this->isWithBodyCenterHorizontalSeparators() && !empty($body_separator_h)) {
            array_pop($table);
        }

        if (!empty($tail)) {
            if (!empty($tail_separator_h)) {
                $table[] = str_repeat($tail_separator_h, $width);
            }

            foreach ($tail as $item) {
                $table[] = $item;
            }

            if (!empty($tail_separator_h)) {
                $table[] = str_repeat($tail_separator_h, $width);
            }
        } else {
            $table[] = str_repeat($body_separator_h, $width);
        }

        $table[] = '';

        return implode(PHP_EOL, $table);
    }

    /*
     * Getters
     */

    /**
     * Get actual functions
     *
     * @return string[]
     */
    public function getTailFunctions(): array
    {
        return $this->tail_functions;
    }

    /**
     * Get a text list or specific, null if specific not found
     *
     * @param string|null $id_text The ID of text you want, leave empty to get all texts
     * @return null|string|string[]
     */
    public function getText(?string $id_text = null)
    {
        if (empty($id_text)) {
            return $this->texts;
        }

        return $this->texts[$id_text] ?? null;
    }

    /**
     * Set a specific text
     *
     * @param string $id_text Text ID
     * @param string $new_text New text to set
     * @return $this
     */
    public function setText(string $id_text, string $new_text): Table1
    {
        if (isset($this->texts[$id_text])) {
            $this->texts[$id_text] = $new_text;
            $this->buildWidth();
        }

        return $this;
    }

    /**
     * Set an array of text
     *
     * @param string $id_text Text ID
     * @param string $new_text New text to set
     * @return $this
     */
    public function setTexts(?array $texts = null): bool
    {
        if (empty($texts)) {
            return false;
        }

        $return = true;

        foreach ($texts as $id_text => $value) {
            $this->setText($id_text, $value);

            if (isset($this->texts[$id_text]) && $this->texts[$id_text] === $value) {
                continue;
            }

            $return = false;

            break;
        }

        return $return;
    }

    /**
     * @return string
     */
    public function getHeadHorizontalSeparator(): string
    {
        return $this->head_horizontal_separator;
    }

    /**
     * @return string
     */
    public function getBodyHorizontalSeparator(): string
    {
        return $this->body_horizontal_separator;
    }

    /**
     * @return string
     */
    public function getTailHorizontalSeparator(): string
    {
        return $this->tail_horizontal_separator;
    }

    /**
     * @return string
     */
    public function getHeadVerticalSeparator(): string
    {
        return $this->head_vertical_separator;
    }

    /**
     * @return string
     */
    public function getBodyVerticalSeparator(): string
    {
        return $this->body_vertical_separator;
    }

    /**
     * @return string
     */
    public function getTailVerticalSeparator(): string
    {
        return $this->tail_vertical_separator;
    }

    /**
     * @return bool
     */
    public function isWithSpaceBetwenValNSeparators(): bool
    {
        return $this->with_space_betwen_val_n_separators;
    }

    /**
     * @return bool
     */
    public function isWithBodyCenterHorizontalSeparators(): bool
    {
        return $this->with_body_center_horizontal_separators;
    }

    /**
     * @return bool
     */
    public function isWithHead(): bool
    {
        return $this->with_head;
    }

    /**
     * @return bool
     */
    public function isWithTailTotal(): bool
    {
        return $this->with_tail_total;  // refactor
    }

    /**
     * @return bool
     */
    public function isWithTailAverage(): bool
    {
        return $this->with_tail_average;
    }

    /**
     * @return bool
     */
    public function isWithRLBorderSeparator(): bool
    {
        return $this->with_r_l_border_separator;
    }

    /*
     * Setters
     */

    /**
     * @param string $head_horizontal_separator
     * @return Table1
     */
    public function setHeadHorizontalSeparator(string $head_horizontal_separator): Table1
    {
        if (mb_strlen($head_horizontal_separator) === 1) {
            $this->head_horizontal_separator = $head_horizontal_separator;
        }

        return $this;
    }

    /**
     * @param string $body_horizontal_separator
     * @return Table1
     */
    public function setBodyHorizontalSeparator(string $body_horizontal_separator): Table1
    {
        if (mb_strlen($body_horizontal_separator) === 1) {
            $this->body_horizontal_separator = $body_horizontal_separator;
        }

        return $this;
    }

    /**
     * @param string $tail_horizontal_separator
     * @return Table1
     */
    public function setTailHorizontalSeparator(string $tail_horizontal_separator): Table1
    {
        if (mb_strlen($tail_horizontal_separator) === 1) {
            $this->tail_horizontal_separator = $tail_horizontal_separator;
        }

        return $this;
    }

    /**
     * @param string $head_vertical_separator
     * @return Table1
     */
    public function setHeadVerticalSeparator(string $head_vertical_separator): Table1
    {
        if (mb_strlen($head_vertical_separator) === 1) {
            $this->head_vertical_separator = $head_vertical_separator;
        }

        return $this;
    }

    /**
     * @param string $body_vertical_separator
     * @return Table1
     */
    public function setBodyVerticalSeparator(string $body_vertical_separator): Table1
    {
        if (mb_strlen($body_vertical_separator) === 1) {
            $this->body_vertical_separator = $body_vertical_separator;
        }

        return $this;
    }

    /**
     * @param string $tail_vertical_separator
     * @return Table1
     */
    public function setTailVerticalSeparator(string $tail_vertical_separator): Table1
    {
        if (mb_strlen($tail_vertical_separator) === 1) {
            $this->tail_vertical_separator = $tail_vertical_separator;
        }

        return $this;
    }

    /**
     * @param bool $with_space_betwen_val_n_separators
     * @return Table1
     */
    public function setWithSpaceBetwenValNSeparators(bool $with_space_betwen_val_n_separators): Table1
    {
        $this->with_space_betwen_val_n_separators = $with_space_betwen_val_n_separators;
        return $this;
    }

    /**
     * @param bool $with_body_center_horizontal_separators
     * @return Table1
     */
    public function setWithBodyCenterHorizontalSeparators(bool $with_body_center_horizontal_separators): Table1
    {
        $this->with_body_center_horizontal_separators = $with_body_center_horizontal_separators;
        return $this;
    }

    /**
     * @param bool $with_head
     * @return Table1
     */
    public function setWithHead(bool $with_head): Table1
    {
        $this->with_head = $with_head;
        return $this;
    }

    /**
     * @param bool $with_tail_total  // refactor
     * @return Table1
     */
    public function setWithTailTotal(bool $with_tail_total): Table1
    {
        $this->with_tail_total = $with_tail_total;  // refactor
        return $this;
    }

    /**
     * @param bool $with_tail_average
     * @return Table1
     */
    public function setWithTailAverage(bool $with_tail_average): Table1
    {
        $this->with_tail_average = $with_tail_average;
        return $this;
    }

    /**
     * @param bool $with_r_l_border_separator
     * @return Table1
     */
    public function setWithRLBorderSeparator(bool $with_r_l_border_separator): Table1
    {
        $this->with_r_l_border_separator = $with_r_l_border_separator;
        return $this;
    }

    /**
     * Add one function on tail
     *
     * @param string $tail_ftc
     * @return bool
     */
    public function addTailFunction(string $tail_ftc): Table1
    {
        if (in_array($tail_ftc, self::getAllTailFunctions())) {
            $this->tail_functions[$tail_ftc] = $tail_ftc;
        }

        return $this;
    }

    /**
     * Set an array of function we want in tail
     *
     * @param string[] $list_tail_ftc
     * @return bool
     */
    public function setTailFunctions(?array $list_tail_ftc = null): bool
    {
        if (empty($list_tail_ftc)) {
            return false;
        }

        $return = true;
        $old_ftcs = $this->getTailFunctions();

        see($return, 'setTailFunctions');

        foreach ($list_tail_ftc as $ftc) {
            $this->addTailFunction($ftc);

            if (isset($this->tail_functions[$ftc]) && $this->tail_functions[$ftc] === $ftc) {
                continue;
            }

            $return = false;

            see($return, 'false');

            break;
        }

        if (!$return) {
            see($return, 'reset');
            $this->setTailFunctions($old_ftcs);
        }

        see($return, 'end');
        return $return;
    }

    /*
     * Cell management
     */

    public function setCellOption($line, $column, string $option, $value): bool
    {
        return $this->setCellConfig($line, $column, [$option => $value]);
    }

    public function setCellConfig($line, $column, array $config): bool
    {
        if (!is_string($line) || !is_numeric($line)) {
            return false;
        }

        if (!is_string($column) || !is_numeric($column)) {
            return false;
        }

        $space = null;
        $sub_space = null;

        if ($line === 0) {
            $space = 'head';
        } else if ($line >= 1) {
            $space = 'body';
            $line--;
        } else if ($line === (count($this->cell_array) + 1)) {
            $space = 'tail';
            $sub_space = 'total';
        } else if ($line === (count($this->cell_array) + 2)) {
            $space = 'tail';
            $sub_space = 'avg';
        }

        /** @var Cell $cell */
        $cell = &$this->cell_array[$line][$column];

        if (!isset($cell)) {
            return false;
        }

        return $cell->setConfig($config);
    }

    public function setCellLineOption($line, string $option, $value): bool
    {
        return $this->setCellLineConfig($line, [$option => $value]);
    }

    public function setCellLineConfig($line, array $config): bool
    {
        $return = true;

        foreach (array_keys($this->cell_array[$line]) as $column) {
            foreach ($config as $option => $value) {
                $return &= $this->setCellOption($line, $column, $option, $value);
            }
        }

        return $return;
    }

    public function setCellColumnOption($column, string $option, $value): bool
    {
        return $this->setCellColumnConfig($column, [$option => $value]);
    }

    public function setCellColumnConfig($column, array $config): bool
    {
        $return = true;

        foreach (array_keys($this->cell_array) as $line) {
            foreach ($config as $option => $value) {
                $return &= $this->setCellOption($line, $column, $option, $value);
            }
        }

        return $return;
    }

    public function setEveryCellOption(string $option, $value): bool
    {
        return $this->setEveryCellConfig([$option => $value]);
    }

    public function setEveryCellConfig(array $config): bool
    {
        $return = true;

        foreach ($this->cell_array as $line => $columns) {
            foreach (array_keys($columns) as $column) {
                foreach ($config as $option => $value) {
                    $return &= $this->setCellOption($line, $column, $option, $value);
                }
            }
        }

        return $return;
    }

}