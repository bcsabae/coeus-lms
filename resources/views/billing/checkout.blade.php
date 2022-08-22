@extends('layouts.app-full-width')

@section('title', 'Plans')

@section('content')
    <div class="bg-secondary border mt-7">
        <p>{{$item['name']}}</p>
        <p>{{$item['price']}}</p>
    </div>
    <a class="btn btn-secondary btn-outline">Proceed to payment</a>
@endsection
