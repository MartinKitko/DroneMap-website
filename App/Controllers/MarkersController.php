<?php

namespace App\Controllers;

use App\Core\AControllerBase;
use App\Core\Responses\Response;
use App\Models\Marker;

class MarkersController extends AControllerBase
{
    public function index(): Response
    {
        $markers = Marker::getAll();
        return $this->html($markers);
    }
}
