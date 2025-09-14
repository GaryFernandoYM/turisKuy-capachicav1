// Coordenadas iniciales del mapa
const map = L.map('map').setView([-15.5, -70.1], 11);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

const visitanteIcon = L.icon({
  iconUrl: 'https://cdn-icons-png.flaticon.com/512/684/684908.png',
  iconSize: [36, 36],
  iconAnchor: [18, 36],
  popupAnchor: [0, -30]
});

const lugarIcon = L.icon({
  iconUrl: 'https://cdn-icons-png.flaticon.com/512/3179/3179068.png',
  iconSize: [36, 36],
  iconAnchor: [18, 36],
  popupAnchor: [0, -30]
});

let markers = {};
let primerCentradoHecho = false;

function actualizarMarcadores() {
  fetch('/admin/tracking/json')
    .then(res => res.json())
    .then(data => {
      const idsActivos = new Set();

      data.forEach(ubicacion => {
        const id = ubicacion.visitor_id;
        const latlng = [ubicacion.latitud, ubicacion.longitud];
        idsActivos.add(id);

        if (markers[id]) {
          markers[id].setLatLng(latlng);
        } else {
          const marker = L.marker(latlng, { icon: visitanteIcon })
            .addTo(map)
            .bindPopup(`🧍‍♂️ Visitante: ${id}`)
            .bindTooltip(`Visitante: ${id}`, { permanent: true, direction: 'top', offset: [0, -20] });

          markers[id] = marker;

          if (!primerCentradoHecho) {
            map.setView(latlng, 13, { animate: true });
            primerCentradoHecho = true;
          }
        }
      });

      for (let id in markers) {
        if (!idsActivos.has(id)) {
          map.removeLayer(markers[id]);
          delete markers[id];
        }
      }

      document.getElementById('contadorVisitantes').textContent = data.length;
    })
    .catch(error => console.error('Error actualizando visitantes:', error));
}

if (typeof window.lugaresTuristicos !== 'undefined') {
  window.lugaresTuristicos.forEach(lugar => {
    const marker = L.marker([lugar.latitud, lugar.longitud], { icon: lugarIcon })
      .addTo(map)
      .bindPopup(`📍 <b>${lugar.nombre}</b><br>${lugar.direccion || ''}`)
      .bindTooltip(lugar.nombre, { permanent: true, direction: 'top', offset: [0, -20] });
  });
}

actualizarMarcadores();
setInterval(actualizarMarcadores, 5000);
