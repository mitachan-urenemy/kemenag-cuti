@extends('layouts.auth')

@section('title','Login Admin')

@section('content')
    <h3>Login Admin</h3>

    {{-- ERROR MESSAGE --}}
    @if ($errors->any())
        <div style="background:#fee2e2;color:#991b1b;padding:8px;border-radius:4px;font-size:12px;margin-bottom:10px">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div class="form-group">
            <label>Username</label>
            <input
                type="email"
                name="email"
                placeholder="Masukkan username"
                required
            >
        </div>

        <div class="form-group">
            <label>Password</label>
            <input
                type="password"
                name="password"
                placeholder="Masukkan password"
                required
            >
        </div>

        <button type="submit">Masuk</button>

        <div class="demo">
            Demo Login:<br>
            Username: admin@kemenag.go.id<br>
            Password: admin123
        </div>
    </form>
@endsection
