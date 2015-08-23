<?php
namespace Concrete\DocumentationGenerator\Generator\Asset;


use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class VendorJavascriptAssetListGenerator extends AbstractVendorAssetListGenerator
{

    /** @var string */
    protected $fileTypeSuffix = ".js";

    /** @var string */
    protected $vendorFileTypeSuffix = ".js";

    public function getHandle()
    {
        return "vendor_javascript_list";
    }

    public function getBaseDirectory()
    {
        return DIR_BASE_CORE . "/js/build/vendor/";
    }

    /**
     * Output the asset
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param string $handle
     * @param string $path
     * @param string $source
     * @return void
     */
    public function outputAsset(InputInterface $input, OutputInterface $output, $handle, $path, $source)
    {
        $output->writeln("`{$handle}` | `{$path}` | `/js/build/vendor/$source`");
    }

    /**
     * Output the title for this generator
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    public function outputTitle(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("# Vendor JavaScript Assets", "");
    }

    /**
     * Output the header
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    public function outputHeader(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(array(
            " Handle | Location | Source ",
            " ------ | -------- | ------ "));
    }

}
