{{-- Paziņojums par veiksmīgu darbību: ->with('success', '...') no kontroliera --}}
@if (session('success'))
    <p class="flash flash-success" role="status">{{ session('success') }}</p>
@endif
