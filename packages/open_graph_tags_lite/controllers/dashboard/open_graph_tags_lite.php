<?php
defined('C5_EXECUTE') or die("Access Denied.");
class DashboardOpenGraphTagsLiteController extends DashboardBaseController {

	public function view() {
		$co = new Config();
		$co->setPackageObject(Package::getByHandle('open_graph_tags_lite'));
		$fb_admin = $co->get('FB_ADMIN_ID');
		$fb_app_id = $co->get('FB_APP_ID');
		$thumbnailID = $co->get('OG_THUMBNAIL_ID');
		$this->set('fb_admin', $fb_admin);
		$this->set('fb_app_id', $fb_app_id);
		$this->set('thumbnailID', $thumbnailID);
		$imageObject = false;
		if ($thumbnailID > 0) {
			$imageObject = File::getByID($thumbnailID);
			if (is_object($imageObject) && $imageObject->isError()) { 
				unset($imageObject);
			}
		}
		$this->set('imageObject', $imageObject);
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
				$co = new Config();
				$co->setPackageObject(Package::getByHandle('open_graph_tags_lite'));
				$co->save('FB_ADMIN_ID', $fb_admin);
				$co->save('FB_APP_ID', $fb_app_id);
				$co->save('OG_THUMBNAIL_ID', $og_thumbnail_id);
				$this->redirect('/dashboard/open_graph_tags_lite','updated');
			}
		} else {
			$this->set('error', array($this->token->getErrorMessage()));
		}
	}

}