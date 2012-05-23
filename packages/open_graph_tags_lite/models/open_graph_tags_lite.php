<?php
defined('C5_EXECUTE') or die("Access Denied.");

class OpenGraphTagsLite {
	
	public function insertTags( $view ) {
		$co = new Config();
		$co->setPackageObject(Package::getByHandle('open_graph_tags_lite'));
		$fb_admin = $co->get('FB_ADMIN_ID');
		$fb_app_id = $co->get('FB_APP_ID');
		$thumbnailID = $co->get('OG_THUMBNAIL_ID');
		
		$page = Page::getCurrentPage();
		$navigation = Loader::helper("navigation");
		$pageTitle = $page->getCollectionName();
		$pageDescription = $page->getCollectionDescription();
		$pageMetaTitle = $page->getCollectionAttributeValue('meta_title');
		$pageMetaDescription = $page->getCollectionAttributeValue('meta_description');
		if ( $pageMetaDescription ) $pageDescription = $pageMetaDescription;
		if ( $pageMetaTitle ) $pageTitle = $pageMetaTitle;
		$pageOgTitle =  $page->getCollectionAttributeValue('og_title');
		if ( $pageOgTitle ) $pageTitle = $pageOgTitle;
		$pageOgType = $page->getCollectionAttributeValue('og_type');
		if ( !$pageOgType ) {
			if ( $page->getCollectionID() == HOME_CID ){
				$pageOgType = 'website';
			} else {
				$pageOgType = 'article';
			}
		}

		Controller::addHeaderItem('<meta property="og:title" content="' . htmlspecialchars($pageTitle, ENT_COMPAT, APP_CHARSET) . '" />');
		Controller::addHeaderItem('<meta property="og:description" content="' . htmlspecialchars($pageDescription, ENT_COMPAT, APP_CHARSET) . '" />');
		Controller::addHeaderItem('<meta property="og:type" content="' .  $pageOgType . '" />');
		Controller::addHeaderItem('<meta property="og:url" content="' . BASE_URL . $navigation->getLinkToCollection($page) . '" />');
		if ( $page->getAttribute('og_image') ) {
			Controller::addHeaderItem('<meta property="og:image" content="' .  BASE_URL . $page->getAttribute('og_image')->getVersion()->getRelativePath() . '" />');
		} else if ( $page->getAttribute('page_thumbnail') ) {
			Controller::addHeaderItem('<meta property="og:image" content="' .  BASE_URL . $page->getAttribute('page_thumbnail')->getVersion()->getRelativePath() . '" />');
		} else if ( $thumbnailID ) {
			$f = File::getByID($thumbnailID);
			Controller::addHeaderItem('<meta property="og:image" content="' .  BASE_URL . $f->getRelativePath() . '" />');
		}
		if ( $page->getCollectionID() != HOME_CID )
			Controller::addHeaderItem('<meta property="og:site_name" content="' .  SITE . '" />');
		if ( $fb_admin ) 
			Controller::addHeaderItem('<meta property="fb:admins" content="' . $fb_admin . '" />');
		if ( $fb_app_id ) 
			Controller::addHeaderItem('<meta property="fb:app_id" content="' . $fb_app_id . '" />');
	}
	
}