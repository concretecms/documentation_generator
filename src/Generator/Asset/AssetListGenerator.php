<?php
namespace Concrete\DocumentationGenerator\Generator\Asset;

use Concrete\DocumentationGenerator\Config\CommentRepositoryFactory;
use Concrete\DocumentationGenerator\Generator\AbstractGenerator;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AssetListGenerator extends AbstractGenerator
{

    public function getHandle()
    {
        return "asset_list";
    }

    public function generate(InputInterface $input, OutputInterface $output)
    {
        $file_loader = \Config::getLoader();
        $app_config = $file_loader->load('', 'app', 'core');

        /** @type CommentRepositoryFactory $comment_repository_factory */
        $comment_repository_factory = \Core::make('documentation_generator/comment_repository_factory');
        $comment_repository = $comment_repository_factory->makeCommentRepository(DIR_BASE_CORE . "/config/app.php");

        $asset_groups = array_get($app_config, 'asset_groups', array());

        $markdown = array("# Asset Groups", "");
        foreach ($asset_groups as $handle => $asset_group) {
            $block = $comment_repository->getDocblock("app.asset_groups.{$handle}");
            $description = $block ? $block->getShortDescription() : '';
            $output->writeln("Generating {$handle}");
            $markdown[] = "* `{$handle}`" . ($description ? ": {$description}" : "");
        }

        $markdown[] = "";
        $markdown[] = "# Individual Assets";
        $markdown[] = "";

        $assets = array_get($app_config, 'assets', array());
        foreach (array_keys($assets) as $handle) {
            $block = $comment_repository->getDocblock("app.assets.{$handle}");
            $description = $block ? $block->getShortDescription() : '';
            $output->writeln("Generating {$handle}");
            $markdown[] = "* `{$handle}`" . ($description ? ": {$description}" : "");
        }

        $markdown_string = implode("\n", $markdown);

        $output->writeln($markdown_string);
    }

}
