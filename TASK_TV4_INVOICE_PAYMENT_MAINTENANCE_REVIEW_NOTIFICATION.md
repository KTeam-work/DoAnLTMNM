# Nhiệm vụ TV4: Hóa đơn, thanh toán, sửa chữa, đánh giá và thông báo

## 1. Mục tiêu

Hoàn thiện toàn bộ chức năng tài chính và chăm sóc người thuê cho hai vai trò:

- **Tenant/người thuê:** xem hóa đơn, thanh toán, gửi yêu cầu sửa chữa, đánh giá phòng và đọc thông báo.
- **Owner/chủ nhà:** tạo hóa đơn tháng từ tiền phòng, điện nước và dịch vụ; theo dõi thanh toán; xử lý yêu cầu sửa chữa; xem đánh giá của người thuê.

Tất cả dữ liệu phải lấy và lưu trong database, không dùng dữ liệu hard-code hoặc thông báo giả lập.

## 2. Phạm vi hiện tại cần xử lý

### Controller tenant hiện có

- `app/Http/Controllers/InvoiceController.php`: hiện mới trả view, chưa truy vấn hóa đơn.
- Chưa có `Tenant\PaymentController`; cần tạo controller riêng hoặc đặt tên controller nhất quán với namespace hiện tại.
- `app/Http/Controllers/MaintenanceController.php`: đã có phần lớn luồng tạo yêu cầu và upload ảnh, cần đối chiếu migration và hoàn thiện.
- `app/Http/Controllers/ReviewController.php`: hiện chưa lưu database.
- `app/Http/Controllers/NotificationController.php`: hiện chưa lấy database và mới có đánh dấu tất cả, chưa có đánh dấu từng thông báo.

### Controller owner hiện có

- `app/Http/Controllers/Owner/InvoiceController.php`: hiện mới trả view.
- `app/Http/Controllers/Owner/PaymentController.php`: hiện mới trả view.
- `app/Http/Controllers/Owner/MaintenanceController.php`: hiện mới trả view.
- `app/Http/Controllers/Owner/ReviewController.php`: hiện mới trả view.

### Database cần sử dụng

- `invoices`: mã hóa đơn, hợp đồng, chỉ số điện nước, kỳ thanh toán, hạn thanh toán, tổng tiền và trạng thái.
- `invoice_items`: các khoản tiền chi tiết của hóa đơn.
- `payments`: mã thanh toán, hóa đơn, người trả, số tiền, phương thức, mã giao dịch và trạng thái.
- `maintenance_requests`: phòng, người thuê, nội dung, mức độ ưu tiên, trạng thái, hình ảnh và ghi chú chủ nhà.
- `reviews`: phòng, người thuê, số sao, nhận xét và trạng thái hiển thị.
- `notifications`: người nhận, tiêu đề, nội dung, loại, dữ liệu tham chiếu và `read_at`.

> Lưu ý: controller sửa chữa hiện đang dùng `contract_id`, `category` nhưng migration `maintenance_requests` hiện chưa có hai cột này. Cần bổ sung migration tương thích hoặc điều chỉnh controller theo schema thống nhất trước khi chạy chức năng.

## 3. Chức năng phía Tenant

### 3.1. Hóa đơn

#### Route yêu cầu

- `GET /tenant/invoices` - xem danh sách hóa đơn.
- `GET /tenant/invoices/{id}` - xem chi tiết hóa đơn.

Tên route đề xuất:

- `tenant.invoices.index`
- `tenant.invoices.show`

#### Danh sách hóa đơn

- Chỉ lấy hóa đơn thuộc các hợp đồng của tenant đang đăng nhập.
- Hiển thị:
    - Mã hóa đơn.
    - Kỳ thanh toán.
    - Phòng và hợp đồng liên quan.
    - Ngày phát hành, hạn thanh toán.
    - Tổng tiền.
    - Trạng thái: chưa thanh toán, đang xử lý, đã thanh toán, quá hạn, đã hủy.
- Có bộ lọc theo tháng và trạng thái.
- Có phân trang hoặc giới hạn dữ liệu hợp lý.
- Có nút xem chi tiết và nút thanh toán đối với hóa đơn chưa thanh toán.
- Có trạng thái rỗng khi tenant chưa có hóa đơn.

#### Chi tiết hóa đơn

- Kiểm tra hóa đơn thuộc tenant trước khi hiển thị.
- Hiển thị:
    - Thông tin người thuê, phòng, hợp đồng.
    - Các khoản tiền trong `invoice_items`.
    - Tiền phòng.
    - Tiền điện: chỉ số cũ, chỉ số mới, số tiêu thụ, đơn giá, thành tiền.
    - Tiền nước: chỉ số cũ, chỉ số mới, số tiêu thụ, đơn giá, thành tiền.
    - Phí dịch vụ.
    - Giảm giá, tạm tính, tổng tiền.
    - Ngày phát hành và hạn thanh toán.
    - Lịch sử thanh toán nếu đã phát sinh.
- Cho phép in hoặc tải hóa đơn nếu hệ thống có hỗ trợ.

### 3.2. Thanh toán hóa đơn

#### Route yêu cầu

- `POST /tenant/payment` - tạo yêu cầu thanh toán.
- Có thể bổ sung callback/return URL riêng cho VNPay/Momo nếu tích hợp online.

Tên route đề xuất:

- `tenant.payment.store`
- `tenant.payment.callback` nếu dùng cổng thanh toán.

#### Phương thức thanh toán

- Tiền mặt: tạo bản ghi thanh toán chờ chủ nhà xác nhận.
- Chuyển khoản hoặc QR: lưu mã giao dịch/nội dung chuyển khoản nếu có.
- Online VNPay/Momo: tạo giao dịch pending, chuyển sang cổng thanh toán, nhận callback và cập nhật kết quả.

#### Quy tắc xử lý

- Chỉ tenant sở hữu hóa đơn mới được thanh toán.
- Không cho thanh toán hóa đơn đã thanh toán, đã hủy hoặc không còn hợp lệ.
- Số tiền thanh toán phải lấy từ hóa đơn, không tin số tiền gửi từ form.
- Tạo `payments` với trạng thái `pending` trước khi thanh toán online.
- Khi thanh toán thành công:
    - Cập nhật payment thành `success`.
    - Ghi `paid_at` và mã giao dịch.
    - Cập nhật invoice thành `paid`.
    - Tạo thông báo cho owner.
- Khi thất bại/hủy:
    - Cập nhật payment thành `failed` hoặc `cancelled`.
    - Không đánh dấu hóa đơn đã thanh toán.
    - Hiển thị lý do và cho phép thử lại nếu phù hợp.
- Dùng transaction và cơ chế chống callback trùng để không ghi nhận thanh toán hai lần.

### 3.3. Yêu cầu sửa chữa

#### Route yêu cầu

- `GET /tenant/maintenance` - xem lịch sử yêu cầu.
- `GET /tenant/maintenance/create` - mở form tạo yêu cầu.
- `POST /tenant/maintenance` - gửi yêu cầu mới.
- `GET /tenant/maintenance/{id}` - xem chi tiết yêu cầu.

Tên route đề xuất:

- `tenant.maintenance.index`
- `tenant.maintenance.create`
- `tenant.maintenance.store`
- `tenant.maintenance.show`

#### Chức năng

- Chọn hợp đồng/phòng đang thuê của tenant.
- Nhập loại sự cố hoặc danh mục thiết bị.
- Nhập tiêu đề và mô tả chi tiết.
- Chọn mức độ: thấp, trung bình, cao, khẩn cấp.
- Đính kèm hình ảnh thiết bị hỏng.
- Validate loại file, kích thước file và số lượng file nếu cho phép nhiều ảnh.
- Lưu trạng thái mặc định `pending`.
- Hiển thị mã yêu cầu sau khi gửi.
- Xem lịch sử theo trạng thái và thời gian.
- Xem ghi chú, trạng thái xử lý và thời điểm hoàn tất.
- Không cho tenant xem hoặc sửa yêu cầu của tenant khác.

### 3.4. Đánh giá phòng

#### Route yêu cầu

- `POST /tenant/reviews` - gửi đánh giá.
- Có thể bổ sung `GET /tenant/reviews` để xem đánh giá của chính tenant.

Tên route đề xuất:

- `tenant.reviews.store`
- `tenant.reviews.index` nếu có trang danh sách.

#### Chức năng

- Chỉ cho phép đánh giá phòng mà tenant đã hoặc đang thuê.
- Chấm từ 1 đến 5 sao.
- Nhập nhận xét tùy chọn nhưng phải giới hạn độ dài.
- Gắn review với `room_id` và `tenant_id` lấy từ tài khoản/hợp đồng, không lấy tùy ý từ form.
- Xác định chính sách đánh giá một lần hay cho phép cập nhật đánh giá.
- Không cho gửi review trùng nếu nghiệp vụ chỉ cho một review/phòng/tenant.
- Review mới mặc định `visible` hoặc chờ owner/admin duyệt theo chính sách.
- Thông báo kết quả thành công/thất bại.

### 3.5. Thông báo

#### Route yêu cầu

- `GET /tenant/notifications` - xem danh sách thông báo của tenant.
- `PUT /tenant/notifications/{id}/read` - đánh dấu một thông báo đã đọc.
- Có thể bổ sung `PUT /tenant/notifications/read-all` - đánh dấu tất cả đã đọc.

Tên route đề xuất:

- `tenant.notifications.index`
- `tenant.notifications.read`
- `tenant.notifications.read_all`

#### Chức năng

- Chỉ hiển thị notification có `user_id` là tenant hiện tại.
- Hiển thị tiêu đề, nội dung, loại, thời gian và trạng thái đã/chưa đọc.
- Có bộ lọc tất cả/chưa đọc/đã đọc.
- Bấm thông báo có thể chuyển tới hóa đơn, yêu cầu sửa chữa hoặc hợp đồng qua `reference_type` và `reference_id`.
- Đánh dấu từng thông báo là đã đọc bằng `read_at`.
- Có số lượng thông báo chưa đọc trên menu tenant.
- Xử lý trường hợp thông báo tham chiếu đã bị xóa mà không gây lỗi.

## 4. Chức năng phía Owner

### 4.1. Tạo và quản lý hóa đơn tháng

#### Route yêu cầu

- `GET /owner/invoices` - xem danh sách hóa đơn.
- `POST /owner/invoices` - tạo hóa đơn hoặc chạy tạo hóa đơn theo tháng.
- Có thể bổ sung `GET /owner/invoices/{id}` để xem chi tiết.

Tên route đề xuất:

- `owner.invoices.index`
- `owner.invoices.store`
- `owner.invoices.show`

#### Chức năng tổng hợp

- Chọn kỳ thanh toán/tháng cần tạo.
- Tự động tìm các hợp đồng đang hiệu lực của owner.
- Tính tiền phòng theo hợp đồng.
- Lấy tiền điện/nước từ `utility_readings` đúng phòng và tháng.
- Tính tiền dịch vụ từ dịch vụ gắn với hợp đồng.
- Tạo các dòng chi tiết trong `invoice_items`.
- Tính `subtotal`, `discount`, `total` chính xác.
- Sinh `invoice_code` duy nhất.
- Thiết lập ngày phát hành, hạn thanh toán và trạng thái `unpaid`.
- Gắn hóa đơn với `contract_id` và `utility_reading_id` nếu có.
- Không tạo hóa đơn trùng một hợp đồng trong cùng kỳ.
- Cho phép tạo từng hóa đơn hoặc tạo hàng loạt cho toàn bộ phòng đủ điều kiện.
- Hiển thị danh sách hóa đơn theo tháng, phòng, tenant và trạng thái.
- Gửi notification cho tenant sau khi tạo hóa đơn.
- Có log lỗi đối với phòng thiếu chỉ số điện nước hoặc dữ liệu dịch vụ.

### 4.2. Theo dõi thanh toán

#### Route yêu cầu

- `GET /owner/payments` - theo dõi lịch sử dòng tiền và trạng thái thanh toán.
- Có thể bổ sung `PUT /owner/payments/{id}/confirm` để xác nhận tiền mặt/chuyển khoản.

Tên route đề xuất:

- `owner.payments.index`
- `owner.payments.confirm`

#### Chức năng

- Chỉ hiển thị payment thuộc hóa đơn của owner.
- Hiển thị mã thanh toán, mã hóa đơn, tenant, phòng, số tiền, phương thức, mã giao dịch, thời gian và trạng thái.
- Lọc theo tháng, trạng thái và phương thức thanh toán.
- Hiển thị tổng đã thu, tổng đang chờ, tổng chưa thu và tổng quá hạn.
- Xem chi tiết giao dịch.
- Owner xác nhận thanh toán tiền mặt/chuyển khoản đang pending.
- Owner từ chối hoặc đánh dấu failed khi giao dịch không hợp lệ, kèm ghi chú nếu cần.
- Khi xác nhận thành công, cập nhật invoice sang `paid` và gửi thông báo cho tenant.
- Chống xác nhận trùng hoặc xác nhận giao dịch đã hủy.

### 4.3. Xử lý yêu cầu sửa chữa

#### Route yêu cầu

- `GET /owner/maintenance` - xem danh sách yêu cầu.
- `PUT /owner/maintenance/{id}/status` - cập nhật trạng thái.
- Có thể bổ sung `GET /owner/maintenance/{id}` để xem chi tiết và `PUT /owner/maintenance/{id}/note` để lưu ghi chú.

Tên route đề xuất:

- `owner.maintenance.index`
- `owner.maintenance.status`
- `owner.maintenance.show`

#### Chức năng

- Chỉ xem yêu cầu thuộc các phòng/bất động sản do owner sở hữu.
- Hiển thị mã yêu cầu, tenant, phòng, tiêu đề, loại sự cố, mức độ, hình ảnh, thời gian tạo và trạng thái.
- Lọc theo trạng thái: chờ xử lý, đang sửa, đã hoàn thành, từ chối.
- Xem hình ảnh đính kèm an toàn.
- Cập nhật trạng thái theo luồng hợp lệ:
    - `pending` -> `processing`.
    - `processing` -> `completed`.
    - `pending` hoặc `processing` -> `rejected` khi có lý do.
- Lưu ghi chú của owner.
- Khi hoàn thành, ghi `completed_at`.
- Gửi notification cho tenant sau mỗi thay đổi trạng thái.
- Không cho owner cập nhật yêu cầu của phòng thuộc owner khác.

### 4.4. Xem đánh giá

#### Route yêu cầu

- `GET /owner/reviews` - xem đánh giá về phòng của owner.

Tên route đề xuất:

- `owner.reviews.index`

#### Chức năng

- Chỉ lấy review của các phòng thuộc owner hiện tại.
- Hiển thị tenant, phòng, số sao, nhận xét, ngày đánh giá và trạng thái.
- Lọc theo phòng, số sao, thời gian và trạng thái.
- Tính điểm trung bình theo phòng và toàn bộ bất động sản.
- Hiển thị phân bố 1-5 sao.
- Xem chi tiết review.
- Nếu có quyền quản lý nội dung, cho phép ẩn/hiện review theo chính sách; không tự ý xóa dữ liệu nếu chưa có yêu cầu.
- Không cho owner sửa nội dung đánh giá của tenant.

## 5. Model và quan hệ cần có

- `Invoice`
    - `belongsTo Contract`
    - `belongsTo UtilityReading`
    - `hasMany InvoiceItem`
    - `hasMany Payment`
- `InvoiceItem`
    - `belongsTo Invoice`
- `Payment`
    - `belongsTo Invoice`
    - `belongsTo User` với vai trò payer
- `MaintenanceRequest`
    - `belongsTo Room`
    - `belongsTo User` với vai trò tenant
    - `belongsTo Contract` nếu bổ sung `contract_id`
- `Review`
    - `belongsTo Room`
    - `belongsTo User` với vai trò tenant
- `Notification`
    - `belongsTo User`
    - quan hệ tham chiếu tùy loại dữ liệu qua `reference_type/reference_id`

Các model phải khai báo `$fillable` hoặc `$guarded` có kiểm soát và cast đúng tiền, ngày giờ, trạng thái.

## 6. Phân quyền và an toàn dữ liệu

- Mọi controller phải lấy owner/tenant từ `auth()->user()` hoặc `$request->user()`.
- Không tin `tenant_id`, `owner_id`, `payer_id`, `room_id` hoặc `contract_id` tùy ý từ form nếu có thể suy ra từ user hiện tại.
- Tenant chỉ xem hóa đơn, payment, yêu cầu, review và notification của mình.
- Owner chỉ xem dữ liệu thuộc phòng/bất động sản của mình.
- Các form web phải có CSRF.
- Upload ảnh phải kiểm tra MIME, dung lượng, tên file và dùng disk public đúng cấu hình.
- Không hiển thị lỗi SQL, đường dẫn file hoặc thông tin nhạy cảm cho người dùng.
- Trả HTTP status phù hợp:
    - `401`: chưa đăng nhập.
    - `403`: sai vai trò.
    - `404`: không tồn tại hoặc không thuộc quyền xem.
    - `422`: dữ liệu đầu vào không hợp lệ.
- Các thao tác tạo invoice, payment callback và cập nhật trạng thái phải dùng database transaction khi thay đổi nhiều bảng.

## 7. Giao diện cần hoàn thiện

### Tenant

- Menu có các mục: Hóa đơn, Thanh toán, Sửa chữa, Đánh giá, Thông báo.
- Badge số thông báo chưa đọc và trạng thái hóa đơn rõ ràng.
- Danh sách hóa đơn dạng bảng trên desktop, card hoặc cuộn ngang trên mobile.
- Chi tiết hóa đơn có bảng khoản phí, tổng tiền nổi bật và nút thanh toán.
- Form sửa chữa có preview ảnh, thông báo lỗi và trạng thái theo dõi.
- Form đánh giá có chọn sao trực quan và giới hạn nội dung.
- Thông báo phân biệt đã đọc/chưa đọc và có liên kết điều hướng.

### Owner

- Dashboard hoặc trang tổng quan có tổng doanh thu, chưa thu, yêu cầu sửa chữa chờ xử lý và điểm đánh giá trung bình.
- Hóa đơn có bộ lọc tháng/trạng thái và nút tạo hàng loạt.
- Thanh toán có bảng dòng tiền, bộ lọc và thao tác xác nhận.
- Sửa chữa có badge mức độ ưu tiên, trạng thái và ảnh đính kèm.
- Đánh giá có điểm trung bình, phân bố sao và danh sách phản hồi.
- Đồng bộ layout, màu sắc, typography, khoảng cách, icon và responsive với các trang hiện có.
- Phải có trạng thái loading, rỗng, lỗi và thành công; không để dữ liệu chồng lấn trên mobile.

## 8. Test và tiêu chí nghiệm thu

### Test tenant

- Tenant xem đúng danh sách hóa đơn của mình.
- Tenant không xem được hóa đơn của tenant khác.
- Tenant xem được chi tiết và các dòng phí chính xác.
- Tenant tạo được payment tiền mặt/chuyển khoản hoặc khởi tạo giao dịch online.
- Callback thành công cập nhật payment và invoice đúng một lần.
- Callback thất bại không đánh dấu invoice đã thanh toán.
- Tenant gửi được yêu cầu sửa chữa kèm ảnh hợp lệ.
- File ảnh sai định dạng hoặc quá dung lượng bị từ chối.
- Tenant xem được lịch sử và trạng thái yêu cầu.
- Tenant gửi được đánh giá hợp lệ; không đánh giá sai phòng hoặc vượt quyền.
- Tenant xem và đánh dấu từng notification đã đọc.

### Test owner

- Owner tạo được hóa đơn cho một hợp đồng.
- Tạo hàng loạt không tạo hóa đơn trùng kỳ/hợp đồng.
- Hóa đơn tổng hợp đúng tiền phòng, điện, nước, dịch vụ và giảm giá.
- Owner xem đúng payment thuộc mình và xác nhận được payment pending.
- Owner không xem hoặc cập nhật dữ liệu của owner khác.
- Owner xem được yêu cầu sửa chữa thuộc phòng của mình.
- Owner cập nhật đúng luồng trạng thái và gửi notification cho tenant.
- Owner xem được review và thống kê điểm theo phòng.

### Test route và giao diện

- Route thực tế khớp tài liệu, không dùng đồng thời các biến thể `/index`, `/store` gây nhầm lẫn.
- Các form web có CSRF và redirect/thông báo đúng.
- API trả JSON đúng cấu trúc nếu request `Accept: application/json`.
- Kiểm tra desktop/mobile, bảng không tràn và các trạng thái rỗng/lỗi/thành công đều hiển thị.
- Chạy toàn bộ test hiện có và bổ sung feature test cho từng nhóm chức năng.

## 9. Hoàn tất khi

- Không còn controller TV4 nào chỉ trả view hoặc giả lập lưu dữ liệu.
- Tenant có thể xem và thanh toán hóa đơn, gửi sửa chữa, đánh giá và xử lý thông báo.
- Owner có thể tạo hóa đơn, theo dõi/xác nhận thanh toán, xử lý sửa chữa và xem đánh giá.
- Dữ liệu giữa invoice, invoice item, utility reading, payment, maintenance, review và notification liên kết đúng.
- Phân quyền không cho xem hoặc thay đổi dữ liệu của tài khoản khác.
- Test backend và kiểm tra giao diện đều đạt.
