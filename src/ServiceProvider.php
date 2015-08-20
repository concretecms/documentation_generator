<?php
namespace Concrete\DocumentationGenerator;

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
        $provider_list = new ConsoleProviderList(\Core::getFacadeApplication(), \Core::make('console'));
        $provider_list->registerProvider('\Concrete\DocumentationGenerator\Console\ServiceProvider');

        $this->registerCommentRepositoryFactory();
    }

}
