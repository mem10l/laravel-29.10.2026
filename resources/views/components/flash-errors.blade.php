{{-- Validācijas kļūdu saraksts: $errors ir pieejams visos skatos --}}
@if ($errors->any())
    <div class="flash flash-error" role="alert">
        <strong>Lūdzu, izlabojiet kļūdas:</strong>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
