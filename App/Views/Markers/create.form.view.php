<?php
/** @var Array $data */
use App\Models\Marker;

/** @var Marker $marker */
$marker = $data['marker'];
?>
<script src="public/js/script.js"></script>
<div class="container">
    <div class="row>">
        <div class="col text-white">
            <h3 >Pridanie/úprava bodu</h3>
            <form action="?c=markers&a=store" method="post">
                <?php if ($marker->getId()) { ?>
                    <input type="hidden" value="<?= $marker->getId() ?>" name="id">
                <?php } ?>
                <div class="mb-3">
                    <label for="title" class="form-label">Názov bodu:</label>
                    <input required maxlength="50" type="text" class="form-control" id="title" name="title" value="<?= $marker->getTitle() ?>">
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Popis:</label>
                    <textarea maxlength="450" class="form-control" id="description" name="description" style="height:100px"
                              onkeyup="countChars('description','charcount');" onkeydown="countChars('description','charcount');"
                              onmouseout="countChars('description','charcount');" onload="countChars('description','charcount');"><?= $marker->getDescription() ?></textarea>
                    <span id="charcount">0</span>/450 znakov
                </div>
                <div class="mb-3">
                    <label for="lat" class="form-label">Zemepisná šírka (latitude):</label>
                    <input required type="text" class="form-control" id="lat" name="lat" value="<?= $marker->getLat() ?>">
                </div>
                <div class="mb-3">
                    <label for="long" class="form-label">Zemepisná dĺžka (longitude):</label>
                    <input required type="text" class="form-control" id="long" name="long" value="<?= $marker->getLong() ?>">
                </div>
                <button type="submit" class="btn btn-success">Potvrdiť</button>
            </form>
        </div>
    </div>
</div>
