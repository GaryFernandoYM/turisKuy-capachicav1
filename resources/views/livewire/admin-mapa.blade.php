<div wire:poll.10s>
    <div id="map" class="w-full h-full min-h-[500px] rounded-xl z-0"></div>

    <script>
        document.addEventListener('livewire:load', function () {
            var map = L.map('map').setView([-15.5, -70.13], 12); // Capachica
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

            @foreach($ubicaciones as $u)
                L.marker([{{ $u->latitud }}, {{ $u->longitud }}]).addTo(map);
            @endforeach
        });
    </script>

    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
</div>
