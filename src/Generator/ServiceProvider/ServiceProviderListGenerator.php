<?php
namespace Concrete\DocumentationGenerator\Generator\ServiceProvider;

use Concrete\DocumentationGenerator\Config\CommentRepositoryFactory;
use Concrete\DocumentationGenerator\Generator\AbstractGenerator;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ServiceProviderListGenerator extends AbstractGenerator
{

    public function getHandle()
    {
        return "service_provider_list";
    }

    public function generate(InputInterface $input, OutputInterface $output)
    {
        $file_loader = \Config::getLoader();
        $app_config = $file_loader->load('', 'app', 'core');
        $providers = array_get($app_config, 'providers', array());

        /** @type CommentRepositoryFactory $comment_repository_factory */
        $comment_repository_factory = \Core::make('documentation_generator/comment_repository_factory');
        $comment_repository = $comment_repository_factory->makeCommentRepository(DIR_BASE_CORE . "/config/app.php");

        $markdown = array("Handle | Class | Description", " --- | --- | ---");
        foreach ($providers as $handle => $class) {
            $docblock = $comment_repository->getDocblock("app.providers.{$handle}");
            $description = $docblock ? $docblock->getShortDescription() : '';

            $markdown[] = "`{$handle}` | `{$class}` | {$description}";
        }

        $output->writeln($markdown);
    }

}
