<?php
namespace Concrete\DocumentationGenerator\Generator\ConfigGroup;

use Concrete\DocumentationGenerator\Config\CommentRepositoryFactory;
use Concrete\DocumentationGenerator\Generator\AbstractGenerator;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ConfigGroupListGenerator extends AbstractGenerator
{

    /** @type \Illuminate\Filesystem\Filesystem */
    protected $filesystem;

    /** @type CommentRepositoryFactory */
    protected $factory;

    public function getHandle()
    {
        return "config_group";
    }

    /**
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @return null|int null or 0 if everything went fine, or an error code
     */
    public function generate(InputInterface $input, OutputInterface $output)
    {
        $file_loader = \Config::getLoader();
        $group_files = $this->getConfigGroupFiles();

        foreach ($group_files as $group => $file) {
            $contents = $file_loader->load("", $group, "core");
            $items = $this->flattenItem($contents, $group);

            $comments = $this->getCommentRepository($file);

            $output->writeln(array(
                "# `{$group}` : `{$file}`",
                '',
                'Config Key | Type | Description | Value',
                '---------- | ---- | ----------- |------'));

            foreach ($items as $key => $item) {
                list($type, $value) = $item;
                $description = "No Description";
                if ($docblock = $comments->getDocblock($key)) {
                    list($inline_type, $inline_description) = $this->getDocblockInfo($docblock);

                    if ($inline_description) {
                        $description = $inline_description;
                    }
                    if ($inline_type) {
                        $type = $inline_type;
                    }
                }

                $output->writeln(
                    "`{$key}` | `{$type}` | {$description} | `{$value}`"
                );
            }

            $output->writeln('');
        }
    }

    protected function getDocblockInfo($docblock)
    {
        $type = "";
        $description = $docblock->getShortDescription();

        /** @var \phpDocumentor\Reflection\DocBlock\Tag\VarTag[] $tags */
        $tags = $docblock->getTagsByName('var');
        foreach ($tags as $tag) {
            list(, $inline_description) = array_pad(explode(' ', $tag->getDescription(), 2), 2, '');
            $inline_type = $tag->getType();

            if ($inline_type) {
                $type = $inline_type;
            }
            if ($inline_description) {
                $description = $inline_description;
            }
        }

        return array($type, $description);
    }

    /**
     * @return array
     */
    protected function getConfigGroupFiles()
    {
        $filesystem = $this->getFilesystem();
        $all_files = $filesystem->files(DIR_BASE_CORE . "/config");
        $config_groups = array();

        foreach ($all_files as $file) {
            if (strtolower($filesystem->extension($file)) === "php") {
                $config_groups[basename($file, '.php')] = $file;
            }
        }

        return $config_groups;
    }

    /**
     * @return \Illuminate\Filesystem\Filesystem
     */
    protected function getFilesystem()
    {
        return $this->filesystem ?: $this->filesystem = new Filesystem();
    }

    /**
     * @param string $path
     *
     * @return \Concrete\DocumentationGenerator\Config\CommentRepository
     */
    protected function getCommentRepository($path)
    {
        /** @type CommentRepositoryFactory $factory */
        $factory = $this->factory ?: $this->factory = \Core::make('documentation_generator/comment_repository_factory');

        return $factory->makeCommentRepository($path);
    }

    protected function flattenItem(array $item, $base_item)
    {
        $items = array();
        foreach ($item as $key => $value) {
            $type = gettype($value);
            $item_key = "{$base_item}.{$key}";
            $items[$item_key] = array(
                $type,
                json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE, 1)
            );

            if ($type == 'array') {
                $items = array_merge($items, $this->flattenItem($value, $item_key));
            }
        }

        return $items;
    }

}
