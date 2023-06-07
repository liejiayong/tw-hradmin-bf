<?php
namespace App\Frontend\Controller;

use App\Common\Controller\AbstractController;

class BaseController extends AbstractController
{
    public function __construct()
    {
        parent::__construct();
        $this->UnAutoResponse();
    }

    public function display($view, $params = [])
    {
        $view_arr = str_replace('.', '/', $view);

        require(VIEW_DIR . '/' . $view_arr . '.html');
    }
}
