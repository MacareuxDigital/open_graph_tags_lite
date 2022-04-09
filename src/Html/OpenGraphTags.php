<?php
namespace Concrete\Package\OpenGraphTagsLite\Src\Html;

use Concrete\Core\Page\Page;
use Package;
use File;
use Config;
use Localization;
use Concrete\Package\OpenGraphTagsLite\Src\Html\Object\OpenGraph;
use Concrete\Package\OpenGraphTagsLite\Src\Html\Object\TwitterCard;
use Core;

class OpenGraphTags
{
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

        $pkg = Package::getByHandle('open_graph_tags_lite');
        $fb_admin = $pkg->getConfig()->get('concrete.ogp.fb_admin_id');
        $fb_app_id = $pkg->getConfig()->get('concrete.ogp.fb_app_id');
        $thumbnailID = $pkg->getConfig()->get('concrete.ogp.og_thumbnail_id');
        $twitter_site = $pkg->getConfig()->get('concrete.ogp.twitter_site');

        $pageTitle = $page->getCollectionAttributeValue('og_title');
        if (!$pageTitle) {
            $pageTitle = $page->getCollectionAttributeValue('meta_title');
            if (!$pageTitle) {
                $pageTitle = $page->getCollectionName();
                if ($page->isSystemPage()) {
                    $pageTitle = t($pageTitle);
                }
            }
        }

        $pageDescription = $page->getCollectionAttributeValue('meta_description');
        if (!$pageDescription) {
            $pageDescription = $page->getCollectionDescription();
        }
        $pageDescription = Core::make('helper/text')->shortenTextWord($pageDescription, 200, '');

        $pageOgType = $page->getCollectionAttributeValue('og_type');
        if (!$pageOgType) {
            $pageOgType = 'website';
        }

        $og_image = $page->getAttribute('og_image');
        if (!is_object($og_image)) {
            $og_image = $page->getAttribute('thumbnail');
            if (!is_object($og_image) && !empty($thumbnailID)) {
                $og_image = File::getByID($thumbnailID);
            }
        }

        if (is_object($og_image) && !$og_image->isError()) {
            $og_image_width = $og_image->getAttribute('width');
            $og_image_height = $og_image->getAttribute('height');
            $og_image_url = $og_image->getURL();
        }

        $pageTwitterCard = $page->getCollectionAttributeValue('twitter_card');
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
        $siteName = Config::get('concrete.site');
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

        $locale = Localization::activeLocale();
        $v->addHeaderAsset((string) OpenGraph::create('og:locale', $locale));

        $published = date(DATE_ISO8601, strtotime($page->getCollectionDatePublic()));
        $v->addHeaderAsset((string) OpenGraph::create('article:published_time', $published));
        $lastModified = date(DATE_ISO8601, strtotime($page->getCollectionDateLastModified()));
        $v->addHeaderAsset((string) OpenGraph::create('article:modified_time', $lastModified));
    }
}
