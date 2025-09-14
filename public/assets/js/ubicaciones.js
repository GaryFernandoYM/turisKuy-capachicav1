// Este script rastrea la ubicación y envía datos al backend si se ha movido más de 100 metros
let lastLocation = null;
let stayStart = null;
let watchId = null;

let visitorData = {
    nombre: localStorage.getItem('nombre') || '',
    telefono: localStorage.getItem('telefono') || '',
    visitorId: localStorage.getItem('visitor_id') || 'anon_' + Math.random().toString(36).substring(2, 10)
};
localStorage.setItem('visitor_id', visitorData.visitorId);

function iniciarRastreoEnTiempoReal() {
    if (!navigator.geolocation) {
        console.error('Geolocalización no soportada.');
        return;
    }

    watchId = navigator.geolocation.watchPosition(
        position => {
            const lat = parseFloat(position.coords.latitude.toFixed(7));
            const lon = parseFloat(position.coords.longitude.toFixed(7));

            const data = {
                visitor_id: visitorData.visitorId,
                nombre: visitorData.nombre,
                celular: visitorData.telefono,
                latitud: lat,
                longitud: lon,
                direccion: '',
                tiempo: new Date().toISOString()
            };

            enviarUbicacion(data);
        },
        error => {
            console.error(`Error al obtener ubicación: ${error.message}`);
        },
        {
            enableHighAccuracy: true,
            maximumAge: 0,
            timeout: 10000
        }
    );
}

function enviarUbicacion(data) {
    fetch('/guardar-ubicacion', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? ''
        },
        body: JSON.stringify(data)
    }).then(response => {
        if (!response.ok) {
            console.error(`Error backend: ${response.statusText}`);
        } else {
            response.json().then(res => console.log('✅ Enviado:', res));
        }
    }).catch(error => console.error('❌ Error al enviar:', error));
}



// Recuperar lastLocation si existe en localStorage
const lastLat = parseFloat(localStorage.getItem('lastLat'));
const lastLon = parseFloat(localStorage.getItem('lastLon'));
if (!isNaN(lastLat) && !isNaN(lastLon)) {
    lastLocation = { lat: lastLat, lon: lastLon };
}

// Generar visitor_id si no existe
if (!visitorData.visitorId) {
    visitorData.visitorId = 'anon_' + Math.random().toString(36).substring(2, 10);
    localStorage.setItem('visitor_id', visitorData.visitorId);
}

// Ocultar formulario si ya hay datos
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('form-identidad');
    if (visitorData.nombre && visitorData.telefono && form) {
        form.style.display = 'none';
        iniciarRastreo();
    }
});

// Guardar identidad
function guardarIdentidad() {
    const nombreInput = document.getElementById('nombre');
    const telefonoInput = document.getElementById('telefono');
    if (!nombreInput || !telefonoInput) return;

    const nombre = nombreInput.value.trim();
    const telefono = telefonoInput.value.trim();

    if (!nombre || !telefono) {
        alert('Por favor completa tu nombre y celular.');
        return;
    }

    visitorData.nombre = nombre;
    visitorData.telefono = telefono;

    localStorage.setItem('nombre', nombre);
    localStorage.setItem('telefono', telefono);

    const form = document.getElementById('form-identidad');
    if (form) form.style.display = 'none';

    alert('Datos guardados. Iniciando rastreo...');
    iniciarRastreo();
}

// Inicia rastreo cada 10 segundos
function iniciarRastreo() {
    getLocation();
    setInterval(getLocation, 10000);
}

// Obtener dirección desde coordenadas
function reverseGeocode(lat, lon, callback) {
    fetch(`https://nominatim.openstreetmap.org/reverse?lat=${lat}&lon=${lon}&format=json`)
        .then(res => res.json())
        .then(data => callback(data.display_name || 'No disponible'))
        .catch(() => callback('No se pudo obtener la dirección.'));
}

// Calcular distancia entre 2 coordenadas (en metros)
function calcularDistancia(lat1, lon1, lat2, lon2) {
    const R = 6371000;
    const rad = Math.PI / 180;
    const dLat = (lat2 - lat1) * rad;
    const dLon = (lon2 - lon1) * rad;
    const a = Math.sin(dLat / 2) ** 2 +
        Math.cos(lat1 * rad) * Math.cos(lat2 * rad) *
        Math.sin(dLon / 2) ** 2;
    return R * 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
}

// ✅ AJAX seguro con Accept + CSRF
async function enviarUbicacion(data) {
    try {
        const response = await fetch('/guardar-ubicacion', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json', // 👈 Clave para evitar errores
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? ''
            },
            body: JSON.stringify(data)
        });

        if (!response.ok) {
            const text = await response.text();
            console.error(`Error backend: ${text}`);
            return;
        }

        const json = await response.json();
        console.log('✅ Ubicación registrada:', json);
    } catch (error) {
        console.error('❌ Error al enviar:', error);
    }
}

// Obtener ubicación actual
function getLocation() {
    const output = document.getElementById('location-result');

    if (!visitorData.nombre || !visitorData.telefono) {
        if (output) output.innerHTML = 'Primero debes ingresar tu nombre y celular.';
        return;
    }

    if (!navigator.geolocation) {
        if (output) output.innerHTML = 'Geolocalización no soportada.';
        return;
    }

    navigator.geolocation.getCurrentPosition(
        position => {
            const lat = parseFloat(position.coords.latitude.toFixed(7));
            const lon = parseFloat(position.coords.longitude.toFixed(7));

            // ✅ Filtro de movimiento mínimo (ruido GPS)
            if (lastLocation) {
                const distancia = calcularDistancia(lat, lon, lastLocation.lat, lastLocation.lon);
                if (distancia < 100) {   // 👈 Ajusta aquí (50 metros recomendado para peatones)
                    console.log(`📡 Movimiento menor a 50m (${distancia.toFixed(1)}m). Ignorado.`);
                    return;
                }
            }

            localStorage.setItem('lastLat', lat);
            localStorage.setItem('lastLon', lon);
            lastLocation = { lat, lon };

            const now = new Date();
            const tiempoEstadia = stayStart ? Math.round((now - stayStart) / 1000) : 0;
            stayStart = now;

            reverseGeocode(lat, lon, direccion => {
                const data = {
                    visitor_id: visitorData.visitorId,
                    nombre: visitorData.nombre,
                    celular: visitorData.telefono,
                    latitud: lat,
                    longitud: lon,
                    direccion: direccion,
                    tiempo: now.toISOString(),
                    duracion: tiempoEstadia
                };

                if (output) {
                    output.innerHTML = `
                        <strong>Nombre:</strong> ${data.nombre}<br>
                        <strong>Celular:</strong> ${data.celular}<br>
                        <strong>Latitud:</strong> ${lat}<br>
                        <strong>Longitud:</strong> ${lon}<br>
                        <strong>Dirección:</strong> ${direccion}<br>
                        <strong>Tiempo en último lugar:</strong> ${tiempoEstadia} segundos<br>
                        <a href="https://maps.google.com/?q=${lat},${lon}" target="_blank">Ver en Google Maps</a>
                    `;
                }

                enviarUbicacion(data);
            });
        },
        error => {
            if (output) output.innerHTML = `Error al obtener ubicación: ${error.message}`;
        },
        {
            enableHighAccuracy: true,
            timeout: 10000,
            maximumAge: 0
        }
    );
}


