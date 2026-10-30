@props(['title' => 'Produkti'])

<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title }} — {{ config('app.name') }}</title>

    {{-- CSS un JS no resources mapes, ko apkalpo Vite serveris (npm run dev) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <header>
        <a href="{{ route('home') }}" class="logo">LOGO — testa logo teksts</a>
    </header>

    <nav aria-label="Galvenā navigācija">
        <ul>
            <li><a href="{{ route('products.index') }}">Produkti</a></li>
            <li><a href="{{ route('products.create') }}">Jauns produkts</a></li>
        </ul>
    </nav>

    <main>
        <x-flash-success />
        <x-flash-errors />

        {{ $slot }}
    </main>

    <aside>
        @isset($aside)
            {{ $aside }}
        @else
            <h2>Reklāma</h2>
            <p>Testa reklāmas teksts: pasūtiet tagad ar 20% atlaidi!</p>
        @endisset
    </aside>

    <footer>
        <p>&copy; {{ date('Y') }} Testa copyright teksts. Visas tiesības aizsargātas.</p>
    </footer>
</body>
</html>
