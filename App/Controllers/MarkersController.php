<?php

namespace App\Controllers;

use App\Core\AControllerBase;
use App\Core\Responses\Response;
use App\Models\Marker;
use Exception;

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
        try {
            $lat = $this->request()->getValue("lat");
            if (!is_numeric($lat)) {
                throw new Exception("Chyba: lat musi byt cislo");
            }
            if ($lat < -90 || $lat > 90) {
                throw new Exception("Chyba: lat musi byt cislo od -90 do 90 ");
            }
            $long = $this->request()->getValue("long");
            if (!is_numeric($long)) {
                throw new Exception("Chyba: long musi byt cislo");
            }
            if ($long < -180 || $long > 180) {
                throw new Exception("Chyba: long musi byt cislo od -180 do 180 ");
            }
            $title = $this->request()->getValue("title");
            if (!is_string($title)) {
                throw new Exception("Chyba: nazov bodu musi byt string");
            }
            if (mb_strlen($title, "UTF-8") > 50) {
                throw new Exception("Chyba: nazov bodu moze mat maximalne 50 znakov");
            }
            $description = $this->request()->getValue("description");
            if (!is_string($description)) {
                throw new Exception("Chyba: popis musi byt string");
            }
            if (mb_strlen($description, "UTF-8") > 450) {
                throw new Exception("Chyba: popis bodu moze mat maximalne 450 znakov");
            }
            $color = $this->request()->getValue("m_color");

            $id = $this->request()->getValue('id');
            $marker = ($id ? Marker::getOne($id) : new Marker());
            $oldPhoto = $marker->getPhoto();

            $marker->setTitle($title);
            $marker->setDescription(trim(preg_replace('/\s\s+/', ' ', $description)));
            $marker->setLat($lat);
            $marker->setLong($long);
            $marker->setColor($color);
            $marker->setPhoto($this->processUploadedFile($marker));
            if (!is_null($oldPhoto) && is_null($marker->getPhoto())) {
                unlink($oldPhoto);
            }
            $marker->save();

            return $this->redirect("?c=markers");
        } catch (Exception $e) {
            $_SESSION['errorOccurred'] = true;
            $_SESSION['errorMessage'] = $e->getMessage();
            return $this->redirect("?c=markers&a=create");
        }
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
