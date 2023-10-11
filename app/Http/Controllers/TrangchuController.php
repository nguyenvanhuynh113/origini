<?php

namespace App\Http\Controllers;

use App\Filament\Resources\MessageResource;
use App\Filament\Resources\OderResource;
use App\Models\Blog;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Category;
use App\Models\Message;
use App\Models\Product;
use App\Models\Type;
use App\Models\User;
use Carbon\Carbon;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TrangchuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // View Home
    public function index()
    {
        // Danh mục sản phẩm -- ( hiển thị hình ảnh )
        $categories = Category::all()->where('active', '=', '1')
            ->where('deleted_at', '=', null);
        // Từ khóa tìm kiếm
        $tags = DB::table('tags')->where('deleted_at', '=', null)
            ->orWhere('active', '=', 1)->get();
        // Sản phẩm mới
        $lasted_products = DB::table('products')->where('deleted_at', '=', null)
            ->orderByDesc('created_at')->get()->take(4);
        // Lượt đánh giá cao nhất
        $top_rate_product = DB::table('products')->where('deleted_at', '=', null)
            ->inRandomOrder()->get()->take(4);
        // Sản phẩm nhiều bình luận
        $review_product = DB::table('sells')->join('products', 'products.id', '=', 'sells.id_product')->get()->take(4);
        // Những bài viết mới
        $from_blogs = DB::table('blogs')->where('deleted_at', '=', null)->inRandomOrder()->get()->take(6);

        return view('khachhang/page/trangchu',
            compact('categories', 'tags', 'lasted_products', 'top_rate_product',
                'review_product', 'from_blogs'));
    }

    // View Shop
    public function shop()
    {
        // Lấy ngẫu nhiên sản phẩm -- Mỗi trang tối đa 12 sản phẩm
        $product = DB::table('products')->inRandomOrder()->paginate(12);
        // Đếm sản phẩm mỗi trang
        $count_product = count($product);
        // Dnah mục sản phẩm
        $categories = Category::all()->where('active', '=', '1')
            ->where('deleted_at', '=', null);
        $lasted_products = DB::table('products')->where('deleted_at', '=', null)
            ->orderByDesc('created_at')->get()->take(6);
        // Lấy ngẫu nhiên sản phẩm từ DB
        $sell = DB::table('sells')->inRandomOrder()->get()->take(8);
        return view('khachhang/page/cuahang', compact('product',
            'count_product', 'lasted_products', 'categories', 'sell'));
    }

    // View details product
    public function shop_details($slug): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $categories = Category::all()->where('active', '=', '1')
            ->where('deleted_at', '=', null);
        // Lấy chi tiết sản phẩm dựa trên slug
        $details = Product::where('slug', $slug)->first();
        // Kiểm tra xem sản phẩm có tồn tại không
        if ($details) {
            // Nếu sản phẩm tồn tại, lấy danh mục của sản phẩm
            $categories = $details->categories;
            // Lấy tất cả sản phẩm cùng danh mục
            $related_products = $categories->flatMap(function ($category) {
                //Lấy tối đa 4 sản phẩm liên quan
                return $category->products->take(4);
            });
        }
        return view('khachhang/page/shop_details', compact('details',
            'related_products', 'categories'));
    }

    //View cart
    public function cart(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        // Kiểm tra đăng nhập
        if (Auth::check()) {
            //true
            $categories = Category::all()->where('active', '=', '1')
                ->where('deleted_at', '=', null);
            //Lấy tất cả sản phẩm trong giỏ hàng
            $id_user = auth()->user()->id;
            // Tính tổng tiền sản phẩm
            $total_cart = 0;
            $cart = DB::table('cart_items')
                ->join('carts', 'carts.id', '=', 'cart_items.id_cart')
                ->join('products', 'cart_items.id_product', '=', 'products.id')
                ->where('carts.id_user', $id_user)
                ->get(['products.name as name', 'products.quantity as product_quantity',
                    'products.image as image',
                    'products.unit_prices as unit_prices',
                    'cart_items.*', 'carts.*']);
            foreach ($cart as $key) {
                $total_cart = $total_cart + ($key->quantity * $key->unit_prices);
            }
            return view('khachhang/page/cart', compact('cart', 'categories', 'total_cart'));
        } else {
            $categories = Category::all()->where('active', '=', '1');
        }

        return view('khachhang/page/cart', compact('categories'));
    }

    // View Bài viết
    public function blog(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $blog = DB::table('blogs')->orderByDesc('created_at')->paginate(6);
        $types = Type::all()->where('active', '=', 1);
        $keys = DB::table('keys')->inRandomOrder()
            ->where('active', '=', 1)->take(6)->get();
        $categories = Category::all()->where('active', '=', '1')
            ->where('deleted_at', '=', null);
        $recent_blog = DB::table('blogs')->orderByDesc('created_at')->take(6)->get();
        return view('khachhang/page/blog', compact('blog', 'types',
            'recent_blog', 'keys', 'categories'));
    }

    // View chi tiết 1 bài viết
    public function blog_details($slug): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $blog_details = Blog::where('slug', $slug)->first();
        $types = Type::all()->where('active', '=', 1);
        $keys = DB::table('keys')->inRandomOrder()
            ->where('active', '=', 1)->take(6)->get();
        $categories = Category::all()->where('active', '=', '1')
            ->where('deleted_at', '=', null);
        $recent_blog = DB::table('blogs')->orderByDesc('created_at')->take(6)->get();
        // nếu tồn tại
        if ($blog_details) {
            $related_blogs = Blog::whereHas('types', function ($query) use ($blog_details) {
                $query->whereIn('id_type', $blog_details->types()->pluck('id_type'));
            })->where('id', '!=', $blog_details->id)->get();
        }
        return view('khachhang/page/blog_details',
            compact('blog_details', 'related_blogs', 'types',
                'keys', 'recent_blog', 'categories'));
    }

    // Bài viết theo thể loại
    public function blog_type($slug)
    {
        $types = Type::all()->where('active', '=', 1);
        $keys = DB::table('keys')->inRandomOrder()
            ->where('active', '=', 1)->take(6)->get();
        $categories = Category::all()->where('active', '=', '1')
            ->where('deleted_at', '=', null);
        $recent_blog = DB::table('blogs')->orderByDesc('created_at')->take(6)->get();
        $type = Type::all()->where('slug', '=', $slug)->first();
        $blog = $type->blogs()->paginate(6);
        return view('khachhang/page/blog_type', compact('blog', 'types', 'keys', 'recent_blog', 'categories'));

    }

    // View Thanh toán
    public function checkout(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        // Kiểm tra đăng nhập
        if (Auth::check()) {
            $total_cart = 0;
            // id người dùng đã đăng nhập
            $id_user = auth()->user()->id;
            //Lấy giá trị thời gian hiện tại
            $currentDate = Carbon::now();
            // $coupons bây giờ chứa tất cả các mã giảm giá còn hiệu lực trong ngày
            $coupon = DB::table('coupons')
                ->whereDate('coupon_star_date', '<=', $currentDate)
                ->whereDate('coupon_end_date', '>=', $currentDate)
                ->where('quantity', '>', 0)
                ->orderByDesc('discount_value') // Sắp xếp giảm dần theo mức giảm giá
                ->orderByDesc('quantity') // Sắp xếp giảm dần theo số lượng giảm giá còn lại
                ->first(); // Lấy mã giảm giá phù hợp nhất
            // Lấy thông tin giỏ hàng
            $cart = DB::table('cart_items')
                ->join('carts', 'carts.id', '=', 'cart_items.id_cart')
                ->join('products', 'cart_items.id_product', '=', 'products.id')
                ->where('carts.id_user', $id_user)
                ->get(['products.name as name', 'products.quantity as product_quantity',
                    'products.image as image', 'products.unit_prices as unit_prices', 'cart_items.*', 'carts.*']);
            // Tính tổng tiền sản phẩm trong gi hàng
            foreach ($cart as $key) {
                $total_cart = $total_cart + ($key->quantity * $key->unit_prices);
            }
            // Danh mục
            $categories = Category::all()->where('active', '=', '1')
                ->where('deleted_at', '=', null);
            return view('khachhang/page/checkout',
                compact('categories', 'cart', 'total_cart', 'coupon'));
        }
        return view('khachhang/page/checkout');
    }

    // View Liên hệ
    public function contact(): \Illuminate\Contracts\View\View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        // Danh mục
        $categories = Category::all()->where('active', '=', '1')
            ->where('deleted_at', '=', null);
        return view('khachhang/page/contact', compact('categories'));
    }

    // Xử lý message người dùng gởi lên hệ thống

    public function message(Request $request)
    {
        $request->validate([
            'name' => 'string|max:255|required',
            'email' => 'email|required|max:255',
            'content' => 'required|max:1000|string'
        ]);
        $message = Message::create(
            [
                'name' => $request->name,
                'email' => $request->email,
                'content' => $request->content
            ]
        );
        if ($message) {
            Notification::make()
                ->title('New Message')
                ->icon('heroicon-o-inbox-in')
                ->body("**{$request->name} Send message.**")
                ->actions([
                    Action::make('View')
                        ->url(MessageResource::getUrl('edit', ['record' => $message])),
                ])
                ->sendToDatabase(User::all());
            return redirect()->back()->with('message', 'Cảm ơn bạn đã đóng góp ý kiến đến chúng tôi');
        } else {
            return redirect()->back()->with('error', 'Lỗi! Vui lòng thử lại sau');
        }

    }

    // Them san pham vao gio hang
    public function add_to_cart($slug)
    {
        // Lấy id người dùng đang đăng nhập
        $id_user = auth()->user()->id;
        // Lấy thông tin giỏ hàng của người dùng thông qua id
        $cart_items = DB::table('cart_items')
            ->join('carts', 'carts.id', '=', 'cart_items.id_cart')
            ->rightJoin('products', 'cart_items.id_product', '=', 'products.id')
            ->where('carts.id_user', $id_user)
            ->get(['products.name as product_name', 'products.image as image', 'products.unit_prices as unit_prices', 'cart_items.*', 'carts.*']);
        // Lấy thông tin sản phẩm để thêm vào giỏ hàng
        $product = DB::table('products')->where('slug', $slug)->select('id')->first();
        // nếu tồn tại sản phẩm
        if ($product) {
            $id_product = $product->id;
            // Bây giờ $id_product chứa giá trị id của sản phẩm với slug tương ứng.
            $exists_product = DB::table('cart_items')->where('id_product', '=', $id_product)->first();
            // Kiểm tra sản phẩm có tồn tại trong giỏ hàng chưa
            if (is_null($exists_product)) {
                // khởi tạo tổng tiền giỏ hàng
                $total = 0;
                // tính toán giá trị total khi duyệt tất cả item trong giỏ hàng
                foreach ($cart_items as $item) {
                    $total = $total + ($item->quantity * $item->unit_prices);
                }
                // Tạo sản phẩm mới trong giỏ hàng
                $cart = Cart::create([
                    'id_user' => $id_user,
                    'total' => $total
                ]);
                CartItem::create(
                    [
                        'id_product' => $id_product,
                        'id_cart' => $cart->id,
                        'quantity' => 1
                    ]
                );
            } else {
                // Nếu sản phẩm đã tồn tại ---- Cập nhật số lượng sản phẩm
                DB::table('cart_items')->where('id_product', '=', $id_product)->update([
                    'quantity' => $exists_product->quantity + 1
                ]);
            }
            // Sử dụng $id_product theo nhu cầu của bạn.
        } else {
            // Xử lý khi không tìm thấy sản phẩm với slug tương ứng
        }
        return redirect()->back()->with('message', 'Thêm giỏ hàng thành công');
    }

    // Xóa sản phẩm trong giỏ hàng
    public function delete_product_cart($id)
    {
        DB::table('cart_items')->where('id_product', '=', $id)->delete();
        return redirect()->back()->with('message', 'Xóa thành công');
    }

    //Cập nhật số lượng sản phẩm trong giỏ hàng
    public function update_product_cart(Request $request, $id)
    {
        // Lấy thông tin sản phẩm
        $product = DB::table('products')->where('id', '=', $id)->first();
        // Số lượng mới mà người dùng muốn cập nhật
        $quantity = $request->quantity;
        // Kiểm tra số lượng --- Nếu số lượng muôn cập nhật lớn hơn số lượng hiên tại của sản phẩm
        if ($quantity > $product->quantity) {
            // Thêm tối đa số lượng mà sản phẩm đang có
            DB::table('cart_items')->where('id_product', '=', $id)->update([
                'quantity' => $product->quantity
            ]);
            // Trả về trang trước đó
            return redirect()->back()->with('message', 'Số lượng sản phẩm đã được cập nhật');
        } elseif ($quantity > 0) {
            // Nếu số lượng lớn hơn 0 --- Cặp nhật lại số lượng mới
            DB::table('cart_items')->where('id_product', '=', $id)->update([
                'quantity' => $quantity
            ]);
            return redirect()->back()->with('message', 'Số lượng sản phẩm đã được cập nhật');
        } else {
            // Xóa sản phẩm ra khỏi giỏ hàng khi số lượng == 0
            DB::table('cart_items')->where('id_product', '=', $id)->delete();
            return redirect()->back()->with('message', 'Xóa sản phẩm khỏi giỏ hàng thành công');
        }

    }

    // Danh sách sản phẩm theo danh mục sản phẩm
    public function category_product($slug)
    {
        // Lấy thông tin danh mục theo slug danh mục
        $category = Category::all()->where('slug', '=', $slug)
            ->where('active', '=', 1)
            ->where('deleted_at', '=', null)
            ->first();
        $sell = DB::table('sells')->inRandomOrder()->get()->take(8);
        // Lấy tất cả sản phẩm theo danh mục
        $product = $category->products()->paginate(12);
        $count_product = count($product);
        // Lấy tất cả danh mục ( Hiển thị navbar )
        $categories = Category::all()->where('active', '=', '1')
            ->where('deleted_at', '=', null);
        // Sản Phẩm mới cập nhật
        $lasted_products = DB::table('products')->where('deleted_at', '=', null)
            ->orderByDesc('created_at')->get()->take(6);
        return view('khachhang/page/product_categories',
            compact('product', 'category', 'sell',
                'categories', 'lasted_products', 'count_product'));
    }

    // Tìm kiếm hiển thị gợi ý sản phẩm
    public function search(Request $request)
    {
        // từ khóa do người dùng nhập vào
        $query = $request->input('query');
        // Thực hiện tìm kiếm theo tên sản phẩm
        $products = Product::where('name', 'like', "%$query%")->get();
        // Trả về chuỗi json kết quả
        return response()->json(['products' => $products]);
    }

    // Tìm kiếm sản phẩm
    public function search_product(Request $request)
    {
        // từ khóa do người dùng nhập vào
        $query = $request->input('query');
        // Tìm kiếm theo tên sản phẩm
        $product = DB::table('products')->where('name', 'like', "%$query%")->paginate(12);
        // Đếm item trong kết quả
        $count_product = count($product);
        $categories = Category::all()->where('active', '=', '1')
            ->where('deleted_at', '=', null);
        $lasted_products = DB::table('products')->where('deleted_at', '=', null)
            ->orderByDesc('created_at')->get()->take(6);
        $sell = DB::table('sells')->inRandomOrder()->get()->take(8);
        return view('khachhang/page/cuahang', compact('product',
            'count_product', 'lasted_products', 'categories', 'sell'));
    }

    // Tìm kiếm bài viết theo tiêu đề bài viết
    public function search_blog(Request $request)
    {
        // The loai bai viết
        $types = Type::all()->where('active', '=', 1);
        // Cac tu khoa bai viết
        $keys = DB::table('keys')->inRandomOrder()
            ->where('active', '=', 1)->take(6)->get();
        $categories = Category::all()->where('active', '=', '1')
            ->where('deleted_at', '=', null);
        // Bài viết liên quan
        $recent_blog = DB::table('blogs')->orderByDesc('created_at')->take(6)->get();
        // Lấy dữ liệu từ thanh tìm kiếm
        $query = $request->input('query');
        $blog = DB::table('blogs')
            ->where('title', 'like', "%$query%")
            ->paginate(6);
        return view('khachhang/page/blog', compact('blog', 'types', 'keys', 'categories', 'recent_blog'));
    }

    // Đăng xuất -- Đăng nhập -- Tạo tào khoản ( Auth ) //

    public function logout()
    {
        // Danh mục sản phẩm -- ( hiển thị hình ảnh )
        $categories = Category::all()->where('active', '=', '1')
            ->where('deleted_at', '=', null);
        // Từ khóa tìm kiếm
        $tags = DB::table('tags')->where('deleted_at', '=', null)
            ->orWhere('active', '=', 1)->get();
        // Sản phẩm mới
        $lasted_products = DB::table('products')->where('deleted_at', '=', null)
            ->orderByDesc('created_at')->get()->take(4);
        // Lượt đánh giá cao nhất
        $top_rate_product = DB::table('products')->where('deleted_at', '=', null)
            ->orderByDesc('created_at')->get()->take(4);
        // Sản phẩm nhiều bình luận
        $review_product = DB::table('products')->where('deleted_at', '=', null)
            ->orderByDesc('created_at')->get()->take(4);
        // Những bài viết mới
        $from_blogs = DB::table('blogs')->where('deleted_at', '=', null)
            ->orderByDesc('created_at')->get()->take(3);
        // Tat ca san pham
        $product = DB::table('products')->inRandomOrder()->take(12);
        Auth::logout();
        return view('khachhang/page/trangchu', compact('categories', 'tags',
            'lasted_products', 'top_rate_product', 'review_product', 'from_blogs', 'product'))
            ->with('message', 'Đăng xuất thành công! Hãy đăng nhập để mua hàng');
    }

    // View đăng nhập
    public function get_login()
    {
        return view('khachhang/page/auth/login');
    }

    // Xử lý đăng nhập
    public function login(Request $request)
    {
        // Danh mục sản phẩm -- ( hiển thị hình ảnh )
        $categories = Category::all()->where('active', '=', '1')
            ->where('deleted_at', '=', null);
        // Từ khóa tìm kiếm
        $tags = DB::table('tags')->where('deleted_at', '=', null)
            ->orWhere('active', '=', 1)->get();
        // Sản phẩm mới
        $lasted_products = DB::table('products')->where('deleted_at', '=', null)
            ->orderByDesc('created_at')->get()->take(4);
        // Lượt đánh giá cao nhất
        $top_rate_product = DB::table('products')->where('deleted_at', '=', null)
            ->orderByDesc('created_at')->get()->take(4);
        // Sản phẩm nhiều bình luận
        $review_product = DB::table('products')->where('deleted_at', '=', null)
            ->orderByDesc('created_at')->get()->take(4);
        // Những bài viết mới
        $from_blogs = DB::table('blogs')->where('deleted_at', '=', null)
            ->orderByDesc('created_at')->get()->take(3);

        $product = DB::table('products')->inRandomOrder()->get();
        // Lay request data tu form dang nhap
        $data = [
            'email' => $request->email,
            'password' => $request->password,
        ];
        // kiem tra dang nhap thanh cong hay khong
        if (Auth::attempt($data)) {
            //true
            return view('khachhang/page/trangchu', compact('categories', 'tags',
                'lasted_products', 'top_rate_product', 'review_product', 'from_blogs'))->with('message', 'Đăng nhập thành công');
        } else {
            //false
            return view('khachhang/page/auth/login')->with('message', 'Đăng nhập thất bại! Hãy thử lại');
        }
    }

    // View dang ki
    public function get_register()
    {
        return view('khachhang/page/auth/register');
    }

    // Xử lý đăng ký
    public function register(Request $request)
    {
        // Kiem tra validate du lieu
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);
        // Tạo tài khoản mới
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        if ($user) {
            // Nếu tạo thành công
            return redirect()->back()->with('message', 'Đăng ký tài khoản thành công');
        } else {
            // Thất bại
            return redirect()->back()->with('error', 'Đăng ký tài khoản thất bại');
        }
    }
}
