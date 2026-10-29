<x-layout :title="$product->name">
    <div class="page-head">
        <h1>{{ $product->name }}</h1>
        <div class="row-actions">
            <a href="{{ route('products.edit', $product) }}" class="btn btn-primary">Rediģēt</a>
            <form action="{{ route('products.destroy', $product) }}" method="POST"
                  data-confirm="Vai tiešām dzēst produktu “{{ $product->name }}”?">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Dzēst</button>
            </form>
        </div>
    </div>

    <dl class="details">
        <dt>ID</dt>
        <dd>{{ $product->id }}</dd>

        <dt>Apraksts</dt>
        <dd>{{ $product->description ?: '—' }}</dd>

        <dt>Cena</dt>
        <dd>{{ number_format($product->price, 2) }} €</dd>

        <dt>Daudzums</dt>
        <dd>{{ $product->quantity }}</dd>

        <dt>Izveidots</dt>
        <dd>{{ $product->created_at->format('d.m.Y H:i') }}</dd>

        <dt>Atjaunināts</dt>
        <dd>{{ $product->updated_at->format('d.m.Y H:i') }}</dd>
    </dl>

    <p><a href="{{ route('products.index') }}">← Atpakaļ uz sarakstu</a></p>
</x-layout>
