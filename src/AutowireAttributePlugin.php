<?php

namespace Sicet7\AutowireAttribute;

use Roave\BetterReflection\BetterReflection;
use Roave\BetterReflection\Reflector\DefaultReflector;
use Roave\BetterReflection\Reflector\Reflector as ReflectorInterface;
use Roave\BetterReflection\SourceLocator\Type\DirectoriesSourceLocator;
use Sicet7\AutowireAttribute\Attributes\Autowire;
use Sicet7\Plugin\Container\Interfaces\PluginInterface;
use Sicet7\Plugin\Container\MutableDefinitionSourceHelper;

class AutowireAttributePlugin implements PluginInterface
{
    private readonly ReflectorInterface $reflector;

    /**
     * @param string ...$directories
     */
    public function __construct(string ...$directories)
    {
        $sourceLocator = new DirectoriesSourceLocator(
            $directories,
            (new BetterReflection())->astLocator()
        );
        $this->reflector = new DefaultReflector($sourceLocator);
    }

    /**
     * @param MutableDefinitionSourceHelper $source
     * @return void
     */
    public function register(MutableDefinitionSourceHelper $source): void
    {
        foreach ($this->reflector->reflectAllClasses() as $class) {
            if(count((new \ReflectionClass($class->getName()))->getAttributes(Autowire::class, \ReflectionAttribute::IS_INSTANCEOF)) == 0) {
                continue;
            }
            $source->autowire($class->getName(), $class->getName());
        }
    }
}