<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OrderTracker Pro — Recuperar Senha</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
</head>
<body class="bg-slate-900 text-slate-100 min-h-screen flex flex-col justify-center items-center p-4">

    <div class="max-w-md w-full bg-slate-950 border border-slate-800 rounded-2xl p-6 md:p-8 shadow-2xl">
        
        <div class="flex flex-col items-center mb-6 text-center">
            <div class="bg-blue-600 p-3 rounded-xl text-white shadow-lg shadow-blue-500/30 mb-3">
                <i class="fa-solid fa-key text-2xl"></i>
            </div>
            <h3 class="font-bold text-white text-xl">Recuperar Senha</h3>
            <p class="text-xs text-slate-400 mt-2 leading-relaxed">
                Esqueceu sua senha? Sem problemas. Digite seu endereço de e-mail abaixo e enviaremos um link para você definir uma nova credencial.
            </p>
        </div>

        @if (session('status'))
            <div class="mb-4 text-emerald-400 text-xs bg-emerald-500/10 p-3 rounded-xl border border-emerald-500/20 text-center font-medium">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Endereço de E-mail</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus class="w-full bg-slate-900 border border-slate-800 rounded-xl px-4 py-3 text-sm focus:outline-none focus:border-blue-500 text-slate-200 placeholder-slate-600 transition" placeholder="seu@email.com">
                @if($errors->has('email'))
                    <p class="mt-1.5 text-xs text-rose-400 font-medium">{{ $errors->first('email') }}</p>
                @endif
            </div>

            <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 text-white py-3 rounded-xl text-sm font-semibold shadow-lg shadow-blue-500/10 transition mt-2">
                Enviar Link de Recuperação
                <i class="fa-solid fa-paper-plane ml-1.5 text-xs"></i>
            </button>
        </form>

        <div class="pt-4 mt-6 border-t border-slate-800 text-center">
            <p class="text-xs text-slate-400">
                Lembrou a senha? 
                <a href="{{ route('login') }}" class="text-blue-400 hover:text-blue-300 font-semibold transition ml-1">Voltar para o Login</a>
            </p>
        </div>

    </div>

</body>
</html>