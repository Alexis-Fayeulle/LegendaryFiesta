<?php declare(strict_types = 1);

namespace AtyKlaxas\LegendaryFiesta;

/**
 * Fonction de debug qui permet d'afficher un objet a 2 dimension
 *
 * @param mixed[] $array2D Tableau a 2 dimension
 * @param bool $use_keys Ignorer les clefs et affichier le tableau par les position
 * @param string $head_char_delimiter Char sur lequel le head vas se delimiter du body de la table
 * @param string $implode_line_str String sur laquelle les lignes vont se joindres
 * @param string $strpad_pad_string Pour le str_pad(), String de remplissage du vide
 * @param int $strpad_pad_type Pour le str_pad(), Type de remplissage
 */
function table(array $array2D, bool $use_keys = false, string $head_char_delimiter = '-', string $implode_line_str = ' | ', string $strpad_pad_string = ' ', int $strpad_pad_type = STR_PAD_RIGHT): void
{
    if (empty($array2D)) {
        echo 'Impossible d\'afficher cela, Entrée vide' . PHP_EOL;

        return;
    }

    // $array2D is list of array ?
    $keys = array_keys($array2D);

    for ($i = 0; $i < count($keys); $i++) {
        $sub_array = $array2D[$keys[$i]];

        if (!is_array($sub_array)) {
            echo 'Impossible d\'afficher cela, un element dans la première dimention n\'est pas un array' . PHP_EOL;

            return;
        }

        // this array is list of non array ?
        $keys2 = array_keys($sub_array);

        for ($j = 0; $j < count($keys2); $j++) {
            if (is_array($sub_array[$keys2[$j]])) {
                echo 'Impossible d\'afficher cela, un element dans la seconde dimention est un array' . PHP_EOL;

                return;
            }
        }
    }

    if ($use_keys) {
        $all_keys = array_values(array_unique(array_merge(...array_values(array_map('array_keys', $array2D)))));
    }

    $columns = [];

    // get columns length
    for ($i = 0; $i < count($keys); $i++) {
        $keys2 = $use_keys ? $all_keys : array_keys($array2D[$keys[$i]]);

        for ($j = 0; $j < count($keys2); $j++) {
            // try to stringify element
            $element = $array2D[ $keys[$i] ][ $keys2[$j] ] ?? '';

            try {
                $element_string = (string) $element;
            } catch (\Throwable $th) {
                $type = gettype($element);
                $element_string = $type;

                if ($type === 'object') {
                    $element_string .= ' ' . get_class($element);
                }
            }

            // mesure element
            // or mb_strlen ?
            $len = strlen($element_string);

            if ($len <= ($columns[$j] ?? -1)) {
                continue;
            }

            // save max
            $columns[$j] = $len;
        }
    }

    // to implode with PHP_EOL
    $display = [];

    // generate display
    for ($i = 0; $i < count($keys); $i++) {
        $keys2 = $use_keys ? $all_keys : array_keys($array2D[$keys[$i]]);
        // the line to implode
        $line = [];

        for ($j = 0; $j < count($keys2); $j++) {
            // try to stringify element
            $element = $array2D[ $keys[$i] ][ $keys2[$j] ] ?? '';
            $col_len = $columns[$j];

            try {
                $element_string = (string) $element;
            } catch (\Throwable $th) {
                $type = gettype($element);
                $element_string = $type;

                if ($type === 'object') {
                    $element_string .= ' ' . get_class($element);
                }
            }

            $line[] = str_pad($element_string, $col_len, $strpad_pad_string, $strpad_pad_type);
        }

        $display[] = implode($implode_line_str, $line);
    }

    if ($use_keys) {
        $imploded = implode($implode_line_str, $all_keys);
        // or mb_strlen ?
        array_unshift($display, str_repeat(substr($head_char_delimiter, 0, 1), strlen($imploded)));
        array_unshift($display, $imploded);
    }

    // echo result
    echo PHP_EOL . implode(PHP_EOL, $display) . PHP_EOL;
}
