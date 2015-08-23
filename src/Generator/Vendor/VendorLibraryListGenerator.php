<?php
namespace Concrete\DocumentationGenerator\Generator\Vendor;

use Concrete\DocumentationGenerator\Generator\AbstractGenerator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class VendorLibraryListGenerator extends AbstractGenerator
{

    public function getHandle()
    {
        return "vendor_library_list";
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return null|int null or 0 if everything went fine, or an error code
     */
    public function generate(InputInterface $input, OutputInterface $output)
    {
        $libraries = json_decode(file_get_contents(DIR_BASE_CORE . "/composer.lock"), true);

        $output->writeln(array(
            "# Composer Libraries",
            "",
            " Handle | Version | Description ",
            " ------ | ------- | ----------- "));

        foreach ($libraries['packages'] as $package) {
            $output->writeln(
                "[{$package['name']}]({$package['source']['url']}) | {$package['version']} | {$package['description']}"
            );
        }
    }

}
