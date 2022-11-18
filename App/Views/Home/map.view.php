<?php /** @var Array $data */ ?>
<div id="map">
    <script>
        mapboxgl.accessToken = 'pk.eyJ1IjoibWFydGluLWtpdCIsImEiOiJjbGFta256ZnQwMHdwM3Zudmxyanc3OGRxIn0.frnxdqab00eiqutXB60fKQ';

        const geojson = {
            'type': 'FeatureCollection',
            'features': [
                {
                    'type': 'Feature',
                    'geometry': {
                        'type': 'Point',
                        'coordinates': [18.494912, 48.775172]
                    },
                    'properties': {
                        'title': 'Prvý test',
                        'description': 'Prvý testovací marker'
                    }
                },
                {
                    'type': 'Feature',
                    'geometry': {
                        'type': 'Point',
                        'coordinates': [18.504912, 48.765172]
                    },
                    'properties': {
                        'title': 'Druhý test',
                        'description': 'Druhý testovací marker'
                    }
                }
            ]
        };

        const map = new mapboxgl.Map({
            container: 'map',
            style: 'mapbox://styles/martin-kit/clamklbrs000714rckzalpmix',
            center: [18.494912, 48.765172],
            zoom: 13.5
        });

        for (const feature of geojson.features) {
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

        //L.marker([18.494912, 48.785172]).addTo(map);
    </script>
</div>