<?php

namespace AtyKlaxas\LegendaryFiesta\Debug\TrashCode\Table;

use AtyKlaxas\LegendaryFiesta\Debug\Table\Cell;

class Section
{

    protected string $vertical_separator = '|';
    protected string $horizontal_separator = '=';

    protected bool $with_vertical_center_separator = true;
    protected bool $with_vertical_border_separator = true;
    protected bool $with_horizontal_center_separator = false;
    protected bool $with_horizontal_border_separator = true;

    /** @var string[] Tableau 0n => Clé de la section pour ordonner les colones */
    protected array $heads = [];
    /** @var mixed[][] Données brutes */
    protected array $data = [];
    /** @var Cell[][] Données brutes */
    protected array $cells = [];
    protected array $columns_width = [];

    /*
     * Methods config
     */

    public function getConfig(): array
    {
        $config = [];

        foreach (self::CONFIG_FIELDS as $field_snake_case) {
            $substr = substr($field_snake_case, 0, 4);
            $get_method = ($substr === 'with' ? 'get' : 'is') . ucfirst($field_snake_case);
            $config[$field_snake_case] = $this->$get_method();
        }

        return $config;
    }

    public function setConfig(array $config): bool
    {
        $return = true;
        $old_config = $this->getConfig();

        foreach ($config as $conf => $value) {
            $set_method = 'set' . ucfirst($conf);
            try {
                $return &= $this->$set_method($value);
            } catch (\Throwable $e) {
                $return = false;
            }
        }

        if ($return === false) {
            $this->setConfig($old_config);
        }

        return $return;
    }

    public static function getDefaultConfig(): array
    {
        $inst = new self();

        return $inst->getConfig();
    }

    /*
     * Methods data
     */

    public function setData(array $data): bool
    {
        foreach ($data as $i => $datum) {
            if (!is_array($datum)) {
                $this->data = [];

                return false;
            }

            foreach (array_keys($datum) as $key) {
                $this->heads[$key] = $key;
            }

            $this->data[$i] = $datum;
        }

        sort($this->heads);

        return true;
    }

    public function getData(): array
    {
        return $this->data;
    }

    /*
     * Methods build
     */

    /**
     * Build cells and columns width
     */
    public function build()
    {
        foreach ($this->data as $i => $line) {
            foreach ($line as $key => $str) {

            }
        }

    }







    public function export()
    {

    }

    /*
     * Getters and Setters
     */

    /**
     * @return string
     */
    public function getVerticalSeparator(): string
    {
        return $this->vertical_separator;
    }

    /**
     * @param string $vertical_separator
     * @return Section
     */
    public function setVerticalSeparator(string $vertical_separator): Section
    {
        $this->vertical_separator = $vertical_separator;
        return $this;
    }

    /**
     * @return string
     */
    public function getHorizontalSeparator(): string
    {
        return $this->horizontal_separator;
    }

    /**
     * @param string $horizontal_separator
     * @return Section
     */
    public function setHorizontalSeparator(string $horizontal_separator): Section
    {
        $this->horizontal_separator = $horizontal_separator;
        return $this;
    }

    /**
     * @return bool
     */
    public function isWithVerticalCenterSeparator(): bool
    {
        return $this->with_vertical_center_separator;
    }

    /**
     * @param bool $with_vertical_center_separator
     * @return Section
     */
    public function setWithVerticalCenterSeparator(bool $with_vertical_center_separator): Section
    {
        $this->with_vertical_center_separator = $with_vertical_center_separator;
        return $this;
    }

    /**
     * @return bool
     */
    public function isWithHorizontalCenterSeparator(): bool
    {
        return $this->with_horizontal_center_separator;
    }

    /**
     * @param bool $with_horizontal_center_separator
     * @return Section
     */
    public function setWithHorizontalCenterSeparator(bool $with_horizontal_center_separator): Section
    {
        $this->with_horizontal_center_separator = $with_horizontal_center_separator;
        return $this;
    }

    /**
     * @return bool
     */
    public function isWithVerticalBorderSeparator(): bool
    {
        return $this->with_vertical_border_separator;
    }

    /**
     * @param bool $with_vertical_border_separator
     * @return Section
     */
    public function setWithVerticalBorderSeparator(bool $with_vertical_border_separator): Section
    {
        $this->with_vertical_border_separator = $with_vertical_border_separator;
        return $this;
    }

    /**
     * @return bool
     */
    public function isWithHorizontalBorderSeparator(): bool
    {
        return $this->with_horizontal_border_separator;
    }

    /**
     * @param bool $with_horizontal_border_separator
     * @return Section
     */
    public function setWithHorizontalBorderSeparator(bool $with_horizontal_border_separator): Section
    {
        $this->with_horizontal_border_separator = $with_horizontal_border_separator;
        return $this;
    }









}