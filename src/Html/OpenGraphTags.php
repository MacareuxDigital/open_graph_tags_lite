<?php
namespace Concrete\Package\OpenGraphTagsLite\Src\Html;

use Concrete\Core\Entity\File\File as FileEntity;
use Concrete\Core\Entity\Site\Locale;
use Concrete\Core\File\File;
use Concrete\Core\Localization\Localization;
use Concrete\Core\Package\PackageService;
use Concrete\Core\Page\Page;
use Concrete\Core\Site\Service;
use Concrete\Core\Support\Facade\Application;
use Concrete\Package\OpenGraphTagsLite\Src\Html\Object\OpenGraph;
use Concrete\Package\OpenGraphTagsLite\Src\Html\Object\TwitterCard;

class OpenGraphTags
{
    protected $overriddenPageTitle = '';
    protected $overriddenPageDescription = '';
    /** @var FileEntity|null */
    protected $overriddenImageFile;

    public function getOverriddenImageFile(): ?FileEntity
    {
        return $this->overriddenImageFile;
    }

    public function setOverriddenImageFile(?FileEntity $overriddenImageFile): OpenGraphTags
    {
        $this->overriddenImageFile = $overriddenImageFile;
        return $this;
    }

    public function getOverriddenPageDescription(): string
    {
        return $this->overriddenPageDescription;
    }

    public function setOverriddenPageDescription(string $overriddenPageDescription): OpenGraphTags
    {
        $this->overriddenPageDescription = $overriddenPageDescription;
        return $this;
    }

    public function getOverriddenPageTitle(): string
    {
        return $this->overriddenPageTitle;
    }

    public function setOverriddenPageTitle(string $overriddenPageTitle): OpenGraphTags
    {
        $this->overriddenPageTitle = $overriddenPageTitle;
        return $this;
    }

    public function insertTags($event)
    {
        $v = $event->getArgument('view');
        if (!method_exists($v, 'getPageObject')) {
            return;
        }

        /** @var Page $page */
        $page = $v->getPageObject();

        if (!is_object($page) || $page->getError() == COLLECTION_NOT_FOUND || $page->isAdminArea()) {
            return;
        }

        $app = Application::getFacadeApplication();

        /** @var PackageService $packageService */
        $packageService = $app->make(PackageService::class);
        $pkg = $packageService->getByHandle('open_graph_tags_lite');
        $fb_admin = $pkg->getConfig()->get('concrete.ogp.fb_admin_id');
        $fb_app_id = $pkg->getConfig()->get('concrete.ogp.fb_app_id');
        $thumbnailID = $pkg->getConfig()->get('concrete.ogp.og_thumbnail_id');
        $twitter_site = $pkg->getConfig()->get('concrete.ogp.twitter_site');

        $pageTitle = $this->getOverriddenPageTitle();
        if (!$pageTitle) {
            $pageTitle = $page->getAttribute('og_title');
            if (!$pageTitle) {
                $pageTitle = $page->getAttribute('meta_title');
                if (!$pageTitle) {
                    $pageTitle = $page->getCollectionName();
                    if ($page->isSystemPage()) {
                        $pageTitle = t($pageTitle);
                    }
                }
            }
        }

        $pageDescription = $this->getOverriddenPageDescription();
        if (!$pageDescription) {
            $pageDescription = $page->getAttribute('meta_description');
            if (!$pageDescription) {
                $pageDescription = $page->getCollectionDescription();
            }
        }
        $pageDescription = $app->make('helper/text')->shortenTextWord($pageDescription, 200, '');

        $pageOgType = $page->getAttribute('og_type');
        if (!$pageOgType) {
            $pageOgType = 'website';
        }

        $og_image = $this->getOverriddenImageFile();
        if (!$og_image) {
            $og_image = $page->getAttribute('og_image');
            if (!is_object($og_image)) {
                $og_image = $page->getAttribute('thumbnail');
                if (!is_object($og_image) && !empty($thumbnailID)) {
                    $og_image = File::getByID($thumbnailID);
                }
            }
        }

        if (is_object($og_image)) {
            $og_image_width = $og_image->getAttribute('width');
            $og_image_height = $og_image->getAttribute('height');
            $og_image_url = $og_image->getURL();
        }

        $pageTwitterCard = $page->getAttribute('twitter_card');
        if (!$pageTwitterCard) {
            if (isset($og_image_width) && $og_image_width > 280) {
                $pageTwitterCard = 'summary_large_image';
            } else {
                $pageTwitterCard = 'summary';
            }
        }

        $v->addHeaderAsset((string) OpenGraph::create('og:title', $pageTitle));
        $v->addHeaderAsset((string) OpenGraph::create('og:description', $pageDescription));
        $v->addHeaderAsset((string) OpenGraph::create('og:type', $pageOgType));
        $v->addHeaderAsset((string) OpenGraph::create('og:url', $page->getCollectionLink(true)));
        if (isset($og_image_url) && isset($og_image_width) && isset($og_image_height)) {
            if ($og_image_width >= 200 && $og_image_height >= 200) {
                $v->addHeaderAsset((string) OpenGraph::create('og:image', $og_image_url));
                $v->addHeaderAsset((string) OpenGraph::create('og:image:width', $og_image_width));
                $v->addHeaderAsset((string) OpenGraph::create('og:image:height', $og_image_height));
            }
        }
        $site = $page->getSite();
        if (!$site) {
            /** @var Service $siteService */
            $siteService = $app->make(Service::class);
            $site = $siteService->getDefault();
        }
        $siteName = $site->getSiteName();
        if ($siteName) {
            $v->addHeaderAsset((string) OpenGraph::create('og:site_name', tc('SiteName', $siteName)));
        }
        if ($fb_admin) {
            $v->addHeaderAsset((string) OpenGraph::create('fb:admins', $fb_admin));
        }
        if ($fb_app_id) {
            $v->addHeaderAsset((string) OpenGraph::create('fb:app_id', $fb_app_id));
        }
        $v->addHeaderAsset((string) TwitterCard::create('card', $pageTwitterCard));
        if ($twitter_site) {
            if (substr($twitter_site, 0, 1) !== '@') {
                $twitter_site = '@' . $twitter_site;
            }
            $v->addHeaderAsset((string) TwitterCard::create('site', $twitter_site));
        }
        $v->addHeaderAsset((string) TwitterCard::create('title', $pageTitle));
        $v->addHeaderAsset((string) TwitterCard::create('description', $pageDescription));
        if (isset($og_image_url)) {
            $v->addHeaderAsset((string) TwitterCard::create('image', $og_image_url));
        }

        $siteTree = $page->getSiteTreeObject();
        if ($siteTree) {
            $siteLocale = $siteTree->getLocale();
            if ($siteLocale instanceof Locale) {
                $locale = $siteLocale->getLocale();
            }
        }
        if (!isset($locale)) {
            $locale = Localization::activeLocale();
        }
        $v->addHeaderAsset((string) OpenGraph::create('og:locale', $locale));

        /** @var \Concrete\Core\Localization\Service\Date $date */
        $date = $app->make('helper/date');
        $published = $date->toDateTime($page->getCollectionDatePublic());
        if ($published) {
            $v->addHeaderAsset((string) OpenGraph::create('article:published_time', $published->format(\DateTime::ATOM)));
        }
        $lastModified = $date->toDateTime($page->getCollectionDateLastModified());
        if ($lastModified) {
            $v->addHeaderAsset((string) OpenGraph::create('article:modified_time', $lastModified->format(\DateTime::ATOM)));
        }
    }
}
