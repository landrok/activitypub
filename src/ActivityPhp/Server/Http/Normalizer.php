<?php

declare(strict_types=1);

namespace ActivityPhp\Server\Http;

use ActivityPhp\Type\AbstractObject;

final class Normalizer implements NormalizerInterface
{

    public function normalize(AbstractObject $object): array
    {
        $keys = array_keys(array_filter(
            $object->getPropertyValues(),
            function($value, $key) {
                return !is_null($value) && $key != '_context';
            },
            ARRAY_FILTER_USE_BOTH
        ));

        $stack = [];

        // native properties
        foreach ($keys as $key) {
            if (null === $object->$key) {
                continue;
            }

            if ($object->$key instanceof AbstractObject) {
                $stack[$key] = $this->normalize($object->$key);
            } elseif (!is_array($object->$key)) {
                $stack[$key] = $object->$key;
            } elseif (is_array($object->$key)) {
                if (is_int(key($object->$key))) {
                    $stack[$key] = array_map(function($value) {
                        return $value instanceof AbstractObject
                            ? $this->normalize($value)
                            : $value;
                    },
                        $this->$key
                    );
                } else {
                    $stack[$key] = $this->$key;
                }
            }
        }

        return $stack;
    }
}