{{--
    Kopīgā forma create un edit skatiem.
    Mainīgie: $action (URL no route()), $method (POST/PUT), $product (Product), $submit (pogas teksts)
--}}
<form action="{{ $action }}" method="POST" class="form">
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    <div class="field">
        <label for="name">Nosaukums</label>
        <input type="text" id="name" name="name" value="{{ old('name', $product->name) }}" required>
    </div>

    <div class="field">
        <label for="quantity">Daudzums</label>
        <input type="number" id="quantity" name="quantity" step="1" min="0" value="{{ old('quantity', $product->quantity ?? 0) }}" required>
    </div>

    <div class="field">
        <label for="description">Apraksts</label>
        <textarea id="description" name="description" rows="4">{{ old('description', $product->description) }}</textarea>
    </div>

    <div class="field">
        <label for="expiration_date">Derīguma termiņš</label>
        <input type="date" id="expiration_date" name="expiration_date"
               value="{{ old('expiration_date', $product->expiration_date?->format('Y-m-d')) }}">
    </div>

    <div class="field">
        <label for="status">Statuss</label>
        <select id="status" name="status" required>
            @foreach (\App\Models\Product::STATUSES as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $product->status ?? 'active') === $value)>
                    {{ $label }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="actions">
        <button type="submit" class="btn btn-primary">{{ $submit }}</button>
        <a href="{{ route('products.index') }}" class="btn">Atcelt</a>
    </div>
</form>
