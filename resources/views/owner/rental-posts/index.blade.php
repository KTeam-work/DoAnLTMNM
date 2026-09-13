<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Trọ Ơi | Quản lý tin đăng</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            background:#f8f5eb;
            color:#213430;
            font-family:'Be Vietnam Pro',sans-serif;
        }

        

        .heading {
            display:flex;
            justify-content:space-between;
            align-items:end;
            margin-bottom:25px;
        }

        .eyebrow {
            color:#20584f;
            font-size:12px;
            font-weight:800;
            letter-spacing:1.5px;
            text-transform:uppercase;
        }

        .heading h1 {
            font-size:30px;
            font-weight:800;
            margin:6px 0;
        }

        .heading p {
            margin:0;
            color:#78837e;
        }

        .btn-owner {
            border:0;
            background:#20584f;
            color:white;
            padding:12px 18px;
            border-radius:14px;
            font-weight:700;
        }

        .btn-owner:hover {
            background:#17463e;
            color:white;
        }

        .post-stats {
            display:grid;
            grid-template-columns:repeat(4,1fr);
            gap:15px;
            margin-bottom:22px;
        }

        .post-stat {
            background:white;
            border:1px solid #e6dcc2;
            border-radius:20px;
            padding:18px;
        }

        .post-stat-icon {
            width:44px;
            height:44px;
            border-radius:14px;
            background:#eaf3ef;
            color:#20584f;
            display:flex;
            align-items:center;
            justify-content:center;
        }

        .post-stat strong {
            display:block;
            font-size:26px;
            margin-top:12px;
        }

        .post-stat span {
            color:#78837e;
            font-size:12px;
        }

        .posts-container {
            background:white;
            border:1px solid #e6dcc2;
            border-radius:24px;
            overflow:hidden;
            box-shadow:0 10px 30px rgba(32,88,79,.05);
        }

        .toolbar {
            padding:20px;
            border-bottom:1px solid #eee7d8;
            display:flex;
            gap:10px;
            flex-wrap:wrap;
        }

        .search {
            flex:1;
            min-width:240px;
            border:1px solid #e6dcc2;
            border-radius:13px;
            padding:11px 14px;
            outline:none;
        }

        .filter {
            min-width:170px;
            border:1px solid #e6dcc2;
            border-radius:13px;
            padding:10px 13px;
            background:white;
        }

        .posts-list {
            padding:20px;
            display:grid;
            gap:15px;
        }

        .post-card {
            display:grid;
            grid-template-columns:220px 1fr auto;
            gap:20px;
            align-items:center;
            border:1px solid #e6dcc2;
            border-radius:19px;
            padding:12px;
            transition:.2s;
        }

        .post-card:hover {
            box-shadow:0 10px 25px rgba(32,88,79,.08);
            transform:translateY(-2px);
        }

        .post-image {
            height:145px;
            border-radius:14px;
            overflow:hidden;
        }

        .post-image img {
            width:100%;
            height:100%;
            object-fit:cover;
        }

        .post-info h3 {
            font-size:18px;
            font-weight:800;
            margin-bottom:5px;
        }

        .post-room {
            font-size:12px;
            color:#78837e;
            margin-bottom:11px;
        }

        .post-price {
            color:#20584f;
            font-size:18px;
            font-weight:800;
            margin-bottom:10px;
        }

        .post-meta {
            display:flex;
            gap:14px;
            color:#78837e;
            font-size:11px;
            flex-wrap:wrap;
        }

        .post-right {
            text-align:right;
            min-width:145px;
        }

        .post-status {
            display:inline-block;
            padding:7px 11px;
            border-radius:20px;
            font-size:10px;
            font-weight:800;
            margin-bottom:14px;
        }

        .status-approved {
            background:#eaf3ef;
            color:#20584f;
        }

        .status-pending {
            background:#fff7d7;
            color:#8b6a00;
        }

        .status-rejected {
            background:#fff0ed;
            color:#c65b4a;
        }

        .status-hidden {
            background:#f1f1ef;
            color:#78837e;
        }

        .post-actions {
            display:flex;
            gap:7px;
            justify-content:flex-end;
        }

        .post-actions button {
            border:0;
            border-radius:9px;
            padding:8px 11px;
            font-size:11px;
            font-weight:700;
        }

        .btn-view {
            background:#eaf3ef;
            color:#20584f;
        }

        .btn-edit {
            background:#f8f5eb;
            color:#53635e;
        }

        @media(max-width:1000px) {
            .post-stats {
                grid-template-columns:repeat(2,1fr);
            }

            .owner-nav {
                display:none;
            }

            .post-card {
                grid-template-columns:160px 1fr;
            }

            .post-right {
                grid-column:1/-1;
                text-align:left;
            }

            .post-actions {
                justify-content:flex-start;
            }
        }

        @media(max-width:650px) {
            .post-stats {
                grid-template-columns:1fr;
            }

            .heading {
                flex-direction:column;
                align-items:flex-start;
                gap:15px;
            }

            .post-card {
                grid-template-columns:1fr;
            }

            .post-image {
                height:200px;
            }
        }
    </style>
</head>

<body>

<nav class="navbar navbar-expand-lg app-navbar">
    <div class="container-fluid">

        <a class="logo" href="{{ route('landlord') }}">
            Trọ <span>Ơi</span>
        </a>

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

        <div class="collapse navbar-collapse" id="mainMenu">

            <ul class="navbar-nav mx-auto align-items-lg-center">

                <li class="nav-item">
                    <a class="app-nav-link" href="{{ route('landlord') }}">
                        Tổng quan
                    </a>
                </li>

                <li class="nav-item dropdown">
                    <a
                        class="app-nav-link dropdown-toggle"
                        href="#"
                        role="button"
                        data-bs-toggle="dropdown"
                        aria-expanded="false">
                        Nhà &amp; Phòng
                    </a>

                    <ul class="dropdown-menu">
                        <li>
                            <a class="dropdown-item"
                               href="{{ route('owner.properties.index') }}">
                                🏠 Quản lý nhà
                            </a>
                        </li>

                        <li>
                            <a class="dropdown-item"
                               href="{{ route('owner.rooms.index') }}">
                                🚪 Quản lý phòng
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a class="app-nav-link active"
                       href="{{ route('owner.rental-posts.index') }}">
                        Tin đăng
                    </a>
                </li>

                <li class="nav-item">
                    <a class="app-nav-link"
                       href="{{ route('owner.appointments.index') }}">
                        Lịch xem
                    </a>
                </li>

                <li class="nav-item">
                    <a class="app-nav-link" href="#">
                        Người thuê
                    </a>
                </li>

                <li class="nav-item">
                    <a class="app-nav-link" href="#">
                        Hợp đồng
                    </a>
                </li>

                <li class="nav-item">
                    <a class="app-nav-link" href="#">
                        Hóa đơn
                    </a>
                </li>

            </ul>

            <div class="navbar-actions">

                <button class="notif-btn" type="button">
                    🔔
                    <span class="notif-dot"></span>
                </button>

                <a href="#" class="user-chip">

                    <span class="user-avatar">
                        AT
                    </span>

                    <span class="user-meta">
                        <span class="user-name d-block">
                            Anh Tuấn
                        </span>

                        <span class="user-role">
                            Chủ trọ
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
<main class="owner-main">

    <div class="heading">
        <div>
            <div class="eyebrow">Rental posts</div>
            <h1>Quản lý tin đăng</h1>
            <p>Theo dõi trạng thái và hiệu quả các tin cho thuê.</p>
        </div>

        <button class="btn-owner">
            ＋ Tạo tin đăng
        </button>
    </div>

    <div class="post-stats">

        <div class="post-stat">
            <div class="post-stat-icon">📢</div>
            <strong>06</strong>
            <span>Tổng tin đăng</span>
        </div>

        <div class="post-stat">
            <div class="post-stat-icon">🟢</div>
            <strong>03</strong>
            <span>Đang hiển thị</span>
        </div>

        <div class="post-stat">
            <div class="post-stat-icon">⏳</div>
            <strong>02</strong>
            <span>Chờ duyệt</span>
        </div>

        <div class="post-stat">
            <div class="post-stat-icon">👁</div>
            <strong>428</strong>
            <span>Lượt xem tháng này</span>
        </div>

    </div>

    <section class="posts-container">

        <div class="toolbar">

            <input class="search"
                   id="postSearch"
                   type="text"
                   placeholder="Tìm tin đăng, phòng...">

            <select class="filter" id="postStatus">
                <option value="">Tất cả trạng thái</option>
                <option value="approved">Đã duyệt</option>
                <option value="pending">Chờ duyệt</option>
                <option value="rejected">Từ chối</option>
                <option value="hidden">Đã ẩn</option>
            </select>

        </div>

        <div class="posts-list">

            <article class="post-card"
                     data-status="approved"
                     data-search="phòng trọ máy lạnh gần q7 phòng 101">

                <div class="post-image">
                    <img src="https://images.unsplash.com/photo-1560185008-b033106af5c3?auto=format&fit=crop&w=900&q=80">
                </div>

                <div class="post-info">

                    <h3>Phòng trọ máy lạnh gần Q.7</h3>

                    <div class="post-room">
                        Phòng 101 · Nhà trọ Nguyễn Thị Thập
                    </div>

                    <div class="post-price">
                        2.800.000đ / tháng
                    </div>

                    <div class="post-meta">
                        <span>📐 22 m²</span>
                        <span>👥 2 người</span>
                        <span>👁 128 lượt xem</span>
                    </div>

                </div>

                <div class="post-right">

                    <span class="post-status status-approved">
                        ĐANG HIỂN THỊ
                    </span>

                    <div class="post-actions">
                        <button class="btn-view">Xem</button>
                        <button class="btn-edit">Sửa</button>
                    </div>

                </div>

            </article>


            <article class="post-card"
                     data-status="approved"
                     data-search="căn hộ mini đầy đủ nội thất phòng 203">

                <div class="post-image">
                    <img src="https://images.unsplash.com/photo-1600210492486-724fe5c67fb0?auto=format&fit=crop&w=900&q=80">
                </div>

                <div class="post-info">

                    <h3>Căn hộ mini đầy đủ nội thất</h3>

                    <div class="post-room">
                        Phòng 203 · Căn hộ mini An Phú
                    </div>

                    <div class="post-price">
                        6.800.000đ / tháng
                    </div>

                    <div class="post-meta">
                        <span>📐 35 m²</span>
                        <span>👥 3 người</span>
                        <span>👁 96 lượt xem</span>
                    </div>

                </div>

                <div class="post-right">

                    <span class="post-status status-approved">
                        ĐANG HIỂN THỊ
                    </span>

                    <div class="post-actions">
                        <button class="btn-view">Xem</button>
                        <button class="btn-edit">Sửa</button>
                    </div>

                </div>

            </article>


            <article class="post-card"
                     data-status="pending"
                     data-search="phòng cửa sổ lớn giờ giấc tự do phòng 301">

                <div class="post-image">
                    <img src="https://images.unsplash.com/photo-1600607687920-4e2a09cf159d?auto=format&fit=crop&w=900&q=80">
                </div>

                <div class="post-info">

                    <h3>Phòng cửa sổ lớn, giờ giấc tự do</h3>

                    <div class="post-room">
                        Phòng 301 · Nhà trọ Tân Phú
                    </div>

                    <div class="post-price">
                        3.200.000đ / tháng
                    </div>

                    <div class="post-meta">
                        <span>📐 24 m²</span>
                        <span>👥 2 người</span>
                        <span>🕐 Chờ duyệt</span>
                    </div>

                </div>

                <div class="post-right">

                    <span class="post-status status-pending">
                        CHỜ DUYỆT
                    </span>

                    <div class="post-actions">
                        <button class="btn-view">Xem</button>
                        <button class="btn-edit">Sửa</button>
                    </div>

                </div>

            </article>


            <article class="post-card"
                     data-status="pending"
                     data-search="phòng mới gần đại học phòng 302">

                <div class="post-image">
                    <img src="https://images.unsplash.com/photo-1598928506311-c55ded91a20c?auto=format&fit=crop&w=900&q=80">
                </div>

                <div class="post-info">

                    <h3>Phòng mới gần khu đại học</h3>

                    <div class="post-room">
                        Phòng 302 · Nhà trọ Tân Phú
                    </div>

                    <div class="post-price">
                        3.400.000đ / tháng
                    </div>

                    <div class="post-meta">
                        <span>📐 25 m²</span>
                        <span>👥 2 người</span>
                        <span>🕐 Chờ duyệt</span>
                    </div>

                </div>

                <div class="post-right">

                    <span class="post-status status-pending">
                        CHỜ DUYỆT
                    </span>

                    <div class="post-actions">
                        <button class="btn-view">Xem</button>
                        <button class="btn-edit">Sửa</button>
                    </div>

                </div>

            </article>


            <article class="post-card"
                     data-status="rejected"
                     data-search="phòng trung tâm q7">

                <div class="post-image">
                    <img src="https://images.unsplash.com/photo-1615874694520-474822394e73?auto=format&fit=crop&w=900&q=80">
                </div>

                <div class="post-info">

                    <h3>Phòng trung tâm Quận 7</h3>

                    <div class="post-room">
                        Phòng 104 · Nhà trọ Nguyễn Thị Thập
                    </div>

                    <div class="post-price">
                        3.500.000đ / tháng
                    </div>

                    <div class="post-meta">
                        <span>📐 25 m²</span>
                        <span>👥 2 người</span>
                        <span>⚠ Thiếu thông tin</span>
                    </div>

                </div>

                <div class="post-right">

                    <span class="post-status status-rejected">
                        TỪ CHỐI
                    </span>

                    <div class="post-actions">
                        <button class="btn-view">Lý do</button>
                        <button class="btn-edit">Sửa</button>
                    </div>

                </div>

            </article>

        </div>

    </section>

</main>

<script>
    const postSearch = document.getElementById('postSearch');
    const postStatus = document.getElementById('postStatus');

    function filterPosts() {

        const keyword = postSearch.value.toLowerCase().trim();
        const status = postStatus.value;

        document.querySelectorAll('.post-card').forEach(card => {

            const text = card.dataset.search.toLowerCase();
            const cardStatus = card.dataset.status;

            const matchKeyword = text.includes(keyword);
            const matchStatus =
                !status || cardStatus === status;

            card.style.display =
                matchKeyword && matchStatus
                    ? ''
                    : 'none';
        });
    }

    postSearch.addEventListener('input', filterPosts);
    postStatus.addEventListener('change', filterPosts);
</script>

</body>
</html>