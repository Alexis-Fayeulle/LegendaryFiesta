<?php

namespace AtyKlaxas\LegendaryFiesta\Debug\Data;

use \AtyKlaxas\LegendaryFiesta\Debug\Render\CellRender;

class CellData extends CellRender
{

    /** @var mixed Data brute */
    protected $value;

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     * @return Cell
     */
    public function setValue($value)
    {
        $this->value = $value;
        $this->setRenderValue($value);
        return $this;
    }

}
