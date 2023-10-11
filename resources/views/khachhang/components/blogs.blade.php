<!-- Blog Section Begin -->
<section class="from-blog spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title from-blog__title">
                    <h2>Bài viết</h2>
                </div>
            </div>
        </div>
        <div class="row">
            @if(empty($from_blogs))
                <h2 class="justify-content-center text-center"> Không có bài viết</h2>
            @else
                @foreach($from_blogs as $item)
                    <div class="col-lg-4 col-md-4 col-sm-6">
                        <div class="blog__item">
                            <div class="blog__item__pic">
                                <img src="http://127.0.0.1:8000/storage/{{$item->image}}" alt=""
                                     style="max-height: 200px">
                            </div>
                            <div class="blog__item__text">
                                <ul>
                                    <li>
                                        <i class="fa fa-calendar-o"></i> {{ \Carbon\Carbon::parse($item->created_at)->format('Y-m-d') }}
                                    </li>
                                    <li><i class="fa fa-comment-o"></i> 5</li>
                                </ul>
                                <h5>
                                    <a href="{{\Illuminate\Support\Facades\URL::to('/blog_details/'.$item->slug)}}">{!! \Illuminate\Support\Str::limit($item->title,100)!!}</a>
                                    <p>{!! \Illuminate\Support\Str::limit($item->content,100) !!}</p>
                                </h5>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif

        </div>

    </div>
</section>
<!-- Blog Section End -->
