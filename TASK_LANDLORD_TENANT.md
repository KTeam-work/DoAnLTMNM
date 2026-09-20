# Nhiệm vụ: Điện nước chủ trọ và hợp đồng người thuê

## Mục tiêu

Hoàn thiện hai luồng chính trong hệ thống:

1. Quyền **chủ trọ (landlord/owner)** có thể xem, tạo mới và cập nhật chỉ số điện nước. Sửa lỗi dữ liệu điện nước hiện tại không lưu được.
2. Quyền **người thuê (tenant)** có trang hợp đồng lấy dữ liệu thật từ database, cho phép xem danh sách, xem chi tiết hợp đồng và tạo yêu cầu mới liên quan đến hợp đồng.
3. Đồng bộ lại giao diện hai khu vực để thống nhất, dễ dùng và đẹp hơn trên desktop/mobile.

## Phạm vi code hiện tại

- Chủ trọ:
    - Controller: `app/Http/Controllers/Owner/OwnerUtilitiesController.php`
    - View danh sách: `resources/views/owner/utilities/index.blade.php`
    - View tạo mới: `resources/views/owner/utilities/create.blade.php`
    - Route: `owner.utilities.*` trong `routes/web.php`
    - Model: `app/Models/UtilityReading.php`
- Người thuê:
    - Controller: `app/Http/Controllers/ContractController.php`
    - View danh sách: `resources/views/tenant/contracts/index.blade.php`
    - View chi tiết: `resources/views/tenant/contracts/show.blade.php`
    - Route: `tenant.contracts.*` trong `routes/web.php`
    - Model: `app/Models/Contract.php`
- Migration cần đối chiếu:
    - `database/migrations/2026_08_24_131449_create_utility_readings_table.php`
    - `database/migrations/2026_08_24_131447_create_contracts_table.php`
    - `database/migrations/2026_08_24_131452_create_maintenance_requests_table.php`

## 1. Chủ trọ: sửa và hoàn thiện điện nước

### Yêu cầu chức năng

- Hiển thị danh sách chỉ số điện nước theo phòng và tháng.
- Cho phép chọn phòng, tháng, chỉ số cũ, chỉ số mới và đơn giá điện/nước.
- Có nút **Tạo chỉ số mới** từ trang danh sách.
- Khi tạo bản ghi mới:
    - Tự động lấy chỉ số cũ từ bản ghi tháng trước; nếu chưa có thì lấy chỉ số ban đầu trong hợp đồng.
    - Chuẩn hóa tháng về ngày đầu tháng.
    - Không cho phép tạo trùng `room_id` và tháng.
    - Không cho phép chỉ số mới nhỏ hơn chỉ số cũ.
    - Tính và hiển thị rõ số tiêu thụ điện/nước và tổng tiền nếu UI hiện có hỗ trợ.
- Cho phép mở bản ghi để chỉnh sửa và lưu thành công.
- Sau khi lưu thành công phải:
    - Ghi dữ liệu vào bảng `utility_readings`.
    - Hiển thị thông báo thành công.
    - Quay về danh sách hoặc cập nhật lại danh sách mà không mất dữ liệu.
- Hiển thị lỗi validation ngay tại form, đặc biệt với lỗi trùng tháng/phòng và chỉ số giảm.
- Chỉ chủ trọ mới được xem hoặc thay đổi dữ liệu thuộc các phòng trong bất động sản của mình.

### Kiểm tra lỗi lưu hiện tại

- Kiểm tra form có dùng đúng method/action/CSRF và tên field với `OwnerUtilitiesController` hay không.
- Kiểm tra route POST/PUT có nhận đúng request web và không chỉ xử lý JSON.
- Kiểm tra dữ liệu ngày tháng, tên input, binding bản ghi sửa và redirect sau khi lưu.
- Kiểm tra không dùng dữ liệu demo thay cho dữ liệu database khi người dùng đã đăng nhập.
- Nếu vẫn cần chế độ demo chưa đăng nhập, phải tách rõ với luồng production và không làm suy yếu phân quyền.

### Thiết kế giao diện chủ trọ

- Đồng bộ với layout chủ trọ hiện tại trong `resources/views/Layout/landlord.blade.php`.
- Bố cục đề xuất:
    - Tiêu đề trang và nút tạo mới ở cùng một hàng.
    - Các bộ lọc phòng/tháng đặt trong một khu vực dễ quét.
    - Bảng danh sách có cột phòng, tháng, điện cũ/mới, nước cũ/mới, thành tiền, trạng thái và thao tác.
    - Form tạo/sửa chia thành hai nhóm rõ ràng: **Điện** và **Nước**.
    - Hiển thị trạng thái rỗng, loading nếu dùng request bất đồng bộ, lỗi và thông báo thành công.
- Giao diện phải responsive, không làm tràn bảng trên màn hình nhỏ; dùng cuộn ngang hoặc card phù hợp.
- Dùng cùng màu sắc, khoảng cách, typography, nút và icon với các trang owner hiện có.

## 2. Người thuê: trang hợp đồng từ database

### Yêu cầu chức năng

- Trang danh sách hợp đồng tại `tenant.contracts.index` phải lấy đúng hợp đồng của tenant đang đăng nhập từ database.
- Hiển thị tối thiểu:
    - Mã hợp đồng.
    - Tên phòng và bất động sản.
    - Chủ trọ.
    - Ngày bắt đầu/kết thúc.
    - Tiền thuê, tiền cọc.
    - Trạng thái: nháp, đang hiệu lực, hết hạn hoặc đã thanh lý.
- Có thể bấm vào một hợp đồng để xem chi tiết tại `tenant.contracts.show`.
- Trang chi tiết hiển thị:
    - Thông tin hợp đồng và phòng.
    - Thông tin chủ trọ.
    - Thông tin các thành viên cùng hợp đồng.
    - Chỉ số điện nước ban đầu và các thông tin dịch vụ liên quan nếu có.
    - Thông tin thanh lý nếu hợp đồng đã kết thúc.
- Không cho tenant xem hợp đồng của tenant khác. API và giao diện phải cùng kiểm tra quyền sở hữu dữ liệu.
- Xử lý rõ trường hợp chưa có hợp đồng, hợp đồng không tồn tại hoặc truy cập sai quyền.

### Tạo yêu cầu mới

- Thêm nút **Tạo yêu cầu mới** từ trang danh sách hoặc chi tiết hợp đồng.
- Yêu cầu phải gắn với:
    - `contract_id`.
    - `tenant_id` của người đang đăng nhập.
    - Loại yêu cầu.
    - Tiêu đề/nội dung.
    - Trạng thái mặc định là chờ xử lý.
- Có thể dùng bảng `maintenance_requests` nếu yêu cầu mới là yêu cầu sửa chữa; nếu nghiệp vụ cần nhiều loại yêu cầu khác nhau thì bổ sung loại yêu cầu theo thiết kế hiện tại.
- Validate dữ liệu bắt buộc, chống gửi yêu cầu cho hợp đồng không thuộc tenant.
- Sau khi tạo thành công, hiển thị mã/trạng thái yêu cầu và liên kết quay lại chi tiết hợp đồng.

### Thiết kế giao diện tenant

- Đồng bộ với `resources/views/Layout/tenant.blade.php` và các trang tenant hiện có.
- Trang danh sách hợp đồng ưu tiên khả năng quét nhanh: trạng thái nổi bật, thông tin phòng, thời hạn và nút xem chi tiết.
- Trang chi tiết có phần thông tin được nhóm theo khối, thứ tự rõ ràng và nút tạo yêu cầu dễ tìm.
- Form tạo yêu cầu có trường loại yêu cầu, tiêu đề, nội dung và file đính kèm nếu hệ thống đã hỗ trợ upload.
- Có trạng thái rỗng, lỗi validation, thành công và responsive cho mobile.

## 3. Phân quyền và an toàn dữ liệu

- Owner chỉ thao tác được trên phòng/bất động sản do mình sở hữu.
- Tenant chỉ xem hợp đồng mà `contracts.tenant_id` là tài khoản hiện tại.
- Không tin tưởng `owner_id` hoặc `tenant_id` gửi từ form nếu có thể suy ra từ user đăng nhập.
- Các request thay đổi dữ liệu phải có CSRF khi dùng web form.
- Các endpoint JSON phải trả HTTP status phù hợp: `401` chưa đăng nhập, `403` sai vai trò, `404` không thuộc quyền truy cập, `422` dữ liệu không hợp lệ.

## 4. Test và tiêu chí nghiệm thu

### Test backend

- Owner tạo được chỉ số điện nước mới và bản ghi xuất hiện trong database.
- Owner cập nhật được chỉ số đã có.
- Không tạo được bản ghi trùng phòng/tháng.
- Không lưu được chỉ số mới nhỏ hơn chỉ số cũ.
- Owner không xem hoặc sửa được phòng của owner khác.
- Tenant xem được danh sách và chi tiết đúng hợp đồng của mình.
- Tenant không xem được hợp đồng của tenant khác.
- Tenant tạo được yêu cầu gắn đúng hợp đồng và tài khoản.
- Tenant không tạo được yêu cầu cho hợp đồng không thuộc mình.

### Test giao diện

- Các route owner utilities và tenant contracts trả về HTTP thành công trong luồng web.
- Form tạo/sửa điện nước hiển thị lại lỗi validation và thông báo thành công.
- Danh sách hợp đồng có dữ liệu từ database, không phụ thuộc dữ liệu hard-code.
- Kiểm tra giao diện ở desktop và mobile, không có nội dung chồng lấn hoặc tràn màn hình.

### Hoàn tất khi

- Dữ liệu điện nước được lưu và đọc lại chính xác sau khi refresh trang.
- Tenant xem được hợp đồng thật, xem được chi tiết và tạo được yêu cầu mới.
- Hai khu vực owner/tenant dùng cùng hệ thống layout, màu sắc, spacing và trạng thái giao diện.
- Các test hiện có và test mới liên quan đều chạy pass.
