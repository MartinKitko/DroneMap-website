<?php
use App\Models\Marker;
/** @var Marker[] $data */
?>

<div id="map">
    <script>
        let $defaultLat = 48.765172;
        let $defaultLong = 18.494912;
        let $defaultZoom = 13;

        let geoJson = [];
        let i = 0;
        <?php foreach ($data as $marker) { ?>
            geoJson[i] = {
                'type': 'Feature',
                'geometry': {
                    'type': 'Point',
                    'coordinates': ['<?= $marker->getLong(); ?>', '<?= $marker->getLat(); ?>']
                },
                'properties': {
                    'title': '<?= $marker->getTitle(); ?>',
                    'description': '<?= $marker->getDescription(); ?>'
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

        for (const feature of geoJson) {
            const element = document.createElement('div');
            element.className = 'marker';

            new mapboxgl.Marker(element)
                .setLngLat(feature.geometry.coordinates)
                .setPopup(
                    new mapboxgl.Popup({ offset: 25 })
                        .setHTML(
                            `<h4>${feature.properties.title}</h4><p>${feature.properties.description}</p>`
                        )
                )
                .addTo(map);
        }
    </script>
</div>