<?php
namespace Concrete\Package\OpenGraphTagsLite\Controller\SinglePage\Dashboard;

use Concrete\Core\Page\Controller\DashboardPageController;

class OpenGraphTagsLite extends DashboardPageController
{
    public function view()
    {
        return $this->buildRedirectToFirstAccessibleChildPage();
    }
}
