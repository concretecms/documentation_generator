<?php
namespace Concrete\DocumentationGenerator\Generator;

use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface GeneratorInterface
{

    public function getHandle();

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return null|int null or 0 if everything went fine, or an error code
     */
    public function generate(InputInterface $input, OutputInterface $output);

    public function addDefinition(InputDefinition $definition);

}
