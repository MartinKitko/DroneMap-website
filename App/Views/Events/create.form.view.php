<?php
/** @var Array $data */

use App\Models\Event;

/** @var Event $event */
$event = $data['event'];
$markers = $data['markers'];
?>

<div class="container">
    <div class="row>">
        <div class="col text-white mt-3">
            <h3>Pridanie/úprava udalosti</h3>
            <form action="?c=events&a=store" method="post" enctype="multipart/form-data">
                <?php if ($event->getId()) { ?>
                    <input type="hidden" value="<?= $event->getId() ?>" name="id">
                <?php } ?>
                <div class="mb-3">
                    <label for="title" class="form-label">Názov udalosti:</label>
                    <input required autofocus maxlength="50" type="text" class="form-control" id="title" name="title"
                           value="<?= $event->getTitle() ?>">
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Popis:</label>
                    <textarea class="form-control" id="description" name="description"
                              style="height:100px" ><?= $event->getDescription() ?></textarea>
                </div>
                <div class="mb-3">
                    <label for="marker-id" class="form-label">Lokalita:</label>
                    <select required name="marker-id" id="marker-id">
                        <?php if ($event->getMarkerId() == 0) { ?>
                            <option disabled selected value> -- vyberte lokalitu -- </option>
                        <?php } else { ?>
                            <option disabled  value> -- vyberte lokalitu -- </option>
                        <?php }
                        foreach ($markers as $marker) {
                            $selected = '';
                            if ($marker->getId() == $event->getMarkerId()) {
                                $selected = ' selected';
                            }
                            echo '<option value="' . $marker->getId() . '"' . $selected . '>' . $marker->getTitle() . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div class="row mb-3">
                    <div class="col">
                        <label for="date-from">Dátum a čas konania udalosti:</label>
                        <input type="datetime-local" id="date-from"
                               name="date-from" value="<?= $event->getDateFrom() ?>"
                               min="2023-01-01T00:00" max="2030-01-01T00:00"
                               required>
                    </div>
                    <div class="col">
                        <label for="date-to">Dátum a čas konca udalosti (nepovinné):</label>
                        <input type="datetime-local" id="date-to"
                               name="date-to" value="<?= $event->getDateTo() ?>"
                               min="2023-01-01T00:00" max="2030-01-01T00:00">
                    </div>
                </div>
                <?php if ($event->getPhoto()) { ?>
                    <div class="mb-3" id="image-delete">
                        <p>Aktuálny obrázok: <?= $event->getPhoto() ?>
                            <button type="button" onclick="deleteImage('events' ,<?= $event->getId() ?>)" class="delete-x">
                                <span class="text-danger">&times;</span>
                            </button>
                        </p>
                    </div>
                <?php } ?>
                <div class="mb-3">
                    <label for="photo" class="form-label">Obrázok:</label>
                    <input class="form-control" type="file" id="photo" name="photo">
                </div>
                <button type="submit" class="btn btn-success">Potvrdiť</button>
            </form>
        </div>
    </div>
</div>