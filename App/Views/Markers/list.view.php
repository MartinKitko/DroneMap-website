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
            <div>
                <div class="card my-3">

                    <div class="card-body">
                        <h5 class="card-title">
                            <?= $marker->getTitle() ?>
                        </h5>
                        <p class="card-text">
                            <?= $marker->getDescription() ?>
                        </p>
                        <?php if ($auth->isLogged()) { ?>
                            <a href="?c=markers&a=edit&id=<?= $marker->getId() ?>" class="btn btn-warning">Upraviť</a>
                            <a href="?c=markers&a=delete&id=<?= $marker->getId() ?>" class="btn btn-danger">Zmazať</a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
