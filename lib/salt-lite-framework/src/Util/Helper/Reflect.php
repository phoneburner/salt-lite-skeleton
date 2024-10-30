<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLite\Framework\Util\Helper;

use ReflectionClass;
use ReflectionMethod;
use ReflectionNamedType;
use ReflectionObject;
use ReflectionType;

abstract class Reflect
{
    /**
     * @template T of object
     * @param T|class-string<T> $class_or_object
     * @return ReflectionClass<T>|ReflectionObject
     */
    public static function object(object|string $class_or_object): ReflectionClass
    {
        return \is_object($class_or_object) ? new ReflectionObject($class_or_object) : new ReflectionClass($class_or_object);
    }

    /**
     * @param object|class-string $class_or_object
     */
    public static function method(object|string $class_or_object, string $method): ReflectionMethod
    {
        return new ReflectionMethod($class_or_object, $method);
    }

    /**
     * @param object|class-string $class_or_object
     */
    public static function hasProperty(object|string $class_or_object, string $property): bool
    {
        return self::object($class_or_object)->hasProperty($property);
    }

    /**
     * @param object|class-string $class_or_object
     * @todo PHP 8.0: Add test coverage when $type_set is instance of ReflectionUnionType
     * @todo PHP 8.1: Add test coverage when $type_set is instance of ReflectionIntersectionType
     */
    public static function hasTypedProperty(object|string $class_or_object, string $property, string $type): bool
    {
        $reflection_class = self::object($class_or_object);
        if (! $reflection_class->hasProperty($property)) {
            return false;
        }

        /** @var ReflectionType|null $type_set */
        $type_set = $reflection_class->getProperty($property)->getType();
        if ($type_set === null) {
            return false;
        }

        if ($type === 'null' && $type_set->allowsNull()) {
            return true;
        }

        /** @var ReflectionNamedType[] $types */
        $types = \method_exists($type_set, 'getTypes') ? $type_set->getTypes() : [$type_set];
        foreach ($types as $named_type) {
            if ($type === $named_type->getName() && ($named_type->isBuiltin() || $type === 'self')) {
                return true;
            }

            if ($named_type->getName() === 'self' && \is_a($type, $reflection_class->getName(), true)) {
                return true;
            }

            if (\is_a($named_type->getName(), $type, true)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @template T of object
     * @param T $object
     * @return T
     */
    public static function setProperty(object $object, string $property, mixed $value = null): object
    {
        $reflection = self::object($object)->getProperty($property);
        $reflection->setValue($object, $value);

        return $object;
    }

    public static function getProperty(object $object, string $property): mixed
    {
        return self::object($object)->getProperty($property)->getValue($object);
    }

    /**
     * @param object|class-string $class_or_object
     * @return mixed|false Value of the constant with the name $name or `false`
     *    if the constant was not found in the class.
     */
    public static function getConstant(object|string $class_or_object, string $name)
    {
        return self::object($class_or_object)->getConstant($name);
    }

    /**
     * @param object|class-string $class_or_object
     * @return array<string,scalar|array<mixed>>
     */
    public static function getConstants(object|string $class_or_object): array
    {
        return self::object($class_or_object)->getConstants();
    }

    /**
     * @param object|class-string $class_or_object
     * @return array<string,scalar|array<mixed>>
     */
    public static function getPublicConstants(object|string $class_or_object): array
    {
        return self::object($class_or_object)->getConstants(\ReflectionClassConstant::IS_PUBLIC);
    }

    /**
     * Does the class or object passed as `$class_or_object` implement the interface
     * referred to by the `$interface` string? This method does essentially the
     * same thing as the PHP builtin function 'is_a' with the extra conditional
     * checks to ensure that the interface and subject class both exist.
     * Note: This method does not work to check if a class inherits from another.
     */
    public static function implements(mixed $class_or_object, string $interface): bool
    {
        if (! \interface_exists($interface)) {
            throw new \InvalidArgumentException($interface . ' is not a valid and defined interface');
        }

        if ($class_or_object instanceof $interface) {
            return true;
        }

        return \is_string($class_or_object)
            && \class_exists($class_or_object)
            && \is_a($class_or_object, $interface, true);
    }

    /**
     * Provide the short name of the class or object passed as `$class_or_object`
     *
     * @param object|class-string $class_or_object
     */
    public static function shortName(object|string $class_or_object): string
    {
        return self::object($class_or_object)->getShortName();
    }
}
