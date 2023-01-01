<?php

namespace App\Controllers;

use App\Core\AControllerBase;
use App\Core\Responses\Response;
use App\Models\Marker;
use App\Models\Rating;
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
     * @throws Exception
     */
    public function index(): Response
    {
        $markers = Marker::getAll();
        return $this->html($markers);
    }

    /**
     * @throws Exception
     */
    public function list(): Response
    {
        $markers = Marker::getAll();
        return $this->html($markers);
    }

    /**
     * @return \App\Core\Responses\RedirectResponse
     * @throws Exception
     */
    public function delete()
    {
        $marker = Marker::getOne($this->request()->getValue('id') * 1);
        if ($marker->getAuthorId() != $this->app->getAuth()->getLoggedUserId()) {
            return $this->redirect('?c=markers');
        }
        if ($marker->getPhoto()) {
            unlink($marker->getPhoto());
        }
        $marker?->delete();

        return $this->redirect('?c=markers');
    }

    /**
     * @return \App\Core\Responses\ViewResponse|\App\Core\Responses\RedirectResponse
     * @throws Exception
     */
    public function edit()
    {
        return $this->html([
            'marker' => Marker::getOne($this->request()->getValue('id') * 1)
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
     * @throws Exception
     */
    public function store()
    {
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
        $marker = ($id ? Marker::getOne($id * 1) : new Marker());

        $marker->setAuthorId($this->app->getAuth()->getLoggedUserId());
        $marker->setTitle($title);
        $marker->setDescription(trim(preg_replace('/\s\s+/', ' ', $description)));
        $marker->setLat($lat);
        $marker->setLong($long);
        $marker->setColor($color);
        $newPhoto = $this->processUploadedFile($marker);
        if ($newPhoto != null) {
            $marker->setPhoto($newPhoto);
        }

        $marker->save();

        if ($_SESSION['lastOpened'] == 'list') {
            return $this->redirect("?c=markers&a=list");
        }
        return $this->redirect("?c=markers");
    }

    /**
     * @return \App\Core\Responses\JsonResponse
     * @throws Exception
     */
    public function rating(): Response
    {
        $id = $this->request()->getValue('id');
        $ratingNum = $this->request()->getValue('rating');
        if ($ratingNum < 1 || $ratingNum > 5) {
            throw new Exception("Chyba: zla hodnota hodnotenia");
        }
        $user_id = $this->app->getAuth()->getLoggedUserId();

        $found = Rating::getAll("marker_id = ? AND user_id = ?", [$id, $user_id]);
        if (count($found) > 0) {
            $rating = $found[0];
        } else {
            $rating = new Rating();
            $rating->setMarkerId($id);
            $rating->setUserId($user_id);
        }

        $marker = Marker::getOne($id * 1);

        if ($rating->getRating() == $ratingNum) {
            $rating?->delete();
            $newAvgRating = $marker->getRating();
            $data = ['deleted' => true, 'newAvgRating' => $newAvgRating];
        } else {
            $rating->setRating($ratingNum);
            $rating->save();
            $newAvgRating = $marker->getRating();
            $data = ['newAvgRating' => $newAvgRating];
        }

        return $this->json($data);
    }

    /**
     * @return \App\Core\Responses\JsonResponse
     * @throws Exception
     */
    public function getUserRatings(): Response
    {
        $user_id = $this->app->getAuth()->getLoggedUserId();
        $ratings = Rating::getAll("user_id = ?", [$user_id]);
        $data = ['ratings' => $ratings];
        return $this->json($data);
    }

    /**
     * @return \App\Core\Responses\JsonResponse
     * @throws Exception
     */
    public function getNumOfUsersMarkers(): Response
    {
        $user_id = $this->app->getAuth()->getLoggedUserId();
        $markersCount = count(Marker::getAll("author_id = ?", [$user_id]));
        $data = ['markersCount' => $markersCount];
        return $this->json($data);
    }

    /**
     * @return \App\Core\Responses\JsonResponse
     * @throws Exception
     */
    public function deletePhoto(): Response
    {
        $id = $this->request()->getValue('elementId');
        $marker = Marker::getOne($id);
        if ($marker->getAuthorId() != $this->app->getAuth()->getLoggedUserId()) {
            $data = ['successful' => false];
            return $this->json($data);
        }
        unlink($marker->getPhoto());
        $marker->setPhoto(null);
        $marker->save();

        $data = ['successful' => true];
        return $this->json($data);
    }

    /**
     * @param $marker
     * @return string|null
     */
    private function processUploadedFile(Marker $marker): ?string
    {
        $photo = $this->request()->getFiles()['photo'];
        if (!is_null($photo) && $photo['error'] == UPLOAD_ERR_OK) {
            $targetFile = "public/photos/" . time() . "_" . $photo['name'];
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
