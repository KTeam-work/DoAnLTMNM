<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Chat real-time giữa khách thuê và chủ trọ

Sau khi cập nhật mã nguồn, chạy `composer install`, `npm install`, `npm run build` và `php artisan migrate`. Trong `.env`, đặt `BROADCAST_CONNECTION=reverb`, tạo giá trị riêng cho `REVERB_APP_ID`, `REVERB_APP_KEY`, `REVERB_APP_SECRET`, và cấu hình `REVERB_HOST`, `REVERB_PORT`, `REVERB_SCHEME` theo địa chỉ mà trình duyệt có thể truy cập. Không dùng khóa mẫu khi triển khai.

Chạy ứng dụng Laravel như bình thường, đồng thời chạy `php artisan reverb:start` ở một terminal khác. Để demo trên cùng máy, địa chỉ `127.0.0.1:8080` là đủ. Đăng nhập khách thuê và chủ trọ trong hai trình duyệt riêng, mở một phòng đang đăng công khai rồi bấm **Nhắn tin cho chủ trọ**. Tin trong trang chat được nhận ngay qua WebSocket; số tin chưa đọc trên thanh điều hướng cập nhật khi tải lại trang.

## Nộp phí tiền mặt qua SePay

Cấu hình `SEPAY_BANK_NAME`, `SEPAY_BANK_ACCOUNT`, `SEPAY_ACCOUNT_NAME` và đặt `SEPAY_WEBHOOK_TOKEN` thành một khóa bí mật riêng trong `.env`. Trong SePay, tạo webhook cho tài khoản nhận tiền, dùng xác thực API Key trùng với `SEPAY_WEBHOOK_TOKEN`, và trỏ tới địa chỉ HTTPS công khai của ứng dụng theo dạng `https://ten-mien-cua-ban/api/sepay/webhook`. Bảo đảm webhook nhận giao dịch tiền vào có nội dung `FEE...`; không giới hạn webhook chỉ nhận các mã hóa đơn khác. Địa chỉ `localhost` chỉ chạy trên máy cá nhân nên SePay không thể tự gọi tới; cần triển khai hoặc dùng đường hầm HTTPS phù hợp.

Chủ trọ mở Tài chính → Phí nền tảng → Nộp phí, chuyển đúng số tiền và nội dung QR. Webhook hợp lệ cập nhật trạng thái; trang tự kiểm tra trong khi mở mã QR. Thử bằng SePay Test Mode khi có thể. Nút giả lập nội bộ chỉ xuất hiện ở môi trường local/testing và được đánh dấu là mô phỏng, không chứng minh có tiền thật. Không đưa webhook token vào mã nguồn hoặc chia sẻ công khai.
