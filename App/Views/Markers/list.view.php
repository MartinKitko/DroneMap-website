<?php

use App\Core\IAuthenticator;

/** @var IAuthenticator $auth */
$_SESSION['lastOpened'] = 'list';
?>

<div class="container-fluid">
    <?php if ($auth->isLogged()) { ?>
        <div class="row mt-3">
            <div class="col">
                <a href="?c=markers&a=create" class="btn btn-success w-100">Pridať novú lokalitu</a>
            </div>
            <div class="col d-none" id="filter-author">
                <a href="javascript:filterAuthor(<?= $auth->getLoggedUserId() ?>)" id="filterBtn"
                   class="btn btn-secondary w-100">Zobraziť iba moje lokality</a>
            </div>
        </div>
    <?php } ?>
    <div class="row">
        <?php
        include "App/Views/Markers/marker_cards.php";
        ?>
    </div>
</div>

<script>
    updateStars();
    setupStarListeners();
    checkHasMarkers();
</script>