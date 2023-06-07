<?php

namespace App\Sys\Controller;

use App\Common\Response\Response;

class DashboardController extends AdminBaseController
{
    /**
     * 首页数据
     */
    public function index()
    {
        Response::instance()->successJson([]);
    }
}
