```blade
@php

/*
|--------------------------------------------------------------------------
| DỮ LIỆU PHÒNG MOCKUP
| Sau này sẽ thay bằng dữ liệu từ bảng rooms/properties/database.
|--------------------------------------------------------------------------
*/

$rooms = [

    1 => [
        'title' => 'Phòng trọ máy lạnh gần Q.7',
        'address' => 'Đường Nguyễn Thị Thập, Quận 7, TP. Hồ Chí Minh',
        'price' => '2.800.000đ',
        'area' => '22 m²',
        'people' => '2 người',
        'bathroom' => 'WC riêng',
        'type' => 'Phòng trọ',

        'images' => [
            'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1493663284031-b7e3aefcae8e?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1484154218962-a197022b5858?auto=format&fit=crop&w=800&q=80',
        ],

        'description' => 'Phòng trọ sạch sẽ, thoáng mát, có máy lạnh và đầy đủ nội thất cơ bản. Vị trí thuận tiện di chuyển đến các trường học, chợ, siêu thị và khu vực trung tâm Quận 7.',

        'amenities' => [
            '🛏️ Nội thất cơ bản',
            '❄️ Máy lạnh',
            '🚿 WC riêng',
            '📶 Wi-Fi',
            '🛵 Chỗ để xe',
            '🔐 An ninh tốt',
            '🧺 Máy giặt',
            '💧 Nước sạch',
            '⚡ Điện riêng',
        ],

        'landlord' => 'Anh Tuấn',
        'phone' => '0900000001',

        'map' => 'Đường Nguyễn Thị Thập, Quận 7, TP. Hồ Chí Minh',
    ],


    2 => [
        'title' => 'Căn hộ mini đầy đủ nội thất',
        'address' => 'Phường An Phú, TP. Thủ Đức, TP. Hồ Chí Minh',
        'price' => '6.800.000đ',
        'area' => '35 m²',
        'people' => '3 người',
        'bathroom' => 'WC riêng',
        'type' => 'Căn hộ mini',

        'images' => [
            'https://images.unsplash.com/photo-1484154218962-a197022b5858?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1493809842364-78817add7ffb?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1493663284031-b7e3aefcae8e?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=800&q=80',
        ],

        'description' => 'Căn hộ mini rộng rãi, đầy đủ nội thất, thiết kế hiện đại. Khu vực an ninh, yên tĩnh, phù hợp cho người đi làm hoặc gia đình nhỏ. Giao thông thuận tiện.',

        'amenities' => [
            '🛋️ Full nội thất',
            '❄️ Máy lạnh',
            '🚿 WC riêng',
            '📶 Wi-Fi',
            '🛵 Chỗ để xe',
            '🔐 Camera an ninh',
            '🧺 Máy giặt',
            '🍳 Bếp riêng',
            '🌐 Internet',
        ],

        'landlord' => 'Chị Lan',
        'phone' => '0900000002',

        'map' => 'Phường An Phú, TP. Thủ Đức, TP. Hồ Chí Minh',
    ],


    3 => [
        'title' => 'Phòng cửa sổ lớn, giờ giấc tự do',
        'address' => 'Phường Tân Thành, Quận Tân Phú, TP. Hồ Chí Minh',
        'price' => '3.200.000đ',
        'area' => '24 m²',
        'people' => '2 người',
        'bathroom' => 'WC riêng',
        'type' => 'Phòng trọ',

        'images' => [
            'https://images.unsplash.com/photo-1493809842364-78817add7ffb?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1493663284031-b7e3aefcae8e?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1484154218962-a197022b5858?auto=format&fit=crop&w=800&q=80',
        ],

        'description' => 'Phòng có cửa sổ lớn, đón nhiều ánh sáng tự nhiên, không gian thoáng mát. Giờ giấc tự do, khu dân cư yên tĩnh và an ninh tốt. Phù hợp cho sinh viên và người đi làm.',

        'amenities' => [
            '🪟 Cửa sổ lớn',
            '🕐 Giờ giấc tự do',
            '🚿 WC riêng',
            '📶 Wi-Fi',
            '🛵 Chỗ để xe',
            '🔐 An ninh tốt',
            '🧺 Máy giặt',
            '💧 Nước sạch',
            '⚡ Điện riêng',
        ],

        'landlord' => 'Anh Minh',
        'phone' => '0900000003',

        'map' => 'Phường Tân Thành, Quận Tân Phú, TP. Hồ Chí Minh',
    ],


    4 => [
        'title' => 'Phòng có gác rộng, thoáng mát',
        'address' => 'Đường Lê Văn Việt, TP. Thủ Đức, TP. Hồ Chí Minh',
        'price' => '3.500.000đ',
        'area' => '28 m²',
        'people' => '3 người',
        'bathroom' => 'WC riêng',
        'type' => 'Phòng trọ có gác',

        'images' => [
            'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1493809842364-78817add7ffb?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1484154218962-a197022b5858?auto=format&fit=crop&w=800&q=80',
        ],

        'description' => 'Phòng trọ có gác rộng, không gian thoáng mát và sạch sẽ. Gác có thể sử dụng làm khu vực ngủ hoặc để đồ. Vị trí gần các trường đại học, khu mua sắm và nhiều tiện ích.',

        'amenities' => [
            '🛏️ Có gác rộng',
            '❄️ Máy lạnh',
            '🚿 WC riêng',
            '📶 Wi-Fi',
            '🛵 Chỗ để xe',
            '🔐 An ninh tốt',
            '🧺 Máy giặt',
            '💧 Nước sạch',
            '⚡ Điện riêng',
        ],

        'landlord' => 'Chú Hùng',
        'phone' => '0900000004',

        'map' => 'Đường Lê Văn Việt, TP. Thủ Đức, TP. Hồ Chí Minh',
    ],


    5 => [
        'title' => 'Phòng ban công riêng, nhiều ánh sáng',
        'address' => 'Nguyễn Gia Trí, Bình Thạnh, TP. Hồ Chí Minh',
        'price' => '4.200.000đ',
        'area' => '30 m²',
        'people' => '2 người',
        'bathroom' => 'WC riêng',
        'type' => 'Phòng trọ',

        'images' => [
            'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1493663284031-b7e3aefcae8e?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1484154218962-a197022b5858?auto=format&fit=crop&w=800&q=80',
        ],

        'description' => 'Phòng có ban công riêng, nhiều ánh sáng tự nhiên và không gian thoáng. Phòng được thiết kế hiện đại, phù hợp cho sinh viên, người đi làm hoặc hai người ở cùng.',

        'amenities' => [
            '🌤️ Ban công riêng',
            '❄️ Máy lạnh',
            '🛏️ Nội thất cơ bản',
            '🚿 WC riêng',
            '📶 Wi-Fi',
            '🛵 Chỗ để xe',
            '🔐 Camera an ninh',
            '🧺 Máy giặt',
            '💧 Nước sạch',
        ],

        'landlord' => 'Chị Hương',
        'phone' => '0900000005',

        'map' => 'Nguyễn Gia Trí, Bình Thạnh, TP. Hồ Chí Minh',
    ],


    6 => [
        'title' => 'Căn hộ nhỏ đầy đủ tiện nghi',
        'address' => 'Phường 4, Quận 3, TP. Hồ Chí Minh',
        'price' => '5.500.000đ',
        'area' => '32 m²',
        'people' => '2 người',
        'bathroom' => 'WC riêng',
        'type' => 'Căn hộ mini',

        'images' => [
            'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=1200&q=80',
            'https://images.unsplash.com/photo-1484154218962-a197022b5858?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1493809842364-78817add7ffb?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1505693416388-ac5ce068fe85?auto=format&fit=crop&w=800&q=80',
            'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=800&q=80',
        ],

        'description' => 'Căn hộ nhỏ đầy đủ tiện nghi, thiết kế gọn gàng và hiện đại. Không gian phù hợp cho một đến hai người ở. Vị trí ngay Quận 3, thuận tiện di chuyển đến trung tâm thành phố, trường học và khu vui chơi.',

        'amenities' => [
            '🛋️ Nội thất đầy đủ',
            '❄️ Máy lạnh',
            '🚿 WC riêng',
            '📶 Wi-Fi',
            '🛵 Chỗ để xe',
            '🔐 Camera an ninh',
            '🧺 Máy giặt',
            '🍳 Bếp riêng',
            '🌐 Internet',
        ],

        'landlord' => 'Anh Nam',
        'phone' => '0900000006',

        'map' => 'Phường 4, Quận 3, TP. Hồ Chí Minh',
    ],

];

$roomId = (int) $id;

$room = $rooms[$roomId] ?? null;

@endphp


<!DOCTYPE html>

<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        {{ $room ? $room['title'] : 'Không tìm thấy phòng' }} | Trọ Ơi
    </title>


    <!-- Bootstrap -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    <!-- Font -->

    <link
        rel="preconnect"
        href="https://fonts.googleapis.com"
    >

    <link
        rel="preconnect"
        href="https://fonts.gstatic.com"
        crossorigin
    >

    <link
        href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap"
        rel="stylesheet"
    >


    <!-- CSS chính -->

    <link
        rel="stylesheet"
        href="{{ asset('css/styles.css') }}"
    >


    <style>

        body {
            font-family: "Be Vietnam Pro", sans-serif;
            background: var(--cream, #fdfbf7);
            color: var(--text, #2d3748);
        }

        .room-detail-header {
            background: var(--green, #20584f);
            min-height: 74px;
            box-shadow: 0 3px 18px rgba(20,55,47,.14);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .room-detail-header-inner {
            max-width: 1360px;
            min-height: 74px;
            margin: 0 auto;
            padding: 0 28px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .room-detail-header .logo {
            color: #fff;
            font-size: 27px;
            font-weight: 800;
            letter-spacing: -1.2px;
            text-decoration: none;
        }

        .room-detail-header .logo span {
            color: var(--yellow, #f5c84b);
        }

        .back-room {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 9px 14px;
            border: 1px solid rgba(255,255,255,.18);
            border-radius: 10px;
            color: rgba(255,255,255,.92);
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
            transition: .2s ease;
        }

        .back-room:hover {
            color: var(--green, #20584f);
            background: #fff;
            transform: translateX(-2px);
        }

        .room-detail-page {
            background:
                radial-gradient(
                    circle at top left,
                    rgba(245,200,75,.12),
                    transparent 28%
                ),
                var(--cream, #fdfbf7);

            min-height: calc(100vh - 74px);
            padding: 34px 0 70px;
        }

        .room-detail-container {
            max-width: 1360px;
            margin: 0 auto;
            padding: 0 28px;
        }

        .room-gallery {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            grid-template-rows: 225px 225px;
            gap: 12px;
            margin-bottom: 24px;
        }

        .room-gallery-item {
            overflow: hidden;
            border-radius: 18px;
            background: #ddd;
            position: relative;
            box-shadow: 0 8px 24px rgba(27,67,58,.08);
        }

        .room-gallery-item:first-child {
            grid-row: 1 / 3;
        }

        .room-gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
            transition: transform .45s ease;
        }

        .room-gallery-item:hover img {
            transform: scale(1.045);
        }

        .gallery-main-badge {
            position: absolute;
            left: 16px;
            bottom: 16px;
            z-index: 2;
            padding: 8px 12px;
            border-radius: 10px;
            background: rgba(0,0,0,.48);
            color: #fff;
            font-size: 12px;
            font-weight: 700;
            backdrop-filter: blur(7px);
        }

        .room-title-section,
        .room-content-card,
        .booking-card {
            background: var(--white, #fff);
            border: 1px solid var(--border, #eee);
            border-radius: 18px;
            box-shadow: 0 8px 24px rgba(27,67,58,.045);
        }

        .room-title-section {
            padding: 28px;
            margin-bottom: 20px;
        }

        .room-title-top {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 20px;
        }

        .room-title-section h1 {
            margin: 0 0 10px;
            color: var(--text, #2d3748);
            font-size: 29px;
            line-height: 1.35;
            font-weight: 800;
            letter-spacing: -.5px;
        }

        .room-address {
            display: flex;
            align-items: flex-start;
            gap: 7px;
            color: var(--muted, #718096);
            font-size: 13px;
            line-height: 1.6;
        }

        .room-price-box {
            text-align: right;
            flex-shrink: 0;
        }

        .room-price {
            color: var(--green, #20584f);
            font-size: 27px;
            font-weight: 800;
        }

        .room-price span {
            color: var(--muted, #718096);
            font-size: 13px;
            font-weight: 500;
        }

        .available-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-top: 7px;
            padding: 6px 10px;
            border-radius: 999px;
            background: #edf8f0;
            color: #3b8158;
            font-size: 11px;
            font-weight: 700;
        }

        .available-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #4d9b67;
        }

        .room-info-box {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-top: 24px;
        }

        .room-info-item {
            background: linear-gradient(
                145deg,
                var(--green-soft, #e8f3f0),
                #f5f9f6
            );
            border: 1px solid rgba(32,88,79,.08);
            border-radius: 13px;
            padding: 15px 16px;
        }

        .room-info-item .label {
            color: var(--muted, #718096);
            font-size: 11px;
            margin-bottom: 5px;
        }

        .room-info-item .value {
            color: var(--text, #2d3748);
            font-size: 14px;
            font-weight: 700;
        }

        .room-content-card {
            padding: 26px 28px;
            margin-bottom: 20px;
        }

        .room-content-card h2 {
            display: flex;
            align-items: center;
            gap: 9px;
            font-size: 19px;
            font-weight: 800;
            color: var(--text, #2d3748);
            margin-bottom: 10px;
        }

        .section-line {
            height: 3px;
            width: 42px;
            border-radius: 99px;
            background: var(--yellow, #f5c84b);
            margin-bottom: 18px;
        }

        .room-content-card p {
            color: #596560;
            font-size: 14px;
            line-height: 1.85;
            margin: 0;
        }

        .amenities {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 11px;
        }

        .amenity-item {
            display: flex;
            align-items: center;
            gap: 10px;
            min-height: 49px;
            padding: 11px 14px;
            border: 1px solid var(--border, #eee);
            border-radius: 11px;
            background: #fffdf8;
            color: var(--text, #2d3748);
            font-size: 13px;
            font-weight: 600;
            transition: .2s;
        }

        .amenity-item:hover {
            border-color: rgba(32,88,79,.28);
            background: var(--green-soft, #e8f3f0);
            transform: translateY(-2px);
        }

        .map-address {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 16px;
            padding: 8px 12px;
            border-radius: 9px;
            background: var(--green-soft, #e8f3f0);
            color: var(--green, #20584f);
            font-size: 12px;
            font-weight: 600;
        }

        .map-wrapper {
            overflow: hidden;
            border-radius: 15px;
            border: 1px solid var(--border, #eee);
        }

        .room-map {
            width: 100%;
            height: 350px;
            border: 0;
            display: block;
        }

        .landlord-box {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            padding: 17px;
            border: 1px solid var(--border, #eee);
            border-radius: 14px;
            background: #fffdf8;
        }

        .landlord-info {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .landlord-avatar {
            width: 55px;
            height: 55px;
            border-radius: 50%;
            background: var(--green-soft, #e8f3f0);
            color: var(--green, #20584f);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            font-weight: 800;
        }

        .landlord-name {
            font-size: 15px;
            font-weight: 800;
            color: var(--text, #2d3748);
        }

        .landlord-status {
            color: #4d8b68;
            font-size: 11px;
            margin-top: 4px;
            font-weight: 600;
        }

        .landlord-contact {
            color: var(--green, #20584f);
            font-size: 12px;
            font-weight: 700;
        }

        .booking-card {
            padding: 24px;
            position: sticky;
            top: 94px;
            box-shadow: 0 12px 30px rgba(27,67,58,.08);
        }

        .booking-top {
            padding-bottom: 18px;
            margin-bottom: 18px;
            border-bottom: 1px solid #eee6d5;
        }

        .booking-card h2 {
            color: var(--text, #2d3748);
            font-size: 19px;
            font-weight: 800;
            margin-bottom: 7px;
        }

        .booking-note {
            color: var(--muted, #718096);
            font-size: 12px;
            line-height: 1.7;
        }

        .booking-room-price {
            margin-top: 13px;
            color: var(--green, #20584f);
            font-size: 17px;
            font-weight: 800;
        }

        .booking-room-price span {
            color: var(--muted, #718096);
            font-size: 11px;
            font-weight: 500;
        }

        .btn-booking {
            width: 100%;
            border: none;
            background: var(--green, #20584f);
            color: #fff;
            padding: 13px;
            border-radius: 11px;
            font-size: 14px;
            font-weight: 800;
            transition: .2s;
            box-shadow: 0 7px 15px rgba(32,88,79,.18);
        }

        .btn-booking:hover {
            background: var(--green-dark, #163f39);
            color: #fff;
            transform: translateY(-2px);
        }

        .booking-secure-note {
            display: flex;
            justify-content: center;
            gap: 5px;
            margin-top: 11px;
            color: var(--muted, #718096);
            font-size: 10px;
        }

        .contact-buttons {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 14px;
        }

        .btn-contact {
            display: flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--border, #eee);
            background: #fffdf8;
            color: var(--text, #2d3748);
            border-radius: 10px;
            padding: 10px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 700;
            transition: .2s;
        }

        .btn-contact:hover {
            border-color: var(--green, #20584f);
            color: var(--green, #20584f);
            background: var(--green-soft, #e8f3f0);
        }

        .not-found {
            text-align: center;
            background: #fff;
            border: 1px solid var(--border, #eee);
            border-radius: 20px;
            padding: 80px 20px;
        }

        .not-found h1 {
            font-size: 30px;
            font-weight: 800;
            margin-bottom: 10px;
        }

        .not-found p {
            color: var(--muted, #718096);
            margin-bottom: 24px;
        }

        .btn-home {
            display: inline-block;
            background: var(--green, #20584f);
            color: #fff;
            text-decoration: none;
            padding: 11px 18px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
        }

        @media (max-width: 992px) {

            .room-gallery {
                grid-template-columns: 1fr 1fr;
                grid-template-rows: 255px 180px 180px;
            }

            .room-gallery-item:first-child {
                grid-column: 1 / 3;
                grid-row: auto;
            }

            .room-info-box {
                grid-template-columns: repeat(2, 1fr);
            }

            .amenities {
                grid-template-columns: repeat(2, 1fr);
            }

            .booking-card {
                position: static;
            }

        }

        @media (max-width: 768px) {

            .room-title-top {
                flex-direction: column;
            }

            .room-price-box {
                width: 100%;
                text-align: left;
            }

        }

        @media (max-width: 576px) {

            .room-detail-container {
                padding: 0 14px;
            }

            .room-detail-header-inner {
                padding: 0 16px;
            }

            .room-detail-header .logo {
                font-size: 22px;
            }

            .back-room {
                font-size: 11px;
                padding: 8px 9px;
            }

            .room-gallery {
                grid-template-columns: 1fr;
                grid-template-rows:
                    230px
                    130px
                    130px
                    130px
                    130px;
            }

            .room-gallery-item:first-child {
                grid-column: auto;
                grid-row: auto;
            }

            .room-title-section,
            .room-content-card {
                padding: 20px;
            }

            .room-title-section h1 {
                font-size: 21px;
            }

            .room-price {
                font-size: 23px;
            }

            .room-info-box {
                grid-template-columns: 1fr 1fr;
            }

            .amenities {
                grid-template-columns: 1fr;
            }

            .room-map {
                height: 270px;
            }

        }

    </style>

</head>


<body>


<!-- =========================================================
     HEADER
========================================================= -->

<header class="room-detail-header">

    <div class="room-detail-header-inner">


        <a
            href="{{ route('tenant') }}"
            class="logo"
        >
            Trọ <span>Ơi</span>
        </a>


        <a
            href="{{ route('rooms.index') }}"
            class="back-room"
        >
            ← Quay lại danh sách phòng
        </a>

    </div>

</header>



<!-- =========================================================
     MAIN
========================================================= -->

<main class="room-detail-page">

    <div class="room-detail-container">


        @if(!$room)


            <!-- =========================
                 KHÔNG TÌM THẤY
            ========================== -->

            <div class="not-found">

                <h1>
                    Không tìm thấy phòng
                </h1>

                <p>
                    Phòng bạn đang tìm không tồn tại
                    hoặc đã được gỡ khỏi hệ thống.
                </p>

                <a
                    href="{{ route('rooms.index') }}"
                    class="btn-home"
                >
                    ← Quay lại danh sách phòng
                </a>

            </div>


        @else


            <!-- =========================
                 HÌNH ẢNH
            ========================== -->

            <div class="room-gallery">

                @foreach($room['images'] as $index => $image)

                    <div class="room-gallery-item">

                        <img
                            src="{{ $image }}"
                            alt="{{ $room['title'] }}"
                        >


                        @if($index === 0)

                            <div class="gallery-main-badge">
                                📷 Hình ảnh phòng
                            </div>

                        @endif

                    </div>

                @endforeach

            </div>



            <div class="row g-4">


                <!-- =================================================
                     LEFT
                ================================================== -->

                <div class="col-lg-8">


                    <!-- =========================
                         THÔNG TIN CHÍNH
                    ========================== -->

                    <section class="room-title-section">

                        <div class="room-title-top">


                            <div>

                                <h1>
                                    {{ $room['title'] }}
                                </h1>


                                <div class="room-address">

                                    📍

                                    <span>
                                        {{ $room['address'] }}
                                    </span>

                                </div>

                            </div>


                            <div class="room-price-box">

                                <div class="room-price">

                                    {{ $room['price'] }}

                                    <span>
                                        / tháng
                                    </span>

                                </div>


                                <div class="available-badge">

                                    <span class="available-dot"></span>

                                    Còn phòng

                                </div>

                            </div>

                        </div>


                        <div class="room-info-box">


                            <div class="room-info-item">

                                <div class="label">
                                    Diện tích
                                </div>

                                <div class="value">
                                    {{ $room['area'] }}
                                </div>

                            </div>


                            <div class="room-info-item">

                                <div class="label">
                                    Số người
                                </div>

                                <div class="value">
                                    {{ $room['people'] }}
                                </div>

                            </div>


                            <div class="room-info-item">

                                <div class="label">
                                    Phòng tắm
                                </div>

                                <div class="value">
                                    {{ $room['bathroom'] }}
                                </div>

                            </div>


                            <div class="room-info-item">

                                <div class="label">
                                    Loại phòng
                                </div>

                                <div class="value">
                                    {{ $room['type'] }}
                                </div>

                            </div>

                        </div>

                    </section>



                    <!-- =========================
                         MÔ TẢ
                    ========================== -->

                    <section class="room-content-card">

                        <h2>
                            📝 Mô tả phòng
                        </h2>

                        <div class="section-line"></div>

                        <p>
                            {{ $room['description'] }}
                        </p>

                    </section>



                    <!-- =========================
                         TIỆN ÍCH
                    ========================== -->

                    <section class="room-content-card">

                        <h2>
                            ✨ Tiện ích phòng
                        </h2>

                        <div class="section-line"></div>


                        <div class="amenities">

                            @foreach($room['amenities'] as $amenity)

                                <div class="amenity-item">
                                    {{ $amenity }}
                                </div>

                            @endforeach

                        </div>

                    </section>



                    <!-- =========================
                         GOOGLE MAP
                    ========================== -->

                    <section class="room-content-card">

                        <h2>
                            📍 Vị trí phòng
                        </h2>

                        <div class="section-line"></div>


                        <div class="map-address">

                            📌

                            {{ $room['map'] }}

                        </div>


                        <div class="map-wrapper">

                            <iframe
                                class="room-map"
                                src="https://www.google.com/maps?q={{ urlencode($room['map']) }}&output=embed"
                                allowfullscreen
                                loading="lazy"
                                referrerpolicy="no-referrer-when-downgrade"
                            ></iframe>

                        </div>

                    </section>



                    <!-- =========================
                         CHỦ TRỌ
                    ========================== -->

                    <section class="room-content-card">

                        <h2>
                            👤 Thông tin chủ trọ
                        </h2>

                        <div class="section-line"></div>


                        <div class="landlord-box">


                            <div class="landlord-info">


                                <div class="landlord-avatar">

                                    {{ strtoupper(substr($room['landlord'], 0, 1)) }}

                                </div>


                                <div>

                                    <div class="landlord-name">

                                        {{ $room['landlord'] }}

                                    </div>


                                    <div class="landlord-status">

                                        ● Đang hoạt động

                                    </div>

                                </div>

                            </div>


                            <div class="landlord-contact">

                                ✓ Chủ trọ xác minh

                            </div>

                        </div>

                    </section>

                </div>



                <!-- =================================================
                     RIGHT
                ================================================== -->

                <div class="col-lg-4">


                    <div class="booking-card">


                        <div class="booking-top">

                            <h2>
                                📅 Đặt lịch xem phòng
                            </h2>


                            <div class="booking-note">

                                Chọn thời gian phù hợp để đến xem phòng.
                                Chủ trọ sẽ xác nhận lịch hẹn của bạn.

                            </div>


                            <div class="booking-room-price">

                                {{ $room['price'] }}

                                <span>
                                    / tháng
                                </span>

                            </div>

                        </div>


                        <!--
                        |-----------------------------------------------------------
                        | QUAN TRỌNG:
                        | Truyền room_id sang trang appointments/create.
                        | Ví dụ:
                        | /appointments/create?id=1
                        | /appointments/create?id=2
                        |-----------------------------------------------------------
                        -->

                        <a
                            href="{{ route('appointments.create', ['id' => $roomId]) }}"
                            class="btn-booking text-decoration-none d-flex align-items-center justify-content-center"
                        >
                            📅 Đặt lịch xem phòng
                        </a>


                        <div class="booking-secure-note">

                            🔒 Thông tin của bạn được bảo mật

                        </div>


                        <!-- LIÊN HỆ NHANH -->

                        <div class="contact-buttons">


                            <a
                                href="tel:{{ $room['phone'] }}"
                                class="btn-contact"
                            >
                                📞 Gọi chủ trọ
                            </a>


                            <a
                                href="#"
                                class="btn-contact"
                            >
                                💬 Zalo
                            </a>

                        </div>

                    </div>

                </div>

            </div>

        @endif

    </div>

</main>



<!-- Bootstrap JS -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>


</body>

</html>
```
