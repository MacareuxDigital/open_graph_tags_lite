<?php
namespace Concrete\Package\OpenGraphTagsLite\Src\Html\Object;

use HtmlObject\Traits\Tag;

/**
 * OpenGraph::create('og:title', 'Example Title').
 *
 * <meta property="og:title" content="Example Title" />
 */
class OpenGraph extends Tag
{
    /**
     * The Open Graph's tag.
     *
     * @var string
     */
    protected $element = 'meta';

    /**
     * Whether the element is self closing.
     *
     * @var bool
     */
    protected $isSelfClosing = true;

    /**
     * Build a new Open Graph Tag.
     *
     * @param string $property
     * @param string $content
     */
    public function __construct($property, $content)
    {
        // HtmlObject does not escape attribute values.
        $this->setAttributes([
            'property' => h($property),
            'content' => h($content),
        ]);
    }

    /**
     * Static alias for constructor.
     */
    public static function create($property, $content)
    {
        return new static($property, $content);
    }
}
