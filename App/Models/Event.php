<?php

namespace App\Models;

use App\Core\Model;
use DateTime;

class Event extends Model
{
    protected ?int $id = 0;
    protected ?int $marker_id = 0;
    protected ?int $author_id = 0;
    protected ?string $title = "";
    protected ?string $description = "";
    protected ?string $date_from = null;
    protected ?string $date_to = null;
    protected ?string $photo = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int|null
     */
    public function getMarkerId(): ?int
    {
        return $this->marker_id;
    }

    /**
     * @param int|null $marker_id
     */
    public function setMarkerId(?int $marker_id): void
    {
        $this->marker_id = $marker_id;
    }

    /**
     * @return int|null
     */
    public function getAuthorId(): ?int
    {
        return $this->author_id;
    }

    /**
     * @param int|null $author_id
     */
    public function setAuthorId(?int $author_id): void
    {
        $this->author_id = $author_id;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     */
    public function setTitle(?string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return string|null
     */
    public function getDateFrom(): ?string
    {
        return $this->date_from;
    }

    /**
     * @param string|null $date_from
     */
    public function setDateFrom(?string $date_from): void
    {
        $this->date_from = $date_from;
    }

    /**
     * @return string|null
     */
    public function getDateTo(): ?string
    {
        return $this->date_to;
    }

    /**
     * @param string|null $date_to
     */
    public function setDateTo(?string $date_to): void
    {
        $this->date_to = $date_to;
    }

    /**
     * @return string|null
     */
    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    /**
     * @param string|null $photo
     */
    public function setPhoto(?string $photo): void
    {
        $this->photo = $photo;
    }
}