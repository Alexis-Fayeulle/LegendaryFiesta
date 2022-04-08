<?php

namespace AtyKlaxas\LegendaryFiesta\Debug\TrashCode;

class Table2 {

    /** @var array This array is document[] */
    protected $data;

    public function setData(array $data): bool
    {
        if (empty($data)) {
            return false;
        }

        foreach ($data as $item) {
            if (!is_array($item)) {
                return false;
            }

            $map = array_map('is_array', $item);

            if (array_sum($map) !== 0) {
                return false;
            }
        }

        $this->data = $data;

        return true;
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function addDocument(array $document): self
    {
        if (self::isDocument($document)) {
            $this->data[] = $document;
        }

        return $this;
    }

    protected static function isDocument($document)
    {
        if (!is_array($document)) {
            return false;
        }

        foreach ($document as $item) {
            if (!is_array($item)) {
                return false;
            }
        }

        return true;
    }
    
}
