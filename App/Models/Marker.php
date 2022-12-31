<?php

namespace App\Models;

use App\Core\Model;
use App\Models\Rating;

class Marker extends Model
{
    protected ?int $id = 0;
    protected ?string $title = "";
    protected ?string $description = "";
    protected ?float $lat = 0.0;
    protected ?float $long = 0.0;
    protected ?string $m_color = "";
    protected ?string $photo = null;

    /**
     * @return string|null
     */
    public function getColor(): ?string
    {
        return $this->m_color;
    }

    /**
     * @param string|null $color
     */
    public function setColor(?string $color): void
    {
        $this->m_color = $color;
    }

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
     * @return float|null
     */
    public function getLat(): ?float
    {
        return $this->lat;
    }

    /**
     * @param float|null $lat
     */
    public function setLat(?float $lat): void
    {
        $this->lat = $lat;
    }

    /**
     * @return float|null
     */
    public function getLong(): ?float
    {
        return $this->long;
    }

    /**
     * @param float|null $long
     */
    public function setLong(?float $long): void
    {
        $this->long = $long;
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

    /**
     * Vypocita priemerne hodnotenie pre dany prispevok
     * @return float
     */
    public function getRating() : float
    {
        $ratings = Rating::getAll("marker_id = ?", [$this->id]);
        $totalRating = 0;
        $numRatings = count($ratings);

        for ($i = 0; $i < $numRatings; $i++) {
            $totalRating += $ratings[$i]->getRating();
        }

        if ($numRatings > 0) {
            return round($totalRating / $numRatings, 1);
        } else {
            return 0;
        }
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function delete()
    {
        Model::getConnection()->beginTransaction();
        parent::delete();
        Model::getConnection()->commit();
    }
}