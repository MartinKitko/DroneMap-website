<?php /** @var Array $data */ ?>

<div class="pics">
    <?php for ($i = 1; $i <= 20; $i++) { ?>
        <a class="card-s"><img src="public/images/gallery/dr<?=$i?>.jpg" alt="dr<?=$i?>"></a>
    <?php } ?>
</div>

<script>
    lightbox()
</script>