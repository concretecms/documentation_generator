<?php
namespace Concrete\DocumentationGenerator\Config;

use Concrete\DocumentationGenerator\Config\CommentRepository;
use Illuminate\Filesystem\Filesystem;

class CommentRepositoryFactory
{

    protected $repositories;

    /** @type \Illuminate\Filesystem\Filesystem */
    protected $file;

    public function __construct(Filesystem $filesystem)
    {
        $this->file = $filesystem;
    }

    public function makeCommentRepository($file_path)
    {
        $path = realpath($file_path);

        if ($repository = array_get($this->repositories, $path)) {
            return $repository;
        }

        $repository = new CommentRepository($this->file->get($path));
        $this->repositories[$path] = $repository;

        return $repository;
    }

}
