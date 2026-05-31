<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Tracker Pro — Novo Pedido</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex">

    <x-sidebar />

    <main class="flex-1 flex items-center justify-center p-6 overflow-y-auto">
        <div class="max-w-xl w-full bg-slate-950 border border-slate-800 rounded-2xl p-6 md:p-8 shadow-2xl">
            <div class="flex items-center gap-4 mb-8 border-b border-slate-800 pb-4">
                <a href="/dashboard" class="text-slate-400 hover:text-white bg-slate-900 border border-slate-800 p-2 rounded-xl transition">
                    <i class="fa-solid fa-arrow-left"></i>
                </a>
                <div>
                    <h3 class="font-bold text-white text-xl">Novo Envio</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Cadastre o pacote digitando apenas os endereços de entrega</p>
                </div>
            </div>

            <form action="/dashboard/orders/create" method="POST" class="space-y-5">
                @csrf
                
                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Nome do Produto</label>
                    <input type="text" name="product_name" required placeholder="Ex: PlayStation 5 Pro" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 text-slate-200 placeholder-slate-600 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Código de Rastreio</label>
                    <div class="flex gap-2">
                        <input type="text" name="tracking_code" id="input-tracking-code" required placeholder="Clique na varinha para gerar" class="flex-1 font-mono bg-slate-900 border border-slate-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 text-slate-200 placeholder-slate-600 transition">
                        <button type="button" onclick="generateRandomTracking()" class="bg-slate-900 hover:bg-slate-800 border border-slate-800 text-blue-400 px-4 rounded-xl text-sm transition">
                            <i class="fa-solid fa-wand-magic-sparkles"></i>
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Endereço de Origem (Onde a carga sai)</label>
                    <input type="text" name="origin_address" required placeholder="Ex: Av. Embaixador Macedo Soares, 10000 - São Paulo, SP" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 text-slate-200 placeholder-slate-600 transition">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Endereço de Destino (Entrega do Cliente)</label>
                    <input type="text" name="destination_address" required placeholder="Ex: Rua das Flores, 123 - Rio de Janeiro, RJ" class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 text-slate-200 placeholder-slate-600 transition">
                </div>

                <div class="pt-4 border-t border-slate-800 flex justify-end gap-3">
                    <a href="/dashboard" class="bg-slate-900 hover:bg-slate-800 border border-slate-800 text-slate-400 hover:text-white px-5 py-3 rounded-xl text-sm font-semibold transition">Cancelar</a>
                    <button type="submit" class="bg-blue-600 hover:bg-blue-500 text-white px-6 py-3 rounded-xl text-sm font-semibold shadow-lg transition">Salvar e Iniciar Frete</button>
                </div>
            </form>
        </div>
    </main>

    <script>
        function generateRandomTracking() {
            const num = Math.floor(10000 + Math.random() * 90000);
            const char = String.fromCharCode(65 + Math.floor(Math.random() * 26));
            document.getElementById('input-tracking-code').value = `BR-${num}-${char}`;
        }
    </script>
</body>
</html>