<?php
namespace Concrete\Package\OpenGraphTagsLite\Src\Html\Object;

use HtmlObject\Traits\Tag;

/**
 * TwitterCard::create('card', 'Example').
 *
 * <meta name="twitter:card" content="Example" />
 */
class TwitterCard extends Tag
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
     * Build a new Twitter Card Tag.
     *
     * @param string $name
     * @param string $content
     */
    public function __construct($name, $content)
    {
        // HtmlObject does not escape attribute values.
        $this->setAttributes([
            'name' => 'twitter:' . h($name),
            'content' => h($content),
        ]);
    }

    /**
     * Static alias for constructor.
     */
    public static function create($name, $content)
    {
        return new static($name, $content);
    }
}
