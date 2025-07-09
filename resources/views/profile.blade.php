@extends('layouts.app')
@section('title', 'Profil Pengguna')

@section('content')
<div class="container-fluid">
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-primary text-white">
            <h6 class="m-0 font-weight-bold">Profil Pengguna</h6>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-3 font-weight-bold">Nama</div>
                <div class="col-md-9">{{ auth()->user()->name }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 font-weight-bold">Email</div>
                <div class="col-md-9">{{ auth()->user()->email }}</div>
            </div>
            <div class="row mb-3">
                <div class="col-md-3 font-weight-bold">Role</div>
                <div class="col-md-9 text-capitalize">{{ auth()->user()->role }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
