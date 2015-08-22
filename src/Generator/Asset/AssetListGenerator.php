<?php
namespace Concrete\DocumentationGenerator\Generator\Asset;

use Concrete\DocumentationGenerator\Config\CommentRepository;
use Concrete\DocumentationGenerator\Config\CommentRepositoryFactory;
use Concrete\DocumentationGenerator\Generator\AbstractGenerator;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AssetListGenerator extends AbstractGenerator
{

    protected $app_config;

    public function getHandle()
    {
        return "asset_list";
    }

    protected function getAppConfig()
    {
        if (!$this->app_config) {
            $file_loader = \Config::getLoader();
            $this->app_config = $file_loader->load('', 'app', 'core');
        }

        return $this->app_config;
    }

    public function generate(InputInterface $input, OutputInterface $output)
    {
        $comment_repository = $this->getCommentRepository();
        $asset_groups = $this->getAssetGroups();

        $markdown = array("# Asset Groups", "");
        $markdown = array_merge($markdown, $this->getMarkdown(
            array_keys($asset_groups),
            'app.asset_groups',
            $comment_repository));

        $markdown[] = "";
        $markdown[] = "# Individual Assets";
        $markdown[] = "";

        $assets = $this->getAssets();
        $markdown = array_merge($markdown, $this->getMarkdown(array_keys($assets), 'app.assets', $comment_repository));

        $markdown_string = implode("\n", $markdown);

        $output->writeln($markdown_string);
    }

    protected function getAssets()
    {
        $app_config = $this->getAppConfig();

        return array_get($app_config, 'assets', array());
    }

    protected function getAssetGroups()
    {
        $app_config = $this->getAppConfig();

        return array_get($app_config, 'asset_groups', array());
    }

    protected function getCommentRepository()
    {
        /** @type CommentRepositoryFactory $comment_repository_factory */
        $comment_repository_factory = \Core::make('documentation_generator/comment_repository_factory');
        return $comment_repository_factory->makeCommentRepository(DIR_BASE_CORE . "/config/app.php");
    }

    protected function getMarkdown($list, $item, CommentRepository $repository)
    {
        $markdown = array();
        foreach ($list as $handle) {
            $block = $repository->getDocblock("{$item}.{$handle}");
            $description = $block ? $block->getShortDescription() : '';
            $markdown[] = "* `{$handle}`" . ($description ? ": {$description}" : "");
        }

        return $markdown;
    }
}
