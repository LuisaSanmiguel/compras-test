@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-sm-12 text-center">
        		Bienvenido
        <br><br>
        <div class="row justify-content-around">
        <a class="col-md-3 jumbotron bg-primary text-white" href="{{url("/suppliers")}}">
            <i class="fa fa-users fa-3x" aria-hidden="true"></i>
            Proveedores
        </a>
       <a class="col-md-3 jumbotron bg-primary text-white" href="{{url("/products")}}">
            <i  class="fa fa-tags fa-3x" aria-hidden="true"></i>
            Productos
       </a>
        <a class="col-md-3  jumbotron bg-primary text-white" href="{{url("/purchases")}}">
            <i class="fa fa-shopping-cart fa-3x" aria-hidden="true"></i>
            Compras

        </a>
    </div>
</div>
    </div>
</div>
@endsection
