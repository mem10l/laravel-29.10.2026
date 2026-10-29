<x-layout title="Produkti">
    <div class="page-head">
        <h1>Produkti</h1>
        <a href="{{ route('products.create') }}" class="btn btn-primary">+ Jauns produkts</a>
    </div>

    @if ($products->isEmpty())
        <p class="muted">Produktu vēl nav. <a href="{{ route('products.create') }}">Pievienojiet pirmo.</a></p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nosaukums</th>
                    <th class="num">Cena</th>
                    <th class="num">Daudzums</th>
                    <th>Darbības</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td><a href="{{ route('products.show', $product) }}">{{ $product->name }}</a></td>
                        <td class="num">{{ number_format($product->price, 2) }} €</td>
                        <td class="num">{{ $product->quantity }}</td>
                        <td class="row-actions">
                            <a href="{{ route('products.show', $product) }}" class="btn btn-sm">Skatīt</a>
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-sm">Rediģēt</a>
                            <form action="{{ route('products.destroy', $product) }}" method="POST"
                                  data-confirm="Vai tiešām dzēst produktu “{{ $product->name }}”?">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Dzēst</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="pagination">
            {{ $products->links() }}
        </div>
    @endif
</x-layout>
