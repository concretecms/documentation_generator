<?php
namespace Concrete\DocumentationGenerator\Config;

use phpDocumentor\Reflection\DocBlock;

class CommentRepository
{

    protected $docblocks = array();

    public function __construct($file_contents)
    {
        $docblocks = array();
        if (preg_match_all('~\/\*\*.+?\*\/~s', $file_contents, $docblocks) === false) {
            throw new \RuntimeException('Couldn\'t parse document blocks.');
        }

        $docblocks = array_get($docblocks, 0, array());
        $this->processDocBlocks($docblocks);
    }

    /**
     * Process comments into docblock objects.
     * @param array $docblocks
     */
    protected function processDocBlocks(array $docblocks)
    {
        $this->docblocks = array();

        foreach ($docblocks as $raw_docblock) {
            $docblock = new DocBlock($raw_docblock);
            $tag = head($docblock->getTagsByName("var"));

            if ($tag && $description = $tag->getDescription()) {
                $this->docblocks[$description] = $docblock;
            }
        }
    }

    /**
     * @param $description
     *
     * @return  \phpDocumentor\Reflection\DocBlock|null
     */
    public function getDocblock($description)
    {
        return array_get($this->docblocks, $description);
    }

}
