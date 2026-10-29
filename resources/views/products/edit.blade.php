<x-layout title="Rediģēt produktu">
    <div class="page-head">
        <h1>Rediģēt: {{ $product->name }}</h1>
    </div>

    @include('products._form', [
        'action' => route('products.update', $product),
        'method' => 'PUT',
        'product' => $product,
        'submit' => 'Atjaunināt',
    ])
</x-layout>
