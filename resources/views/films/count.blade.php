<h1>{{ $title }}</h1>
@if ($count == 0)
    <font color="red">No se ha encontrado ninguna pelicula</font>
@else
    <h2>{{ $count }}</h2>
@endif