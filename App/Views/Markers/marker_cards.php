<?php

use App\Core\IAuthenticator;
use App\Models\Marker;

/** @var Marker[] $data */
/** @var IAuthenticator $auth */
foreach ($data['markers'] as $marker) { ?>
    <div class="col-xl-3 col-md-4 col-sm-6 my-3 markerCard" data-author-id="<?= $marker->getAuthorId() ?>">
        <div class="card h-100">
            <?php if ($marker->getPhoto()) { ?>
                <img src="<?= $marker->getPhoto() ?>" class="card-img-top" alt="...">
            <?php } ?>
            <div class="card-body">
                <h5 class="card-title">
                    <a class="text-dark"
                       href="?c=markers&lat=<?= $marker->getLat() ?>&long=<?= $marker->getLong() ?>"><?= $marker->getTitle() ?></a>
                </h5>
                <p class="card-text">
                    <?= $marker->getDescription() ?>
                </p>
            </div>
            <div class="row">
                <p class="col">
                                <span data-m-id="<?= $marker->getId() ?>"
                                      class="rating-number"><?= $marker->getRating() ?></span>
                    <span class="rating-star">★</span></p>
                <?php if ($auth->isLogged()) { ?>
                    <div class="col rating">
                        <p class="star star5" data-marker-id="<?= $marker->getId() ?>">☆</p>
                        <p class="star star4" data-marker-id="<?= $marker->getId() ?>">☆</p>
                        <p class="star star3" data-marker-id="<?= $marker->getId() ?>">☆</p>
                        <p class="star star2" data-marker-id="<?= $marker->getId() ?>">☆</p>
                        <p class="star star1" data-marker-id="<?= $marker->getId() ?>">☆</p>
                    </div>
                <?php } ?>
            </div>
            <?php if ($auth->isLogged() && $marker->getAuthorId() == $auth->getLoggedUserId()) { ?>
                <div class="card-footer">
                    <a href="?c=markers&a=edit&id=<?= $marker->getId() ?>" class="btn btn-warning">Upraviť</a>
                    <a href="?c=markers&a=delete&id=<?= $marker->getId() ?>" class="btn btn-danger"
                       onclick="return confirm('Naozaj chcete odstrániť túto lokalitu?');">Zmazať</a>
                </div>
            <?php } ?>
        </div>
    </div>
<?php }