<?php

use App\Core\IAuthenticator;
use App\Models\Marker;

/** @var Marker[] $data */
/** @var IAuthenticator $auth */

if ($auth->isLogged()) { ?>
<div class="row">
    <a href="?c=markers&a=create" class="btn btn-success">Pridať novú lokalitu</a>
</div>
<div class="top98" id="map">
    <?php } else { ?>
    <div class="top60" id="map">
        <?php } ?>
        <script>
            let $defaultLat = 48.7562;
            let $defaultLong = 18.5586;
            let $defaultZoom = 11.5;

            mapboxgl.accessToken = 'pk.eyJ1IjoibWFydGluLWtpdCIsImEiOiJjbGFta256ZnQwMHdwM3Zudmxyanc3OGRxIn0.frnxdqab00eiqutXB60fKQ';

            const map = new mapboxgl.Map({
                container: 'map',
                style: 'mapbox://styles/martin-kit/clamklbrs000714rckzalpmix',
                center: [$defaultLong, $defaultLat],
                zoom: $defaultZoom
            });

            map.addControl(new mapboxgl.FullscreenControl());
            map.addControl(new mapboxgl.NavigationControl());
            map.doubleClickZoom.disable();

            let element;
            <?php foreach ($data as $marker) { ?>
            element = document.createElement('div');
            element.className = 'marker';
            element.style.backgroundImage = `url(public/images/markers/marker-<?= $marker->getColor(); ?>.png)`;

            new mapboxgl.Marker(element, {anchor: 'bottom'})
                .setLngLat(['<?= $marker->getLong(); ?>', '<?= $marker->getLat(); ?>'])
                .setPopup(
                    new mapboxgl.Popup({offset: 25})
                        .setHTML(
                            `<h4><?= $marker->getTitle(); ?></h4>` +
                            <?php if ($marker->getPhoto()) { ?>
                            `<img src="<?= $marker->getPhoto() ?>" class="card-img-top" alt="...">` +
                            <?php } ?>
                            `<p class="mt-2" ><?= $marker->getDescription(); ?></p>` +
                            `<div class="row">` +
                            `<p id="marker-rating" class="col"><span data-m-id="<?= $marker->getId(); ?>" class="rating-number"><?= $marker->getRating(); ?></span> <span class="rating-star">★</span></p>` +
                            `</div>`
                            <?php if ($auth->isLogged() && $marker->getAuthorId() == $auth->getLoggedUserId()) { ?>
                            + `<a href="?c=markers&a=edit&id=<?= $marker->getId(); ?>" class="btn btn-warning m-1">Upraviť</a>` +
                            `<a href="?c=markers&a=delete&id=<?= $marker->getId(); ?>" class="btn btn-danger m-1" onclick="return confirm('Naozaj chcete odstrániť túto lokalitu?');">Zmazať</a>`
                            <?php } ?>
                        )
                )
                .addTo(map);
            <?php } ?>
            map.on('style.load', function () {
                map.on('dblclick', function (e) {
                    let lat = e.lngLat.lat;
                    let lng = e.lngLat.lng;
                    new mapboxgl.Popup()
                        .setLngLat(e.lngLat)
                        .setHTML('Súradnice miesta: <br/>' + lat + '<br>' + lng)
                        .addTo(map);
                });
            });
        </script>
    </div>
</div>