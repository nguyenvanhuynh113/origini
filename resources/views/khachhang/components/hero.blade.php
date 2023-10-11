<!-- Hero Section Begin -->
<section class="hero">
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
                        max-height: 200px;
                        overflow-y: auto;
                        border: 1px solid #ccc;
                        padding: 10px;
                        background-color: #fff;
                        position: absolute; /* Đặt vị trí tuyệt đối */
                        width: 100%;
                        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
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
                <div class="hero__item set-bg" data-setbg="img/hero/banner.jpg">
                    <div class="hero__text">
                        <span>THỰC PHẨM SẠCH</span>
                        <h2>Rau xanh <br/>100% hữu cơ</h2>
                        <p>Giao hàng miễn phí</p>
                        <a href="{{\Illuminate\Support\Facades\URL::to('/shop')}}" class="primary-btn">MUA NGAY</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Hero Section End -->

