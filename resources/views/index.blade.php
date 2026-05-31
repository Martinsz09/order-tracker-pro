<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Tracker Pro — Rastreamento Premium</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .sidebar-link.active { @apply bg-blue-600 text-white; }
        #map { height: 400px; width: 100%; border-radius: 0.75rem; z-index: 1; }
    </style>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex">

    <x-sidebar />

    <main class="flex-1 flex flex-col min-w-0 overflow-y-auto">
        <header class="h-16 border-b border-slate-800 bg-slate-950/50 backdrop-blur px-6 flex items-center justify-between sticky top-0 z-10">
            <div class="flex items-center gap-4">
                <h2 id="page-title" class="text-xl font-bold text-white">Dashboard</h2>
            </div>
            <div class="flex items-center gap-4">
                @if(session('msg'))
                    <span class="bg-emerald-500/20 text-emerald-400 px-4 py-1.5 border border-emerald-500/30 rounded-xl text-xs font-semibold">
                        {{ session('msg') }}
                    </span>
                @endif
               
            </div>
        </header>

        <div class="p-6 max-w-7xl w-full mx-auto space-y-6">
            <section id="tab-dashboard" class="space-y-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div class="bg-slate-950 border border-slate-800 rounded-2xl p-5 shadow-sm">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total de Pedidos</p>
                                <h3 class="text-3xl font-bold text-white mt-2">{{ $ownedOrders->count() }}</h3>
                            </div>
                            <div class="bg-blue-500/10 text-blue-400 p-3 rounded-xl"><i class="fa-solid fa-cubes text-xl"></i></div>
                        </div>
                    </div>
                    <div class="bg-slate-950 border border-slate-800 rounded-2xl p-5 shadow-sm">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Em Trânsito</p>
                                <h3 class="text-3xl font-bold text-amber-400 mt-2">{{ $ownedOrders->count() > 0 ? 1 : 0 }}</h3>
                            </div>
                            <div class="bg-amber-500/10 text-amber-400 p-3 rounded-xl"><i class="fa-solid fa-truck-fast text-xl"></i></div>
                        </div>
                    </div>
                    <div class="bg-slate-950 border border-slate-800 rounded-2xl p-5 shadow-sm">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Entregues</p>
                                <h3 class="text-3xl font-bold text-emerald-400 mt-2">0</h3>
                            </div>
                            <div class="bg-emerald-500/10 text-emerald-400 p-3 rounded-xl"><i class="fa-solid fa-circle-check text-xl"></i></div>
                        </div>
                    </div>
                    <div class="bg-slate-950 border border-slate-800 rounded-2xl p-5 shadow-sm">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Atrasados</p>
                                <h3 class="text-3xl font-bold text-rose-400 mt-2">0</h3>
                            </div>
                            <div class="bg-rose-500/10 text-rose-400 p-3 rounded-xl"><i class="fa-solid fa-triangle-exclamation text-xl"></i></div>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-950 border border-slate-800 rounded-2xl p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h4 class="font-bold text-white text-lg">Acompanhamento Rápido</h4>
                        <button onclick="switchTab('orders')" class="text-blue-400 hover:text-blue-300 text-sm font-medium">Ver todos</button>
                    </div>
                    <div class="divide-y divide-slate-800">
                        @forelse($ownedOrders->take(3) as $order)
                            <div class="py-4 flex justify-between items-center gap-4 first:pt-0 last:pb-0 cursor-pointer" 
                                 onclick="carregarMapa('{{ $order->latitude_origem }}', '{{ $order->longitude_origem }}', '{{ $order->latitude_destino }}', '{{ $order->longitude_destino }}', '{{ $order->tracking_code }}', '{{ addslashes($order->origin_address) }}', '{{ addslashes($order->destination_address) }}')">
                                <div class="flex items-center gap-4">
                                    <div class="bg-slate-900 p-3 rounded-xl text-slate-400"><i class="fa-solid fa-box-open text-lg"></i></div>
                                    <div>
                                        <h5 class="text-white font-medium text-sm">{{ $order->product_name }}</h5>
                                        <p class="text-xs text-slate-400 mt-0.5">Código: <span class="font-mono text-blue-400">{{ $order->tracking_code }}</span></p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="bg-amber-500/10 text-amber-400 px-3 py-1 rounded-full text-xs font-medium border border-amber-500/20">Em Trânsito</span>
                                    <p class="text-xs text-slate-500 mt-1">Clique para ver no mapa</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500 py-4">Nenhum pedido cadastrado ainda.</p>
                        @endforelse
                    </div>
                </div>
            </section>

            <section id="tab-orders" class="space-y-6 hidden">
                <div class="bg-slate-950 border border-slate-800 rounded-2xl overflow-hidden">
                    <div class="p-6 border-b border-slate-800 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
                        <h4 class="font-bold text-white text-lg">Todos os Pedidos</h4>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-slate-900/50 text-slate-400 text-xs font-bold uppercase tracking-wider border-b border-slate-800">
                                    <th class="p-4 pl-6">Código</th>
                                    <th class="p-4">Produto</th>
                                    <th class="p-4">Destino</th>
                                    <th class="p-4">Status</th>
                                    <th class="p-4 pr-6 text-right">Ação</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-800 text-sm">
                                @forelse($ownedOrders as $order)
                                    <tr>
                                        <td class="p-4 pl-6 font-mono font-medium text-blue-400">{{ $order->tracking_code }}</td>
                                        <td class="p-4 text-white font-medium">{{ $order->product_name }}</td>
                                        <td class="p-4 text-slate-400 max-w-xs truncate" title="{{ $order->destination_address }}">{{ $order->destination_address }}</td>
                                        <td class="p-4"><span class="bg-amber-500/10 text-amber-400 px-3 py-1 rounded-full text-xs font-medium border border-amber-500/20">Em Trânsito</span></td>
                                        <td class="p-4 pr-6 text-right">
                                            <button onclick="carregarMapa('{{ $order->latitude_origem }}', '{{ $order->longitude_origem }}', '{{ $order->latitude_destino }}', '{{ $order->longitude_destino }}', '{{ $order->tracking_code }}', '{{ addslashes($order->origin_address) }}', '{{ addslashes($order->destination_address) }}')" class="bg-blue-600 hover:bg-blue-500 text-white px-3 py-1.5 rounded-xl text-xs font-semibold transition">Rastrear</button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="p-6 text-center text-slate-500">Você ainda não enviou nenhum pedido.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>

            <section id="tab-tracking" class="space-y-6 hidden">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2 space-y-6">
                        <div class="bg-slate-950 border border-slate-800 rounded-2xl p-4 shadow-sm space-y-4">
                            <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-3">
                                <div>
                                    <span class="text-xs font-bold text-blue-400 uppercase tracking-wider">Mapa de Rastreamento Real-time</span>
                                    <h3 id="tracking-title-code" class="text-lg font-bold text-white mt-0.5">Selecione um pedido para visualizar</h3>
                                </div>
                                <button id="sim-btn" class="bg-emerald-600 hover:bg-emerald-500 text-white px-4 py-2.5 rounded-xl text-xs font-bold shadow-lg flex items-center gap-2 transition whitespace-nowrap">
                                    <i class="fa-solid fa-play"></i> Iniciar Simulação (Rota)
                                </button>
                            </div>
                            <div id="map" class="border border-slate-800"></div>
                        </div>
                        <div class="bg-slate-950 border border-slate-800 rounded-2xl p-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <h5 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Origem da Carga</h5>
                                <p id="tracking-origin-text" class="text-sm text-white font-medium">---</p>
                            </div>
                            <div>
                                <h5 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Destino Final</h5>
                                <p id="tracking-dest-text" class="text-sm text-white font-medium">---</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-950 border border-slate-800 rounded-2xl p-6 flex flex-col justify-between">
                        <div>
                            <h4 class="font-bold text-white text-lg mb-6">Status do Envio</h4>
                            <div class="relative pl-6 space-y-6 before:absolute before:bottom-2 before:top-2 before:left-2 before:w-0.5 before:bg-slate-800">
                                <div class="relative">
                                    <div id="dot-transit" class="absolute -left-6 top-1 w-4 h-4 rounded-full bg-amber-500 border-4 border-slate-950 ring-4 ring-amber-500/20"></div>
                                    <h5 id="title-transit" class="text-sm font-semibold text-white">Mapeamento Concluído</h5>
                                    <p id="desc-transit" class="text-xs text-slate-400 mt-0.5">As coordenadas foram processadas e salvas com sucesso. O pacote está pronto no sistema.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </main>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="{{ asset('js/index.js') }}"></script>
</body>
</html>