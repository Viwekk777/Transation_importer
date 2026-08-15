<?php
declare(strict_types=1);
namespace App;

use ReflectionClass;
use ReflectionParameter;
use ReflectionNamedType;
use ReflectionUnionType;
use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    private array $entries = [];

    public function get(string $id)
    {
        if ($this->has($id)) {
            $callable = $this->entries[$id];
            return $callable($this);
        }

        return $this->resolve($id);
    }

    public function has(string $id): bool
    {
        return isset($this->entries[$id]);
    }

    public function set(string $id, callable $callable): void
    {
        $this->entries[$id] = $callable;
    }

    public function resolve(string $id): object
    {
        $reflectionClass = new ReflectionClass($id);

        if (!$reflectionClass->isInstantiable()) {
            throw new \Exception("The class {$id} is not instantiable");
        }

        $constructor = $reflectionClass->getConstructor();

        if (!$constructor) {
            return new $id();
        }

        $parameters = $constructor->getParameters();

        if (!$parameters) {
            return new $id();
        }

        $dependencies = array_map(
            fn (ReflectionParameter $param) => $this->resolveParameter($param),
            $parameters
        );

        return $reflectionClass->newInstanceArgs($dependencies);
    }

    private function resolveParameter(ReflectionParameter $param)
    {
        $type = $param->getType();

        // No type hint at all — only okay if there's a default value
        if (!$type) {
            if ($param->isDefaultValueAvailable()) {
                return $param->getDefaultValue();
            }
            throw new \Exception("Could not resolve dependency: \${$param->getName()}");
        }

        // Union types (e.g. int|string $x) — not supported by auto-wiring
        if ($type instanceof ReflectionUnionType) {
            throw new \Exception("Cannot resolve union type for \${$param->getName()}");
        }

        // Class/interface type hint — recursively resolve it
        if ($type instanceof ReflectionNamedType && !$type->isBuiltin()) {
            return $this->get($type->getName());
        }

        // Builtin type (int, string, bool, etc.) — fall back to default value if present
        if ($param->isDefaultValueAvailable()) {
            return $param->getDefaultValue();
        }

        // Nullable builtin with no default (e.g. ?int $x) — allow null
        if ($type->allowsNull()) {
            return null;
        }

        throw new \Exception("Could not resolve dependency: \${$param->getName()}");
    }
}