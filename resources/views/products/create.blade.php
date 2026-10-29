<x-layout title="Jauns produkts">
    <div class="page-head">
        <h1>Jauns produkts</h1>
    </div>

    @include('products._form', [
        'action' => route('products.store'),
        'method' => 'POST',
        'product' => new \App\Models\Product(),
        'submit' => 'Saglabāt',
    ])
</x-layout>
