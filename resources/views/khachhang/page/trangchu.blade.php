@extends('khachhang.layout.main')
@section('content')
    @include('khachhang.components.hero')
    @include('khachhang.components.categories')
{{--    @include('khachhang.components.featured_product')--}}
    @include('khachhang.components.baner')
    @include('khachhang.components.lasted_product')
    @include('khachhang.components.blogs')
    @include('khachhang.layout.footer')
@endsection
