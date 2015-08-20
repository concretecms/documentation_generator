<?php
namespace Concrete\DocumentationGenerator;

use Concrete\Core\Config\Repository\Repository;
use Concrete\Core\Foundation\Service\Provider;
use Concrete\DocumentationGenerator\Config\CommentRepositoryFactory;
use Concrete\DocumentationGenerator\Provider\ConsoleProviderList;
use Illuminate\Filesystem\Filesystem;

class ServiceProvider extends Provider
{

    protected function registerCommentRepositoryFactory()
    {
        $this->app->bindShared(
            'documentation_generator/comment_repository_factory',
            function () {
                $filesystem = new Filesystem();
                return new CommentRepositoryFactory($filesystem);
            });
    }

    public function register()
    {
        /** @var Repository $config */
        $config = $this->app['config'];
        $namespaces = $config->getNamespaces();
        if (!isset($namespaces['documentation_generator'])) {
            $config->addNamespace('documentation_generator', realpath(__DIR__ . "/../config"));
        }

        $obj = $this;
        \Concrete\Core\Support\Facade\Events::addListener('on_before_console_run', function () use ($obj) {
            $provider_list = new ConsoleProviderList(\Core::getFacadeApplication(), \Core::make('console'));
            $provider_list->registerProvider('\Concrete\DocumentationGenerator\Console\ServiceProvider');

            $obj->registerCommentRepositoryFactory();
        });
    }

}
