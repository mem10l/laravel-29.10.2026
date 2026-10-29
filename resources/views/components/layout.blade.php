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
        <a href="{{ route('home') }}">{{ config('app.name') }}</a>
    </header>

    <nav aria-label="Galvenā navigācija">
        <ul>
            <li><a href="{{ route('products.index') }}">Produkti</a></li>
            <li><a href="{{ route('products.create') }}">Jauns produkts</a></li>
        </ul>
    </nav>

    <main>
        @if (session('success'))
            <p role="status">{{ session('success') }}</p>
        @endif

        @if ($errors->any())
            <div role="alert">
                <strong>Lūdzu, izlabojiet kļūdas:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{ $slot }}
    </main>

    <aside>
        @isset($aside)
            {{ $aside }}
        @else
            <h2>Informācija</h2>
            <p>Produktu pārvaldība: skatīt, pievienot, rediģēt un dzēst.</p>
        @endisset
    </aside>

    <footer>
        <p>&copy; {{ date('Y') }} {{ config('app.name') }}</p>
    </footer>
</body>
</html>
