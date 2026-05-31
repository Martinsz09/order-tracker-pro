<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OrderTracker Pro — Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex flex-col justify-center items-center p-4">

    <div class="max-w-md w-full bg-slate-950 border border-slate-800 rounded-2xl p-6 md:p-8 shadow-2xl">
        
        <div class="flex flex-col items-center mb-6 text-center">
            <div class="bg-blue-600 p-3 rounded-xl text-white shadow-lg shadow-blue-500/30 mb-3">
                <i class="fa-solid fa-boxes-packing text-2xl"></i>
            </div>
            <h3 class="font-bold text-white text-xl">OrderTracker Pro</h3>
            <p class="text-xs text-slate-400 mt-1">Faça login para gerenciar e rastrear seus fretes</p>
        </div>

        @if (session('status'))
            <div class="mb-4 text-emerald-400 text-xs bg-emerald-500/10 p-3 rounded-xl border border-emerald-500/20 text-center font-medium">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">E-mail</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 text-slate-200 placeholder-slate-600 transition" placeholder="seu@email.com">
                @if($errors->has('email'))
                    <p class="mt-1.5 text-xs text-rose-400 font-medium">{{ $errors->first('email') }}</p>
                @endif
            </div>

            <div>
                <div class="flex justify-between items-center mb-2">
                    <label class="text-xs font-bold text-slate-400 uppercase tracking-wider">Senha</label>
                    @if (Route::has('password.request'))
                        <a class="text-xs text-blue-400 hover:text-blue-300 transition font-medium" href="{{ route('password.request') }}">
                            Esqueceu a senha?
                        </a>
                    @endif
                </div>
                <input type="password" name="password" required class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 text-slate-200 placeholder-slate-600 transition" placeholder="••••••••">
                @if($errors->has('password'))
                    <p class="mt-1.5 text-xs text-rose-400 font-medium">{{ $errors->first('password') }}</p>
                @endif
            </div>

            <div class="flex items-center">
                <label for="remember_me" class="inline-flex items-center cursor-pointer select-none">
                    <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 rounded bg-slate-900 border-slate-800 text-blue-600 focus:ring-blue-500 focus:ring-offset-slate-950 accent-blue-600">
                    <span class="ms-2 text-xs text-slate-400 font-medium">Lembrar de mim</span>
                </label>
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white py-3 rounded-xl text-sm font-semibold shadow-lg shadow-blue-500/10 transition mt-2">
                Entrar no Painel
                <i class="fa-solid fa-right-to-bracket ml-1.5 text-xs"></i>
            </button>
        </form>

        <div class="pt-4 mt-6 border-t border-slate-800 text-center">
            <p class="text-xs text-slate-400">
                Não tem uma conta? 
                <a href="{{ route('register') }}" class="text-blue-400 hover:text-blue-300 font-semibold transition ml-1">Criar Conta Agora</a>
            </p>
        </div>

    </div>

</body>
</html>