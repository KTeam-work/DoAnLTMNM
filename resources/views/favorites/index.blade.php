<!DOCTYPE html>
<html lang="vi">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Trọ Ơi | Phòng yêu thích</title>

    <!-- Bootstrap -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- CSS chính -->
    <link
        rel="stylesheet"
        href="{{ asset('css/styles.css') }}">

</head>

<body>


    <!-- =========================================================
     NAVBAR
========================================================= -->

    <nav class="navbar navbar-expand-lg app-navbar">

        <div class="container-fluid">

            <!-- LOGO -->
            <a
                class="logo"
                href="{{ route('tenant.home') }}">
                Trọ <span>Ơi</span>
            </a>


            <!-- MOBILE BUTTON -->
            <button
                class="navbar-toggler bg-light"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#mainMenu"
                aria-controls="mainMenu"
                aria-expanded="false"
                aria-label="Mở menu">
                <span class="navbar-toggler-icon"></span>
            </button>


            <!-- MENU -->
            <div
                class="collapse navbar-collapse"
                id="mainMenu">

                <ul class="navbar-nav mx-auto align-items-lg-center">

                    <!-- TRANG CHỦ -->
                    <li class="nav-item">

                        <a
                            class="app-nav-link"
                            href="{{ route('tenant.home') }}">
                            Trang chủ
                        </a>

                    </li>


                    <!-- TÌM PHÒNG -->
                    <li class="nav-item">

                        <a
                            class="app-nav-link"
                            href="{{ route('rooms.index') }}">
                            Tìm phòng
                        </a>

                    </li>


                    <!-- YÊU THÍCH -->
                    <li class="nav-item">

                        <a
                            class="app-nav-link active"
                            href="{{ route('favorites.index') }}">
                            Yêu thích
                        </a>

                    </li>


                    <!-- LỊCH XEM PHÒNG -->
                    <li class="nav-item">

                        <a
                            class="app-nav-link"
                            href="{{ route('appointments.index') }}">
                            Lịch xem phòng
                        </a>

                    </li>


                    <li class="nav-item"><a class="app-nav-link" href="{{ route('tenant.contracts.index') }}">Hợp Đồng</a></li>
                    <li class="nav-item"><a class="app-nav-link" href="{{ url('/tenant/invoices/index') }}">Hóa đơn</a></li>

                    <li class="nav-item"><a class="app-nav-link" href="{{ route('tenant.maintenance.index') }}">Sửa chữa</a></li>
                    <li class="nav-item"><a class="app-nav-link" href="{{ route('tenant.reviews.index') }}">Đánh giá</a></li>
                </ul>


                <!-- RIGHT USER -->
                <div class="navbar-actions">

                    <button
                        class="notif-btn"
                        type="button"
                        title="Thông báo">
                        🔔
                        <span class="notif-dot"></span>
                    </button>


                    <a
                        href="#"
                        class="user-chip">

                        <span class="user-avatar">
                            TH
                        </span>


                        <span class="user-meta">

                            <span class="user-name d-block">
                                Thanh Huyền
                            </span>

                            <span class="user-role">
                                Người thuê
                            </span>

                        </span>


                        <span class="caret">
                            ▾
                        </span>

                    </a>

                </div>

            </div>

        </div>

    </nav>



    <!-- =========================================================
     MAIN
========================================================= -->

    <div class="page-wrap">


        <!-- =====================================================
         PAGE HEADER
    ====================================================== -->

        <div class="page-header">

            <div>

                <h1 class="page-title">
                    Phòng yêu thích
                </h1>

                <div class="page-desc">
                    Lưu lại những phòng bạn quan tâm để xem lại và so sánh sau.
                </div>

            </div>


            <a
                href="{{ route('rooms.index') }}"
                class="btn-brand text-decoration-none">
                🔎 Tìm thêm phòng
            </a>

        </div>



        <!-- =====================================================
         TOP SUMMARY
    ====================================================== -->

        <div class="row g-3 mb-4">


            <!-- SỐ PHÒNG -->
            <div class="col-md-4">

                <div class="stat-card">

                    <div class="stat-top">

                        <div class="stat-icon red">
                            ♥
                        </div>

                        <span class="stat-trend">
                            Đã lưu
                        </span>

                    </div>


                    <div class="stat-value">
                        {{ $favoriteCount }}
                    </div>


                    <div class="stat-label">
                        Phòng yêu thích
                    </div>

                </div>

            </div>


            <!-- KHU VỰC -->
            <div class="col-md-4">

                <div class="stat-card">

                    <div class="stat-top">

                        <div class="stat-icon green">
                            📍
                        </div>

                        <span class="stat-trend">
                            Quan tâm
                        </span>

                    </div>


                    <div class="stat-value">
                        {{ $areaCount }}
                    </div>


                    <div class="stat-label">
                        Khu vực đang quan tâm
                    </div>

                </div>

            </div>


            <!-- GIÁ -->
            <div class="col-md-4">

                <div class="stat-card">

                    <div class="stat-top">

                        <div class="stat-icon">
                            💰
                        </div>

                        <span class="stat-trend">
                            Tham khảo
                        </span>

                    </div>


                    <div class="stat-value">
                        @if ($minPrice !== null && $maxPrice !== null)
                        {{ number_format((float) $minPrice / 1000000, 1, ',', '.') }}–{{ number_format((float) $maxPrice / 1000000, 1, ',', '.') }} triệu
                        @else
                        Chưa có dữ liệu
                        @endif
                    </div>


                    <div class="stat-label">
                        Khoảng giá phòng đã lưu
                    </div>

                </div>

            </div>

        </div>



        <!-- =====================================================
         FILTER / SORT
    ====================================================== -->

        <div class="panel mb-4">

            <div
                class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                <div>

                    <h2 class="panel-title">
                        Danh sách đã lưu
                    </h2>

                    <div class="page-desc">
                        Các phòng bạn đã đánh dấu yêu thích.
                    </div>

                </div>


                <div class="d-flex gap-2 flex-wrap">

                    <button
                        type="button"
                        class="btn-outline-brand">
                        Tất cả
                    </button>


                    <button
                        type="button"
                        class="btn-outline-brand">
                        Giá thấp
                    </button>


                    <button
                        type="button"
                        class="btn-outline-brand">
                        Mới lưu
                    </button>


                    <select
                        class="form-select"
                        style="
                        width:auto;
                        min-width:175px;
                        border-radius:11px;
                        border-color:var(--border);
                        font-size:12px;
                        font-weight:600;
                    ">

                        <option selected>
                            Sắp xếp: Mới nhất
                        </option>

                        <option>
                            Giá thấp → cao
                        </option>

                        <option>
                            Giá cao → thấp
                        </option>

                        <option>
                            Diện tích lớn → nhỏ
                        </option>

                    </select>

                </div>

            </div>

        </div>



        <!-- =====================================================
         FAVORITE ROOM LIST
         
         Dữ liệu mẫu
    ====================================================== -->


        <div class="row g-4">
            @forelse ($favorites as $favorite)
            @php
            $room = $favorite->room;
            @endphp

            @if ($room)
            <div class="col-md-6 col-lg-4">
                <div class="room-card">
                    <form action="{{ route('favorites.destroy', $favorite->id) }}"
                        method="POST" class="favorite-form">
                        @csrf
                        @method('DELETE')
                        <button class="fav-btn" type="submit"
                            title="Bỏ yêu thích"
                            onclick="return confirm('Bỏ phòng này khỏi yêu thích?')"
                            style="color: #e53935;">♥</button>
                    </form>

                    <img class="room-img"
                        src="https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=1000&q=80"
                        alt="{{ $room->name ?? 'Phòng trọ' }}">

                    <div class="room-body">
                        <div class="room-badges">
                            <span class="badge-room">
                                Mã phòng: {{ $room->room_code ?? $room->id }}
                            </span>
                        </div>

                        <div class="room-title">
                            {{ $room->name ?? 'Phòng trọ' }}
                        </div>

                        <div class="room-address">
                            {{ $room->property?->address ?? 'Chưa cập nhật địa chỉ' }}
                            {{ $room->property?->district ?? '' }}
                        </div>

                        <div class="room-stats">
                            <span>📐 {{ $room->area }} m²</span>
                            @forelse ($room->amenities->take(2) as $amenity)
                            <span>{{ $amenity->name }}</span>
                            @empty
                            <span>Chưa có tiện ích</span>
                            @endforelse
                        </div>

                        <div class="room-bottom">
                            <div class="room-price">
                                {{ number_format((float) $room->price, 0, ',', '.') }} đ
                                <small>/ tháng</small>
                            </div>
                            <a href="{{ route('rooms.show', $room->id) }}"
                                class="detail-btn text-decoration-none">
                                Xem phòng
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif
            @empty
            <div class="col-12">
                <div class="panel text-center py-5">
                    <h5>Chưa có phòng yêu thích</h5>
                    <p>Hãy tìm phòng và bấm trái tim để lưu lại nhé.</p>
                    <a href="{{ route('rooms.index') }}"
                        class="btn-brand text-decoration-none">
                        Tìm phòng trọ
                    </a>
                </div>
            </div>
            @endforelse
        </div>




        <!-- =====================================================
         BOTTOM INFO
    ====================================================== -->

        <div class="panel mt-4">

            <div class="d-flex align-items-center gap-3">

                <div class="stat-icon red">
                    ♥
                </div>

                <div>

                    <div class="cell-title">
                        Lưu phòng để xem lại bất cứ lúc nào
                    </div>

                    <div class="cell-sub">
                        Khi tìm thấy phòng phù hợp, hãy bấm biểu tượng
                        ♥ trên phòng để thêm vào danh sách yêu thích.
                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- =========================================================
     BOOTSTRAP JS
========================================================= -->

    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
```