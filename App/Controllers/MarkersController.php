<?php

namespace App\Controllers;

use App\Core\AControllerBase;
use App\Core\Responses\Response;
use App\Models\Marker;

class MarkersController extends AControllerBase
{
    /**
     * @param $action
     * @return bool
     */
    public function authorize($action)
    {
        return match ($action) {
            "create", "store", "edit", "delete" => $this->app->getAuth()->isLogged(),
            default => true,
        };
    }

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
    public function list(): Response
    {
        $markers = Marker::getAll();
        return $this->html($markers);
    }

    /**
     * @return \App\Core\Responses\RedirectResponse
     * @throws \Exception
     */
    public function delete()
    {
        $marker = Marker::getOne($this->request()->getValue('id'));
        if ($marker->getPhoto()) {
            unlink($marker->getPhoto());
        }
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
        $oldPhoto = $marker->getPhoto();

        $marker->setTitle($this->request()->getValue("title"));
        $marker->setDescription(trim(preg_replace('/\s\s+/', ' ', $this->request()->getValue("description"))));
        $marker->setLat($this->request()->getValue("lat"));
        $marker->setLong($this->request()->getValue("long"));
        $marker->setColor($this->request()->getValue("m_color"));
        $marker->setPhoto($this->processUploadedFile($marker));
        if (!is_null($oldPhoto) && is_null($marker->getPhoto())) {
            unlink($oldPhoto);
        }
        $marker->save();

        return $this->redirect("?c=markers");
    }

    /**
     * @param $marker
     * @return string|null
     */
    private function processUploadedFile(Marker $marker)
    {
        $photo = $this->request()->getFiles()['photo'];
        if (!is_null($photo) && $photo['error'] == UPLOAD_ERR_OK) {
            $targetFile = "public" . DIRECTORY_SEPARATOR . "photos" . DIRECTORY_SEPARATOR . time() . "_" . $photo['name'];
            if (move_uploaded_file($photo["tmp_name"], $targetFile)) {
                if ($marker->getId() && $marker->getPhoto()) {
                    unlink($marker->getPhoto());
                }
                return $targetFile;
            }
        }
        return null;
    }
}
