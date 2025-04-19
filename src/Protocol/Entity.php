<?php

namespace Aberdeener\LaravelMcpServer\Protocol;

use Aberdeener\LaravelMcpServer\Protocol\Exceptions\Entity\EntityAttributeMissingException;
use Aberdeener\LaravelMcpServer\Protocol\Exceptions\Entity\EntityMustProvideCallMethodException;
use Aberdeener\LaravelMcpServer\Protocol\Exceptions\Entity\MultipleEntityAttributesDefinedException;
use ReflectionAttribute;
use ReflectionClass;
use ReflectionParameter;

/**
 * @method mixed call(...$args)
 */
abstract class Entity
{
    public function __construct()
    {
        if (! method_exists($this, 'call')) {
            throw new EntityMustProvideCallMethodException;
        }
    }

    abstract public function toArray(): array;

    final protected function getAttributeValue(ReflectionClass|ReflectionParameter $reflector, $attribute, bool $raise = true)
    {
        $attributes = collect($reflector->getAttributes())->filter(fn (ReflectionAttribute $attr) => $attr->getName() === $attribute);
        if ($attributes->isEmpty()) {
            if (! $raise) {
                return null;
            } else {
                throw new EntityAttributeMissingException($attribute);
            }
        }

        if ($attributes->count() > 1) {
            throw new MultipleEntityAttributesDefinedException($attribute);
        }

        return $attributes->first()->newInstance()->value;
    }
}
