<?php
namespace Concrete\DocumentationGenerator\Generator\Asset;


use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class VendorCssAssetListGenerator extends AssetListGenerator
{

    public function getHandle()
    {
        return "vendor_css_list";
    }

    public function generate(InputInterface $input, OutputInterface $output)
    {
        $files = new Filesystem();
        $vendor_stylesheets = $files->allFiles(DIR_BASE_CORE . "/css/build/vendor/");
        $vendor_stylesheet_names = array();
        /** @var \Symfony\Component\Finder\SplFileInfo $vendor_javascript */
        foreach ($vendor_stylesheets as $vendor_javascript) {
            $vendor_stylesheet_names[strtolower($vendor_javascript->getBasename('.less'))] = $vendor_javascript->getRelativePathname();
        }

        $output->writeln("# Vendor CSS Assets", "");
        $types = \BlockTypeList::getInstalledList();

        $output->writeln(array(
            " Handle | Location | Source ",
            " ------ | -------- | ------ "));

        $assets = $this->getAssets();
        foreach ($assets as $handle => $asset) {
            foreach ((array)$asset as $file) {
                list($type, $path,) = $file;

                if (isset($vendor_stylesheet_names[strtolower(basename($path, '.css'))])) {
                    $source = $vendor_stylesheet_names[strtolower(basename($path, '.css'))];
                    $output->writeln("`{$handle}` | `{$path}` | `/js/build/vendor/$source`");
                }
            }
        }
    }

}
