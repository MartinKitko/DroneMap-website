<?php

namespace App\Controllers;

use App\Core\AControllerBase;
use App\Core\Responses\Response;
use App\Models\Marker;

class MarkersController extends AControllerBase
{
    /**
     * @throws \Exception
     */
    public function index(): Response
    {
        $markers = Marker::getAll();
        return $this->html($markers);
    }

    /**
     * @throws \Exception
     */
    public function delete()
    {
        $marker = Marker::getOne($this->request()->getValue('id'));
        $marker?->delete();

        return $this->redirect('?c=markers');
    }

    /**
     * @return \App\Core\Responses\ViewResponse
     * @throws \Exception
     */
    public function edit()
    {
        return $this->html([
            'marker' => Marker::getOne($this->request()->getValue('id'))
        ],
            'create.form'
        );
    }

    /**
     * @return \App\Core\Responses\ViewResponse
     */
    public function create()
    {
        return $this->html([
            'marker' => new Marker()
        ],
            'create.form'
        );
    }

    /**
     * @return \App\Core\Responses\RedirectResponse
     * @throws \Exception
     */
    public function store()
    {
        $id = $this->request()->getValue('id');
        $marker = ($id ? Marker::getOne($id) : new Marker());

        $marker->setTitle($this->request()->getValue("title"));
        $marker->setDescription($this->request()->getValue("description"));
        $marker->setLat($this->request()->getValue("lat"));
        $marker->setLong($this->request()->getValue("long"));
        $marker->save();

        return $this->redirect("?c=markers");
    }
}
