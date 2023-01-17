<?php

$_SESSION['lastOpened'] = 'posts';
?>

<div class="container-fluid">
    <div class="row">
        <?php
        include "App/Views/Markers/marker_cards.php";
        include "App/Views/Events/event_cards.php";
        ?>
    </div>
</div>

<script>
    updateStars();
    setupStarListeners();
    checkHasMarkers();
</script>