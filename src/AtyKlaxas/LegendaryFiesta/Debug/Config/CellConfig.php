<?php declare(strict_types=1);

namespace AtyKlaxas\LegendaryFiesta\Debug\Config;

use \AtyKlaxas\LegendaryFiesta\Debug\Configurable;

class CellConfig extends Configurable
{

    /** @var array To define for setters, Array of field anf the limits */
    public const CONFIG_LIMIT = [
        'number_format_decimals' => 100,
        'str_pad_length' => 1000,
        'str_pad_type' => 2,
    ];

    /** @var array To define for parent, Array of field we have in get / set Config */
    public const CONFIG_FIELDS = [
        'number_format_decimals',
        'number_format_decimal_separator',
        'number_format_thousands_separator',
        'str_pad_length',
        'str_pad_string',
        'str_pad_type',
    ];

    /** @var int Nombre de decimal après la virgule */
    protected int $number_format_decimals = 0;
    /** @var string Caractère après la virgule */
    protected string $number_format_decimal_separator = ',';
    /** @var string Caractère de separation des centaines */
    protected string $number_format_thousands_separator = '';

    /** @var int Taille minimum de la string */
    protected int $str_pad_length = 0;
    /** @var string Caractère de remplissage si string trop courte */
    protected string $str_pad_string = ' ';
    /** @var int Type de remplissage (STR_PAD_RIGHT, STR_PAD_LEFT, STR_PAD_BOTH) */
    protected int $str_pad_type = STR_PAD_RIGHT;

    /*
     * Getters
     */

    /**
     * @return int
     */
    public function getNumberFormatDecimals(): int
    {
        return $this->number_format_decimals;
    }

    /**
     * @return string
     */
    public function getNumberFormatDecimalSeparator(): string
    {
        return $this->number_format_decimal_separator;
    }

    /**
     * @return string
     */
    public function getNumberFormatThousandsSeparator(): string
    {
        return $this->number_format_thousands_separator;
    }

    /**
     * @return int
     */
    public function getStrPadLength(): int
    {
        return $this->str_pad_length;
    }

    /**
     * @return string
     */
    public function getStrPadString(): string
    {
        return $this->str_pad_string;
    }

    /**
     * @return int
     */
    public function getStrPadType(): int
    {
        return $this->str_pad_type;
    }

    /*
     * Setters
     */

    /**
     * @param int $number_format_decimals
     * @return Cell
     */
    public function setNumberFormatDecimals(int $number_format_decimals): Cell
    {
        $this->int_setter('number_format_decimals', $number_format_decimals);
        return $this;
    }

    /**
     * @param string $number_format_decimal_separator
     * @return Cell
     */
    public function setNumberFormatDecimalSeparator(string $number_format_decimal_separator): Cell
    {
        $this->string_setter('number_format_decimal_separator', $number_format_decimal_separator);
        return $this;
    }

    /**
     * @param string $number_format_thousands_separator
     * @return Cell
     */
    public function setNumberFormatThousandsSeparator(string $number_format_thousands_separator): Cell
    {
        $this->string_setter('number_format_thousands_separator', $number_format_thousands_separator);
        return $this;
    }

    /**
     * @param int $str_pad_length
     * @return Cell
     */
    public function setStrPadLength(int $str_pad_length): Cell
    {
        $this->int_setter('str_pad_length', $str_pad_length);
        return $this;
    }

    /**
     * @param string $str_pad_string
     * @return Cell
     */
    public function setStrPadString(string $str_pad_string): Cell
    {
        $this->string_setter('str_pad_string', $str_pad_string);
        return $this;
    }

    /**
     * @param int $str_pad_type
     * @return Cell
     */
    public function setStrPadType(int $str_pad_type): Cell
    {
        $this->int_setter('str_pad_type', $str_pad_type);
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
    private function int_setter(string $variable, int $value)
    {
        $this->$variable = $value;

        if ($value <= self::CONFIG_LIMIT[$variable] && $value >= 0) {
            return;
        }

        $this->$variable = $value > 0 ? self::CONFIG_LIMIT[$variable] : 0;

        trigger_error('Parameter is to big: ' . $value . ', limit: ' . self::CONFIG_LIMIT[$variable] . ', value used: ' . $this->$variable);
    }

    /**
     * Specific private fast dev function to set and trigger notice to something in string
     *
     * @param string $variable Variable name
     * @param int $value Int value
     */
    private function string_setter(string $variable, string $value)
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
