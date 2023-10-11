<!-- Page Preloder -->
<div id="preloder">
    <div class="loader"></div>
</div>

<!-- Humberger Begin -->
<div class="humberger__menu__overlay"></div>
<div class="humberger__menu__wrapper">
    <div class="humberger__menu__logo">
        <a href="{{\Illuminate\Support\Facades\URL::to('/')}}"><img src="/img/logo.png" alt=""></a>
    </div>
    @guest
        <div class="col-lg-3">
            <div class="header__cart">
                <ul>
                    <li><a href="#"><i class="fa fa-heart"></i> <span>1</span></a></li>
                    <li><a href="{{\Illuminate\Support\Facades\URL::to('/cart')}}"><i
                                class="fa fa-shopping-bag"></i> <span>3</span></a></li>
                </ul>
            </div>
        </div>
    @else
        @php
            // Lấy id từ người dùng đã đăng nhập
            $id_user=auth()->user()->id;
            // Lấy thông tin giỏ hàng của người dùng
            $cart = DB::table('cart_items')
                           ->join('carts', 'carts.id', '=', 'cart_items.id_cart')
                           ->join('products', 'cart_items.id_product', '=', 'products.id')
                           ->where('carts.id_user', $id_user)
                           ->get(['products.name as name', 'products.image as image', 'products.unit_prices as unit_prices', 'cart_items.*', 'carts.*']);
           //Đếm số lượng sản  phẩm trong giỏ hàng
            $count_cart = $cart->count();
        @endphp
        <div class="col-lg-3">
            <div class="header__cart">
                <ul>
                    <li><a href="#"><i class="fa fa-heart"></i> <span>1</span></a></li>
                    <li><a href="{{\Illuminate\Support\Facades\URL::to('/cart')}}"><i
                                class="fa fa-shopping-bag"></i> <span>{{$count_cart}}</span></a></li>
                </ul>
            </div>
        </div>
    @endguest
    <nav class="humberger__menu__nav mobile-menu">
        <ul>
            <li class="active"><a href="{{\Illuminate\Support\Facades\URL::to('/')}}">Trang chủ</a></li>
            <li><a href="{{\Illuminate\Support\Facades\URL::to('/shop')}}">Cửa hàng</a></li>
            <li><a href="{{\Illuminate\Support\Facades\URL::to('/blog')}}">Bài viết</a></li>
            <li><a href="{{\Illuminate\Support\Facades\URL::to('/contact')}}">Liên hệ</a></li>
        </ul>
    </nav>
    <div id="mobile-menu-wrap"></div>
    <div class="header__top__right__social">
        <a href="#"><i class="fa fa-facebook"></i></a>
        <a href="#"><i class="fa fa-twitter"></i></a>
        <a href="#"><i class="fa fa-linkedin"></i></a>
        <a href="#"><i class="fa fa-pinterest-p"></i></a>
    </div>
    <div class="humberger__menu__contact">
        <ul>
            <li><i class="fa fa-envelope"></i> origi@nongsansach.com</li>
            <li>Miễn phí vận chuyển cho đơn hàng từ 99,000 đ</li>
        </ul>
    </div>
</div>
<!-- Humberger End -->

<!-- Header Section Begin -->
<header class="header">
    <div class="header__top">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="header__top__left">
                        <ul>
                            <li><i class="fa fa-envelope"></i> origi@nongsansach.com</li>
                            <li>Miễn phí vận chuyển cho đơn hàng từ 99,000 đ</li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="header__top__right">
                        <div class="header__top__right__social">
                            <a href="#"><i class="fa fa-facebook"></i></a>
                            <a href="#"><i class="fa fa-twitter"></i></a>
                            <a href="#"><i class="fa fa-linkedin"></i></a>
                            <a href="#"><i class="fa fa-pinterest-p"></i></a>
                        </div>
                        @guest
                            <div class="header__top__right__auth">
                                <a href="{{\Illuminate\Support\Facades\URL::to('get-login')}}"><i
                                        class="fa fa-user"></i>Đăng nhập</a>
                            </div>
                        @else
                            <div class="header__top__right__language">
                                <div class="header__top__right__auth">
                                    <a href="#"><i class="fa fa-user"></i> {{auth()->user()->name}}</a>
                                </div>
                                <span class="fa fa-angle-down"></span>
                                <ul>
                                    <li><a href="{{\Illuminate\Support\Facades\URL::to('logout')}}"><i
                                                class="fa fa-sign-out"></i> Đăng xuất</a></li>
                                </ul>
                            </div>
                        @endguest

                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
        <div class="humberger__open">
            <i class="fa fa-bars"></i>
        </div>
    </div>
</header>
<!-- Header Section End -->
