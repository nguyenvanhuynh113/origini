<div class="product__discount">
    <div class="section-title product__discount__title">
        <h2>Giảm giá</h2>
    </div>
    <div class="row">
        <div class="product__discount__slider owl-carousel">
            @if(!is_null($sell))
                @foreach($sell as $item)
                    @php
                        $product=\Illuminate\Support\Facades\DB::table('products')->where('id','=',$item->id_product)->first()
                    @endphp
                    <div class="col-lg-4">
                        <div class="product__discount__item">
                            <div class="product__discount__item__pic set-bg"
                                 data-setbg="http://127.0.0.1:8000/storage/{{$product->image}}">
                                <div class="product__discount__percent">-{{$item->sell_discount}}%</div>
                                <ul class="product__item__pic__hover">
                                    <li>
                                        <a href="{{\Illuminate\Support\Facades\URL::to('shop_details/'.$product->slug)}}"><i
                                                class="fa fa-retweet"></i></a></li>
                                    @guest
                                        <li>
                                            <a><i class="fa fa-shopping-cart"></i></a></li>
                                    @else
                                        <li>
                                            <a href="{{\Illuminate\Support\Facades\URL::to('add_to_cart/'.$product->slug)}}"><i
                                                    class="fa fa-shopping-cart"></i></a></li>
                                    @endguest

                                </ul>
                            </div>
                            <div class="product__discount__item__text">
                                <h5>
                                    <a href="{{\Illuminate\Support\Facades\URL::to('shop_details/'.$product->slug)}}">{{$product->name}}</a>
                                </h5>
                                <div
                                    class="product__item__price">{{$product->unit_prices-(($product->unit_prices * $item->sell_discount)/100) }}
                                    đ <span>{{$product->unit_prices}}</span></div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>
</div>
