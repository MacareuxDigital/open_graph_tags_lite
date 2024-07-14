<?php
namespace Concrete\Package\OpenGraphTagsLite;

use Concrete\Core\Events\EventDispatcher;
use Concrete\Core\Package\Package;
use Concrete\Core\Page\Single as SinglePage;
use Concrete\Core\Attribute\Key\CollectionKey as CollectionAttributeKey;
use Concrete\Core\Attribute\Type as AttributeType;
use Concrete\Package\OpenGraphTagsLite\Src\Html\OpenGraphTags;

class Controller extends Package
{
    protected $pkgHandle = 'open_graph_tags_lite';
    protected $appVersionRequired = '9.0.0';
    protected $pkgVersion = '3.0.2';
    protected $pkgAutoloaderRegistries = [
        'src' => '\Concrete\Package\OpenGraphTagsLite\Src',
    ];

    public function getPackageDescription()
    {
        return t('Auto insert Open Graph Tags (OGP) into HEAD tag');
    }

    public function getPackageName()
    {
        return t('Open Graph Tags Lite');
    }

    public function install()
    {
        $pkg = parent::install();

        //Add dashboard page
        $sp = SinglePage::add('/dashboard/open_graph_tags_lite', $pkg);
        if (is_object($sp)) {
            $sp->update(['cName' => t('Open Graph Tags Lite'), 'cDescription' => t('Auto insert Open Graph Tags (OGP) into HEAD tag')]);
        }
        $sp = SinglePage::add('/dashboard/open_graph_tags_lite/settings', $pkg);
        if (is_object($sp)) {
            $sp->update(['cName' => t('Open Graph Tags Settings'), 'cDescription' => '']);
        }

        //Add og:image attribute
        $cak = CollectionAttributeKey::getByHandle('og_image');
        if (!is_object($cak)) {
            $at = AttributeType::getByHandle('image_file');
            $category = $this->app->make('Concrete\Core\Attribute\Category\CategoryService')
                ->getByHandle('collection');
            $category->getController()->add($at, ['akHandle' => 'og_image', 'akName' => t('og:image')], $pkg);
        }

        return $pkg;
    }

    public function on_start()
    {
        $app = $this->app;
        $app->singleton(OpenGraphTags::class);
        /** @var EventDispatcher $dispatcher */
        $dispatcher = $this->app->make(EventDispatcher::class);
        $dispatcher->addListener('on_before_render', function ($event) use ($app) {
            $app->make(OpenGraphTags::class)->insertTags($event);
        });
    }
}
