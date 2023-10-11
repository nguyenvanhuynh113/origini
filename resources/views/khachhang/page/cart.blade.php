@extends('khachhang.layout.main')
@section('content')
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

    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-section set-bg" data-setbg="img/breadcrumb.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb__text">
                        <h2>Giỏ hàng</h2>
                        <div class="breadcrumb__option">
                            <a href="{{\Illuminate\Support\Facades\URL::to('/')}}">Trang chủ</a>
                            <span>Giỏ hàng</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Shoping Cart Section Begin -->
    <section class="shoping-cart spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="shoping__cart__table">
                        <table>
                            <thead>
                            <tr>
                                <th class="shoping__product">Sản phẩm</th>
                                <th>Giá</th>
                                <th>Số lượng</th>
                                <th>Tổng</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(session()->has('message'))
                                <div class="alert alert-success" id="messageAlert">
                                    {{ session()->get('message') }}
                                </div>
                            @endif
                            <script>
                                // Tự động ẩn thông báo sau 30 giây
                                setTimeout(function () {
                                    document.getElementById('messageAlert').style.display = 'none';
                                }, 10000);
                            </script>
                            @guest
                            @else
                                @foreach($cart as $item)
                                    @php $total= $item->quantity * $item->unit_prices @endphp
                                    <tr>
                                        <td class="shoping__cart__item">
                                            <img src="http://127.0.0.1:8000/storage/{{$item->image}}" alt=""
                                                 style="max-height: 100px">
                                            <h5>{{$item->name}}</h5>
                                        </td>
                                        <td class="shoping__cart__price">
                                            {{$item->unit_prices}} d
                                        </td>
                                        <form action="{{route('update_product_cart', ['id' => $item->id_product])}}"
                                              method="post">
                                            @csrf
                                            @method('patch')
                                            <td class="shoping__cart__quantity">
                                                <div class="quantity">
                                                    <div class="pro-qty">
                                                        <input type="text" value="{{$item->quantity}}" name="quantity">
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="shoping__cart__total">
                                                {{$total}} đ
                                            </td>
                                            <td>
                                                <button type="submit" style="border: none"><span
                                                        class="fa fa-refresh"></span>
                                                </button>
                                            </td>
                                        </form>
                                        <td class="shoping__cart__item__close">
                                            <form action="{{route('delete_product_cart', ['id' => $item->id_product])}}"
                                                  method="post">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" style="border: none"><span
                                                        class="fa fa-close"></span>
                                                </button>
                                            </form>
                                        </td>

                                    </tr>
                                @endforeach
                            @endguest
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="shoping__cart__btns">
                        <a href="{{\Illuminate\Support\Facades\URL::to('/shop')}}" class="primary-btn cart-btn">
                            <i class="fa fa-backward"></i> TIẾP TỤC MUA SẮM</a>
                    </div>
                </div>
                @guest
                @else
                    <div class="col-lg-12">
                        <div class="shoping__checkout">
                            <h5>Tổng</h5>
                            <ul>
                                <li>Tổng tiền sản phẩm<span>{{ $total_cart }} đ</span></li>
                                <li>Tổng tiền đơn hàng<span>{{ $total_cart }} đ</span></li>
                            </ul>
                            @if($total_cart === 0)
                            @else
                                <a href="{{\Illuminate\Support\Facades\URL::to('checkout')}}" class="primary-btn">THANH
                                    TOÁN
                                    ĐƠN
                                    HÀNG</a>
                            @endif

                        </div>
                    </div>
                @endguest

            </div>
        </div>
    </section>
    <!-- Shoping Cart Section End -->
    @include('khachhang.layout.footer')
@endsection
