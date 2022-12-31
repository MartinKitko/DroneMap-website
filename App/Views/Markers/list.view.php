<?php

use App\Core\IAuthenticator;
use App\Models\Marker;

/** @var Marker[] $data */
/** @var IAuthenticator $auth */
?>

<div class="container-fluid">
    <?php if ($auth->isLogged()) { ?>
        <div class="row">
            <div class="col">
                <a href="?c=markers&a=create" class="btn btn-success">Pridať nový bod</a>
            </div>
        </div>
    <?php } ?>
    <div class="row">
        <?php foreach ($data as $marker) { ?>
            <div class="col-xl-3 col-md-4 col-sm-6 my-3">
                <div class="card h-100">
                    <?php if ($marker->getPhoto()) { ?>
                        <img src="<?= $marker->getPhoto() ?>" class="card-img-top" alt="...">
                    <?php } ?>
                    <div class="card-body">
                        <h5 class="card-title">
                            <?= $marker->getTitle() ?>
                        </h5>
                        <p class="card-text">
                            <?= $marker->getDescription() ?>
                        </p>
                    </div>
                    <?php if ($auth->isLogged()) { ?>
                        <div class="row">
                            <p id="marker-rating" class="col">
                                <span data-m-id="<?= $marker->getId() ?>"
                                      class="rating-number"><?= $marker->getRating() ?></span>
                                <span class="rating-star">★</span></p>
                            <div class="col rating">
                                <p id="star5" class="star" data-marker-id="<?= $marker->getId() ?>">☆</p>
                                <p id="star4" class="star" data-marker-id="<?= $marker->getId() ?>">☆</p>
                                <p id="star3" class="star" data-marker-id="<?= $marker->getId() ?>">☆</p>
                                <p id="star2" class="star" data-marker-id="<?= $marker->getId() ?>">☆</p>
                                <p id="star1" class="star" data-marker-id="<?= $marker->getId() ?>">☆</p>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="?c=markers&a=edit&id=<?= $marker->getId() ?>" class="btn btn-warning">Upraviť</a>
                            <a href="?c=markers&a=delete&id=<?= $marker->getId() ?>" class="btn btn-danger">Zmazať</a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<script>
    updateStars();
    setupStarListeners();
</script>