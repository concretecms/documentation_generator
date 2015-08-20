<?php
namespace Concrete\DocumentationGenerator\Console\Command;

use Concrete\DocumentationGenerator\Generator\GeneratorList;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateCommand extends Command
{

    /**
     * @type \Concrete\DocumentationGenerator\Generator\GeneratorList;
     */
    protected $generator_list;

    protected $default_generator;

    public function __construct($name = null, GeneratorList $generator_list, $default_generator = null)
    {
        parent::__construct($name);
        $this->generator_list = $generator_list;
        $this->default_generator = $default_generator;
    }

    /**
     * @return \Concrete\DocumentationGenerator\Generator\GeneratorInterface
     */
    protected function getDefaultGenerator()
    {
        foreach ($this->generator_list as $generator) {
            if ($generator->getHandle() == $this->default_generator) {
                return $generator;
            }
        }

        throw new \RuntimeException(sprintf("Default generator \"%s\" doesn't exist!", $this->default_generator));
    }

    /**
     * Gets the InputDefinition attached to this Command.
     *
     * @return InputDefinition An InputDefinition instance
     *
     * @api
     */
    public function getDefinition()
    {
        $definition = parent::getDefinition();

        if (!$definition) {
            $definition = new InputDefinition();

            if ($this->default_generator) {
                $generator = $this->getDefaultGenerator();
                $generator->addDefinition($definition);
            } else {
                foreach ($this->generator_list as $generator) {
                    $generator->addDefinition($definition);
                }
            }

            $this->setDefinition($definition);
        }

        return $definition;
    }

    /**
     * Executes the current command.
     *
     * This method is not abstract because you can use this class
     * as a concrete class. In this case, instead of defining the
     * execute() method, you set the code to execute by passing
     * a Closure to the setCode() method.
     *
     * @param InputInterface $input An InputInterface instance
     * @param OutputInterface $output An OutputInterface instance
     *
     * @return null|int null or 0 if everything went fine, or an error code
     *
     * @throws \LogicException When this abstract method is not implemented
     *
     * @see setCode()
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($this->default_generator) {
            return $this->getDefaultGenerator()->generate($input, $output);
        } else {
            foreach ($this->generator_list as $generator) {
                $output->writeln(sprintf('Starting generation for "%s"', $generator->getHandle()));
                if ($code = $generator->generate($input, $output)) {
                    return $code;
                }
                $output->writeln(sprintf('Completed generation for "%s"', $generator->getHandle()));
            }
        }

        return null;
    }

    /**
     * Returns alternative usages of the command.
     *
     * @return array
     */
    public function getUsages()
    {
        return array();
    }

}
