<?php
namespace Concrete\DocumentationGenerator\Provider;

use Concrete\Core\Application\Application;
use Concrete\Core\Foundation\Service\Provider;

abstract class ConsoleProvider extends Provider
{

    /**
     * @type \Symfony\Component\Console\Application
     */
    protected $console;

    public function __construct(Application $app, \Symfony\Component\Console\Application $console)
    {
        parent::__construct($app);
        $this->console = $console;
    }

}
