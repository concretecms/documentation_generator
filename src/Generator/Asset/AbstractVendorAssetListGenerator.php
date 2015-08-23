<?php
/**
 * Created by PhpStorm.
 * User: Korvin
 * Date: 8/22/15
 * Time: 11:03 PM
 */

namespace Concrete\DocumentationGenerator\Generator\Asset;

use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class AbstractVendorAssetListGenerator extends AssetListGenerator
{

    /** @var string */
    protected $baseDirectory;

    /** @var string */
    protected $fileTypeSuffix;

    /** @var string */
    protected $vendorFileTypeSuffix;

    /**
     * @return string The path to the base directory
     */
    public function getBaseDirectory()
    {
        return $this->baseDirectory;
    }

    /**
     * @return string The suffix of the file type Eg. '.js' or '.css'
     */
    public function getFileTypeSuffix()
    {
        return $this->fileTypeSuffix;
    }

    /**
     * @return string The suffix of the file type Eg. '.js' or '.less'
     */
    public function getVendorFileTypeSuffix()
    {
        return $this->vendorFileTypeSuffix;
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
    abstract public function outputAsset(InputInterface $input, OutputInterface $output, $handle, $path, $source);

    /**
     * Output the title for this generator
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    abstract public function outputTitle(InputInterface $input, OutputInterface $output);

    /**
     * Output the header
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    abstract public function outputHeader(InputInterface $input, OutputInterface $output);

    /**
     * @return array
     */
    public function getVendorNames()
    {
        $files = new Filesystem();
        $vendor_directories = $files->allFiles($this->getBaseDirectory());
        $vendor_directory_names = array();

        foreach ($vendor_directories as $vendor_directory) {
            $name = strtolower($vendor_directory->getBasename($this->getVendorFileTypeSuffix()));
            $vendor_directory_names[$name] = $vendor_directory->getRelativePathname();
        }
        return $vendor_directory_names;
    }

    public function generate(InputInterface $input, OutputInterface $output)
    {
        $suffix = $this->getFileTypeSuffix();

        $vendor_names = $this->getVendorNames();
        $this->outputTitle($input, $output);
        $this->outputHeader($input, $output);

        $assets = $this->getAssets();
        foreach ($assets as $handle => $asset) {
            foreach ((array) $asset as $file) {
                list(, $path,) = $file;

                if (isset($vendor_names[strtolower(basename($path, $suffix))])) {
                    $source = $vendor_names[strtolower(basename($path, $suffix))];
                    $this->outputAsset($input, $output, $handle, $path, $source);

                }
            }
        }
    }

}
