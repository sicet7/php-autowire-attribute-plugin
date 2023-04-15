<?php

namespace Sicet7\AutowireAttribute;

use Roave\BetterReflection\Reflector\Reflector as ReflectorInterface;
use Sicet7\AutowireAttribute\Attributes\Autowire;
use Sicet7\Plugin\Container\Interfaces\PluginInterface;
use Sicet7\Plugin\Container\MutableDefinitionSourceHelper;

class AutowireAttributePlugin implements PluginInterface
{
    /**
     * @param string ...$directories
     */
    public function __construct(private readonly ReflectorInterface $reflector)
    {
    }

    /**
     * @param MutableDefinitionSourceHelper $source
     * @return void
     */
    public function register(MutableDefinitionSourceHelper $source): void
    {
        foreach ($this->reflector->reflectAllClasses() as $class) {
            if(count($class->getAttributesByInstance(Autowire::class)) === 0) {
                continue;
            }
            $source->autowire($class->getName(), $class->getName());
        }
    }
}