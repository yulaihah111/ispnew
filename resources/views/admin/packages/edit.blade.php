@extends('layouts.admin')

@section('content')
<div class="mb-8">
    <h2 class="text-4xl font-bold text-slate-900">Edit Paket Internet</h2>
    <p class="mt-2 text-xl text-slate-500">Perbarui data paket internet</p>
</div>

<div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm lg:p-8">
    <form action="{{ route('admin.packages.update', $package) }}" method="POST">
        @method('PUT')
        @include('admin.packages._form')
    </form>
</div>
@endsection