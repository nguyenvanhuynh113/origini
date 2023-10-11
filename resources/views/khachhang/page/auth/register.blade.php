<style>
    body {
        background-color: #f5f5f5;
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

    .btn-link {
        color: #069306;
    }

    .btn-link:hover {
        text-decoration: none;
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
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                @if(session()->has('message'))
                    <div class="alert alert-success" id="messageAlert">
                        {{ session()->get('message') }}
                    </div>
                @endif
                @if(session()->has('error'))
                    <div class="alert alert-success" id="messageAlert">
                        {{ session()->get('message') }}
                    </div>
                @endif
                <script>
                    // Tự động ẩn thông báo sau 30 giây
                    setTimeout(function () {
                        document.getElementById('messageAlert').style.display = 'none';
                    }, 10000); // 10 giây
                </script>
                <div class="card">
                    <div class="card-body my-5 mx-3">
                        <h3 class="card-title text-center mb-4">ĐĂNG KÝ</h3>
                        <form action="{{route('register')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="form2Example1">Họ và tên</label>
                                <input type="text" class="form-control" id="form2Example1" name="name"
                                       value="{{ old('name') }}"
                                       placeholder="Nhập họ và tên của bạn">
                            </div>
                            <div class="form-group">
                                <label for="form2Example2">Địa chỉ email</label>
                                <input type="email" class="form-control" id="form2Example2" name="email"
                                       value="{{ old('email') }}"
                                       placeholder="Nhập địa chỉ email của bạn">
                            </div>
                            <div class="form-group">
                                <label for="form2Example3">Mật khẩu</label>
                                <input type="password" class="form-control" id="form2Example3" name="password"
                                       value="{{ old('password') }}"
                                       placeholder="Nhập mật khẩu">
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" value="true"
                                       name="agree" id="form2Example31" checked>
                                <label class="form-check-label" for="form2Example31">Đồng ý điều khoản</label>
                            </div>
                            <button type="submit" class="btn btn-success btn-block">Đăng ký</button>
                        </form>
                        <hr>
                        <div class="text-center">
                            <p class="mb-0">Đã có tài khoản? <a
                                    href="{{\Illuminate\Support\Facades\URL::to('get-login')}}">Đăng nhập</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
