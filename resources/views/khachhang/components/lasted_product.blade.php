<!-- Latest Product Section Begin -->
<section class="latest-product spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="latest-product__text">
                    <h4>Sản phẩm mới</h4>
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
            <div class="col-lg-4 col-md-6">
                <div class="latest-product__text">
                    <h4>Sản phẩm nổi bật</h4>
                    <div class="latest-product__slider owl-carousel">
                        <div class="latest-prdouct__slider__item">
                            @foreach($top_rate_product as $item)
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
            <div class="col-lg-4 col-md-6">
                <div class="latest-product__text">
                    <h4>Sản phẩm giảm giá</h4>
                    <div class="latest-product__slider owl-carousel">
                        <div class="latest-prdouct__slider__item">
                            @foreach($review_product as $item)
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
</section>
<!-- Latest Product Section End -->
