<?php

namespace src\controllers;

use \core\Controller as ctrl;

class AssinaturasController extends ctrl
{
    public function index()
    {
        $this->render(\'assinaturas\');
    }
}
