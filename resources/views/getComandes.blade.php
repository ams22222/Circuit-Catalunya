@extends('layouts.master')

@section('header')
<h2>Comandes:</h2>
@endsection

@section('token')
    {{ $tokenName }}
@endsection

@section('content')
<head>
    <meta charset="UTF-8">
    <title>Tabla de peticiones</title>
    <style>
        .table td:nth-child(4) {
    padding-right: 90px !important;
}
.table a.btn-primary {
    margin-right: 10px !important;
}
    </style>
</head>
<table class="table">
    <thead class="table-dark">
        <tr>
            <th scope="col">Email</th>
            <th scope="col">Data Solicitud</th>
            <th scope="col">Núm. Reserves</th>
            <th scope="col">Estat</th>
            <th scope="col" colspan="3"></th>
        </tr> 
    </thead>
    <tbody>
        @foreach($peticions as $peticio)
        <tr>
            <th scope="row">{{ $peticio->email }}</th>
            <td>{{ $peticio->data_solicitud }}</td>
            <td>{{ $peticio->entrades }}</td>
            <td>{{ $peticio->estat_comanda }}</td>
            <td>
                <a href="{{ url('pendents/'. $peticio->id . '/edit')}}" class="btn btn-success">Validar</a>
            </td>
            <td>
                <form action="{{url('pendentss',$peticio->id)}}" method='POST'>
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Rebutjar</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>

    <div class="container" style="margin-bottom:100px">
    <span>{{$peticions->links()}}</span>

        <style>
            .w-5{
                display: none;
            }
        </style>
    </div>
@endsection
