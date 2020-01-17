<?php

declare(strict_types=1);

namespace ActivityPhp\Server\Http;

use ActivityPhp\Type\AbstractObject;
use ActivityPhp\TypeFactory;

final class Denormalizer implements DenormalizerInterface
{

    /**
     * @var TypeFactory
     */
    private $typeFactory;

    public function __construct(TypeFactory $typeFactory)
    {
        $this->typeFactory = $typeFactory;
    }

    public function denormalize(array $data)
    {
        return $this->transform($data);
    }

    private function transform($value)
    {
        if (!is_array($value)) {
            return $value;
        }

        if (isset($value['type'])) {
            $subObject = $this->typeFactory->create($value['type']);

            foreach ($value as $property => $propValue) {
                if ('type' === $property) {
                    continue;
                }

                $subObject->set($property, $this->transform($propValue));
            }

            return $subObject;
        }

        if (is_int(key($value))) {
            return array_map(function($value) {
                return is_array($value) && isset($value['type'])
                    ? $this->transform($value)
                    : $value;
            },
                $value
            );
        }

        // Empty array, array that should not be casted as ActivityStreams types
        return $value;
    }
}
