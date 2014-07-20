<?php
defined('C5_EXECUTE') or die(_("Access Denied."));

class OpenGraphTagsLitePackage extends Package {

	protected $pkgHandle = 'open_graph_tags_lite';
	protected $appVersionRequired = '5.5.0';
	protected $pkgVersion = '1.4';
	
	public function getPackageDescription() {
		return t("Auto insert Open Graph Tags (OGP) into HEAD tag");
	}
	
	public function getPackageName() {
		return t("Open Graph Tags Lite");
	}
	
	public function install() {
		$pkg = parent::install();
		
		//Install dashboard page
		Loader::model('single_page');
		$sp = SinglePage::add('/dashboard/open_graph_tags_lite', $pkg);
		if (is_object($sp)) {
			$sp->update(array('cName'=>t('Open Graph Tags Lite'), 'cDescription'=>t('Auto insert Open Graph Tags (OGP) into HEAD tag')));
		}
		$sp = SinglePage::add('/dashboard/open_graph_tags_lite/settings', $pkg);
		if (is_object($sp)) {
			$sp->update(array('cName'=>t('Open Graph Tags Settings'), 'cDescription'=>''));
			$this->_setupDashboardIcons($sp, 'icon-thumbs-up');
		}
	}
	
	public function upgrade(){
		$pkg = Package::getByHandle($this->pkgHandle);
		
		//Add dashboard page
		$sp = Page::getByPath('/dashboard/open_graph_tags_lite/settings');
		if (!$sp || !is_object($sp) || !$sp->getCollectionID()) {
			$sp = SinglePage::add('/dashboard/open_graph_tags_lite/settings', $pkg);
			if (is_object($sp)) {
				$sp->update(array('cName'=>t('Open Graph Tags Settings'), 'cDescription'=>''));
				$this->_setupDashboardIcons($sp, 'icon-thumbs-up');
			}
		}
		
		parent::upgrade();
	}
	
	public function on_start() {
		Events::extend('on_start', 'OpenGraphTagsLiteHelper', 'insertTags', 'packages/open_graph_tags_lite/helpers/open_graph_tags_lite.php');
	}
	
	private function _setupDashboardIcons($sp,$icon) {
		$cak = CollectionAttributeKey::getByHandle('icon_dashboard');
		if (is_object($cak)) {
			$sp->setAttribute('icon_dashboard', $icon);
		}
	}

}