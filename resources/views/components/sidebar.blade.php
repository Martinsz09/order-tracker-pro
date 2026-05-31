<aside class="w-64 bg-slate-950 border-r border-slate-800 flex flex-col justify-between hidden md:flex min-h-screen">
    <div class="p-6">
        <div class="flex items-center gap-3 mb-8">
            <div class="bg-blue-600 p-2.5 rounded-xl text-white shadow-lg shadow-blue-500/30">
                <i class="fa-solid fa-boxes-packing text-xl"></i>
            </div>
            <div>
                <h1 class="font-bold text-lg leading-tight tracking-tight text-white">OrderTracker</h1>
                <span class="text-xs text-blue-400 font-semibold tracking-wider uppercase">Pro Dashboard</span>
            </div>
        </div>

        <nav class="space-y-1.5">
            @if(Request::is('dashboard'))
                <button onclick="switchTab('dashboard')" id="btn-dashboard" class="sidebar-link w-full flex items-center gap-3 px-4 py-3 text-slate-400 hover:text-white hover:bg-slate-900 rounded-xl transition font-medium text-sm text-left bg-blue-600 text-white">
                    <i class="fa-solid fa-chart-pie text-lg w-5 text-center"></i> Dashboard
                </button>
                <button onclick="switchTab('orders')" id="btn-orders" class="sidebar-link w-full flex items-center gap-3 px-4 py-3 text-slate-400 hover:text-white hover:bg-slate-900 rounded-xl transition font-medium text-sm text-left">
                    <i class="fa-solid fa-box text-lg w-5 text-center"></i> Meus Pedidos
                </button>
                <button onclick="switchTab('tracking')" id="btn-tracking" class="sidebar-link w-full flex items-center gap-3 px-4 py-3 text-slate-400 hover:text-white hover:bg-slate-900 rounded-xl transition font-medium text-sm text-left">
                    <i class="fa-solid fa-map-location-dot text-lg w-5 text-center"></i> Detalhes & Mapa
                </button>
            @else
                <a href="/dashboard" class="w-full flex items-center gap-3 px-4 py-3 text-slate-400 hover:text-white hover:bg-slate-900 rounded-xl transition font-medium text-sm">
                    <i class="fa-solid fa-chart-pie text-lg w-5 text-center"></i> Voltar p/ Dashboard
                </a>
            @endif

            <div class="pt-4 mt-4 border-t border-slate-800">
                <a href="/dashboard/orders/create" class="w-full flex items-center justify-center gap-2 px-4 py-3 bg-blue-600 hover:bg-blue-500 text-white rounded-xl transition font-semibold text-sm shadow-lg shadow-blue-500/10">
                    <i class="fa-solid fa-plus text-xs"></i> Novo Pedido
                </a>
            </div>
        </nav>
    </div>

    <div class="p-4 border-t border-slate-800 flex items-center gap-3">
        <div class="w-10 h-10 rounded-full bg-blue-600 text-white font-bold flex items-center justify-center border-2 border-slate-700">
            {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
        </div>
        <div class="flex-1 min-w-0">
            <p class="text-sm font-semibold text-white truncate">{{ Auth::user()->name }}</p>
            <p class="text-xs text-slate-400 truncate">{{ Auth::user()->email }}</p>
        </div>
        <div class="flex items-center gap-1 shrink-0">
            <a href="{{ route('profile.edit') }}" class="text-slate-400 hover:text-blue-400 transition p-2 rounded-lg">
                <i class="fa-solid fa-gear"></i>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="button" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-slate-400 hover:text-rose-400 transition p-2 rounded-lg">
                    <i class="fa-solid fa-right-from-bracket"></i>
                </button>
            </form>
        </div>
    </div>
</aside>