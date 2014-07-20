<?php
defined('C5_EXECUTE') or die("Access Denied.");

class OpenGraphTagsLiteHelper {
	
	public function insertTags( $view ) {
		$navigation = Loader::helper("navigation");
		$th = Loader::helper('text');
		
		$co = new Config();
		$co->setPackageObject(Package::getByHandle('open_graph_tags_lite'));
		$fb_admin = $co->get('FB_ADMIN_ID');
		$fb_app_id = $co->get('FB_APP_ID');
		$thumbnailID = $co->get('OG_THUMBNAIL_ID');
		$twitter_site = $co->get('TWITTER_SITE');
		
		$page = Page::getCurrentPage();
		
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
			$og_image_path = $og_image->getRelativePath(true);
		}

		$v = View::getInstance();
		$v->addHeaderItem('<meta property="og:title" content="' . $th->entities($pageTitle) . '" />');
		$v->addHeaderItem('<meta property="og:description" content="' . $th->entities($pageDescription) . '" />');
		$v->addHeaderItem('<meta property="og:type" content="' .  $th->entities($pageOgType) . '" />');
		$v->addHeaderItem('<meta property="og:url" content="' . $navigation->getLinkToCollection($page,true) . '" />');
		if ( isset($og_image_path) ) {
			$v->addHeaderItem('<meta property="og:image" content="' .  $og_image_path . '" />');
		}
		if ( $page->getCollectionID() != HOME_CID ) {
			$v->addHeaderItem('<meta property="og:site_name" content="' .  $th->entities(SITE) . '" />');
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
	}
	
}