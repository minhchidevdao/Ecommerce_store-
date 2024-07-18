@extends('front-end.layouts.app')
@section('title', 'Thanks You')
@section('contents')
    <section class="container">
        @if (Session::has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ Session::get('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
          </div>
        @endif
        <div class="col-md-12 text-center py-5">
            <h1>Thank You</h1>
        </div>
    </section>

@endsection
