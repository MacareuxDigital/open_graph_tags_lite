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
	}

	public function thumbnail_saved() {
		$this->set('message', t("Thumbnail updated successfully."));	
		$this->view();
	}

	public function thumbnail_removed() {
		$this->set('message', t("Thumbnail removed successfully."));	
		$this->view();
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
				$co = new Config();
				$co->setPackageObject(Package::getByHandle('open_graph_tags_lite'));
				$co->save('FB_ADMIN_ID', $fb_admin);
				$co->save('FB_APP_ID', $fb_app_id);
				$this->redirect('/dashboard/open_graph_tags_lite','updated');
			}
		} else {
			$this->set('error', array($this->token->getErrorMessage()));
		}
	}

	function update_thumbnail(){
		Loader::library('file/importer');
		if ($this->token->validate("update_thumbnail")) { 
			$co = new Config();
			$co->setPackageObject(Package::getByHandle('open_graph_tags_lite'));
		
			if(intval($this->post('remove_thumbnail'))==1){
				$co->save('OG_THUMBNAIL_ID',0);
				$this->redirect('/dashboard/open_graph_tags_lite', 'thumbnail_removed');
			} else {
				$fi = new FileImporter();
				$resp = $fi->import($_FILES['thumbnail_file']['tmp_name'], $_FILES['thumbnail_file']['name'], $fr);
				if (!($resp instanceof FileVersion)) {
					switch($resp) {
						case FileImporter::E_FILE_INVALID_EXTENSION:
							$this->error->add(t('Invalid file extension.'));
							break;
						case FileImporter::E_FILE_INVALID:
							$this->error->add(t('Invalid file.'));
							break;
						
					}
				} else {
					$co->save('OG_THUMBNAIL_ID',$resp->getFileID());
					$filepath=$resp->getPath();  
					$this->redirect('/dashboard/open_graph_tags_lite', 'thumbnail_saved');

				}
			}		
			
		}else{
			$this->set('error', array($this->token->getErrorMessage()));
		}
	}

}