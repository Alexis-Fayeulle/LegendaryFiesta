<?php

namespace AtyKlaxas\LegendaryFiesta\Debug\Render;

use \AtyKlaxas\LegendaryFiesta\Debug\Config\CellConfig;

class CellRender extends CellConfig
{

    protected string $render_value;

    protected function setRenderValue($value)
    {
        if (is_bool($value)) {
            $value = $value ? 'true' : 'false';
        } elseif (is_array($value)) {
            $value = '[array]';
        } elseif (is_object($value)) {
            if (method_exists($value, '__toString')) {
                $value = $value->__toString();
            } else {
                $class = get_class($value);
                $value = "[$class]";
            }
        } elseif (is_callable($value)) {
            $value = '[callable]';
        } elseif (is_iterable($value)) {
            $value = '[iterable]';
        } elseif (is_resource($value)) {
            $value = '[resource]';
        } elseif (is_null($value)) {
            $value = '';
        }

        $this->render_value = (string) $value;
    }

    protected function getRenderValue(): string
    {
        return $this->render_value;
    }

    public function getLength(): int
    {
        return strlen($this->export(false));
    }

    public function export(bool $use_strpad = true): string
    {
        $number_format = number_format($this->render_value, $this->getNumberFormatDecimals(), $this->getNumberFormatDecimalSeparator(), $this->getNumberFormatThousandsSeparator());

        return $use_strpad ? str_pad($number_format, $this->getStrPadLength(), $this->getStrPadString(), $this->getStrPadType()) : $number_format;
    }

}