<?php

namespace App\Http\Controllers;

use App\Filament\Resources\OderResource;
use App\Mail\InvoiceMail;
use App\Models\Oder;
use App\Models\OderItem;
use App\Models\User;
use Carbon\Carbon;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CheckoutController extends Controller
{
    // Thanh toán đơn hàng

    public function payment(\Illuminate\Http\Request $request)
    {
        // Random mã đơn hàng
        $randomNumber = mt_rand(99999, 1000000);
        $orderCode = "OD-" . $randomNumber;
        // Lấy thông tin đăng nhập người dùng
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
        // Thông tin giỏ hàng -- Tính tổng tiền sản phẩm
        $cart = DB::table('cart_items')
            ->join('carts', 'carts.id', '=', 'cart_items.id_cart')
            ->join('products', 'cart_items.id_product', '=', 'products.id')
            ->where('carts.id_user', $id_user)
            ->get(['products.name as name', 'products.quantity as product_quantity',
                'products.image as image', 'products.unit_prices as unit_prices', 'cart_items.*', 'carts.*']);
        // Tổng giá trị đơn hàng
        $total_cart = 0;
        foreach ($cart as $key) {
            $total_cart = $total_cart + ($key->quantity * $key->unit_prices);
        }
        // Nếu tổng tiền lớn hơn 99000 -- miễn phí giao hàng -- Ngược lại 11000
        // Tổng tiền đơn hàng = Tổng tiền sản phẩm + voucher + phí giao hàng
        if ($total_cart > 99000) {
            if ($coupon) {
                $total_oder = ($total_cart - $coupon->discount_value);
            } else {
                $total_oder = $total_cart;
            }
        } else {
            if ($coupon) {
                $total_oder = ($total_cart - $coupon->discount_value) + 11000;
            } else {
                $total_oder = $total_cart + 11000;
            }
        }
        // Lấy thông tin từ form thanh toán
        $user_name = $request->user_name;
        $email = $request->email;
        $phone = $request->sdt;
        $country = $request->country;
        $address = $request->address;
        $city = $request->city;
        // phương thức thanh toán
        $payment = $request->payment;;
        // Thanh toán paypal
        if ($payment === 'vnpay') {
            //Cau hinh vn_pay
            error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            // trang thanh toan VNPAY
            $vnp_Url = "https://sandbox.vnpayment.vn/paymentv2/vpcpay.html";
            // URL tra ve khi thanh toan thanh cong
            $vnp_Returnurl = "http://127.0.0.1:8000/checkout";
            // Tai khoan mk sanbox vnpay -- Đăng kí tài khoản test tại trang chủ
            $vnp_TmnCode = "N64WNFSX";//Mã website tại VNPAY
            $vnp_HashSecret = "EEIZYGHTNYAXGDCRYNQTDEAYGFIZQJSN"; //Chuỗi bí mật
            // Cau hinh thong tin don hang
            $vnp_TxnRef = $orderCode; //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
            $vnp_OrderInfo = 'Thanh toán đơn hàng'; // Thong tin don hang
            $vnp_Amount = $total_oder * 100; // Gia tri don hang
            $vnp_Locale = 'vn';
            $vnp_OrderType = 'payment';

            $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];
            $inputData = array(
                "vnp_Version" => "2.1.0",
                "vnp_TmnCode" => $vnp_TmnCode,
                "vnp_Amount" => $vnp_Amount,
                "vnp_Command" => "pay",
                "vnp_CreateDate" => date('YmdHis'),
                "vnp_CurrCode" => "VND",
                "vnp_IpAddr" => $vnp_IpAddr,
                "vnp_Locale" => $vnp_Locale,
                "vnp_OrderInfo" => $vnp_OrderInfo,
                "vnp_OrderType" => $vnp_OrderType,
                "vnp_ReturnUrl" => $vnp_Returnurl,
                "vnp_TxnRef" => $vnp_TxnRef,
            );

            if (isset($vnp_BankCode) && $vnp_BankCode != "") {
                $inputData['vnp_BankCode'] = $vnp_BankCode;
            }
            if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
                $inputData['vnp_Bill_State'] = $vnp_Bill_State;
            }
            //Sap xep
            ksort($inputData);
            $query = "";
            $i = 0;
            $hashdata = "";
            foreach ($inputData as $key => $value) {
                if ($i == 1) {
                    $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
                } else {
                    $hashdata .= urlencode($key) . "=" . urlencode($value);
                    $i = 1;
                }
                $query .= urlencode($key) . "=" . urlencode($value) . '&';
            }

            $vnp_Url = $vnp_Url . "?" . $query;

            if (isset($vnp_HashSecret)) {
                $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);//
                $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
            }
            $returnData = array('code' => '00'
            , 'message' => 'Đặt hàng thành công! Kiểm tra email để xem hóa đơn đặt hàng'
            , 'data' => $vnp_Url);
            if (isset($_POST['redirect'])) {
                header('Location: ' . $vnp_Url);
                die();
            } else {
                echo json_encode($returnData);
            }
            $name = auth()->user()->name;
            $oder = Oder::create([
                'number' => $orderCode,
                'id_user' => auth()->user()->id,
                'total_price' => $total_oder,
                'country' => $country,
                'city' => $city,
                'address' => $address,
                'phone_number' => $phone,
                'payment' => 'THANH TOÁN VN PAY'
            ]);
            if ($oder) {
                $id_oder = $oder->id;
                $items = DB::table('carts')->join('cart_items', 'carts.id', '=', 'cart_items.id_cart')
                    ->where('carts.id_user', '=', $id_user)->get();
                foreach ($items as $item) {
                    // Lấy thông tin sản phẩm từ bảng products
                    $product = DB::table('products')->where('id', $item->id_product)->first();
                    // Create OrderItem
                    $oder_item = OderItem::create([
                        'id_oder' => $id_oder,
                        'id_product' => $product->id,
                        'quantity' => $item->quantity,
                        'unit_prices' => $product->unit_prices
                    ]);
                }
                if ($oder_item) {
                    // Cập nhật số lượng sản phẩm nếu đơn hàng tạo thành công
                    // Lấy tất cả item trong bảng chi tiêt đơn hàng vừa tạo
                    $get_items_oder = DB::table('oder_items')->where('id_oder', '=', $id_oder)->get();
                    //Duyệt
                    foreach ($get_items_oder as $product) {
                        //Gán id_product
                        $product_id = $product->id_product;
                        // Lấy thông tin product có id === id_product vừa gán ( để lấy số lượng sản phẩm)
                        $quantity_product = DB::table('products')
                            ->where('id', '=', $product_id)->first();
                        // gán số lượng sản phẩm
                        $quantity = $quantity_product->quantity;
                        // gán số lượng sản phẩm mới sau khi tạo đơn hàng
                        $new_quantity = $quantity - $product->quantity;
                        // cập nhật sản phẩm
                        DB::table('products')->where('id', '=', $product_id)
                            ->update([
                                'quantity' => $new_quantity
                            ]);
                    }
                    // Xóa toàn bộ giỏ hàng sau khi tạo đơn hàng
                    DB::table('carts')->join('cart_items', 'carts.id', '=', 'cart_items.id_cart')
                        ->where('carts.id_user', '=', $id_user)->delete();
                    // Gởi thong báo về admin
                    Notification::make()
                        ->title('New order')
                        ->icon('heroicon-o-shopping-bag')
                        ->body("**{$name} ordered {$oder->items->count()} products.**")
                        ->actions([
                            Action::make('View')
                                ->url(OderResource::getUrl('edit', ['record' => $oder])),
                        ])
                        ->sendToDatabase(User::all());
                    // gởi mail cho người dùng --- Thông tin hóa đơn
                    Mail::to(auth()->user()->email)->send(new InvoiceMail($oder));
                    return redirect()->away($vnp_Url);
                } else {
                    return redirect()->back()->with('message', 'Lỗi! Hình như bạn đang gặp vấn đề gì đó');
                }
            }


        } // THANH TOÁN KHI NHẬN HÀNG
        elseif ($request->payment === 'offline') {
            $name = auth()->user()->name;
            $oder = Oder::create([
                'number' => $orderCode,
                'id_user' => auth()->user()->id,
                'total_price' => $total_oder,
                'country' => $country,
                'city' => $city,
                'address' => $address,
                'phone_number' => $phone,
                'payment' => 'THANH TOÁN KHI NHẬN HÀNG'
            ]);
            // Nếu đơn hàng khởi tạo thành công
            if ($oder) {
                $id_oder = $oder->id;
                $items = DB::table('carts')->join('cart_items', 'carts.id', '=', 'cart_items.id_cart')
                    ->where('carts.id_user', '=', $id_user)->get();
                foreach ($items as $item) {
                    // Lấy thông tin sản phẩm từ bảng products
                    $product = DB::table('products')->where('id', $item->id_product)->first();
                    // Create OrderItem
                    $oder_item = OderItem::create([
                        'id_oder' => $id_oder,
                        'id_product' => $product->id,
                        'quantity' => $item->quantity,
                        'unit_prices' => $product->unit_prices
                    ]);
                }
                if ($oder_item) {
                    // Cập nhật số lượng sản phẩm nếu đơn hàng tạo thành công
                    // Lấy tất cả item trong bảng chi tiêt đơn hàng vừa tạo
                    $get_items_oder = DB::table('oder_items')->where('id_oder', '=', $id_oder)->get();
                    //Duyệt
                    foreach ($get_items_oder as $product) {
                        //Gán id_product
                        $product_id = $product->id_product;
                        // Lấy thông tin product có id === id_product vừa gán ( để lấy số lượng sản phẩm)
                        $quantity_product = DB::table('products')
                            ->where('id', '=', $product_id)->first();
                        // gán số lượng sản phẩm
                        $quantity = $quantity_product->quantity;
                        // gán số lượng sản phẩm mới sau khi tạo đơn hàng
                        $new_quantity = $quantity - $product->quantity;
                        // cập nhật sản phẩm
                        DB::table('products')->where('id', '=', $product_id)
                            ->update([
                                'quantity' => $new_quantity
                            ]);
                    }
                    // Xóa toàn b giỏ hàng sau khi tạo đơn hàng
                    DB::table('carts')->join('cart_items', 'carts.id', '=', 'cart_items.id_cart')
                        ->where('carts.id_user', '=', $id_user)->delete();
                    // Tạo thông báo
                    Notification::make()
                        ->title('New order')
                        ->icon('heroicon-o-shopping-bag')
                        ->body("**{$name} ordered {$oder->items->count()} products.**")
                        ->actions([
                            Action::make('View')
                                ->url(OderResource::getUrl('edit', ['record' => $oder])),
                        ])
                        ->sendToDatabase(User::all());
                    // gởi mail cho người dùng --- Thông tin hóa đơn
                    Mail::to(auth()->user()->email)->send(new InvoiceMail($oder));
                    return redirect()->back()->with('message', 'Đặt hàng thành công vui lòng kiểm tra email để theo dõi đơn hàng');
                } else {
                    return redirect()->back()->with('message', 'Lỗi! Hình như bạn đang gặp vấn đề gì đó');
                }
            }
        }
        return redirect()->back()->with('message', 'Đặt hàng thành công vui lòng kiểm tra email để theo dõi đơn hàng');
    }
}
