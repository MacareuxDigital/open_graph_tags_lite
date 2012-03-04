<?php

class OpenGraphTagsLite {
	
	public function insertTags( $view ) {
		$co = new Config();
		$co->setPackageObject(Package::getByHandle('open_graph_tags_lite'));
		$fb_admin = $co->get('FB_ADMIN_ID');
		$fb_app_id = $co->get('FB_APP_ID');
		$thumbnailID = $co->get('OG_THUMBNAIL_ID');
		
		$page = Page::getCurrentPage();
		$pageTitle = $page->getCollectionName();
		$pageMetaTitle = $page->getCollectionAttributeValue('meta_title');
		if ( $pageMetaTitle ) $pageTitle = $pageMetaTitle;
		$pageOgTitle =  $page->getCollectionAttributeValue('og_title');
		if ( $pageOgTitle ) $pageTitle = $pageOgTitle;
		$pageOgType = $page->getCollectionAttributeValue('og_type');
		if ( !$pageOgType ) $pageOgType = 'article';

		Controller::addHeaderItem('<meta property="og:title" content="' . htmlspecialchars($pageTitle, ENT_COMPAT, APP_CHARSET) . '" />');
		Controller::addHeaderItem('<meta property="og:type" content="' .  $pageOgType . '" />');
		Controller::addHeaderItem('<meta property="og:url" content="' . BASE_URL . DIR_REL . $page->getCollectionPath() . '" />');
		if ( $page->getAttribute('og_image') ) {
			Controller::addHeaderItem('<meta property="og:image" content="' .  BASE_URL . DIR_REL . $page->getAttribute('og_image')->getVersion()->getRelativePath() . '" />');
		} else if ( $page->getAttribute('page_thumbnail') ) {
			Controller::addHeaderItem('<meta property="og:image" content="' .  BASE_URL . DIR_REL . $page->getAttribute('page_thumbnail')->getVersion()->getRelativePath() . '" />');
		} else if ( $thumbnailID ) {
			$f = File::getByID($thumbnailID);
			Controller::addHeaderItem('<meta property="og:image" content="' .  BASE_URL . $f->getRelativePath() . '" />');
		}
		Controller::addHeaderItem('<meta property="og:site_name" content="' .  SITE . '" />');
		if ( $fb_admin ) 
			Controller::addHeaderItem('<meta property="fb:admins" content="' . $fb_admin . '" />');
		if ( $fb_app_id ) 
			Controller::addHeaderItem('<meta property="fb:app_id" content="' . $fb_app_id . '" />');
	}
	
}