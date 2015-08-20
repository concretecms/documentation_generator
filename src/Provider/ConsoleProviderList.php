<?php
namespace Concrete\DocumentationGenerator\Provider;

use Concrete\Core\Foundation\Service\ProviderList;

class ConsoleProviderList extends ProviderList
{

    /** @type \Symfony\Component\Console\Application */
    protected $console;

    public function __construct(\Concrete\Core\Application\Application $app, \Symfony\Component\Console\Application $console)
    {
        parent::__construct($app);
        $this->console = $console;
    }

    protected function createInstance($class)
    {
        return new $class($this->app, $this->console);
    }

}
