<?php
namespace Concrete\DocumentationGenerator\Generator\Asset;


use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class VendorJavascriptAssetListGenerator extends AssetListGenerator
{

    public function getHandle()
    {
        return "vendor_javascript_list";
    }

    public function generate(InputInterface $input, OutputInterface $output)
    {
        $files = new Filesystem();
        $vendor_javascripts = $files->allFiles(DIR_BASE_CORE . "/js/build/vendor/");
        $vendor_javascript_names = array();
        /** @var \Symfony\Component\Finder\SplFileInfo $vendor_javascript */
        foreach ($vendor_javascripts as $vendor_javascript) {
            $vendor_javascript_names[strtolower($vendor_javascript->getBasename('.js'))] = $vendor_javascript->getRelativePathname();
        }

        $output->writeln("# Vendor JavaScript Assets", "");
        $types = \BlockTypeList::getInstalledList();

        $output->writeln(array(
            " Handle | Location | Source ",
            " ------ | -------- | ------ "));

        $assets = $this->getAssets();
        foreach ($assets as $handle => $asset) {
            foreach ((array)$asset as $file) {
                list($type, $path,) = $file;

                if (isset($vendor_javascript_names[strtolower(basename($path, '.js'))])) {
                    $source = $vendor_javascript_names[strtolower(basename($path, '.js'))];
                    $output->writeln("`{$handle}` | `{$path}` | `/js/build/vendor/$source`");
                }
            }
        }
    }

}
