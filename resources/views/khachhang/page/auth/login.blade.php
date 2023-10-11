<style>
    body {

        background-color: #e2e8f0;
    }

    .card {
        background: rgba(255, 255, 255, 0.8); /* Đặt nền mờ cho card */
        border: none;
        border-radius: 15px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .card-title {
        color: #333;
    }

    .form-group label {
        color: #555;
    }

    .form-check-label {
        color: #06a90a;
    }

    #btn-signin {
        background-color: #008000; /* Màu xanh lá cây đậm */
        border: none;
        color: #fff; /* Màu chữ trắng */
    }

    .btn-primary {
        background-color: #008000; /* Màu xanh lá cây đậm */
        border: none;
        color: #fff; /* Màu chữ trắng */
    }

    .btn-primary:hover {
        background-color: #006400; /* Màu xanh lá cây đậm sáng hơn khi hover */
    }
</style>
@extends('khachhang.layout.auth')
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 my-5">
                <div class="card">
                    <div class="card-body my-5 mx-3">
                        <h3 class="card-title text-center mb-4">ĐĂNG NHẬP</h3>
                        <form action="{{route('login')}}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="form2Example1">Địa chỉ email</label>
                                <input type="email" class="form-control" id="form2Example1" name="email"
                                       placeholder="Nhập địa chỉ email của bạn">
                            </div>
                            <div class="form-group">
                                <label for="form2Example2">Mật khẩu</label>
                                <input type="password" class="form-control" id="form2Example2" name="password"
                                       placeholder="Mật khẩu">
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" value="" name="remember"
                                       id="form2Example31" checked>
                                <label class="form-check-label" for="form2Example31">Lưu đăng nhập</label>
                            </div>
                            <button type="submit" class="btn btn-primary btn-block" id="btn-signin">Đăng nhập</button>
                        </form>
                        <hr>
                        <div class="text-center">
                            <p class="mb-1">Quên mật khẩu? <a href="#">Lấy mật khẩu tại đây</a></p>
                            <p class="mb-0">Bạn chưa có tài khoản? <a
                                    href="{{\Illuminate\Support\Facades\URL::to('get-register')}}">Đăng ký tài khoản</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
