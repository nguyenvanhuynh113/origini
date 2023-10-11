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
    </section>>
    <!-- Breadcrumb Section Begin -->
    <section class="breadcrumb-section set-bg" data-setbg="/img/breadcrumb.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="breadcrumb__text">
                        <h2>Thanh toán</h2>
                        <div class="breadcrumb__option">
                            <a href="{{\Illuminate\Support\Facades\URL::to('/')}}">Trang chủ</a>
                            <span>Thanh toán</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->
    @if(auth()->user())
        @php
            $user=\Illuminate\Support\Facades\DB::table('users')
         ->where('id','=',auth()->user()->id)->first()
        @endphp
            <!-- Checkout Section Begin -->
        <section class="checkout spad">
            <div class="container">
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
                <div class="checkout__form">
                    <h4>Chi tiết đơn hàng</h4>
                    <form action="{{route('payment')}}" method="post">
                        @csrf
                        <div class="row">
                            <div class="col-lg-8 col-md-6">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="checkout__input">
                                            <p>Họ Tên<span>*</span></p>
                                            <input type="text" name="user_name" required
                                                   placeholder="Tên người đặt đơn hàng" value="{{$user->name}}"
                                                   disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="checkout__input">
                                    <p>Quốc gia<span>*</span></p>
                                    <input type="text" name="country" required placeholder="Quốc gia">
                                </div>
                                <div class="checkout__input">
                                    <p>Tỉnh/Thành phố<span>*</span></p>
                                    <input type="text" name="city" required placeholder="Thành phố/Tỉnh">
                                </div>
                                <div class="checkout__input">
                                    <p>Thị trấn/Huyện<span>*</span></p>
                                    <input type="text" name="province" required placeholder="Thị trấn/Huyện">
                                </div>
                                <div class="checkout__input">
                                    <p>Địa chỉ<span>*</span></p>
                                    <input type="text" placeholder="Tên đường/Số nhà/ngõ..."
                                           class="checkout__input__add"
                                           name="address" required>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="checkout__input">
                                            <p>Số điện thoại<span>*</span></p>
                                            <input type="text" required name="sdt">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="checkout__input">
                                            <p>Email<span>*</span></p>
                                            <input type="email" name="email" required value="{{$user->email}}" disabled>
                                        </div>
                                    </div>
                                </div>
                                <div class="checkout__input">
                                    <p>Ghi chú đơn hàng<span>*</span></p>
                                    <input type="text"
                                           placeholder="Ghi chú địa chỉ giao hàng..." name="note">
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="checkout__order">
                                    <h4>Đơn hàng của bạn</h4>
                                    <div class="checkout__order__products">Sản phẩm <span>Tổng</span></div>
                                    <ul>
                                        @foreach($cart as $item)
                                            <li>{{\Illuminate\Support\Str::limit($item->name,20).' ('.$item->quantity.')'}}
                                                <span>{{$item->unit_prices}} đ</span></li>
                                        @endforeach
                                    </ul>
                                    <div class="checkout__order__products">Mã giảm giá</div>
                                    <ul>
                                        <li>
                                            @if($total_cart === 0  || is_null($coupon))

                                            @else
                                                {{$coupon->code}} <span>{{$coupon->discount_value}} đ</span>
                                            @endif

                                        </li>
                                    </ul>
                                    <div class="checkout__order__products">Phí vận chuyển</div>
                                    <ul>
                                        @if( $total_cart > 99000 )
                                            @php $fee_ship=0 @endphp
                                            <li>
                                                Giao hàng nhanh - GHN<span class="text-danger">free</span>
                                            </li>
                                        @elseif($total_cart===0)
                                            <li>
                                                @php $fee_ship=0 @endphp
                                                Giao hàng nhanh - GHN <span> {{$fee_ship}} đ</span>
                                            </li>
                                        @else
                                            <li>
                                                @php $fee_ship=11000 @endphp
                                                Giao hàng nhanh - GHN <span> {{$fee_ship}} đ</span>
                                            </li>
                                        @endif

                                    </ul>
                                    <div class="checkout__order__subtotal">Tổng giá sản phẩm
                                        <span>{{$total_cart}} đ</span>
                                    </div>
                                    @php
                                        if($total_cart===0 || is_null($coupon)) {
                                             $total_oder = $total_cart  + $fee_ship;

                                        } else{
                                             $total_oder = ($total_cart - $coupon->discount_value) + $fee_ship;
                                           }

                                    @endphp
                                    <div class="checkout__order__total">Tổng đơn hàng<span>{{$total_oder}} đ</span>
                                    </div>
                                    <p></p>
                                    <div class="checkout__input__checkbox">
                                        <label>
                                            THANH TOÁN VN PAY
                                            <input type="radio" name="payment" value="vnpay">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <div class="checkout__input__checkbox">
                                        <label for="offline">
                                            Thanh toán khi nhận hàng
                                            <input type="radio" id="offline" name="payment" value="offline">
                                            <span class="checkmark"></span>
                                        </label>
                                    </div>
                                    <button type="submit" class="site-btn">ĐẶT HÀNG</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </section>
        <!-- Checkout Section End -->
    @else
        <!-- Checkout Section Begin -->
        <section class="checkout spad">
            <div class="container">
                <div class="checkout__form">
                    <h4>Vui lòng đăng nhập</h4>
                </div>
            </div>
        </section>
        <!-- Checkout Section End -->
    @endif
    @include('khachhang.layout.footer')
@endsection
