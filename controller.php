<?php
namespace Concrete\Package\DocumentationGenerator;

use Concrete\Core\Foundation\Service\ProviderList;
use Concrete\DocumentationGenerator\Provider\ConsoleProviderList;
use Illuminate\Filesystem\Filesystem;

class Controller extends \Package
{

    protected $pkgHandle = 'documentation_generator';

    public function __construct()
    {
        $this->registerConfigNamespace();

        $this->pkgVersion = $this->getFileConfig()->get('package.version');
        $this->pkgDescription = $this->getFileConfig()->get('package.description');
        $this->pkgName = $this->getFileConfig()->get('package.name');
    }

    public function on_start()
    {
        if (\Core::isRunThroughCommandLineInterface()) {
            $this->setUp();
        }
    }

    /**
     * Pull in composer and register service provider
     */
    public function setUp()
    {
        $filesystem = new Filesystem();
        $filesystem->requireOnce(__DIR__ . "/vendor/autoload.php");

        $list = new ProviderList(\Core::getFacadeApplication());
        $list->registerProvider("\\Concrete\\DocumentationGenerator\\ServiceProvider");
    }

}
