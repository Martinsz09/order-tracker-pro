let mapObj = null;
let originMarker = null;
let destMarker = null;
let deliveryMarker = null;
let simulationInterval = null;
let isSimulating = false;
let progress = 0;
let activeOrigin = [0, 0];
let activeDest = null;

function switchTab(tabName) {
    document.getElementById('tab-dashboard').classList.add('hidden');
    document.getElementById('tab-orders').classList.add('hidden');
    document.getElementById('tab-tracking').classList.add('hidden');

    document.getElementById('btn-dashboard').classList.remove('bg-blue-600', 'text-white');
    document.getElementById('btn-orders').classList.remove('bg-blue-600', 'text-white');
    document.getElementById('btn-tracking').classList.remove('bg-blue-600', 'text-white');

    document.getElementById('tab-' + tabName).classList.remove('hidden');
    document.getElementById('btn-' + tabName).classList.add('bg-blue-600', 'text-white');

    const titles = { 'dashboard': 'Dashboard', 'orders': 'Meus Pedidos', 'tracking': 'Detalhes & Mapa' };
    document.getElementById('page-title').innerText = titles[tabName];

    if (tabName === 'tracking' && mapObj) {
        setTimeout(() => {
            mapObj.invalidateSize();
        }, 50);
    }
}

function carregarMapa(latOrigem, lngOrigem, latDestino, lngDestino, codigo, txtOrigem, txtDestino) {
    switchTab('tracking');

    document.getElementById('tracking-title-code').innerText = "Pedido #" + codigo;
    document.getElementById('tracking-origin-text').innerText = txtOrigem;
    document.getElementById('tracking-dest-text').innerText = txtDestino;

    // Log preventivo para checar no F12 se as coordenadas estão chegando do banco
    console.log("Dados recebidos:", {latOrigem, lngOrigem, latDestino, lngDestino});

    if (!latOrigem || !lngOrigem || latOrigem === "" || lngOrigem === "") {
        alert('Este pedido não possui coordenadas de origem válidas no banco de dados.');
        return;
    }

    if (simulationInterval) clearInterval(simulationInterval);
    isSimulating = false;
    progress = 0;
    resetTimeline();

    activeOrigin = [parseFloat(latOrigem), parseFloat(lngOrigem)];
    activeDest = (latDestino && lngDestino && latDestino !== "" && lngDestino !== "") ? [parseFloat(latDestino), parseFloat(lngDestino)] : null;

    if (!activeDest) {
        alert('Aviso: Este pedido não possui coordenadas de destino válidas para simular a rota.');
        return;
    }

    if (!mapObj) {
        mapObj = L.map('map').setView(activeOrigin, 10);
        L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap &copy; CARTO'
        }).addTo(mapObj);
    } else {
        mapObj.setView(activeOrigin, 10);
        if (originMarker) mapObj.removeLayer(originMarker);
        if (destMarker) mapObj.removeLayer(destMarker);
        if (deliveryMarker) mapObj.removeLayer(deliveryMarker);
    }

    originMarker = L.circleMarker(activeOrigin, { radius: 8, color: '#3b82f6', fillColor: '#3b82f6', fillOpacity: 1 }).addTo(mapObj).bindPopup('Origem da Carga');

    if (activeDest) {
        destMarker = L.circleMarker(activeDest, { radius: 8, color: '#10b981', fillColor: '#10b981', fillOpacity: 1 }).addTo(mapObj).bindPopup('Destino Final');
        L.polyline([activeOrigin, activeDest], { color: '#3b82f6', weight: 3, dashArray: '5, 10', opacity: 0.6 }).addTo(mapObj);

        const truckIcon = L.divIcon({
            html: '<div class="bg-amber-500 text-slate-950 w-8 h-8 rounded-full flex items-center justify-center border-2 border-white shadow-lg animate-bounce"><i class="fa-solid fa-truck text-xs"></i></div>',
            className: 'custom-truck-icon',
            iconSize: [32, 32],
            iconAnchor: [16, 16]
        });

        deliveryMarker = L.marker(activeOrigin, { icon: truckIcon }).addTo(mapObj).bindPopup('<b>Carga em Trânsito</b>').openPopup();

        let group = new L.featureGroup([originMarker, destMarker]);
        mapObj.fitBounds(group.getBounds().pad(0.1));
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const simBtn = document.getElementById('sim-btn');
    if (!simBtn) return;

    simBtn.addEventListener('click', function() {
        if (!activeDest) {
            alert('Selecione um pedido válido primeiro.');
            return;
        }

        if (isSimulating) {
            clearInterval(simulationInterval);
            isSimulating = false;
            simBtn.innerHTML = '<i class="fa-solid fa-play"></i> Continuar Simulação';
            simBtn.className = "bg-emerald-600 hover:bg-emerald-500 text-white px-4 py-2.5 rounded-xl text-xs font-bold shadow-lg flex items-center gap-2 transition whitespace-nowrap";
        } else {
            isSimulating = true;
            simBtn.innerHTML = '<i class="fa-solid fa-pause"></i> Pausar Simulação';
            simBtn.className = "bg-amber-600 hover:bg-amber-500 text-white px-4 py-2.5 rounded-xl text-xs font-bold shadow-lg flex items-center gap-2 transition whitespace-nowrap";

            simulationInterval = setInterval(() => {
                if (progress >= 100) {
                    clearInterval(simulationInterval);
                    isSimulating = false;
                    simBtn.innerHTML = '<i class="fa-solid fa-arrow-rotate-left"></i> Reiniciar Envio';
                    simBtn.className = "bg-blue-600 hover:bg-blue-500 text-white px-4 py-2.5 rounded-xl text-xs font-bold shadow-lg flex items-center gap-2 transition whitespace-nowrap";
                    updateTimelineToDelivered();
                    return;
                }

                progress += 4;

                let currentLat = activeOrigin[0] + (activeDest[0] - activeOrigin[0]) * (progress / 100);
                let currentLng = activeOrigin[1] + (activeDest[1] - activeOrigin[1]) * (progress / 100);

                if (deliveryMarker) deliveryMarker.setLatLng([currentLat, currentLng]);

                if (progress >= 80 && progress < 100) {
                    updateTimelineToLastMile();
                }
            }, 400);
        }
    });
});

function resetTimeline() {
    const simBtn = document.getElementById('sim-btn');
    const dot = document.getElementById('dot-transit');
    const title = document.getElementById('title-transit');
    const desc = document.getElementById('desc-transit');

    if (simBtn) simBtn.innerHTML = '<i class="fa-solid fa-play"></i> Iniciar Simulação (Rota)';
    if (dot && title && desc) {
        dot.className = "absolute -left-6 top-1 w-4 h-4 rounded-full bg-amber-500 border-4 border-slate-950 ring-4 ring-amber-500/20";
        title.innerText = "Mapeamento Concluído";
        title.className = "text-sm font-semibold text-white";
        desc.innerText = "As coordenadas foram processadas e salvas com sucesso. O pacote está pronto no sistema.";
    }
}

function updateTimelineToLastMile() {
    const dot = document.getElementById('dot-transit');
    const title = document.getElementById('title-transit');
    const desc = document.getElementById('desc-transit');

    if (dot && title && desc) {
        dot.className = "absolute -left-6 top-1 w-4 h-4 rounded-full bg-rose-500 border-4 border-slate-950 ring-4 ring-rose-500/20";
        title.innerText = "Saiu para Entrega na sua região!";
        title.className = "text-sm font-semibold text-rose-400";
        desc.innerText = "O motorista já iniciou a rota final até a sua residência.";
    }
}

function updateTimelineToDelivered() {
    const dot = document.getElementById('dot-transit');
    const title = document.getElementById('title-transit');
    const desc = document.getElementById('desc-transit');

    if (dot && title && desc) {
        dot.className = "absolute -left-6 top-1 w-4 h-4 rounded-full bg-emerald-500 border-4 border-slate-950 ring-4 ring-emerald-500/20";
        title.innerText = "Produto Entregue!";
        title.className = "text-sm font-semibold text-emerald-400";
        desc.innerText = "Encomenda recebida no endereço cadastrado.";
    }

    if (deliveryMarker) deliveryMarker.bindPopup('<b>Destino alcançado!</b>').openPopup();
    progress = 0;
}