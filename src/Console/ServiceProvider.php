<?php
namespace Concrete\DocumentationGenerator\Console;

use Concrete\DocumentationGenerator\Console\Command\GenerateCommand;
use Concrete\DocumentationGenerator\Generator\Asset\AssetListGenerator;
use Concrete\DocumentationGenerator\Generator\GeneratorList;
use Concrete\DocumentationGenerator\Provider\ConsoleProvider;
use Symfony\Component\Console\Command\Command;

class ServiceProvider extends ConsoleProvider
{

    public function register()
    {
        $generator_list = new GeneratorList();

        foreach ($this->app['config']['documentation_generator::generator.generators'] as $generator) {
            $generator_list->addGenerator(new $generator);
        }

        $this->app->instance('documentation_generator/generator_list', $generator_list);

        $command = new GenerateCommand("c5:docs:generate", $generator_list);
        $this->console->add($command);

        foreach ($generator_list as $generator) {
            $command = new GenerateCommand(sprintf("c5:docs:%s:generate", $generator->getHandle()), $generator_list, $generator->getHandle());
            $this->console->add($command);
        }

    }

}
