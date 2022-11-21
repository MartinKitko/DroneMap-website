<?php

use App\Core\IAuthenticator;
use App\Models\Marker;
/** @var Marker[] $data */
/** @var IAuthenticator $auth */

if ($auth->isLogged()) { ?>
    <div class="row">
        <a href="?c=markers&a=create" class="btn btn-success">Pridať nový bod</a>
    </div>
    <div class="top115" id="map">
<?php } else { ?>
<div class="top60" id="map">
    <?php } ?>
    <script>
        let $defaultLat = 48.7562;
        let $defaultLong = 18.5586;
        let $defaultZoom = 11.5;

        let geoJson = [];
        let i = 0;
        <?php foreach ($data as $marker) { ?>
            geoJson[i] = {
                'type': 'Feature',
                'id': '<?= $marker->getId(); ?>',
                'geometry': {
                    'type': 'Point',
                    'coordinates': ['<?= $marker->getLong(); ?>', '<?= $marker->getLat(); ?>']
                },
                'properties': {
                    'title': '<?= $marker->getTitle(); ?>',
                    'description': '<?= $marker->getDescription(); ?>',
                    'color': '<?= $marker->getColor(); ?>'
                }
            }
            i++;
        <?php } ?>

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

        for (const feature of geoJson) {
            const element = document.createElement('div');
            element.className = 'marker';
            element.style.backgroundImage = `url(public/images/markers/marker-${feature.properties.color}.png)`;

            new mapboxgl.Marker(element, {anchor: 'bottom'})
                .setLngLat(feature.geometry.coordinates)
                .setPopup(
                    new mapboxgl.Popup({ offset: 25 })
                        .setHTML(
                            `<h4>${feature.properties.title}</h4><p>${feature.properties.description}</p>`
                            <?php if ($auth->isLogged()) { ?> +
                                `<a href="?c=markers&a=edit&id=${feature.id}" class="btn btn-warning m-1">Upraviť</a>` +
                                `<a href="?c=markers&a=delete&id=${feature.id}" class="btn btn-danger m-1">Zmazať</a>`
                            <?php } ?>
                        )
                )
                .addTo(map);
        }
        map.on('style.load', function() {
            map.on('dblclick', function(e) {
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