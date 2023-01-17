<?php

$_SESSION['lastOpened'] = 'posts';
?>

<div class="container-fluid">
    <div class="row">
        <?php
        include "marker_cards.php";
        include "event_cards.php";
        ?>
    </div>
</div>

<script>
    updateStars();
    setupStarListeners();
    checkHasMarkers();
</script>