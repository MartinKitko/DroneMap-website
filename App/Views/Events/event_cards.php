<?php

use App\Core\IAuthenticator;
use App\Models\Marker;

/** @var Marker[] $data */
/** @var IAuthenticator $auth */

foreach ($data['events'] as $event) {
    $location = $data['locations'][$event->getMarkerId()]; ?>
    <div class="col-xl-3 col-md-4 col-sm-6 my-3 markerCard" data-author-id="<?= $event->getAuthorId() ?>">
        <div class="card h-100">
            <?php if ($event->getPhoto()) { ?>
                <img src="<?= $event->getPhoto() ?>" class="card-img-top" alt="...">
            <?php } ?>
            <div class="card-body">
                <h5 class="card-title">
                    <?= $event->getTitle() ?>
                </h5>
                <p class="card-text mt-2">
                    <?= $event->getDescription() ?>
                </p>
                <p>Začiatok: <?= date("d. m. Y H:i", strtotime($event->getDateFrom())) ?><br>
                    <?php if ($event->getDateTo() != "") { ?>
                        Koniec: <?= date("d. m. Y H:i", strtotime($event->getDateto())) ?>
                    <?php } ?>
                </p>
                <p>Miesto konania: <a class="text-dark"
                                      href="?c=markers&lat=<?= $location->getLat() ?>&long=<?= $location->getLong() ?>"><?= $location->getTitle() ?></a>
                </p>
            </div>
            <?php if ($auth->isLogged() && $event->getAuthorId() == $auth->getLoggedUserId()) { ?>
                <div class="card-footer">
                    <a href="?c=events&a=edit&id=<?= $event->getId() ?>" class="btn btn-warning">Upraviť</a>
                    <a href="?c=events&a=delete&id=<?= $event->getId() ?>" class="btn btn-danger"
                       onclick="return confirm('Naozaj chcete odstrániť túto udalosť?');">Zmazať</a>
                </div>
            <?php } ?>
        </div>
    </div>
<?php } ?>