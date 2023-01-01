<?php
/** @var Array $data */

use App\Models\Marker;

/** @var Marker $marker */
$marker = $data['marker'];
?>

<div class="container">
    <div class="row>">
        <div class="col text-white mt-3">
            <h3>Pridanie/úprava lokality</h3>
            <form action="?c=markers&a=store" method="post" enctype="multipart/form-data">
                <?php if ($marker->getId()) { ?>
                    <input type="hidden" value="<?= $marker->getId() ?>" name="id">
                <?php } ?>
                <div class="mb-3">
                    <label for="title" class="form-label">Názov lokality:</label>
                    <input required autofocus maxlength="50" type="text" class="form-control" id="title" name="title"
                           value="<?= $marker->getTitle() ?>">
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Popis:</label>
                    <textarea maxlength="450" class="form-control" id="description" name="description"
                              style="height:100px"
                              onkeyup="countChars('description','charcount');"
                              onkeydown="countChars('description','charcount');"
                              onmouseout="countChars('description','charcount');"><?= $marker->getDescription() ?></textarea>
                    <span id="charcount">0</span>/450 znakov
                </div>
                <div class="mb-3">
                    <label for="m_color" class="form-label">Farba:</label>
                    <select name="m_color" id="m_color">
                        <option value="red">Červená</option>
                        <option value="pink">Ružová</option>
                        <option value="orange">Oranžová</option>
                        <option value="green">Zelená</option>
                        <option value="darkGreen">Tmavo zelená</option>
                        <option value="blue">Modrá</option>
                        <option value="darkBlue">Tmavo modrá</option>
                        <option value="black">Čierna</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="lat" class="form-label">Zemepisná šírka (latitude):</label>
                    <input required type="number" step="any" min="-90" max="90" class="form-control" id="lat" name="lat"
                           value="<?= $marker->getLat() ?>">
                </div>
                <div class="mb-3">
                    <label for="long" class="form-label">Zemepisná dĺžka (longitude):</label>
                    <input required type="number" step="any" min="-180" max="180" class="form-control" id="long"
                           name="long" value="<?= $marker->getLong() ?>">
                </div>
                <?php if ($marker->getPhoto()) { ?>
                    <div class="mb-3" id="image-delete">
                        <p>Aktuálny obrázok: <?= $marker->getPhoto() ?>
                            <button type="button" onclick="deleteImage('markers' ,<?= $marker->getId() ?>)" class="delete-x">
                                <span class="text-danger">&times;</span>
                            </button>
                        </p>
                    </div>
                <?php } ?>
                <div class="mb-3">
                    <label for="photo" class="form-label">Obrázok:</label>
                    <input class="form-control" type="file" id="photo" name="photo"">
                </div>
                <button type="submit" class="btn btn-success">Potvrdiť</button>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    countChars('description', 'charcount');
    setDefaultOption('<?= $marker->getColor() ?>');
</script>