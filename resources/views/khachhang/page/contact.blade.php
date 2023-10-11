@extends('khachhang.layout.main')
@section('content')
    <!-- Hero Section Begin -->
    <section class="hero hero-normal">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="hero__categories">
                        <div class="hero__categories__all">
                            <i class="fa fa-bars"></i>
                            <span>Tất cả danh mục</span>
                        </div>
                        <ul>
                            @foreach($categories as $item)
                                @php
                                    $count = $item->products->count(); // Đếm số lượng bài viết
                                @endphp
                                <li>
                                    <a href="{{\Illuminate\Support\Facades\URL::to('/category/'.$item->slug)}}">
                                        @if($count > 0)
                                            {{ $item->name . ' (' . $count . ')' }}
                                        @else
                                            {{ $item->name }}
                                        @endif
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="hero__search">
                        <div class="hero__search__form">
                            <form action="{{route('search-product')}}" method="POST">
                                @csrf
                                <div class="hero__search__categories">
                                    Danh mục
                                    <span class="fa fa-angle-down"></span>
                                </div>
                                <input type="text" id="searchInput" placeholder="Search for products" name="query">
                                <button type="submit" class="site-btn">TÌM KIẾM</button>
                            </form>
                        </div>
                        <div class="hero__search__phone">
                            <div class="hero__search__phone__icon">
                                <i class="fa fa-phone"></i>
                            </div>
                            <div class="hero__search__phone__text">
                                <h5>+84 9782622</h5>
                                <span>hỗ trợ 24/7</span>
                            </div>
                        </div>
                    </div>

                    <style>
                        /* Chỉnh sửa giao diện kết quả trả về của auto complete search */
                        #searchResults {
                            display: none;
                            max-height: 250px;
                            overflow-y: auto;
                            border: 1px solid #ccc;
                            padding: 10px;
                            background-color: #fff;
                            position: absolute; /* Đặt vị trí tuyệt đối */
                            width: 100%;
                            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
                            z-index: 1000;
                        }

                        #searchResults div {
                            padding: 5px;
                            cursor: pointer;
                        }

                        #searchResults div:hover {
                            background-color: #f0f0f0;
                        }

                        .product-item {
                            display: flex;
                            align-items: center;
                            padding: 10px;
                            cursor: pointer;
                            border-bottom: 1px solid #ccc;
                        }

                        .product-item img {
                            margin-right: 10px;
                            max-width: 50px; /* Điều chỉnh kích thước hình ảnh */
                            max-height: 50px;
                        }

                        .product-item .info {
                            flex: 1;
                        }
                    </style>
                    {{--Thẻ chứa kết quả trả về--}}
                    <div id="searchResults"></div>
                    {{--JQUERY xử lý kết quả người dùng nhập vào--}}
                    <script>
                        $(document).ready(function () {
                            $('#searchInput').on('input', function () {
                                const query = $(this).val();

                                // Làm ẩn hoặc hiện kết quả tùy thuộc vào có hay không kết quả
                                const searchResults = $('#searchResults');
                                if (query.length < 2) {
                                    searchResults.hide();
                                    return;
                                }

                                $.ajax({
                                    url: '/search', // Địa chỉ api search
                                    dataType: 'json',
                                    data: {
                                        query: query
                                    },
                                    success: function (data) {
                                        const products = data.products;
                                        const searchResults = $('#searchResults');
                                        searchResults.empty();

                                        if (products.length > 0) {
                                            $.each(products, function (index, product) {
                                                const productDiv = $('<div class="product-item">');

                                                // Thêm hình ảnh, tên và giá sản phẩm
                                                const productImage = $('<img>').attr('src', 'http://127.0.0.1:8000/storage/' + product.image);
                                                const productInfo = $('<div class="info">').html(`<strong>${product.name}</strong><br>Price: ${product.unit_prices} đ`);

                                                productDiv.append(productImage);
                                                productDiv.append(productInfo);

                                                productDiv.on('click', function () {
                                                    // Xử lý khi người dùng chọn sản phẩm
                                                    // Ví dụ: điều hướng đến trang sản phẩm
                                                    window.location.href = '/shop_details/' + product.slug;
                                                });

                                                searchResults.append(productDiv);
                                            });

                                            searchResults.show();  // Hiển thị kết quả khi có sản phẩm
                                        } else {
                                            searchResults.hide();  // Ẩn kết quả khi không có sản phẩm
                                        }
                                    }
                                });
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
    </section>
    <!-- Hero Section End -->

    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-section set-bg" data-setbg="/img/breadcrumb.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb__text">
                        <h2>Liên hệ</h2>
                        <div class="breadcrumb__option">
                            <a href="{{\Illuminate\Support\Facades\URL::to('/')}}">Home</a>
                            <span>Liên hệ</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Contact Section Begin -->
    <section class="contact spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-6 text-center">
                    <div class="contact__widget">
                        <span class="fa fa-phone"></span>
                        <h4>Số điện thoại</h4>
                        <p>+84-3-8888-6868</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 text-center">
                    <div class="contact__widget">
                        <span class="fa fa-map"></span>
                        <h4>Địa chỉ</h4>
                        <p>60-49 Road 11378 New York</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 text-center">
                    <div class="contact__widget">
                        <span class="fa fa-clock-o"></span>
                        <h4>Thời gian mở</h4>
                        <p>10:00 am to 23:00 pm</p>
                    </div>
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 text-center">
                    <div class="contact__widget">
                        <span class="fa fa-envelope-o"></span>
                        <h4>Email</h4>
                        <p>hello@colorlib.com</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Contact Section End -->

    <!-- Map Begin -->
    <div class="map">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3723.9813257454886!2d105.85431561532197!3d21.028513593926794!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3135aaf9d2f9774b%3A0xa9de4121cc3e2196!2sHanoi%2C%20Vietnam!5e0!3m2!1sen!2sus!4v1632094925436!5m2!1sen!2sus"
            height="500" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>

        <div class="map-inside">
            <i class="fa fa-map-marker"></i>
            <div class="inside-widget">
                <h4>Việt Nam</h4>
                <ul>
                    <li>Phone: +12-345-6789</li>
                    <li>Địa chỉ: Hà Nội, Việt Nam</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Map End -->

    <!-- Contact Form Begin -->
    <div class="contact-form spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="contact__form__title">
                        <h2>Để lại lời nhắn cho chúng tôi</h2>
                    </div>
                </div>
            </div>
            <form action="{{route('message')}}" method="post">
                @csrf
                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <input type="text" placeholder="Tên đầy đủ" name="name">
                        @error('name')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-lg-6 col-md-6">
                        <input type="text" placeholder="Email" name="email">
                        @error('email')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-lg-12 text-center">
                        <textarea placeholder="Nội dung" name="content"></textarea>
                        @error('content')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        <button type="submit" class="site-btn">Gửi</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- Contact Form End -->
    @include('khachhang.layout.footer')
@endsection
