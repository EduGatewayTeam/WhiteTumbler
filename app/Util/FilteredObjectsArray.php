<?php


namespace App\Util;

class FilteredObjectsArray {

    private $data;
    private $with;
    private $without;


    public function __construct($data) {
        $this->data = $data;
    }

    public function get() {
        return array_map([$this, 'filter'], $this->data);
    }

    public function with($fields) {
        $this->with = $fields;

        return $this;
    }

    public function without($fields) {
        $this->without = $fields;

        return $this;
    }

    public function filter($item) {
        if ($this->with != null) {
            if (is_array($item)) {
                return array_filter(
                    $item,
                    fn ($key) => in_array($key, $this->with),
                    ARRAY_FILTER_USE_KEY);
            }
            $result = [];
            foreach ($this->with as $key) {
                if (method_exists($item, $key)) {
                    $result[$key] = $item->$key();
                } else if (property_exists($item, $key)) {
                    $result[$key] = $item->$key;
                }
            }
            return $result;
        }
        if ($this->without != null) {
            return array_filter(
                $item,
                fn ($key) => !in_array($key, $this->without),
                ARRAY_FILTER_USE_KEY);
        }
        return $item;
    }


}
