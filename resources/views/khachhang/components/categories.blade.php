<!-- Categories Section Begin -->
<section class="categories">
    <div class="container">
        <div class="row">
            <div class="categories__slider owl-carousel">
                @foreach($categories as $item)
                    <div class="col-lg-3">
                        <div class="categories__item set-bg" data-setbg="http://127.0.0.1:8000/storage/{{$item->image}}">
                            <h5><a href="{{\Illuminate\Support\Facades\URL::to('/category/'.$item->slug)}}">{{$item->name}}</a></h5>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
<!-- Categories Section End -->
