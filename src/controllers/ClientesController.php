<?php

namespace src\controllers;

use \core\Controller as ctrl;

class ClientesController extends ctrl
{
    public function index()
    {
        $this->render(\'clientes\');
    }
}
