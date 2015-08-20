<?php
namespace Concrete\DocumentationGenerator\Generator;

use Symfony\Component\Console\Input\InputDefinition;

abstract class AbstractGenerator implements GeneratorInterface
{

    public function addDefinition(InputDefinition $definition)
    {
        // Implement this method if you'd like to add to the command definition.
    }

}
