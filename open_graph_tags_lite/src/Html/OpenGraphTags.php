<?php
namespace Concrete\Package\OpenGraphTagsLite\Html;

use Loader;
use Page;
use Package;
use File;
use View;
use Config;
use Localization;

class OpenGraphTags {
    
    public function insertTags( $view )
    {
        $navigation = Loader::helper("navigation");
        $th = Loader::helper('text');
        
        $page = Page::getCurrentPage();
        
        if (!is_object($page) || $page->getError() == COLLECTION_NOT_FOUND || $page->isAdminArea()) {
            return;
        }
        
        $pkg = Package::getByHandle('open_graph_tags_lite');
        $fb_admin = $pkg->getConfig()->get('open_graph_tags_lite.fb_admin_id');
        $fb_app_id = $pkg->getConfig()->get('open_graph_tags_lite.fb_app_id');
        $thumbnailID = $pkg->getConfig()->get('open_graph_tags_lite.og_thumbnail_id');
        $twitter_site = $pkg->getConfig()->get('open_graph_tags_lite.twitter_site');
        
        $pageTitle = $page->getCollectionAttributeValue('og_title');
        if (!$pageTitle) {
            $pageTitle = $page->getCollectionAttributeValue('meta_title');
            if (!$pageTitle) {
                $pageTitle = $page->getCollectionName();
                if($page->isSystemPage()) {
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
        if ( !$pageOgType ) {
            if ( $page->getCollectionID() == HOME_CID ){
                $pageOgType = 'website';
            } else {
                $pageOgType = 'article';
            }
        }
        
        $pageTwitterCard = $page->getCollectionAttributeValue('twitter_card');
        if ( !$pageTwitterCard ) {
            $pageTwitterCard = 'summary';
        }
        
        $og_image = $page->getAttribute('og_image');
        if (!$og_image instanceof File) {
            $og_image = $page->getAttribute('page_thumbnail');
            if (!$og_image instanceof File && !empty($thumbnailID)) {
                $og_image = File::getByID($thumbnailID);
            }
        }
        
        if ($og_image instanceof File && !$og_image->isError()) {
            $fv = $og_image->getApprovedVersion();
            $size = $fv->getFullSize();
            if ($size > 5000000) {
                $thumb = Loader::helper('image')->getThumbnail($og_image,1200,630,true);
                $og_image_width = $thumb->width;
                $og_image_height = $thumb->height;
                $og_image_url = BASE_URL . $thumb->src;
            } else {
                $og_image_width = $og_image->getAttribute('width');
                $og_image_height = $og_image->getAttribute('height');
                $og_image_url = BASE_URL . File::getRelativePathFromID($og_image->getFileID());
            }
        }

        $v = View::getInstance();
        $v->addHeaderItem('<meta property="og:title" content="' . $th->entities($pageTitle) . '" />');
        $v->addHeaderItem('<meta property="og:description" content="' . $th->entities($pageDescription) . '" />');
        $v->addHeaderItem('<meta property="og:type" content="' .  $th->entities($pageOgType) . '" />');
        $v->addHeaderItem('<meta property="og:url" content="' . $navigation->getLinkToCollection($page,true) . '" />');
        if ( isset($og_image_url) && isset($og_image_width) && isset($og_image_height) ) {
            $v->addHeaderItem('<meta property="og:image" content="' .  $og_image_url . '" />');
            $v->addHeaderItem('<meta property="og:image:width" content="' .  $og_image_width . '" />');
            $v->addHeaderItem('<meta property="og:image:height" content="' .  $og_image_height . '" />');
        }
        if ( $page->getCollectionID() != HOME_CID ) {
            $v->addHeaderItem('<meta property="og:site_name" content="' .  $th->entities(Config::get('concrete.site')) . '" />');
        }
        if ( $fb_admin ) {
            $v->addHeaderItem('<meta property="fb:admins" content="' . $th->entities($fb_admin) . '" />');
        }
        if ( $fb_app_id ) {
            $v->addHeaderItem('<meta property="fb:app_id" content="' . $th->entities($fb_app_id) . '" />');
        }
        if ( $twitter_site ) {
            $v->addHeaderItem('<meta name="twitter:card" content="' . $th->entities($pageTwitterCard) . '" />');
            $v->addHeaderItem('<meta name="twitter:site" content="@' . $th->entities($twitter_site) . '" />');
        }
        
        $localization = Localization::getInstance();
        $locale = $localization->getLocale();
        $v->addHeaderItem('<meta name="og:locale" content="' . $th->entities($locale) . '" />');
        
        $cv = $page->getVersionObject();
        if (is_object($cv)) {
            $lastModified = $cv->getVersionDateCreated();
            $lastModified = date(DATE_ATOM, strtotime($lastModified));
            $v->addHeaderItem('<meta name="og:updated_time" content="' . $th->entities($lastModified) . '" />');
        }
    }
    
}