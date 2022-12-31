<?php

namespace App\Models;

use App\Core\Model;

class Rating extends Model
{
    protected ?int $id = 0;
    protected ?int $marker_id = 0;
    protected ?int $user_id = 0;
    protected ?int $rating = 0;

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
    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    /**
     * @param int|null $user_id
     */
    public function setUserId(?int $user_id): void
    {
        $this->user_id = $user_id;
    }

    /**
     * @return int|null
     */
    public function getRating(): ?int
    {
        return $this->rating;
    }

    /**
     * @param int|null $rating
     */
    public function setRating(?int $rating): void
    {
        $this->rating = $rating;
    }
}