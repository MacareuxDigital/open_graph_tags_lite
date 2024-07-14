<?php
namespace Concrete\Package\OpenGraphTagsLite\Controller\SinglePage\Dashboard\OpenGraphTagsLite;

use Concrete\Core\File\File;
use Concrete\Core\Page\Controller\DashboardSitePageController;
use Concrete\Package\OpenGraphTagsLite\Src\Config\ConfigService;

class Settings extends DashboardSitePageController
{
    public function updated()
    {
        $this->set('message', t('Settings saved.'));
        $this->view();
    }

    public function view()
    {
        /** @var ConfigService $configService */
        $configService = $this->app->make(ConfigService::class, ['site' => $this->site]);
        $fb_admin = $configService->get('fb_admin_id');
        $fb_app_id = $configService->get('fb_app_id');
        $thumbnailID = $configService->get('og_thumbnail_id');
        $twitter_site = $configService->get('twitter_site');
        $this->set('fb_admin', $fb_admin);
        $this->set('fb_app_id', $fb_app_id);
        $this->set('thumbnailID', $thumbnailID);
        $imageObject = false;
        if (!empty($thumbnailID)) {
            $imageObject = File::getByID($thumbnailID);
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

            /** @var ConfigService $configService */
            $configService = $this->app->make(ConfigService::class, ['site' => $this->site]);
            $configService->set('fb_admin_id', $fb_admin);
            $configService->set('fb_app_id', $fb_app_id);
            $configService->set('og_thumbnail_id', (int) $og_thumbnail_id);
            $configService->set('twitter_site', $twitter_site);

            return $this->buildRedirect('/dashboard/open_graph_tags_lite/settings/updated');
        }
    }
}
