<?php
namespace Concrete\Package\OpenGraphTagsLite\Controller\SinglePage\Dashboard;

use Concrete\Core\Page\Controller\DashboardPageController;

class OpenGraphTagsLite extends DashboardPageController
{
    public function view()
    {
        $this->redirect('/dashboard/open_graph_tags_lite/settings');
    }
}
