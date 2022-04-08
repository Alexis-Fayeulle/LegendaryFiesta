<?php declare(strict_types=1);

namespace AtyKlaxas\LegendaryFiesta\Debug\Config;

use \AtyKlaxas\LegendaryFiesta\Debug\Configurable;

class SectionConfig extends Configurable
{

    /** @var array To define for setters, Array of field anf the limits */
    public const CONFIG_LIMIT = [
        'vertical_center_padding' => 20,
        'vertical_border_padding' => 20,
        'horizontal_center_padding' => 20,
        'horizontal_border_padding' => 20,
    ];

    /** @var array To define for parent, Array of field we have in get / set Config */
    public const CONFIG_FIELDS = [
        'vertical_separator',
        'horizontal_separator',
        'with_vertical_center_separator',
        'with_horizontal_center_separator',
        'with_vertical_border_separator',
        'with_horizontal_border_separator',
        'vertical_center_padding',
        'vertical_border_padding',
        'horizontal_center_padding',
        'horizontal_border_padding',
    ];

    /** @var string Séparateur verical */
    protected string $vertical_separator = '|';

    /** @var string Séparateur horizontal */
    protected string $horizontal_separator = '=';

    /** @var bool Activer les séparateurs verticaux entre les colonnes */
    protected bool $with_vertical_center_separator = true;

    /** @var bool Activer les séparateurs verticaux sur les bords */
    protected bool $with_vertical_border_separator = true;

    /** @var bool Activer les séparateurs horizontaux entre les colonnes */
    protected bool $with_horizontal_center_separator = false;

    /** @var bool Activer les séparateurs horizontaux sur les bords */
    protected bool $with_horizontal_border_separator = true;

    /** @var int Padding de vide dans les séparateurs verticaux centraux */
    protected int $vertical_center_padding = 0;

    /** @var int Padding de vide dans les séparateurs verticaux de bordure */
    protected int $vertical_border_padding = 0;

    /** @var int Padding de vide dans les séparateurs horizontaux centraux */
    protected int $horizontal_center_padding = 0;

    /** @var int Padding de vide dans les séparateurs horizontaux de bordure */
    protected int $horizontal_border_padding = 0;

    /*
     * Getters
     */

    /**
     * @return string
     */
    public function getVerticalSeparator(): string
    {
        return $this->vertical_separator;
    }

    /**
     * @return string
     */
    public function getHorizontalSeparator(): string
    {
        return $this->horizontal_separator;
    }

    /**
     * @return bool
     */
    public function isWithVerticalCenterSeparator(): bool
    {
        return $this->with_vertical_center_separator;
    }

    /**
     * @return bool
     */
    public function isWithVerticalBorderSeparator(): bool
    {
        return $this->with_vertical_border_separator;
    }

    /**
     * @return bool
     */
    public function isWithHorizontalCenterSeparator(): bool
    {
        return $this->with_horizontal_center_separator;
    }

    /**
     * @return bool
     */
    public function isWithHorizontalBorderSeparator(): bool
    {
        return $this->with_horizontal_border_separator;
    }

    /**
     * @return int
     */
    public function getVerticalCenterPadding(): int
    {
        return $this->vertical_center_padding;
    }

    /**
     * @return int
     */
    public function getVerticalBorderPadding(): int
    {
        return $this->vertical_border_padding;
    }

    /**
     * @return int
     */
    public function getHorizontalCenterPadding(): int
    {
        return $this->horizontal_center_padding;
    }

    /**
     * @return int
     */
    public function getHorizontalBorderPadding(): int
    {
        return $this->horizontal_border_padding;
    }

    /*
     * Setters
     */

    /**
     * @param string $vertical_separator
     * @return Section
     */
    public function setVerticalSeparator(string $vertical_separator): Section
    {
        $this->string_setter_one_char('vertical_separator', $vertical_separator);
        return $this;
    }

    /**
     * @param string $horizontal_separator
     * @return Section
     */
    public function setHorizontalSeparator(string $horizontal_separator): Section
    {
        $this->string_setter_one_char('horizontal_separator', $horizontal_separator);
        return $this;
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
     * @param bool $with_vertical_border_separator
     * @return Section
     */
    public function setWithVerticalBorderSeparator(bool $with_vertical_border_separator): Section
    {
        $this->with_vertical_border_separator = $with_vertical_border_separator;
        return $this;
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
     * @param bool $with_horizontal_border_separator
     * @return Section
     */
    public function setWithHorizontalBorderSeparator(bool $with_horizontal_border_separator): Section
    {
        $this->with_horizontal_border_separator = $with_horizontal_border_separator;
        return $this;
    }

    /**
     * @param int $vertical_center_padding
     * @return Section
     */
    public function setVerticalCenterPadding(int $vertical_center_padding): Section
    {
        $this->int_setter_conf_limit('vertical_center_padding', $vertical_center_padding);
        return $this;
    }

    /**
     * @param int $vertical_border_padding
     * @return Section
     */
    public function setVerticalBorderPadding(int $vertical_border_padding): Section
    {
        $this->int_setter_conf_limit('vertical_border_padding', $vertical_border_padding);
        return $this;
    }

    /**
     * @param int $horizontal_center_padding
     * @return Section
     */
    public function setHorizontalCenterPadding(int $horizontal_center_padding): Section
    {
        $this->int_setter_conf_limit('horizontal_center_padding', $horizontal_center_padding);
        return $this;
    }

    /**
     * @param int $horizontal_border_padding
     * @return Section
     */
    public function setHorizontalBorderPadding(int $horizontal_border_padding): Section
    {
        $this->int_setter_conf_limit('horizontal_border_padding', $horizontal_border_padding);
        return $this;
    }

    /*
     * Private fast dev functions
     */

    /**
     * Private fast dev function to set, modulate and trigger notice to something in int
     *
     * @param string $variable Variable name
     * @param int $value Int value
     */
    private function int_setter_conf_limit(string $variable, int $value)
    {
        $this->$variable = $value % self::CONFIG_LIMIT['str_pad_type'];

        if ($value <= self::CONFIG_LIMIT[$variable]) {
            return;
        }

        trigger_error('Parameter is to big: ' . $value . ', limit: ' . self::CONFIG_LIMIT[$variable] . ', value used: ' . $this->$variable);
    }

    /**
     * Specific private fast dev function to set and trigger notice to something in string
     *
     * @param string $variable Variable name
     * @param int $value Int value
     */
    private function string_setter_one_char(string $variable, string $value)
    {
        $this->$variable = $value[0];

        if (!isset($value[1])) {
            return;
        }

        $input = substr($value, 0, 5);

        if (strlen($value) > 5) {
            $input .= '...';
        }

        trigger_error('Parameter is to big: ' . $input . ', limit: One character, value used: ' . $this->$variable);
    }

}

