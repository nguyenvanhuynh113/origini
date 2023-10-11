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
    <section class="breadcrumb-section set-bg" data-setbg="/img/breadcrumb.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb__text">
                        <h2>Organi Shop</h2>
                        <div class="breadcrumb__option">
                            <a href="{{\Illuminate\Support\Facades\URL::to('/')}}">Trang chủ</a>
                            <span>Cửa hàng | {{$category->name}}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Product Section Begin -->
    <section class="product spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 col-md-5">
                    <div class="sidebar">
                        <div class="sidebar__item">
                            <h4>Danh mục</h4>
                            <ul>
                                <li><a href="#">All</a></li>
                                @foreach($categories as $item)
                                    @php
                                        $count = $item->products->count(); // Đếm số lượng sản phẩm
                                    @endphp
                                    <li>
                                        <a href="#">
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
                        <div class="sidebar__item">
                            <div class="latest-product__text">
                                <h4>Sản Phẩm Mới</h4>
                                <div class="latest-product__slider owl-carousel">
                                    <div class="latest-prdouct__slider__item">
                                        @foreach($lasted_products as $item)
                                            <a href="{{\Illuminate\Support\Facades\URL::to('shop_details/'.$item->slug)}}"
                                               class="latest-product__item">
                                                <div class="latest-product__item__pic">
                                                    <img src="http://127.0.0.1:8000/storage/{{$item->image}}" alt="">
                                                </div>
                                                <div class="latest-product__item__text">
                                                    <h6>{{$item->name}}</h6>
                                                    <span>{{$item->unit_prices}} d</span>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-9 col-md-7">
                    @if(session()->has('message'))
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
                    @include('khachhang.components.shop.sell')
                    <div class="filter__item">
                        <div class="row">
                            <div class="col-lg-4 col-md-5">
                                <div class="filter__sort">
                                    <span>Sắp xếp</span>
                                    <select>
                                        <option value="0">Mặc định</option>
                                        <option value="0">Mặc định</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4">
                                <div class="filter__found">
                                    <h6><span>{{$count_product}}</span> Sản phẩm</h6>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-3">
                                <div class="filter__option">
                                    <span class="fa fa-th"></span>
                                    <span class="fa fa-list"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @foreach($product as $item)
                            <div class="col-lg-4 col-md-6 col-sm-6">
                                <div class="product__item">
                                    <div class="product__item__pic set-bg"
                                         data-setbg="http://127.0.0.1:8000/storage/{{$item->image}}">
                                        <ul class="product__item__pic__hover">
                                            <li><a href="#"><i class="fa fa-heart"></i></a></li>
                                            <li>
                                                <a href="{{\Illuminate\Support\Facades\URL::to('shop_details/'.$item->slug)}}"><i
                                                        class="fa fa-retweet"></i></a></li>
                                            <li>
                                                <a href="{{\Illuminate\Support\Facades\URL::to('add_to_cart/'.$item->slug)}}"><i
                                                        class="fa fa-shopping-cart"></i></a></li>
                                        </ul>
                                    </div>
                                    <div class="product__item__text">
                                        <h6>
                                            <a href="{{\Illuminate\Support\Facades\URL::to('shop_details/'.$item->slug)}}">{{$item->name}}</a>
                                        </h6>
                                        <h5>{{$item->unit_prices}} d</h5>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="col-lg-12">
                        <div class="product__pagination blog__pagination">
                            {{ $product->links('khachhang.vendor.pagination.custom_pagination') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Product Section End -->
    @include('khachhang.layout.footer')
@endsection
