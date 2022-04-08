<?php declare(strict_types=1);

namespace AtyKlaxas\LegendaryFiesta\Debug\TrashCode;

class Cell {

    /** @var int|float|string Valeur de la cellule */
    protected $value;

    /** @var int Nombre de decimal aprés la virgule */
    protected $number_format_decimals = 0;
    /** @var string Caractère après la virgule */
    protected $number_format_decimal_separator = ',';
    /** @var string Caractère de separation des centaines */
    protected $number_format_thousands_separator = '';

    /** @var int Taille minimum de la string */
    protected $str_pad_length = 0;
    /** @var string Caractère de remplissage si string trop courte */
    protected $str_pad_string = ' ';
    /** @var int Type de remplissage (STR_PAD_RIGHT, STR_PAD_LEFT, STR_PAD_BOTH) */
    protected $str_pad_type = STR_PAD_RIGHT;

    /** @var string[] Array of variable of this class, edit it then you make a child of this class */
    protected static $CONFIG_FIELDS = [
        'number_format_decimals',
        'number_format_decimal_separator',
        'number_format_thousands_separator',
        'str_pad_length',
        'str_pad_string',
        'str_pad_type',
    ];

    /**
     * @return string[] Array de champs modifiable
     */
    public function getConfigFields(): array
    {
        return self::$CONFIG_FIELDS;
    }

    /*
     * Getters
     */

    /**
     * @return float|int|string
     */
    public function getValue()
    {
        return $this->value;
    }

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
     * @param float|int|string $value
     */
    public function setValue($value): self
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @param int $number_format_decimals
     */
    public function setNumberFormatDecimals(int $number_format_decimals): self
    {
        $this->number_format_decimals = $number_format_decimals;

        return $this;
    }

    /**
     * @param string $number_format_decimal_separator
     */
    public function setNumberFormatDecimalSeparator(string $number_format_decimal_separator): self
    {
        $this->number_format_decimal_separator = $number_format_decimal_separator;

        return $this;
    }

    /**
     * @param string $number_format_thousands_separator
     */
    public function setNumberFormatThousandsSeparator(string $number_format_thousands_separator): self
    {
        $this->number_format_thousands_separator = $number_format_thousands_separator;

        return $this;
    }

    /**
     * @param int $str_pad_length
     */
    public function setStrPadLength(int $str_pad_length): self
    {
        $this->str_pad_length = $str_pad_length;

        return $this;
    }

    /**
     * @param string $str_pad_string
     */
    public function setStrPadString(string $str_pad_string): self
    {
        if (mb_strlen($str_pad_string) === 1) {
            $this->str_pad_string = $str_pad_string;
        }

        return $this;
    }

    /**
     * @param int $str_pad_type
     */
    public function setStrPadType(int $str_pad_type): self
    {
        if (in_array($str_pad_type, [STR_PAD_LEFT, STR_PAD_RIGHT, STR_PAD_BOTH])) {
            $this->str_pad_type = $str_pad_type;
        }

        return $this;
    }

    /*
     * Methods
     */

    /**
     * Export config in array
     *
     * @return array
     */
    public function getConfig(): array
    {
        $config = [];

        foreach ($this->getConfigFields() as $field_snake_case) {
            $field_pascal_case = implode('', array_map('ucfirst', explode('_', $field_snake_case)));
            $get_function = 'get' . $field_pascal_case;

            $config[$field_snake_case] = $this->$get_function();
        }

        return $config;
    }

    /**
     * Import config from array
     *
     * @param array $config
     * @return bool false if invalid input config
     */
    public function setConfig(array $config): bool
    {
        $return = true;
        $old_conf = $this->getConfig();

        foreach ($config as $field_snake_case => $value) {
            if (!in_array($field_snake_case, $this->getConfigFields(), true)) {
                $return = false;

                break;
            }

            $explode = explode('_', $field_snake_case);
            $explode = array_map('strtolower', $explode);
            $explode = array_map('ucfirst', $explode);
            $field_pascal_case = implode('', $explode);
            $set_function = 'set' . $field_pascal_case;

            $this->$set_function($value);
        }

        if (!$return) {
            $this->setConfig($old_conf);
        }

        return $return;
    }

    /**
     * Constructor
     *
     * @param mixed $value Value in cell
     */
    public function __construct($value = null)
    {
        $this->setValue($value);
    }

    /**
     * Generate the string without str_pad()
     *
     * @return string
     */
    protected function generateString(): string
    {
        $string = '';

        if (is_string($this->value) && !is_numeric($this->value)) {
            $string = (string) $this->value;
        } else if (is_numeric($this->value)) {
            $valued = floatval(str_replace(',', '.', (string) $this->value));
            $string = (string) number_format($valued, $this->number_format_decimals, $this->number_format_decimal_separator, $this->number_format_thousands_separator);
        } else if (method_exists($this->value, '__toString')) {
            $string = (string) $this->value->__toString();
        }

        return $string;
    }

    /**
     * Get length without str_pad()
     *
     * @return int Length
     */
    public function getLength(): int
    {
        return mb_strlen($this->generateString(), 'UTF-8');
    }

    /**
     * Export the generated string
     *
     * @return string
     */
    public function export(): string
    {
        return str_pad($this->generateString(), $this->str_pad_length, $this->str_pad_string, $this->str_pad_type);
    }

}
