<?php declare(strict_types=1);

namespace AtyKlaxas\LegendaryFiesta\Debug;

use AtyKlaxas\LegendaryFiesta\Debug\Helper\NamingHelper;

class Configurable
{

    /** @var array To define in child, Array of field we have in get / set Config */
    public const CONFIG_FIELDS = [];

    /**
     * Export config in array
     *
     * @return array
     */
    public function getConfig(): array
    {
        $config = [];

        foreach ($this::CONFIG_FIELDS as $field_snake_case) {
            $with = substr($field_snake_case, 0, 4);
            $field_pascal_case = NamingHelper::convert_auto(NamingHelper::PASCAL_CASE, $field_snake_case);
            $get_function = ($with === 'with' ? 'is' : 'get') . $field_pascal_case;

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
            if (!in_array($field_snake_case, $this::CONFIG_FIELDS, true)) {
                $return = false;

                break;
            }

            $field_pascal_case = NamingHelper::convert_auto(NamingHelper::PASCAL_CASE, $field_snake_case);
            $set_function = 'set' . $field_pascal_case;

            $this->$set_function($value);
        }

        if (!$return) {
            $this->setConfig($old_conf);
        }

        return $return;
    }

}
