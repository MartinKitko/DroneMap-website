<?php

namespace App\Controllers;

use App\Core\AControllerBase;
use App\Core\Responses\Response;
use App\Models\Event;
use App\Models\Marker;
use DateTime;
use Exception;

class EventsController extends AControllerBase
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
        $events = Event::getAll();
        return $this->html($events);
    }

    /**
     * @throws Exception
     */
    public function list(): Response
    {
        $events = Event::getAll();
        return $this->html($events);
    }

    /**
     * @return \App\Core\Responses\RedirectResponse
     * @throws Exception
     */
    public function delete()
    {
        $event = Event::getOne($this->request()->getValue('id') * 1);
        if ($event->getAuthorId() != $this->app->getAuth()->getLoggedUserId()) {
            return $this->redirect('?c=events');
        }
        if ($event->getPhoto()) {
            unlink($event->getPhoto());
        }
        $event?->delete();

        return $this->redirect('?c=events');
    }

    /**
     * @return \App\Core\Responses\ViewResponse|\App\Core\Responses\RedirectResponse
     * @throws Exception
     */
    public function edit()
    {
        return $this->html([
            'event' => Event::getOne($this->request()->getValue('id') * 1), 'markers' => Marker::getAll()
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
            'event' => new Event(), 'markers' => Marker::getAll()
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
        $marker_id = $this->request()->getValue("marker-id");
        if (!is_numeric($marker_id) || $marker_id < 0) {
            throw new Exception("Chyba: id lokality musi byt nezaporne cislo");
        }
        $title = $this->request()->getValue("title");
        if (!is_string($title)) {
            throw new Exception("Chyba: nazov udalosti musi byt string");
        }
        if (mb_strlen($title, "UTF-8") > 50) {
            throw new Exception("Chyba: nazov udalosti moze mat maximalne 50 znakov");
        }
        $description = $this->request()->getValue("description");
        if (!is_string($description)) {
            throw new Exception("Chyba: popis udalosti musi byt string");
        }
        $date_from = $this->request()->getValue("date-from");
        $date_to = $this->request()->getValue("date-to");
        if (!is_string($date_from) || !is_string($date_to)) {
            throw new Exception("Chyba: datum musi byt typu string");
        }

        $id = $this->request()->getValue('id');
        $event = ($id ? Event::getOne($id * 1) : new Event());
        $oldPhoto = $event->getPhoto();

        $event->setMarkerId($marker_id);
        $event->setAuthorId($this->app->getAuth()->getLoggedUserId());
        $event->setTitle($title);
        $event->setDescription(trim(preg_replace('/\s\s+/', ' ', $description)));
        $event->setDateFrom($date_from);
        if ($date_to != "2023-01-01T12:00") {
            $event->setDateTo($date_to);
        }
        $event->setPhoto($this->processUploadedFile($event));
        if (!is_null($oldPhoto) && is_null($event->getPhoto())) {
            unlink($oldPhoto);
        }
        $event->save();

        return $this->redirect("?c=events");
    }

    /**
     * @param $event
     * @return string|null
     */
    private function processUploadedFile(Event $event): ?string
    {
        $photo = $this->request()->getFiles()['photo'];
        if (!is_null($photo) && $photo['error'] == UPLOAD_ERR_OK) {
            $targetFile = "public/photos/events/" . time() . "_" . $photo['name'];
            if (move_uploaded_file($photo["tmp_name"], $targetFile)) {
                if ($event->getId() && $event->getPhoto()) {
                    unlink($event->getPhoto());
                }
                return $targetFile;
            }
        }
        return null;
    }
}