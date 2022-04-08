<?php

namespace AtyKlaxas\LegendaryFiesta\Debug\Render;

use AtyKlaxas\LegendaryFiesta\Debug\Render\CellRender;
use AtyKlaxas\LegendaryFiesta\Debug\Config\SectionConfig;

class SectionRender extends SectionConfig
{

    /*Todo:
     * Faire tout le code manquant
     * - Getters
     * - Setters
     * - Adders
     * - Modificateurs de cell
     * - la fonction extract() (voir class SectionConfig)
     * Gérer la double dimensionnalité par clef
     */

    /** @var CellRender[][] Matrice de CellRender */
    protected array $render = [];

}