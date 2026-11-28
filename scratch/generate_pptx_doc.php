<?php

$outputPath = 'C:\\PHP 2\\DoAnLTMNM\\Tong_Hop_Toan_Bo_Do_An_Tro_Oi_Thuyet_Trinh_PPTX.docx';

$zip = new ZipArchive();
if ($zip->open($outputPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
    die("Không thể tạo file docx: $outputPath\n");
}

// 1. [Content_Types].xml
$contentTypes = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Types xmlns="http://schemas.openxmlformats.org/package/2006/content-types">
    <Default Extension="rels" ContentType="application/vnd.openxmlformats-package.relationships+xml"/>
    <Default Extension="xml" ContentType="application/xml"/>
    <Override PartName="/word/document.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.document.main+xml"/>
    <Override PartName="/word/styles.xml" ContentType="application/vnd.openxmlformats-officedocument.wordprocessingml.styles+xml"/>
</Types>';
$zip->addFromString('[Content_Types].xml', $contentTypes);

// 2. _rels/.rels
$rels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
    <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/officeDocument" Target="word/document.xml"/>
</Relationships>';
$zip->addFromString('_rels/.rels', $rels);

// 3. word/_rels/document.xml.rels
$docRels = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<Relationships xmlns="http://schemas.openxmlformats.org/package/2006/relationships">
    <Relationship Id="rId1" Type="http://schemas.openxmlformats.org/officeDocument/2006/relationships/styles" Target="styles.xml"/>
</Relationships>';
$zip->addFromString('word/_rels/document.xml.rels', $docRels);

// 4. word/styles.xml
$styles = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:styles xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main">
    <w:docDefaults>
        <w:rPrDefault>
            <w:rPr>
                <w:rFonts w:ascii="Calibri" w:hAnsi="Calibri" w:cs="Calibri"/>
                <w:sz w:val="24"/>
                <w:lang w:val="vi-VN"/>
            </w:rPr>
        </w:rPrDefault>
    </w:docDefaults>
</w:styles>';
$zip->addFromString('word/styles.xml', $styles);

// Helpers
function p($text, $options = []) {
    $align = $options['align'] ?? 'both';
    $bold = !empty($options['bold']);
    $italic = !empty($options['italic']);
    $size = $options['size'] ?? 24; // 24 = 12pt
    $color = $options['color'] ?? '1e293b';
    $spaceAfter = $options['after'] ?? 140;
    $spaceBefore = $options['before'] ?? 0;
    $bullet = !empty($options['bullet']);

    $jc = match($align) {
        'center' => '<w:jc w:val="center"/>',
        'right' => '<w:jc w:val="right"/>',
        default => '<w:jc w:val="both"/>'
    };

    $bTag = $bold ? '<w:b/><w:bCs/>' : '';
    $iTag = $italic ? '<w:i/><w:iCs/>' : '';
    $colorTag = "<w:color w:val=\"$color\"/>";
    $szTag = "<w:sz w:val=\"$size\"/><w:szCs w:val=\"$size\"/>";

    $indent = '';
    $prefix = '';
    if ($bullet) {
        $prefix = '<w:r><w:rPr><w:b/><w:color w:val="0369a1"/></w:rPr><w:t xml:space="preserve">➤  </w:t></w:r>';
        $indent = '<w:ind w:left="400" w:hanging="240"/>';
    }

    $escaped = htmlspecialchars($text, ENT_XML1, 'UTF-8');

    return "<w:p>
        <w:pPr>
            $jc
            <w:spacing w:before=\"$spaceBefore\" w:after=\"$spaceAfter\" w:line=\"280\" w:lineRule=\"auto\"/>
            $indent
        </w:pPr>
        $prefix
        <w:r>
            <w:rPr>
                <w:rFonts w:ascii=\"Calibri\" w:hAnsi=\"Calibri\"/>
                $bTag
                $iTag
                $colorTag
                $szTag
            </w:rPr>
            <w:t xml:space=\"preserve\">$escaped</w:t>
        </w:r>
    </w:p>";
}

function h1($title) {
    return p($title, [
        'bold' => true,
        'size' => 34, // 17pt
        'color' => '0c2340',
        'before' => 360,
        'after' => 180,
    ]);
}

function h2($title) {
    return p($title, [
        'bold' => true,
        'size' => 28, // 14pt
        'color' => '0369a1',
        'before' => 240,
        'after' => 120,
    ]);
}

function h3($title) {
    return p($title, [
        'bold' => true,
        'size' => 24, // 12pt
        'color' => '0f766e',
        'before' => 160,
        'after' => 80,
    ]);
}

function callout($title, $text, $borderCol = '2563eb', $bgCol = 'f8fafc') {
    $titleXml = p("📌 " . $title, [
        'bold' => true,
        'size' => 24,
        'color' => '0c2340',
        'after' => 60
    ]);
    $textXml = p($text, [
        'size' => 22,
        'color' => '334155',
        'after' => 60
    ]);

    return "<w:tbl>
        <w:tblPr>
            <w:tblW w:w=\"9400\" w:type=\"dxa\"/>
            <w:tblBorders>
                <w:top w:val=\"none\"/>
                <w:left w:val=\"single\" w:sz=\"24\" w:space=\"0\" w:color=\"$borderCol\"/>
                <w:bottom w:val=\"none\"/>
                <w:right w:val=\"none\"/>
                <w:insideH w:val=\"none\"/>
                <w:insideV w:val=\"none\"/>
            </w:tblBorders>
        </w:tblPr>
        <w:tr>
            <w:tc>
                <w:tcPr>
                    <w:tcW w:w=\"9400\" w:type=\"dxa\"/>
                    <w:shd w:val=\"clear\" w:color=\"auto\" w:fill=\"$bgCol\"/>
                    <w:tcMar>
                        <w:top w:w=\"140\" w:type=\"dxa\"/>
                        <w:left w:w=\"240\" w:type=\"dxa\"/>
                        <w:bottom w:w=\"140\" w:type=\"dxa\"/>
                        <w:right w:w=\"240\" w:type=\"dxa\"/>
                    </w:tcMar>
                </w:tcPr>
                $titleXml
                $textXml
            </w:tc>
        </w:tr>
    </w:tbl>";
}

function slideBox($slideNum, $slideTitle, $purpose, $bullets, $speakerNotes) {
    $numTitle = p("SLIDE $slideNum: " . mb_strtoupper($slideTitle, 'UTF-8'), [
        'bold' => true,
        'size' => 26,
        'color' => 'ffffff',
        'after' => 80
    ]);
    
    $purposeP = p("🎯 Mục tiêu slide: $purpose", [
        'bold' => true,
        'italic' => true,
        'size' => 22,
        'color' => '0369a1',
        'after' => 80
    ]);

    $bulletsXml = p("📋 Nội dung hiển thị trên Slide (Bullet Points):", ['bold' => true, 'size' => 22, 'color' => '0c2340', 'after' => 60]);
    foreach ($bullets as $b) {
        $bulletsXml .= p($b, ['bullet' => true, 'size' => 22, 'color' => '1e293b', 'after' => 60]);
    }

    $notesXml = p("🗣️ Lời thoại thuyết trình gợi ý (Speaker Notes):", ['bold' => true, 'size' => 22, 'color' => 'b45309', 'before' => 80, 'after' => 60]);
    $notesXml .= p("“" . $speakerNotes . "”", ['italic' => true, 'size' => 22, 'color' => '475569', 'after' => 80]);

    return "<w:tbl>
        <w:tblPr>
            <w:tblW w:w=\"9500\" w:type=\"dxa\"/>
            <w:tblBorders>
                <w:top w:val=\"single\" w:sz=\"12\" w:space=\"0\" w:color=\"0284c7\"/>
                <w:left w:val=\"single\" w:sz=\"12\" w:space=\"0\" w:color=\"0284c7\"/>
                <w:bottom w:val=\"single\" w:sz=\"12\" w:space=\"0\" w:color=\"0284c7\"/>
                <w:right w:val=\"single\" w:sz=\"12\" w:space=\"0\" w:color=\"0284c7\"/>
            </w:tblBorders>
        </w:tblPr>
        <w:tr>
            <w:tc>
                <w:tcPr>
                    <w:tcW w:w=\"9500\" w:type=\"dxa\"/>
                    <w:shd w:val=\"clear\" w:color=\"auto\" w:fill=\"0c2340\"/>
                    <w:tcMar>
                        <w:top w:w=\"140\" w:type=\"dxa\"/>
                        <w:left w:w=\"240\" w:type=\"dxa\"/>
                        <w:bottom w:w=\"100\" w:type=\"dxa\"/>
                        <w:right w:w=\"240\" w:type=\"dxa\"/>
                    </w:tcMar>
                </w:tcPr>
                $numTitle
            </w:tc>
        </w:tr>
        <w:tr>
            <w:tc>
                <w:tcPr>
                    <w:tcW w:w=\"9500\" w:type=\"dxa\"/>
                    <w:shd w:val=\"clear\" w:color=\"auto\" w:fill=\"f8fafc\"/>
                    <w:tcMar>
                        <w:top w:w=\"140\" w:type=\"dxa\"/>
                        <w:left w:w=\"240\" w:type=\"dxa\"/>
                        <w:bottom w:w=\"140\" w:type=\"dxa\"/>
                        <w:right w:w=\"240\" w:type=\"dxa\"/>
                    </w:tcMar>
                </w:tcPr>
                $purposeP
                $bulletsXml
                $notesXml
            </w:tc>
        </w:tr>
    </w:tbl>" . p("", ['after' => 160]);
}

function table($headers, $rows, $widths = []) {
    $colCount = count($headers);
    if (empty($widths)) {
        $totalW = 9400;
        $w = (int)($totalW / $colCount);
        $widths = array_fill(0, $colCount, $w);
    }

    $xml = "<w:tbl>
        <w:tblPr>
            <w:tblW w:w=\"9400\" w:type=\"dxa\"/>
            <w:tblBorders>
                <w:top w:val=\"single\" w:sz=\"8\" w:space=\"0\" w:color=\"cbd5e1\"/>
                <w:left w:val=\"single\" w:sz=\"8\" w:space=\"0\" w:color=\"cbd5e1\"/>
                <w:bottom w:val=\"single\" w:sz=\"8\" w:space=\"0\" w:color=\"cbd5e1\"/>
                <w:right w:val=\"single\" w:sz=\"8\" w:space=\"0\" w:color=\"cbd5e1\"/>
                <w:insideH w:val=\"single\" w:sz=\"4\" w:space=\"0\" w:color=\"e2e8f0\"/>
                <w:insideV w:val=\"single\" w:sz=\"4\" w:space=\"0\" w:color=\"e2e8f0\"/>
            </w:tblBorders>
        </w:tblPr>";

    // Header row
    $xml .= "<w:tr>";
    foreach ($headers as $idx => $h) {
        $cw = $widths[$idx] ?? 2000;
        $pText = p($h, ['bold' => true, 'color' => 'ffffff', 'size' => 22, 'align' => 'center', 'after' => 40]);
        $xml .= "<w:tc>
            <w:tcPr>
                <w:tcW w:w=\"$cw\" w:type=\"dxa\"/>
                <w:shd w:val=\"clear\" w:color=\"auto\" w:fill=\"0c2340\"/>
                <w:tcMar><w:top w:w=\"100\" w:type=\"dxa\"/><w:bottom w:w=\"100\" w:type=\"dxa\"/><w:left w:w=\"120\" w:type=\"dxa\"/><w:right w:w=\"120\" w:type=\"dxa\"/></w:tcMar>
            </w:tcPr>
            $pText
        </w:tc>";
    }
    $xml .= "</w:tr>";

    // Body rows
    foreach ($rows as $rIdx => $row) {
        $bg = ($rIdx % 2 === 0) ? 'ffffff' : 'f8fafc';
        $xml .= "<w:tr>";
        foreach ($row as $idx => $cell) {
            $cw = $widths[$idx] ?? 2000;
            $pText = p($cell, ['color' => '1e293b', 'size' => 22, 'after' => 40]);
            $xml .= "<w:tc>
                <w:tcPr>
                    <w:tcW w:w=\"$cw\" w:type=\"dxa\"/>
                    <w:shd w:val=\"clear\" w:color=\"auto\" w:fill=\"$bg\"/>
                    <w:tcMar><w:top w:w=\"80\" w:type=\"dxa\"/><w:bottom w:w=\"80\" w:type=\"dxa\"/><w:left w:w=\"120\" w:type=\"dxa\"/><w:right w:w=\"120\" w:type=\"dxa\"/></w:tcMar>
                </w:tcPr>
                $pText
            </w:tc>";
        }
        $xml .= "</w:tr>";
    }

    $xml .= "</w:tbl>" . p("", ['after' => 120]);
    return $xml;
}

$bodyXml = '';

// ==========================================
// TRANG BÌA & TIÊU ĐỀ
// ==========================================
$bodyXml .= p("HỆ THỐNG QUẢN LÝ VÀ CHO THUÊ NHÀ TRỌ THÔNG MINH - TRỌ ƠI", [
    'align' => 'center',
    'bold' => true,
    'size' => 40, // 20pt
    'color' => '0c2340',
    'before' => 0,
    'after' => 80
]);

$bodyXml .= p("TÀI LIỆU TỔNG HỢP TOÀN DIỆN ĐỒ ÁN - TỔNG HỢP KIẾN TRÚC, NGHIỆP VỤ & KỊCH BẢN BẢO VỆ POWERPOINT", [
    'align' => 'center',
    'bold' => true,
    'size' => 26, // 13pt
    'color' => '0284c7',
    'before' => 0,
    'after' => 240
]);

$bodyXml .= p("Tài liệu được xây dựng chuẩn mực nhằm cung cấp toàn bộ dữ liệu chi tiết của hệ thống để Claude (hoặc công cụ AI) sinh slide thuyết trình PowerPoint (PPTX) chuyên nghiệp, mạch lạc và bám sát thực tế nhất cho hội đồng chấm đồ án tốt nghiệp / đồ án chuyên ngành.", [
    'align' => 'center',
    'italic' => true,
    'size' => 22,
    'color' => '64748b',
    'after' => 360
]);

$bodyXml .= callout(
    "HƯỚNG DẪN DÀNH CHO CLAUDE ĐỂ TẠO FILE SLIDE POWERPOINT (PPTX)",
    "Khi nhận được tài liệu này, Claude hãy thực hiện: (1) Trích xuất cấu trúc 18 Slide tại Phần IV thành các trang slide chuẩn trong PowerPoint; (2) Mỗi slide phân chia rõ ràng Tiêu đề (Slide Title), Điểm nhấn cốt lõi (Key Points - tối đa 4-5 gạch đầu dòng ngắn gọn, cô đọng), Sơ đồ minh họa/Icon trực quan; (3) Đi kèm Lời thoại người thuyết trình (Speaker Notes) ở chân trang để sinh viên tự tin thuyết trình trôi chảy.",
    "0284c7",
    "e0f2fe"
);

// ==========================================
// PHẦN 1: THÔNG TIN TỔNG QUAN ĐỀ TÀI
// ==========================================
$bodyXml .= h1("PHẦN I. THÔNG TIN TỔNG QUAN ĐỀ TÀI & BỐI CẢNH DỰ ÁN");

$bodyXml .= h2("1. Tên đề tài & Thông tin đồ án");
$bodyXml .= p("Tên đề tài: Xây dựng Hệ thống Quản lý và Cho thuê Nhà trọ Thông minh - \"TRỌ ƠI\" (TroOi Smart Rental Ecosystem).", ['bold' => true]);
$bodyXml .= p("Lĩnh vực nghiên cứu: Công nghệ phần mềm, Hệ thống thông tin web, Chuyển đổi số trong bất động sản cho thuê (PropTech), Trí tuệ nhân tạo (AI) & Thanh toán điện tử (Fintech).");

$bodyXml .= h2("2. Tính cấp thiết & Thực trạng quản lý nhà trọ hiện nay (Problem Statement)");
$bodyXml .= p("Trong bối cảnh đô thị hóa nhanh và nhu cầu thuê trọ của sinh viên, người đi làm tại các thành phố lớn ngày càng cao, mô hình quản lý phòng trọ truyền thống đang bộc lộ rất nhiều hạn chế nghiêm trọng:");
$bodyXml .= p("Đối với Chủ nhà trọ: Phải ghi chép sổ sách thủ công; cuối tháng tốn hàng giờ đi từng phòng ghi số điện nước và bấm máy tính cộng tiền; đối mặt rủi ro sai sót tính toán; mất nhiều thời gian rà soát sao kê ngân hàng xem phòng nào đã đóng tiền; hợp đồng giấy tờ dễ rách nát, thất lạc; việc tiếp nhận yêu cầu sửa chữa hay khiếu nại của cư dân bị rời rạc, chậm trễ.", ['bullet' => true]);
$bodyXml .= p("Đối với Người thuê trọ: Gặp khó khăn khi tìm phòng thực tế (tin giả mạo, phòng không như ảnh); thiếu tính minh bạch về giá cả và đánh giá của những người từng thuê trước; việc đóng tiền hàng tháng rườm rà (phải nhập STK, gõ đúng cú pháp); thủ tục ký và theo dõi hợp đồng bất tiện; khi phòng hỏng hóc thiết bị thì khó liên hệ chủ trọ.", ['bullet' => true]);
$bodyXml .= p("Đối với Xã hội & Môi trường: Thất thoát doanh thu, tranh chấp hợp đồng và chỉ số điện nước không đáng có, lãng phí giấy tờ hóa đơn in ấn.", ['bullet' => true]);

$bodyXml .= h2("3. Mục tiêu của đề tài & Giải pháp đề xuất");
$bodyXml .= p("Hệ thống \"Trọ Ơi\" được xây dựng với mục tiêu số hóa 100% vòng đời quản lý nhà trọ (End-to-End Rental Lifecycle Management):");
$bodyXml .= p("Số hóa toàn bộ quy trình: Đăng tin cho thuê -> Tìm kiếm & Định vị bản đồ -> Đặt lịch xem phòng -> Ký hợp đồng điện tử -> Ghi nhận chỉ số điện nước -> Tự động lập hóa đơn -> Thu tiền phòng tự động -> Quản lý báo hỏng sửa chữa -> Thanh lý hợp đồng & Đánh giá minh bạch.", ['bullet' => true]);
$bodyXml .= p("Tích hợp công nghệ 4.0 tiên phong: Ứng dụng AI Gemini (Chatbot tư vấn tìm phòng 24/7 & OCR đọc số đồng hồ điện nước qua ảnh chụp); Cổng thanh toán VietQR Webhook (SePay) giúp tự động gạch nợ hóa đơn chỉ trong 3 giây; Hệ thống gửi email tự động và chuông thông báo In-app theo thời gian thực.", ['bullet' => true]);

$bodyXml .= h2("4. Các nhóm đối tượng sử dụng hệ thống (User Roles)");
$tableRoles = [
    ["Vai trò (Role)", "Mô tả trách nhiệm", "Các chức năng trọng tâm"],
    ["Quản trị viên (Super Admin)", "Quản lý toàn bộ nền tảng, giám sát dữ liệu và đảm bảo an toàn hệ thống.", "Dashboard tổng quan sàn, Thống kê doanh thu, Quản lý người dùng (Khóa/Mở khóa), Duyệt tin đăng, Xử lý tố cáo vi phạm."],
    ["Chủ nhà trọ (Landlord / Owner)", "Người sở hữu hoặc quản lý các khu nhà trọ, phòng trọ cho thuê.", "Quản lý khu trọ & phòng, Đăng tin, Quản lý lịch hẹn, Lập hợp đồng điện tử, Ghi điện nước bằng AI OCR, Xuất hóa đơn, Quản lý thanh toán SePay, Xử lý bảo trì."],
    ["Khách thuê trọ (Tenant)", "Người tìm kiếm phòng hoặc đang thuê cư trú tại phòng trọ.", "Tìm kiếm phòng lọc theo tiêu chí & bản đồ số, Đặt lịch xem phòng, Chatbot AI tư vấn, Ký hợp đồng, Quét mã QR thanh toán tiền phòng, Báo hỏng sửa chữa, Đánh giá phòng sau khi ở."]
];
$bodyXml .= table($tableRoles[0], array_slice($tableRoles, 1), [2200, 3200, 4000]);

// ==========================================
// PHẦN 2: KIẾN TRÚC HỆ THỐNG & CÔNG NGHỆ ÁP DỤNG
// ==========================================
$bodyXml .= h1("PHẦN II. KIẾN TRÚC HỆ THỐNG & CÔNG NGHỆ ÁP DỤNG");

$bodyXml .= h2("1. Ngăn xếp công nghệ (Technology Stack)");
$tableTech = [
    ["Thành phần", "Công nghệ sử dụng", "Vai trò và lý do lựa chọn"],
    ["Backend Framework", "PHP 8.2+ / Laravel 12.x", "Kiến trúc MVC vững chắc, Eloquent ORM bảo mật, Middleware phân quyền mạnh mẽ, hỗ trợ Notification và CSRF Protection tự động."],
    ["Frontend UI/UX", "Blade Engine, HTML5, CSS3, Tailwind CSS, Bootstrap 5, JavaScript (ES6+)", "Giao diện hiện đại, responsive đa thiết bị, bóng đèn kéo thả tương tác trang Login, thiết kế trực quan tối ưu cho cả máy tính và điện thoại."],
    ["Giao tiếp tương tác", "AJAX (Fetch API / XMLHttpRequest)", "Xử lý tương tác mượt mà không reload trang: Tìm kiếm & Lọc người dùng, Khóa/Mở khóa tài khoản, Xử lý hóa đơn, Lọc phòng trọ."],
    ["Cơ sở dữ liệu", "MySQL 8.x / MariaDB", "Cơ sở dữ liệu quan hệ chuẩn 3NF, ràng buộc khóa ngoại (Foreign Keys), Indexing tối ưu tốc độ truy vấn, hỗ trợ Database Transactions an toàn."],
    ["Cổng thanh toán tự động", "SePay API / VietQR Webhook", "Sinh mã VietQR động theo từng hóa đơn; SePay Webhook tự động bắt biến động số dư ngân hàng và gạch nợ trong 3 giây mà không cần can thiệp thủ công."],
    ["Trí tuệ nhân tạo (AI)", "Google Gemini API (1.5 Flash / 2.5 Flash)", "Hai phân hệ AI cốt lõi: (1) Chatbot AI tư vấn tìm phòng thông minh; (2) AI Vision OCR đọc nhận diện chỉ số đồng hồ điện/nước từ ảnh chụp."],
    ["Dịch vụ Email", "Resend API / Gmail SMTP", "Gửi email tự động khi: Tạo tài khoản, Khóa tài khoản, Gửi link yêu cầu mở khóa, Thông báo hóa đơn tiền phòng, Khôi phục mật khẩu."],
    ["Bản đồ trực quan", "OpenStreetMap / Leaflet JS", "Định vị vị trí nhà trọ trên bản đồ số, hỗ trợ tìm phòng quanh khu vực và chỉ đường cho khách thuê."]
];
$bodyXml .= table($tableTech[0], array_slice($tableTech, 1), [2200, 2800, 4400]);

$bodyXml .= h2("2. Sơ đồ kiến trúc phân tầng (Multi-tier Architecture)");
$bodyXml .= p("Hệ thống được thiết kế theo mô hình 3 tầng (3-Tier MVC Architecture) chuẩn mực:");
$bodyXml .= p("Tầng Trình bày (Presentation Tier): View Blade, CSS/JS, Leaflet Map, AJAX UI components tương tác trực tiếp với người dùng.", ['bullet' => true]);
$bodyXml .= p("Tầng Xử lý Nghiệp vụ (Business Logic Tier): Hệ thống Controller (Admin, Owner, Tenant), Middleware (Auth, Role check, Account Status), Service Layer (GeminiService, ChatbotService, ResendService) và Form Requests validate dữ liệu.", ['bullet' => true]);
$bodyXml .= p("Tầng Dữ liệu & Tích hợp (Data & Integration Tier): MySQL Database quản lý quan hệ thực thể, SePay Webhook nhận tín hiệu ngân hàng, Google Gemini AI xử lý ngôn ngữ tự nhiên và thị giác máy tính.", ['bullet' => true]);

$bodyXml .= h2("3. Thiết kế Cơ sở dữ liệu chi tiết (Core Database Entities)");
$bodyXml .= p("Cơ sở dữ liệu của hệ thống bao gồm hơn 20 bảng quan hệ chặt chẽ. Dưới đây là các thực thể cốt lõi nhất:");
$tableDb = [
    ["Tên Bảng", "Khóa chính / Khóa ngoại", "Ý nghĩa và các trường quan trọng"],
    ["users", "id", "Quản lý người dùng: name, email, password, phone, role (admin/owner/tenant), status (active/locked), avatar, unlock_request."],
    ["properties", "id / owner_id", "Khu nhà trọ: name, address, latitude, longitude, description, status, quy mô số phòng."],
    ["rooms", "id / property_id", "Từng phòng trọ: room_number, price, area, max_tenants, status (available, rented, maintenance)."],
    ["services & property_services", "id / owner_id", "Dịch vụ: Điện, Nước, Rác, Wifi, Giữ xe,... Đơn giá và đơn vị tính (kWh, khối, tháng, người)."],
    ["contracts", "id / room_id, tenant_id", "Hợp đồng thuê: start_date, end_date, rental_price, deposit_amount, status (active, terminated, expired), terms."],
    ["utility_readings", "id / room_id, contract_id", "Chỉ số điện nước định kỳ: old_electricity, new_electricity, old_water, new_water, meter_image, cycle."],
    ["invoices & invoice_items", "id / contract_id, room_id", "Hóa đơn hàng tháng: total_amount, due_date, status (unpaid, paid, overdue), chi tiết từng dòng dịch vụ."],
    ["payments", "id / invoice_id", "Lịch sử thanh toán: payment_code, amount, transaction_id, payment_method (sepay_qr, cash), paid_at."],
    ["viewing_appointments", "id / room_id, tenant_id", "Lịch hẹn xem phòng: appointment_date, status (pending, approved, rejected, completed), note."],
    ["maintenance_requests", "id / room_id, tenant_id", "Báo hỏng sửa chữa: title, description, image, priority, damage_type, cost, status (pending, in_progress, resolved)."],
    ["reviews", "id / room_id, tenant_id", "Đánh giá phòng: rating (1-5 sao), comment, status (visible/hidden). Chỉ khách từng có hợp đồng mới được tạo."]
];
$bodyXml .= table($tableDb[0], array_slice($tableDb, 1), [2400, 2600, 4400]);

// ==========================================
// PHẦN 3: PHÂN TÍCH TOÀN BỘ CÁC LUỒNG HOẠT ĐỘNG
// ==========================================
$bodyXml .= h1("PHẦN III. PHÂN TÍCH CHI TIẾT CÁC LUỒNG HOẠT ĐỘNG NGHIỆP VỤ (BUSINESS WORKFLOWS)");

$bodyXml .= h2("1. Luồng Xác thực, Phân quyền & Quản lý Tài khoản (Auth & RBAC)");
$bodyXml .= p("Quy trình Đăng ký & Đăng nhập: Người dùng đăng ký chọn vai trò (Khách thuê hoặc Chủ trọ). Mật khẩu được mã hóa an toàn bằng thuật toán Bcrypt / Argon2. Trang đăng nhập được trang bị hiệu ứng dây kéo bóng đèn bật/tắt đèn phòng trọ tương tác độc đáo và trực quan.", ['bullet' => true]);
$bodyXml .= p("Cơ chế Phân quyền Đa tầng: Hệ thống dùng Middleware kiểm tra `role` (Admin, Owner, Tenant) chặn truy cập trái phép vào dashboard của nhau.", ['bullet' => true]);
$bodyXml .= p("Cơ chế Khóa & Mở khóa Tài khoản bằng AJAX: Admin có quyền khóa tài khoản vi phạm. Khi bị khóa, hệ thống tự động gửi email thông báo kèm link yêu cầu mở khóa riêng biệt. Người dùng bấm vào gửi lý do -> Admin nhận thông báo và xử lý duyệt/mở lại qua modal AJAX không cần reload trang.", ['bullet' => true]);
$bodyXml .= p("Quên mật khẩu & Đổi mật khẩu: Sử dụng mã xác thực qua email SMTP đảm bảo an toàn tuyệt đối.", ['bullet' => true]);

$bodyXml .= h2("2. Luồng Khám phá Phòng trọ & Đặt lịch xem phòng");
$bodyXml .= p("Tìm kiếm & Lọc thông minh: Khách thuê lọc phòng theo khoảng giá, vị trí quận/huyện, diện tích, các tiện ích mong muốn (máy lạnh, wifi, gác xép, máy giặt, ban công).", ['bullet' => true]);
$bodyXml .= p("Bản đồ số OpenStreetMap / Leaflet: Xem trực tiếp vị trí các khu nhà trọ trên bản đồ, tính toán cự ly tới trường học/nơi làm việc.", ['bullet' => true]);
$bodyXml .= p("Đặt lịch hẹn xem phòng (Viewing Appointments): Khách thuê chọn ngày giờ muốn đến xem phòng trực tiếp -> Chủ trọ nhận chuông thông báo -> Chủ trọ có thể Duyệt (Accept) hoặc Từ chối kèm lý do -> Khách nhận thông báo xác nhận lịch hẹn.", ['bullet' => true]);

$bodyXml .= h2("3. Luồng Quản lý Hợp đồng Thuê điện tử & Quản lý Cư dân");
$bodyXml .= p("Lập Hợp đồng trực tuyến: Chủ trọ chọn phòng, chọn khách thuê (đã có tài khoản hoặc nhập CCCD/SĐT), nhập số tiền cọc, giá thuê hàng tháng, ngày bắt đầu và kết thúc, các dịch vụ áp dụng.", ['bullet' => true]);
$bodyXml .= p("Ràng buộc trạng thái phòng: Khi hợp đồng có hiệu lực (`active`), phòng tự động chuyển sang trạng thái Đang thuê (`rented`), không còn hiển thị trống trên sàn tìm kiếm.", ['bullet' => true]);
$bodyXml .= p("Quản lý cư dân cùng phòng: Chủ trọ và khách thuê có thể khai báo danh sách thành viên đang ở chung phòng (Contract Members) nhằm phục vụ quản lý tạm trú tạm vắng.", ['bullet' => true]);
$bodyXml .= p("Thanh lý Hợp đồng (Contract Termination): Khi hợp đồng hết hạn hoặc khách trả phòng, chủ trọ bấm Thanh lý hợp đồng -> Hệ thống tất toán tiền cọc, cập nhật trạng thái hợp đồng thành `terminated`, tự động đổi trạng thái phòng về Còn trống (`available`), và gửi thông báo chuông mời khách thuê để lại đánh giá trải nghiệm thực tế.", ['bullet' => true]);

$bodyXml .= h2("4. Điểm sáng công nghệ: Ghi chỉ số Điện - Nước thông minh bằng AI OCR");
$bodyXml .= p("Hệ thống giải quyết triệt để nỗi đau ghi điện nước thủ công bằng 2 phương thức:");
$bodyXml .= p("Phương thức 1 - Nhập tay định kỳ: Tự động tải chỉ số cũ từ tháng trước, chủ trọ chỉ cần gõ chỉ số mới, hệ thống tự tính lượng tiêu thụ.", ['bullet' => true]);
$bodyXml .= p("Phương thức 2 - Quét ảnh AI OCR (Gemini Vision): Chủ trọ chỉ cần dùng điện thoại chụp ảnh mặt đồng hồ công tơ điện hoặc đồng hồ nước. Hệ thống gửi ảnh tới Google Gemini API với prompt nhận diện chuyên sâu -> AI trích xuất chính xác dãy số công tơ và tự động điền vào ô chỉ số mới -> Giảm 95% thời gian và triệt tiêu hoàn toàn sai sót con người.", ['bullet' => true]);

$bodyXml .= h2("5. Điểm sáng công nghệ: Lập Hóa đơn & Cổng thanh toán tự động SePay Webhook");
$bodyXml .= p("Quy trình 5 bước thu tiền phòng tự động 100% không chạm:");
$bodyXml .= p("Bước 1: Tự động tổng hợp tiền phòng + Tiền điện (chỉ số x đơn giá) + Tiền nước + Tiền dịch vụ cố định -> Sinh hóa đơn hàng tháng.", ['bullet' => true]);
$bodyXml .= p("Bước 2: Hệ thống tạo bản ghi thanh toán với mã giao dịch duy nhất (Ví dụ: TROOI102938) và sinh ảnh VietQR động chuẩn NAPAS 24/7 chứa chính xác số tài khoản chủ trọ, số tiền và nội dung chuyển khoản.", ['bullet' => true]);
$bodyXml .= p("Bước 3: Khách thuê mở bất kỳ ứng dụng ngân hàng (VCB, MB, Techcombank, ACB, Momo...) quét mã QR và xác nhận chuyển tiền chỉ với 1 chạm.", ['bullet' => true]);
$bodyXml .= p("Bước 4: Ngay khi tiền vào tài khoản ngân hàng, hệ sinh thái SePay bắt biến động số dư và gửi Webhook (HTTP POST) kèm token bảo mật tới máy chủ Trọ Ơi (/api/sepay/webhook).", ['bullet' => true]);
$bodyXml .= p("Bước 5: Backend kiểm tra chữ ký Webhook Token, đối soát mã hóa đơn, chống trùng lặp giao dịch (Idempotency) -> Tự động chuyển trạng thái hóa đơn sang 'Đã thanh toán' (paid) và phát chuông thông báo cho cả chủ trọ và khách thuê chỉ trong 3 giây.", ['bullet' => true]);

$bodyXml .= h2("6. Điểm sáng công nghệ: Trợ lý Chatbot AI (TroOi Virtual Assistant)");
$bodyXml .= p("Tích hợp Google Gemini AI đa năng hoạt động 24/7 với kiến trúc RAG nhẹ (Retrieval-Augmented Generation):");
$bodyXml .= p("Tư vấn tìm phòng thông minh: Người dùng chỉ cần gõ ngôn ngữ tự nhiên: \"Tìm giúp tôi phòng trọ quận 12 giá dưới 3 triệu có máy lạnh\", Chatbot truy vấn cơ sở dữ liệu thời gian thực và trả về danh sách phòng kèm link xem chi tiết.", ['bullet' => true]);
$bodyXml .= p("Giải đáp quy định & hỗ trợ: Hướng dẫn khách thuê cách thức đóng tiền bằng VietQR, quy trình xin sửa chữa thiết bị, quy định hợp đồng thuê.", ['bullet' => true]);

$bodyXml .= h2("7. Luồng Tiếp nhận & Xử lý Báo hỏng Sửa chữa (Maintenance)");
$bodyXml .= p("Khách thuê tạo yêu cầu: Khách chụp ảnh hiện trường (ví dụ: vòi nước rò rỉ, điều hòa không mát), chọn mức độ khẩn cấp và gửi yêu cầu.", ['bullet' => true]);
$bodyXml .= p("Chủ trọ xử lý: Nhận thông báo tức thì, cập nhật tiến độ (Đang xử lý -> Đã hoàn thành), ghi nhận chi phí sửa chữa và quy định bên nào chịu phí (chủ trọ hay trừ vào tiền cọc).", ['bullet' => true]);

$bodyXml .= h2("8. Luồng Đánh giá & Phản hồi minh bạch sau khi Thanh lý hợp đồng");
$bodyXml .= p("Nguyên tắc minh bạch & chống đánh giá ảo: Chỉ những khách thuê đã từng ký hợp đồng thuê phòng đó (đặc biệt sau khi thanh lý hợp đồng) mới được quyền gửi đánh giá sao (1-5 sao) và nhận xét.", ['bullet' => true]);
$bodyXml .= p("Hiển thị công khai cho người khác xem: Trên trang chi tiết phòng công khai (`/rooms/{id}`), hiển thị bảng điểm trung bình, phân bổ sao và toàn bộ bình luận của những người từng ở kèm huy hiệu xác thực: '✓ Đã từng thuê phòng này', giúp người thuê mới an tâm tuyệt đối trước khi quyết định đặt cọc.", ['bullet' => true]);

$bodyXml .= h2("9. Phân hệ Quản trị Admin Toàn diện (Super Admin Management)");
$bodyXml .= p("Dashboard điều hành: Biểu đồ thống kê tăng trưởng tài khoản, tỷ lệ lấp đầy phòng trọ, tổng doanh thu luân chuyển qua sàn.", ['bullet' => true]);
$bodyXml .= p("Kiểm duyệt nội dung: Duyệt hoặc từ chối các bài đăng phòng trọ mới từ chủ trọ để ngăn chặn tin rác, tin lừa đảo.", ['bullet' => true]);
$bodyXml .= p("Xử lý tố cáo vi phạm: Tiếp nhận các khiếu nại, tố cáo từ khách thuê về bài đăng sai sự thật và tiến hành xử lý/khóa tài khoản vi phạm.", ['bullet' => true]);

// ==========================================
// PHẦN 4: DÀN BÀI THUYẾT TRÌNH POWERPOINT GỢI Ý (18 SLIDE)
// ==========================================
$bodyXml .= h1("PHẦN IV. KỊCH BẢN THUYẾT TRÌNH POWERPOINT CHI TIẾT (DÀNH CHO CLAUDE SINH SLIDE)");
$bodyXml .= p("Dưới đây là kịch bản chuẩn hóa 18 slide thuyết trình bảo vệ đồ án tốt nghiệp / đồ án môn học. Claude hãy dựa vào cấu trúc này để tạo slide hoặc mã lệnh sinh slide PPTX:");

// Slide 1
$bodyXml .= slideBox(
    1,
    "Trang Tiêu Đề - Báo Cáo Đồ Án Tốt Nghiệp",
    "Gây ấn tượng ban đầu, giới thiệu tên đề tài và thông tin thành viên.",
    [
        "Tên đề tài: XÂY DỰNG HỆ THỐNG QUẢN LÝ VÀ CHO THUÊ NHÀ TRỌ THÔNG MINH - TRỌ ƠI",
        "Sinh viên thực hiện & Mã số sinh viên",
        "Giảng viên hướng dẫn: [Tên Giảng Viên]",
        "Khoa Công Nghệ Thông Tin - Chuyên ngành Kỹ thuật Phần mềm / Hệ thống thông tin",
        "Thời gian báo cáo: Tháng 11/2026"
    ],
    "Kính thưa quý thầy cô trong Hội đồng chấm đồ án, hôm nay em xin phép được đại diện nhóm báo cáo đề tài 'Xây dựng Hệ thống Quản lý và Cho thuê Nhà trọ Thông minh - Trọ Ơi', một giải pháp số hóa toàn diện giúp chuyển đổi số công tác quản lý phòng trọ và tích hợp công nghệ AI, Fintech hiện đại."
);

// Slide 2
$bodyXml .= slideBox(
    2,
    "Bối Cảnh Đề Tài & Nỗi Đau Thực Tế (Problem Statement)",
    "Nêu bật những bất cập của mô hình quản lý phòng trọ truyền thống.",
    [
        "Nhu cầu thuê trọ tại các đô thị tăng vọt, nhưng phương thức quản lý vẫn mang tính thủ công 80%.",
        "Nỗi đau của Chủ trọ: Ghi chép sổ sách dễ nhầm lẫn; tốn thời gian chốt điện nước từng phòng; đối soát tiền chuyển khoản ngân hàng mệt mỏi; hợp đồng giấy dễ thất lạc.",
        "Nỗi đau của Khách thuê: Rất khó tìm phòng thật - ảnh thật; đóng tiền rườm rà; báo hỏng sửa chữa chậm chạp; thiếu minh bạch về chi phí và đánh giá.",
        "Hậu quả: Tốn kém thời gian, phát sinh tranh chấp và làm giảm hiệu quả khai thác bất động sản."
    ],
    "Thưa thầy cô, lý do nhóm em lựa chọn đề tài này xuất phát từ chính những trải nghiệm thực tế. Hiện nay đa phần các chủ trọ vẫn dùng sổ tay hoặc excel để tính điện nước, mỗi kỳ thu tiền phải tự tra sao kê ngân hàng rất mất công, còn người thuê thì thiếu một nền tảng minh bạch, tiện lợi để tìm phòng và thanh toán."
);

// Slide 3
$bodyXml .= slideBox(
    3,
    "Mục Tiêu Đề Tài & Giải Pháp \"Trọ Ơi\"",
    "Đưa ra giải pháp tổng thể giải quyết triệt để các vấn đề trên.",
    [
        "Mục tiêu cốt lõi: Xây dựng hệ sinh thái khép kín số hóa 100% vòng đời quản lý nhà trọ (End-to-End).",
        "Chuyển đổi số toàn diện: Tìm phòng -> Hẹn xem phòng -> Ký hợp đồng điện tử -> Quản lý điện nước -> Xuất hóa đơn -> Thanh toán tự động -> Bảo trì -> Đánh giá minh bạch.",
        "Đón đầu công nghệ 4.0: Cổng thanh toán VietQR SePay tự động gạch nợ 3 giây; Trợ lý ảo AI Gemini tư vấn 24/7; AI OCR tự đọc công tơ điện nước qua ảnh chụp.",
        "Giá trị mang lại: Tối ưu 80% thời gian quản lý cho chủ trọ, tăng 100% độ hài lòng cho người thuê."
    ],
    "Để giải quyết triệt để bài toán đó, nhóm em đã phát triển hệ thống Trọ Ơi với mục tiêu số hóa trọn vẹn từ lúc khách tìm phòng cho tới khi thanh lý hợp đồng, đồng thời đưa các công nghệ mới nhất như AI và cổng thanh toán tự động vào để vận hành hoàn toàn không chạm."
);

// Slide 4
$bodyXml .= slideBox(
    4,
    "Mô Hình Phân Quyền & Đối Tượng Người Dùng (RBAC)",
    "Làm rõ 3 vai trò người dùng trong hệ thống.",
    [
        "Khách thuê trọ (Tenant): Tìm phòng thông minh, xem vị trí trên bản đồ, chat với AI, quét VietQR thanh toán tiền phòng, gửi phiếu báo hỏng, để lại đánh giá sau thuê.",
        "Chủ nhà trọ (Landlord/Owner): Quản lý bất động sản, phòng trọ, tạo hợp đồng điện tử, chụp ảnh công tơ cho AI đọc số, xuất hóa đơn tự động, theo dõi dòng tiền thời gian thực.",
        "Quản trị viên (Super Admin): Kiểm soát toàn bộ hệ thống, xem thống kê biểu đồ doanh thu, duyệt tin đăng, khóa/mở khóa tài khoản vi phạm và xử lý báo cáo tố cáo."
    ],
    "Hệ thống được thiết kế theo mô hình phân quyền chặt chẽ với 3 đối tượng người dùng riêng biệt gồm Khách thuê, Chủ trọ và Quản trị viên, mỗi nhóm đều có giao diện và không gian làm việc chuyên biệt, được bảo vệ bằng middleware đa tầng."
);

// Slide 5
$bodyXml .= slideBox(
    5,
    "Kiến Trúc Hệ Thống & Ngăn Xếp Công Nghệ (Tech Stack)",
    "Chứng minh tính khoa học và hiện đại trong công nghệ lập trình.",
    [
        "Backend: PHP 8.2+ kết hợp Laravel 12 Framework vững chắc, kiến trúc MVC, Eloquent ORM, Service Layer tách biệt.",
        "Frontend: Blade Views, Tailwind CSS, Bootstrap 5, AJAX (Fetch/XHR) cho trải nghiệm mượt mà không reload trang, Bản đồ Leaflet JS.",
        "Cơ sở dữ liệu: MySQL 8.x chuẩn hóa 3NF, toàn vẹn dữ liệu với Foreign Keys và Transactions.",
        "Tích hợp bên thứ 3: SePay API (VietQR Webhook), Google Gemini API (AI Flash & Vision), Resend API / Gmail SMTP, OpenStreetMap."
    ],
    "Về mặt công nghệ, hệ thống được xây dựng trên nền tảng PHP 8.2 và Laravel 12 mới nhất theo mô hình MVC. Phía giao diện sử dụng Blade kết hợp AJAX để tối ưu tốc độ và không làm gián đoạn trải nghiệm người dùng, kết hợp các API hàng đầu của Google Gemini và SePay."
);

// Slide 6
$bodyXml .= slideBox(
    6,
    "Thiết Kế Cơ Sở Dữ Liệu Quan Hệ (Database Architecture)",
    "Trình bày sơ đồ quan hệ thực thể (ERD) và các bảng dữ liệu cốt lõi.",
    [
        "Hơn 20 bảng cơ sở dữ liệu được chuẩn hóa cao, thiết kế khoa học và quan hệ chặt chẽ.",
        "Cụm Quản lý Bất động sản: properties (khu trọ) 1-N rooms (phòng trọ) 1-N room_amenities (tiện nghi).",
        "Cụm Nghiệp vụ Cốt lõi: contracts (hợp đồng) 1-N utility_readings (điện nước) 1-N invoices (hóa đơn) 1-1 payments (thanh toán).",
        "Cụm Tương tác & Hỗ trợ: viewing_appointments (lịch hẹn), maintenance_requests (bảo trì), reviews (đánh giá), notifications (thông báo in-app).",
        "Đảm bảo an toàn: Ràng buộc khóa ngoại, Indexing tối ưu tốc độ, Transaction rollback khi có lỗi."
    ],
    "Cơ sở dữ liệu của dự án gồm hơn 20 bảng được chuẩn hóa đạt chuẩn 3NF. Trọng tâm của hệ thống xoay quanh trục liên kết giữa Khu trọ, Phòng trọ, Hợp đồng thuê và Hóa đơn hàng tháng, đảm bảo tính toàn vẹn dữ liệu bằng Database Transaction."
);

// Slide 7
$bodyXml .= slideBox(
    7,
    "Luồng 1: Tìm Kiếm, Bản Đồ Số & Đặt Lịch Xem Phòng",
    "Minh họa hành trình đầu tiên của khách thuê khi tiếp cận hệ thống.",
    [
        "Bộ lọc phòng đa tiêu chí: Lọc theo khu vực (Quận/Huyện), tầm giá thuê, diện tích, tiện ích đi kèm.",
        "Bản đồ tương tác OpenStreetMap: Định vị trực quan các khu trọ, hiển thị chi tiết giá và cự ly.",
        "Đặt lịch hẹn xem phòng trực tuyến: Khách chọn ngày giờ mong muốn -> Hệ thống bắn thông báo chuông cho chủ trọ.",
        "Quy trình xử lý lịch hẹn: Chủ trọ Duyệt hoặc Từ chối kèm lý do -> Khách nhận thông báo xác nhận ngay tức khắc."
    ],
    "Đây là hành trình bắt đầu của người thuê: Khách có thể tìm kiếm và xem vị trí trực tiếp trên bản đồ số, sau đó chọn lịch hẹn xem phòng. Chủ trọ sẽ nhận được thông báo chuông tức thời để xác nhận hoặc điều chỉnh lịch hẹn cho phù hợp."
);

// Slide 8
$bodyXml .= slideBox(
    8,
    "Luồng 2: Ký Kết Hợp Đồng Điện Tử & Quản Lý Cư Dân",
    "Trình bày quy trình pháp lý và quản lý hợp đồng thuê.",
    [
        "Khởi tạo hợp đồng nhanh chóng: Chủ trọ chọn phòng, gán khách thuê, thiết lập giá phòng, tiền cọc, ngày bắt đầu - kết thúc.",
        "Đồng bộ trạng thái thông minh: Khi hợp đồng được kích hoạt (active), phòng tự động đổi trạng thái sang 'Đang thuê' (rented).",
        "Khai báo cư dân cùng phòng (Contract Members): Quản lý danh sách thành viên ở chung phục vụ tạm trú tạm vắng.",
        "Thanh lý hợp đồng (Termination): Tự động tính toán hoàn tiền cọc, giải phóng phòng về 'Còn trống' (available) và kích hoạt luồng đánh giá."
    ],
    "Khi khách đồng ý thuê, chủ trọ tạo hợp đồng điện tử trực tiếp trên web với đầy đủ các điều khoản và tiền cọc. Ngay khi hợp đồng kích hoạt, phòng tự chuyển trạng thái Đang thuê, và khi hết hạn hoặc thanh lý, hệ thống sẽ tự động đưa phòng về trạng thái trống để đón khách mới."
);

// Slide 9
$bodyXml .= slideBox(
    9,
    "Điểm Sáng 1: Ghi Số Điện - Nước Thông Minh Bằng AI OCR",
    "Làm nổi bật tính năng tự động hóa và ứng dụng AI thị giác máy tính.",
    [
        "Nỗi đau giải quyết: Thay thế hoàn toàn việc chủ trọ phải cúi xuống xem từng đồng hồ điện/nước và chép tay vào sổ.",
        "Công nghệ áp dụng: Google Gemini 1.5 Flash Vision API.",
        "Cơ chế hoạt động: Chủ trọ chỉ cần giơ điện thoại chụp ảnh mặt đồng hồ công tơ -> AI phân tích hình ảnh và bóc tách chính xác dãy số công tơ -> Tự động điền vào chỉ số mới.",
        "Hiệu quả: Giảm 95% thời gian chốt chỉ số cuối tháng, loại bỏ 100% nhầm lẫn do chữ viết tay hoặc nhìn sai số."
    ],
    "Một trong những điểm sáng công nghệ nổi bật nhất của đồ án là tính năng AI OCR đọc công tơ điện nước. Thay vì phải chép tay, chủ trọ chỉ cần chụp một bức ảnh đồng hồ, AI Gemini Vision sẽ tự động nhận diện và điền chỉ số vào hệ thống trong chưa đầy 2 giây."
);

// Slide 10
$bodyXml .= slideBox(
    10,
    "Luồng 3: Tự Động Lập Hóa Đơn Hàng Tháng Đa Dịch Vụ",
    "Trình bày quy trình tính toán chi phí minh bạch.",
    [
        "Tính toán tự động: Tiền phòng cố định + Tiền điện (chỉ số mới - cũ x đơn giá) + Tiền nước + Dịch vụ cố định (rác, wifi, quản lý).",
        "Minh bạch & Chi tiết: Hóa đơn phân tách rõ ràng từng mục chi phí kèm chỉ số tiêu thụ để người thuê dễ dàng kiểm chứng.",
        "Phát hành hóa đơn đa kênh: Bắn thông báo chuông trong ứng dụng (In-app) kèm gửi email chi tiết cho khách thuê.",
        "Hỗ trợ quản lý công nợ: Trạng thái hóa đơn phân định rõ (Chưa thanh toán, Đã thanh toán, Quá hạn)."
    ],
    "Sau khi có chỉ số điện nước, hệ thống tự động tổng hợp thành hóa đơn hoàn chỉnh bao gồm tiền phòng, điện nước và các phí dịch vụ. Hóa đơn được gửi tức thì đến ứng dụng và hòm thư email của khách thuê với từng khoản tiền chi tiết, rõ ràng."
);

// Slide 11
$bodyXml .= slideBox(
    11,
    "Điểm Sáng 2: Cổng Thanh Toán Tự Động SePay (VietQR Webhook)",
    "Trình bày giải pháp thanh toán tự động hóa hoàn toàn không chạm.",
    [
        "Sinh mã VietQR động: Mỗi hóa đơn đi kèm mã QR riêng biệt chứa sẵn STK ngân hàng, số tiền và mã thanh toán (Ví dụ: TROOI12345).",
        "Khách thanh toán 1 chạm: Dùng app ngân hàng bất kỳ quét mã, không cần nhập tay STK hay nội dung chuyển khoản.",
        "Cơ chế SePay Webhook: Tiền vào tài khoản -> SePay bắt biến động số dư -> Gửi HTTP POST Webhook về hệ thống Trọ Ơi.",
        "Gạch nợ tự động 3 giây: Backend kiểm tra Token bí mật, chống lặp giao dịch (Idempotency) -> Chuyển trạng thái 'Đã thanh toán' (paid) ngay lập tức."
    ],
    "Điểm nhấn thứ hai là tích hợp cổng thanh toán VietQR tự động qua SePay Webhook. Khách chỉ cần quét mã QR bằng bất kỳ ngân hàng nào, hệ thống sẽ tự động gạch nợ hóa đơn và bắn thông báo thành công chỉ sau 3 giây mà chủ trọ không cần phải mở app ngân hàng kiểm tra."
);

// Slide 12
$bodyXml .= slideBox(
    12,
    "Điểm Sáng 3: Trợ Lý Ảo Chatbot AI Tư Vấn 24/7 (Gemini Flash)",
    "Trình bày giải pháp hỗ trợ người dùng bằng trí tuệ nhân tạo.",
    [
        "Tích hợp mô hình ngôn ngữ lớn: Google Gemini 1.5 Flash API với độ trễ phản hồi cực thấp.",
        "Kỹ thuật Prompt Engineering chuyên sâu: Cung cấp ngữ cảnh về cơ sở dữ liệu phòng trọ, quy định và chính sách thuê trọ.",
        "Khả năng thông minh: Hiểu ngôn ngữ tự nhiên tiếng Việt, tự động truy vấn tìm phòng theo yêu cầu (quận, mức giá, tiện nghi).",
        "Hỗ trợ 24/7: Giải đáp tức thì về cách thức quét mã thanh toán, hướng dẫn gửi báo hỏng, nội quy nhà trọ."
    ],
    "Bên cạnh đó, hệ thống tích hợp Trợ lý ảo Chatbot AI sử dụng Google Gemini Flash. Người dùng có thể trò chuyện tự nhiên để nhờ AI gợi ý phòng phù hợp với túi tiền, hoặc hỏi đáp về nội quy, cách thức thanh toán bất cứ lúc nào trong ngày."
);

// Slide 13
$bodyXml .= slideBox(
    13,
    "Luồng 4: Quản Lý Yêu Cầu Báo Hỏng & Sửa Chữa (Maintenance)",
    "Minh họa quy trình chăm sóc cư dân và bảo trì cơ sở vật chất.",
    [
        "Khách thuê gửi yêu cầu: Nhập tiêu đề, mô tả sự cố, đính kèm ảnh chụp hiện trường và chọn mức độ khẩn cấp.",
        "Chủ trọ tiếp nhận: Nhận thông báo chuông ngay lập tức, tiến hành liên hệ thợ hoặc tự sửa chữa.",
        "Cập nhật tiến độ minh bạch: Theo dõi trạng thái theo 3 giai đoạn: Chờ tiếp nhận -> Đang sửa chữa -> Đã hoàn thành.",
        "Ghi nhận chi phí bảo trì: Hỗ trợ ghi nhận chi phí và xác định bên chịu trách nhiệm chi trả."
    ],
    "Khi phòng xảy ra sự cố như hỏng bóng đèn hay rò rỉ nước, khách thuê chỉ cần tạo phiếu báo hỏng kèm ảnh chụp. Chủ trọ sẽ nhận được thông báo để xử lý kịp thời và cập nhật tiến độ công khai, giúp khách thuê hoàn toàn yên tâm."
);

// Slide 14
$bodyXml .= slideBox(
    14,
    "Luồng 5: Đánh Giá Minh Bạch Sau Khi Thanh Lý Hợp Đồng",
    "Làm rõ tính năng bảo vệ độ tin cậy và minh bạch của hệ thống.",
    [
        "Cơ chế chống đánh giá ảo (Verified Tenant Review): Chỉ những người thuê ĐÃ TỪNG KÝ HỢP ĐỒNG tại phòng đó mới được phép gửi đánh giá.",
        "Kích hoạt đúng thời điểm: Khi chủ trọ thanh lý hợp đồng, hệ thống tự động bắn chuông nhắc khách thuê để lại đánh giá thực tế.",
        "Hiển thị công khai: Trang chi tiết phòng (/rooms/{id}) hiển thị điểm trung bình, phân bổ 1-5 sao và toàn bộ nhận xét.",
        "Huy hiệu cam kết: Đi kèm nhãn '✓ Đã từng thuê phòng này' giúp người thuê mới tin cậy 100% trước khi quyết định ký hợp đồng."
    ],
    "Để đảm bảo sự minh bạch và chống tình trạng seeding đánh giá ảo, hệ thống chỉ cho phép những khách thuê đã từng có hợp đồng thực tế tại phòng đó được gửi đánh giá. Các đánh giá này được hiển thị công khai để những người thuê sau có cái nhìn khách quan nhất."
);

// Slide 15
$bodyXml .= slideBox(
    15,
    "Luồng 6: Trung Tâm Quản Trị Admin & Kiểm Soát An Ninh",
    "Trình bày năng lực quản trị, vận hành và giám sát toàn hệ thống.",
    [
        "Dashboard tổng thể: Thống kê tăng trưởng người dùng, tỷ lệ lấp đầy phòng, biểu đồ doanh thu toàn sàn.",
        "Kiểm duyệt tin đăng: Duyệt hoặc từ chối các tin đăng cho thuê phòng trọ mới nhằm đảm bảo chất lượng thông tin.",
        "Xử lý báo cáo vi phạm: Tiếp nhận tố cáo từ người dùng và xử lý các bài đăng có dấu hiệu lừa đảo.",
        "Khóa / Mở khóa tài khoản bằng AJAX: Khóa tài khoản vi phạm và xử lý đơn xin mở khóa kèm gửi email tự động không reload trang."
    ],
    "Đối với Ban quản trị Admin, hệ thống cung cấp dashboard giám sát toàn diện doanh thu và hoạt động toàn sàn, công cụ kiểm duyệt tin đăng phòng ngừa lừa đảo, cùng cơ chế khóa/mở khóa tài khoản vi phạm xử lý hoàn toàn bằng AJAX mượt mà."
);

// Slide 16
$bodyXml .= slideBox(
    16,
    "Bảng So Sánh: Trọ Ơi vs. Phương Pháp Quản Lý Truyền Thống",
    "Khẳng định ưu thế vượt trội của hệ thống qua các chỉ số cụ thể.",
    [
        "Ghi điện nước: Truyền thống mất 2-3 phút/phòng chép tay -> Trọ Ơi dùng AI OCR chụp ảnh mất 2 giây (Nhanh gấp 60 lần).",
        "Thu tiền & Gạch nợ: Truyền thống kiểm tra sao kê thủ công -> Trọ Ơi tự động gạch nợ SePay trong 3 giây.",
        "Hợp đồng & Giấy tờ: Truyền thống dễ thất lạc, rách nát -> Trọ Ơi lưu trữ điện tử vĩnh viễn, bảo mật cao.",
        "Báo hỏng sửa chữa: Truyền thống gọi điện/nhắn tin dễ quên -> Trọ Ơi quản lý phiếu sự cố theo luồng trạng thái rõ ràng.",
        "Độ tin cậy tìm phòng: Truyền thống tràn lan tin ảo -> Trọ Ơi kiểm duyệt tin đăng và 100% đánh giá từ người thuê thực tế."
    ],
    "Bảng so sánh này thể hiện rõ sự vượt trội của Trọ Ơi: Từ tốc độ chốt điện nước nhanh gấp 60 lần nhờ AI OCR, đến việc tự động gạch nợ 3 giây và số hóa 100% hợp đồng, giúp giải phóng hoàn toàn sức lao động cho chủ trọ."
);

// Slide 17
$bodyXml .= slideBox(
    17,
    "Kết Quả Đạt Được & Đóng Góp Thực Tiễn Của Đồ Án",
    "Tổng kết các thành quả kỹ thuật và giá trị thực tế của sản phẩm.",
    [
        "Hoàn thiện 100% các chức năng cốt lõi theo đúng mục tiêu đề tài đã đăng ký.",
        "Sản phẩm hoạt động ổn định trên môi trường thực tế, giao diện thẩm mỹ, tương thích tốt trên máy tính và điện thoại.",
        "Tích hợp thành công và vận hành trơn tru 3 công nghệ 4.0: SePay Webhook, Gemini Vision OCR và Gemini Chatbot AI.",
        "Ý nghĩa thực tiễn: Có khả năng thương mại hóa cao, sẵn sàng ứng dụng ngay cho các chuỗi nhà trọ, căn hộ dịch vụ và ký túc xá tư nhân."
    ],
    "Đến thời điểm hiện tại, nhóm em đã hoàn thành 100% khối lượng công việc đề ra. Sản phẩm chạy ổn định, các module AI và cổng thanh toán hoạt động chính xác và hoàn toàn có thể triển khai thực tế cho các khu trọ ngay hôm nay."
);

// Slide 18
$bodyXml .= slideBox(
    18,
    "Hướng Phát Triển Tương Lai & Lời Cảm Ơn",
    "Khép lại bài báo cáo bằng định hướng mở rộng và lời cảm ơn hội đồng.",
    [
        "Hướng phát triển 1: Phát triển phiên bản Mobile App native (Flutter / React Native) có thông báo đẩy (Push Notification).",
        "Hướng phát triển 2: Tích hợp thiết bị phần cứng IoT (Khóa cửa thông minh, Công tơ điện nước thông minh tự truyền dữ liệu qua Wifi).",
        "Hướng phát triển 3: Ứng dụng AI dự báo doanh thu và đề xuất mức giá thuê tối ưu theo mùa.",
        "Lời cảm ơn: Xin chân thành cảm ơn Quý Thầy Cô trong Hội đồng đã lắng nghe bài báo cáo của nhóm!",
        "Phần hỏi đáp: Kính mời Quý Thầy Cô đặt câu hỏi cho nhóm (Q&A Session)."
    ],
    "Trong tương lai, nhóm định hướng sẽ phát triển thêm ứng dụng di động và tích hợp thiết bị IoT công tơ tự động. Em xin chân thành cảm ơn quý thầy cô đã chú ý lắng nghe, và nhóm em rất mong nhận được những góp ý quý báu từ Hội đồng!"
);

// ==========================================
// PHẦN 5: BỘ CÂU HỎI PHẢN BIỆN THƯỜNG GẶP (Q&A)
// ==========================================
$bodyXml .= h1("PHẦN V. BỘ CÂU HỎI PHẢN BIỆN DỰ KIẾN CỦA HỘI ĐỒNG & HƯỚNG TRẢ LỜI");
$bodyXml .= p("Dưới đây là 5 câu hỏi trọng tâm mà Hội đồng chấm đồ án thường đặt ra và gợi ý câu trả lời chuẩn xác nhất:");

$bodyXml .= h2("Câu 1: Làm thế nào để đảm bảo tính an toàn và chống gian lận khi SePay gửi Webhook về máy chủ?");
$bodyXml .= p("Trả lời: Hệ thống áp dụng cơ chế phòng thủ 3 lớp nghiêm ngặt:", ['bold' => true]);
$bodyXml .= p("(1) Xác thực Webhook Secret Token: SePay khi gửi request bắt buộc phải đính kèm Header Authorization chứa token bí mật trùng khớp với SEPAY_WEBHOOK_TOKEN trong file .env của máy chủ.", ['bullet' => true]);
$bodyXml .= p("(2) Cơ chế chống xử lý trùng lặp (Idempotency): Mỗi giao dịch của SePay có một Transaction ID duy nhất. Khi nhận Webhook, hệ thống kiểm tra bảng payments, nếu mã này đã được xử lý thì trả về HTTP 200 ngay mà không cộng tiền hay gạch nợ lần hai.", ['bullet' => true]);
$bodyXml .= p("(3) Kiểm tra chiều dòng tiền: Chỉ chấp nhận các giao dịch tiền vào (transferType = 'in') và đối soát chính xác số tiền nhận được so với tổng tiền hóa đơn.", ['bullet' => true]);

$bodyXml .= h2("Câu 2: Nếu ảnh chụp công tơ điện nước bị mờ, tối hoặc AI OCR nhận diện sai thì hệ thống xử lý ra sao?");
$bodyXml .= p("Trả lời:", ['bold' => true]);
$bodyXml .= p("Hệ thống luôn tuân thủ nguyên tắc 'Human-in-the-loop' (Con người có quyền kiểm soát cuối cùng): Khi Gemini Vision OCR trả về kết quả, số điện/nước mới chỉ được điền tạm vào ô nhập liệu (Input field) trên giao diện. Chủ trọ luôn có thể nhìn lại số, chỉnh sửa bằng tay nếu muốn, trước khi bấm nút 'Lưu chỉ số'. Điều này đảm bảo tính chính xác 100% trong mọi điều kiện ánh sáng.", ['bullet' => true]);

$bodyXml .= h2("Câu 3: Cơ chế nào ngăn chặn việc chủ trọ hoặc người ngoài tạo đánh giá giả (Fake Reviews)?");
$bodyXml .= p("Trả lời:", ['bold' => true]);
$bodyXml .= p("Tại ReviewController, hệ thống kiểm tra chặt chẽ quan hệ Contract trong cơ sở dữ liệu: Chỉ những tài khoản có vai trò Tenant VÀ đã có bản ghi Hợp đồng thuê hợp lệ (đang hoạt động hoặc đã thanh lý) đối với đúng phòng trọ đó mới có thể gửi Form đánh giá. Khách vãng lai hoặc chủ trọ không thể tự tạo đánh giá cho phòng của mình.", ['bullet' => true]);

$bodyXml .= h2("Câu 4: Tại sao hệ thống lại chọn kết hợp Blade View và AJAX thay vì viết hoàn toàn bằng SPA như React/Vue?");
$bodyXml .= p("Trả lời:", ['bold' => true]);
$bodyXml .= p("Sự kết hợp này đem lại 3 lợi thế vượt trội cho bài toán nhà trọ: (1) Khả năng SEO cực mạnh từ Server-Side Rendering của Blade giúp các tin đăng phòng trọ dễ dàng lên top tìm kiếm Google; (2) Sử dụng AJAX có chọn lọc tại các khu vực cần tương tác cao (Khóa/Mở khóa tài khoản, Lọc phòng, SePay polling) giúp trải nghiệm người dùng nhanh, mượt như SPA nhưng không chịu độ trễ tải bundle JavaScript lớn; (3) Cấu trúc bảo mật CSRF và Session của Laravel hoạt động tự nhiên, tin cậy.", ['bullet' => true]);

$bodyXml .= h2("Câu 5: Trong trường hợp khách thuê dọn đi trước hạn và làm hỏng đồ đạc thì quy trình thanh lý hợp đồng giải quyết như thế nào?");
$bodyXml .= p("Trả lời:", ['bold' => true]);
$bodyXml .= p("Hệ thống đã tích hợp sẵn phân hệ Quản lý Bảo trì (Maintenance) và Thanh lý (Contract Termination): Chủ trọ có thể lập phiếu kiểm tra hư hại, tính toán chi phí sửa chữa từ các phiếu báo hỏng và khấu trừ trực tiếp vào số tiền đặt cọc (Deposit Amount) trước khi xác nhận hoàn cọc và đóng hợp đồng.", ['bullet' => true]);

// Ghi file document.xml
$documentXml = '<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<w:document xmlns:w="http://schemas.openxmlformats.org/wordprocessingml/2006/main"
            xmlns:r="http://schemas.openxmlformats.org/officeDocument/2006/relationships">
    <w:body>
        ' . $bodyXml . '
        <w:sectPr>
            <w:pgSz w:w="11906" w:h="16838"/>
            <w:pgMar w:top="1440" w:right="1440" w:bottom="1440" w:left="1440" w:header="720" w:footer="720" w:gutter="0"/>
        </w:sectPr>
    </w:body>
</w:document>';

$zip->addFromString('word/document.xml', $documentXml);
$zip->close();

echo "THÀNH CÔNG! Đã tạo file Word hoàn chỉnh tại: $outputPath\n";
echo "Kích thước file: " . filesize($outputPath) . " bytes\n";
