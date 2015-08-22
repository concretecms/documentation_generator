<?php
namespace Concrete\DocumentationGenerator\Generator\BlockType;

use Concrete\DocumentationGenerator\Generator\AbstractGenerator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BlockTypeListGenerator extends AbstractGenerator
{

    public function getHandle()
    {
        return "block_type_list";
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return null|int null or 0 if everything went fine, or an error code
     */
    public function generate(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("# Core Block Types", "");
        $types = \BlockTypeList::getInstalledList();

        $output->writeln(array(
            " Handle | Name | Description ",
            " ------ | ---- | ----------- "));
        foreach ($types as $type) {
            if (!$type->getPackageHandle()) {
                $handle = $type->getBlockTypeHandle();
                $name = $type->getBlockTypeName();
                $description = $type->getBlockTypeDescription();

                $output->writeln("`{$handle}` | {$name} | {$description}");
            }
        }
    }

}
