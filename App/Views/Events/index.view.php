<?php

use App\Core\IAuthenticator;
use App\Models\Event;

/** @var Event[] $data['events'] */
/** @var String[] $data['locations'] */
/** @var IAuthenticator $auth */
?>

<div class="container-fluid">
    <?php if ($auth->isLogged()) { ?>
        <div class="row mt-3">
            <div class="col">
                <a href="?c=events&a=create" class="btn btn-success w-100">Pridať novú udalosť</a>
            </div>
        </div>
    <?php } ?>
    <div class="row">
        <?php
        include "App/Views/Events/event_cards.php";
        ?>
    </div>
</div>