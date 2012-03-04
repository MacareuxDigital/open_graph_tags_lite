<?php
defined('C5_EXECUTE') or die(_("Access Denied."));

class OpenGraphTagsLitePackage extends Package {

	protected $pkgHandle = 'open_graph_tags_lite';
	protected $appVersionRequired = '5.5.0';
	protected $pkgVersion = '1.0';
	
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
		SinglePage::add('/dashboard/open_graph_tags_lite', $pkg);
	}
	
	public function on_start() {
		Events::extend('on_start', 'OpenGraphTagsLite', 'insertTags', './packages/open_graph_tags_lite/models/open_graph_tags_lite.php');
	}

}