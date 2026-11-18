
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Google Maps | Trọ Ơi</title>

    <style>
        body {
            margin: 0;
            padding: 30px;
            font-family: Arial, sans-serif;
            background: #f5f7f5;
            color: #0f172a;
        }

        .map-container {
            max-width: 1100px;
            margin: 0 auto;
            padding: 24px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
        }

        h1 {
            color: #005d4b;
        }

        iframe {
            display: block;
            width: 100%;
            height: 450px;
            border: 0;
            border-radius: 12px;
        }

        @media (max-width: 600px) {
            body {
                padding: 12px;
            }

            .map-container {
                padding: 14px;
            }

            iframe {
                height: 350px;
            }
        }
    </style>
</head>
<body>
    <div class="map-container">
        <h1>Vị trí phòng trọ</h1>
        <p>Xem vị trí trên Google Maps (Hỗ trợ cả tọa độ Kinh độ, Vĩ độ và Địa chỉ).</p>

        @php
            $lat = request('lat');
            $lng = request('lng');
            $q = request('q');
            if (!empty($lat) && !empty($lng)) {
                $target = "{$lat},{$lng}";
            } elseif (!empty($q)) {
                $target = $q;
            } else {
                $target = '10.7826,106.6958'; // Hồ Con Rùa tọa độ mặc định
            }
        @endphp

        <iframe
            title="Bản đồ vị trí phòng trọ"
            loading="lazy"
            allowfullscreen
            referrerpolicy="no-referrer-when-downgrade"
            src="https://maps.google.com/maps?q={{ urlencode($target) }}&hl=vi&z=16&output=embed">
        </iframe>
    </div>
</body>
</html>
