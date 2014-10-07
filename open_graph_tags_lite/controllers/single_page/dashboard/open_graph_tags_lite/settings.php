<?php
namespace Concrete\Package\OpenGraphTagsLite\Controller\SinglePage\Dashboard\OpenGraphTagsLite;

use \Concrete\Core\Page\Controller\DashboardPageController;
use Config;
use Package;
use File;

class Settings extends DashboardPageController {

	public function view() {
		$pkg = Package::getByHandle('open_graph_tags_lite');
		$pkd_id = $pkg->getPackageID();
		$fb_admin = Config::get('concrete.open_graph_tags_lite.fb_admin_id',$pkd_id);
		$fb_app_id = Config::get('concrete.open_graph_tags_lite.fb_app_id',$pkd_id);
		$thumbnailID = Config::get('concrete.open_graph_tags_lite.og_thumbnail_id',$pkd_id);
		$twitter_site = Config::get('concrete.open_graph_tags_lite.twitter_site',$pkd_id);
		$this->set('fb_admin', $fb_admin);
		$this->set('fb_app_id', $fb_app_id);
		$this->set('thumbnailID', $thumbnailID);
		$imageObject = false;
		if (!empty($thumbnailID)) {
			$imageObject = File::getByID($thumbnailID);
			if (is_object($imageObject) && $imageObject->isError()) { 
				unset($imageObject);
			}
		}
		$this->set('imageObject', $imageObject);
		$this->set('twitter_site', $twitter_site);
	}

	public function updated() {
		$this->set('message', t("Settings saved."));	
		$this->view();
	}
	
	public function save_settings() {
		if ($this->token->validate("save_settings")) {
			if ($this->isPost()) {
				$fb_admin = $this->post('fb_admin');
				$fb_app_id = $this->post('fb_app_id');
				$og_thumbnail_id = $this->post('og_thumbnail_id');
				$twitter_site = $this->post('twitter_site');
				$pkg = Package::getByHandle('open_graph_tags_lite');
				$pkd_id = $pkg->getPackageID();
				Config::save('concrete.open_graph_tags_lite.fb_admin_id', $fb_admin, $pkd_id);
				Config::save('concrete.open_graph_tags_lite.fb_app_id', $fb_app_id, $pkd_id);
				Config::save('concrete.open_graph_tags_lite.og_thumbnail_id', $og_thumbnail_id, $pkd_id);
				Config::save('concrete.open_graph_tags_lite.twitter_site', $twitter_site, $pkd_id);
				$this->redirect('/dashboard/open_graph_tags_lite/settings','updated');
			}
		} else {
			$this->set('error', array($this->token->getErrorMessage()));
		}
	}

}