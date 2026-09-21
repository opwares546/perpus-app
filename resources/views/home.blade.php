@extends('layouts.app')
@section('tittle', $judul)
@section('content')
    <h1>Selamat datang di {{ $judul }}</h1>
    <p>Ini adalah halaman utama aplikasi yang sedang di bangun pada mata kuliah pemograman Web II</p>
    @endsection