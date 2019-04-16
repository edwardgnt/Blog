@extends('layouts.app')

@section('content')     

  <!-- Main jumbotron for a primary marketing message or call to action -->
  <div class="jumbotron text-center">
        <div class="container">
            <h1>Welcome to Laravel</h1>
            <p>This is laravel application from the "Laravel from scratch" YouTube series.</p>
          <p><a class="btn btn-primary btn-lg" href="/login" role="button">Login</a> 
            <a class="btn btn-success btn-lg" href="/register" role="button">Register</a></p>
        </div>
      </div>
@endsection

