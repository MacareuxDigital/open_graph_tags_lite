<?php
namespace Concrete\Package\OpenGraphTagsLite\Controller\SinglePage\Dashboard\OpenGraphTagsLite;

use Concrete\Core\Page\Controller\DashboardPageController;
use Package;
use File;

class Settings extends DashboardPageController
{
    public function updated()
    {
        $this->set('message', t('Settings saved.'));
        $this->view();
    }

    public function view()
    {
        $pkg = Package::getByHandle('open_graph_tags_lite');
        $fb_admin = $pkg->getConfig()->get('concrete.ogp.fb_admin_id');
        $fb_app_id = $pkg->getConfig()->get('concrete.ogp.fb_app_id');
        $thumbnailID = $pkg->getConfig()->get('concrete.ogp.og_thumbnail_id');
        $twitter_site = $pkg->getConfig()->get('concrete.ogp.twitter_site');
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

    public function save_settings()
    {
        if (!$this->token->validate('save_settings')) {
            $this->error->add($this->token->getErrorMessage());
        }

        if (!$this->error->has()) {
            $fb_admin = $this->post('fb_admin');
            $fb_app_id = $this->post('fb_app_id');
            $og_thumbnail_id = $this->post('og_thumbnail_id');
            $twitter_site = $this->post('twitter_site');
            $pkg = Package::getByHandle('open_graph_tags_lite');
            $pkg->getConfig()->save('concrete.ogp.fb_admin_id', $fb_admin);
            $pkg->getConfig()->save('concrete.ogp.fb_app_id', $fb_app_id);
            $pkg->getConfig()->save('concrete.ogp.og_thumbnail_id', $og_thumbnail_id);
            $pkg->getConfig()->save('concrete.ogp.twitter_site', $twitter_site);
            $this->redirect('/dashboard/open_graph_tags_lite/settings', 'updated');
        }
    }
}
