<?php
namespace Concrete\Package\OpenGraphTagsLite\Src\Html;

use Loader;
use Page;
use Package;
use File;
use Config;
use Localization;
use Concrete\Package\OpenGraphTagsLite\Src\Html\Object\OpenGraph;

class OpenGraphTags
{
    public function insertTags($view)
    {
        $navigation = Loader::helper("navigation");
        $th = Loader::helper('text');

        $page = Page::getCurrentPage();
        $v = $page->getPageController()->getViewObject();

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
        $pageDescription = $th->shortenTextWord($pageDescription, 200, '');

        $pageOgType = $page->getCollectionAttributeValue('og_type');
        if (!$pageOgType) {
            if ($page->getCollectionID() == HOME_CID) {
                $pageOgType = 'website';
            } else {
                $pageOgType = 'article';
            }
        }

        $pageTwitterCard = $page->getCollectionAttributeValue('twitter_card');
        if (!$pageTwitterCard) {
            $pageTwitterCard = 'summary';
        }

        $og_image = $page->getAttribute('og_image');
        if (!$og_image instanceof File) {
            $og_image = $page->getAttribute('thumbnail');
            if (!$og_image instanceof File && !empty($thumbnailID)) {
                $og_image = File::getByID($thumbnailID);
            }
        }

        if ($og_image instanceof File && !$og_image->isError()) {
            $fv = $og_image->getApprovedVersion();
            $size = $fv->getFullSize();
            if ($size > 5000000) {
                $thumb = Loader::helper('image')->getThumbnail($og_image, 1200, 630, true);
                $og_image_width = $thumb->width;
                $og_image_height = $thumb->height;
                $og_image_url = BASE_URL . $thumb->src;
            } else {
                $og_image_width = $og_image->getAttribute('width');
                $og_image_height = $og_image->getAttribute('height');
                $og_image_url = BASE_URL . File::getRelativePathFromID($og_image->getFileID());
            }
        }

        $v->addHeaderAsset((string) OpenGraph::create('og:title', $pageTitle));
        $v->addHeaderAsset((string) OpenGraph::create('og:description', $pageDescription));
        $v->addHeaderAsset((string) OpenGraph::create('og:type', $pageOgType));
        $v->addHeaderAsset((string) OpenGraph::create('og:url', $page->getCollectionLink(true)));
        if (isset($og_image_url) && isset($og_image_width) && isset($og_image_height)) {
            $v->addHeaderAsset((string) OpenGraph::create('og:image', $og_image_url));
            $v->addHeaderAsset((string) OpenGraph::create('og:image:width', $og_image_width));
            $v->addHeaderAsset((string) OpenGraph::create('og:image:height', $og_image_height));
        }
        if ($page->getCollectionID() != HOME_CID) {
            $v->addHeaderAsset((string) OpenGraph::create('og:site_name', tc('SiteName', Config::get('concrete.site'))));
        }
        if ($fb_admin) {
            $v->addHeaderAsset((string) OpenGraph::create('fb:admins', $fb_admin));
        }
        if ($fb_app_id) {
            $v->addHeaderAsset((string) OpenGraph::create('fb:app_id', $fb_app_id));
        }
        if ($twitter_site) {
            $v->addHeaderAsset((string) OpenGraph::create('twitter:card', $pageTwitterCard));
            $v->addHeaderAsset((string) OpenGraph::create('twitter:site', $twitter_site));
        }

        $localization = Localization::getInstance();
        $locale = $localization->getLocale();
        $v->addHeaderAsset((string) OpenGraph::create('og:locale', $locale));

        $lastModified = $page->getCollectionDateLastModified();
        $lastModified = date(DATE_ATOM, strtotime($lastModified));
        $v->addHeaderAsset((string) OpenGraph::create('og:updated_time', $lastModified));
    }
}
