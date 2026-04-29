## Hướng dẫn sử dụng bảng quản trị website

Tài liệu này viết cho **nhân viên vận hành nội dung, lễ tân bán tour, hoặc quản trị** — **không cần biết lập trình**. Bạn chỉ cần trình duyệt (Chrome, Safari, Edge…) và tài khoản được cấp.

Nếu có chỗ nào không khớp với màn hình thực tế (nhãn nút, thứ tự menu), hãy tin vào **giao diện đang dùng**; phần dưới mô tả **đúng ý nghĩa chức năng** trong hệ thống hiện tại.

---

## 1. Bảng điều khiển là gì?

**Bảng điều khiển** (admin panel) là **phòng làm việc riêng** của công ty trên website: không phải khách du lịch xem chung ở trang chủ hay trang tour. Tại đây bạn:

- Thêm / sửa **tour**, **bài viết blog**, **điểm đến**
- Xem và xử lý **yêu cầu đặt chỗ** khách gửi từ trang tour
- Upload và chọn **ảnh**
- (Tùy quyền) Sửa **thông tin liên hệ, banner trang chủ, mạng xã hội**, và **tài khoản nhân viên**

Trên máy tính, bạn thường thấy **thanh menu dọc bên trái**; điện thoại có thể **mở menu bằng nút gạch ngang** — nội dung giống nhau, chỉ khác cách bố trí màn hình.

---

## 2. Đăng nhập và thoát

### Địa chỉ đăng nhập

Thông thường admin vào định dạng:

`https://vietnamcosmotravel.com/admin/login`

(Một số cấu hình cũng cho phép vào `/login` rồi **tự chuyển** sang trang đăng nhập admin — nếu không được, hãy dùng đường dẫn có `admin/login` như trên.)

### Sau khi đăng nhập thành công

Hệ thống đưa bạn tới **Dashboard** — trang tổng quan (số liệu nhanh, bài blog gần đây…).

### Đăng xuất

Nên **đăng xuất** khi dùng máy chung hoặc xong ca làm việc. Nút đăng xuất thường nằm **góc trên** (khu vực tài khoản của bạn).

### Khi nào **không** đăng nhập được dù nhập đúng mật khẩu?

Có vài trường hợp phổ biến:

| Tình huống | Ý nghĩa đơn giản |
|------------|------------------|
| Báo sai email hoặc mật khẩu | Kiểm tra lại chữ hoa/thường, dấu cách; thử gõ lại. |
| Báo kiểu “tài khoản không hoạt động” / tương tự | Tài khoản bị **tạm khóa** — chỉ **quản trị viên có quyền quản lý người dùng** mới bật lại được (mục [Users](#9-cài-đặt-website-và-quản-lý-tài-khoản-nhân-sự-chỉ-quản-trị-viên-đầy-đủ)). |
| Đăng nhập xong lại bị đẩy ra | Tài khoản **chưa được phép vào bảng điều khiển** — do cấu hình nội bộ; cần **quản trị viên** kiểm tra lại quyền truy cập (xem [mục 3](#3-quyền-truy-cập--hiểu-một-lần-làm-đúng-lâu-dài)). |

---

## 3. Quyền truy cập — hiểu một lần, làm đúng lâu dài

Hình dung có **ba “lớp cửa”** trước khi bạn thấy được đủ chức năng:

### Lớp 1 — “Được mời vào phòng làm việc chưa?”

Kể cả có đúng email và mật khẩu, nếu công ty **chưa bật quyền “được vào bảng điều khiển”** cho tài khoản của bạn, hệ thống **không cho vào**. Đây là cách để tránh nhân viên nghỉ việc nhưng vẫn còn mật khẩu trên giấy — chỉ người được giao việc quản trị mới vào được.

**Ai chỉnh lớp này?** Thường là **quản trị viên đầy đủ** khi **tạo hoặc sửa tài khoản** nhân viên.

### Lớp 2 — “Tài khoản này còn được phép làm việc không?”

Mỗi tài khoản có **trạng thái**:

- **Đang hoạt động** — làm việc bình thường.
- **Đã tắt** — **không đăng nhập được**, giống như khóa thẻ ra vào tạm thời (nhân viên nghỉ, thử việc kết thúc, v.v.).

**Ai chỉnh?** **Quản trị viên đầy đủ** trong mục quản lý người dùng.

### Lớp 3 — “Được vào hết phòng hay chỉ một phần?”

Ở đây người ta hay nói tới hai kiểu:

#### **Quản trị viên đầy đủ** (Administrator)

- Vào được **toàn bộ** chức năng trong bảng điều khiển theo thiết kế website: **cài đặt website**, **quản lý user**, cộng thêm **tour, blog, điểm đến, đặt chỗ, media**, v.v.

#### **Biên tập / nhân viên nội dung** (không bật Administrator)

- Thường chỉ làm việc với **nội dung và đặt chỗ**: Dashboard, hồ sơ cá nhân, tour, blog, điểm đến, media, danh sách đặt chỗ.
- **Không** thấy (hoặc không vào được) phần **Cài đặt chung website** và **quản lý tài khoản nhân viên** — những việc đó dành cho **quản trị viên đầy đủ**.

Bảng so sánh **thực hành**:

| Việc bạn muốn làm | Quản trị viên đầy đủ | Biên tập (không Administrator) |
|-------------------|----------------------|--------------------------------|
| Xem Dashboard, sửa **hồ sơ của chính mình** | Có | Có |
| Thêm / sửa **tour**, **blog**, **điểm đến** | Có | Có |
| **Đặt chỗ** — xem, đổi trạng thái | Có | Có |
| Quản lý **ảnh** trong thư viện | Có | Có |
| Sửa **thông tin website**, **liên hệ**, **mạng xã hội**, **banner**, **khối “vì sao chọn chúng tôi”** | Có | Không (menu không hiện hoặc không được phép) |
| **Tạo / sửa / xóa** tài khoản nhân viên | Có | Không |

*(Trên một số màn hình danh sách, **quản trị viên đầy đủ** có thêm cột “ai tạo / ai sửa” — chỉ để đối chiếu nội bộ, không ảnh hưởng khách.)*

---

## 4. Menu bên trái và từng mục làm việc gì

### Chung cho hầu hết mọi người được vào admin

- **Dashboard** — “Tổng quan”: nhìn nhanh con số (ví dụ số tour, số bài, số đặt chỗ) và vài bài blog mới để nắm tình hình.
- **Profile (Hồ sơ)** — Chỉnh **tên hiển thị, email đăng nhập, mật khẩu** của **chính bạn**. Đây **không phải** chỗ sửa thông tin nhân viên khác.

### Khách hàng / vận hành

- **Bookings (Đặt chỗ)** — Nơi khách gửi **form đặt tour** từ trang công khai sẽ hiện ra đây. Bạn **đọc thông tin khách**, **liên hệ** qua điện thoại hoặc email ngoài hệ thống, rồi **cập nhật trạng thái** trong danh sách (chi tiết ở [mục 7](#7-đặt-chỗ-từ-khách-luồng-xử-lý-thực-tế)).

### Nội dung website

- **Tours** — Mỗi dòng là một **gói tour**: giá, số ngày, mô tả, ảnh, lịch trình… Hiển thị ra **trang tour** cho khách xem (nếu tour đang **bật hiển thị** — xem [mục 6](#6-tour-và-bài-blog-đăng-và-ẩn-trên-website)).
- **Destinations (Điểm đến)** — Danh mục **khu vực / thành phố** để gắn với tour và trang điểm đến phía khách.
- **Blog** — Bài viết tin tức / cẩm nang, hiển thị ở khu **Blog** trên website.
- **Media** — **Kho ảnh**: upload ảnh mới, sau đó khi soạn tour hoặc blog bạn **chọn ảnh** từ kho thay vì dán link lung tung.

### Chỉ **quản trị viên đầy đủ**

- **Settings (Cài đặt)** — Gồm các trang kiểu **Website**, **Contact**, **Social**, **Home why section**, **Hero Banners**… dùng để chỉnh **text, logo, banner đầu trang**, **số điện thoại hiển thị**, v.v. Tuỳ team marketing đặt tên; ý nghĩa là **chỗ khách nhìn thấy ngoài trang chủ / trang liên hệ**.
- **Users (Người dùng)** — Tạo tài khoản cho đồng nghiệp, đặt **Administrator hay không**, **bật / tắt** tài khoản.

---

## 5. Cách dùng cụ thể theo công việc

### Bạn được giao: “Đăng tour mới”

1. Vào **Tours** → **Thêm tour** (hoặc nút tương đương).
2. Điền các ô bắt buộc (thường có **điểm đến**, **tiêu đề**, **giá**, **mô tả**, **ảnh**…). Menu chọn **dịch vụ / tiện ích** nếu có — giúp tour nhất quán khi hiển thị.
3. Chọn **Trạng thái**: **Đang hoạt động** nếu muốn khách thấy ngay trên web; **Đã tắt** nếu chỉ lưu nháp hoặc chờ duyệt nội bộ.
4. **Lưu**. Nếu có lỗi (thiếu ô, ảnh sai định dạng…), màn hình sẽ báo đỏ ngay dưới ô đó — đọc và sửa từng dòng.

### Bạn được giao: “Sửa giá tour đang chạy”

1. **Tours** → bấm vào tour cần sửa (thường là **chỉnh sửa** / icon bút).
2. Đổi **giá** hoặc nội dung khác → **Lưu**.
3. Kiểm tra nhanh trên **website khách** (mở tab ẩn danh hoặc điện thoại) xem đã đúng chưa — đôi khi trình duyệt **cache** (lưu bản cũ); thử **tải lại trang** hoặc đợi vài phút.

### Bạn được giao: “Viết bài blog”

1. **Blog** → **Thêm bài**.
2. Chọn **chuyên mục** (nếu có), **tiêu đề**, **thumbnail** từ Media.
3. Phần **nội dung** có thể là HTML — team thường dùng công cụ ngoài (ví dụ chuyển Word sang HTML) rồi dán vào; làm theo **lưu ý trên form** (ví dụ font có thể được chuẩn hoá khi lưu).
4. Chọn **Trạng thái** giống tour: chỉ **Đang hoạt động** mới ra trang blog công khai.

### Bạn được giao: “Ẩn tour hết chỗ / ngừng bán”

**Không nhất thiết phải xóa.** Chỉ cần **sửa tour** → **Trạng thái** = **Đã tắt** → Lưu. Tour biến khỏi các trang khách; dữ liệu vẫn giữ để sau này bật lại.

---

## 6. Tour và bài Blog: “đăng” và “ẩn” trên website

| Trạng thái trong admin | Khách trên website thấy gì? |
|------------------------|----------------------------|
| **Đang hoạt động** | Tour/bài xuất hiện bình thường ở các trang công khai (danh sách, chi tiết, đặt chỗ nếu có tour đó…). |
| **Đã tắt** | **Không** thấy trong danh sách công khai; link trực tiếp cũng **không** mở được như bài bình thường (giống như “gỡ tạm khỏi kệ hàng”). |

**Gợi ý:** Dùng **Đã tắt** cho mùa ngừng bán, đợi duyệt nội dung, hoặc lỗi giá cần sửa gấp — tránh xóa nhầm rồi mất dữ liệu.

---

## 7. Đặt chỗ từ khách: luồng xử lý thực tế

### Khách làm gì trên website?

Khách xem **trang chi tiết một tour**, điền form (tên, email, điện thoại, ngày đi, số người, ghi chú…) và gửi. Yêu cầu đó **chảy vào mục Đặt chỗ** trong admin.

### Trạng thái trong danh sách (và ý nghĩa đề xuất)

| Trạng thái | Khi nào dùng (gợi ý vận hành) |
|------------|-------------------------------|
| **Chờ xử lý** | Vừa mới nhận — chưa gọi khách hoặc chưa chốt được chỗ. |
| **Đã xác nhận** | Đã liên hệ khách, tour **chốt** (hoặc đã chốt nguyên tắc — tuỳ quy trình công ty). |
| **Đã huỷ** | Khách huỷ, hết chỗ, không đạt điều kiện… — để lịch sử rõ ràng, không nhầm với tour đang chờ. |

**Lưu ý:** Admin chỉ **ghi nhận trạng thái và thông tin**. Việc **thanh toán, ký hợp đồng, email marketing** thường làm **ngoài** hệ thống (điện thoại, banking, CRM…) — hãy **đồng bộ** quy trình trong team.

### Cách xử lý một đặt chỗ (thao tác)

1. Vào **Đặt chỗ**.
2. Dùng **ô tìm kiếm** (tên, email, SĐT, ghi chú…) hoặc **lọc** theo **trạng thái** / **tên tour** để tìm nhanh.
3. Mở chi tiết (tuỳ giao diện — có thể là **xem trong popup** hoặc **một trang**): đọc **ngày đi**, **số người**, **ghi chú**.
4. **Liên hệ khách** theo quy trình công ty.
5. Trong admin, **đổi trạng thái** sang **Đã xác nhận** hoặc **Đã huỷ** rồi **lưu**.

Sau khi lưu, danh sách thường **giữ nguyên bộ lọc** bạn đang xem — tiện xử lý hàng loạt trong cùng một ngày.

---

## 8. Thư viện ảnh (Media)

### Vì sao cần Media?

Ảnh upload một lần, **dùng lại** nhiều lần cho tour, blog, thumbnail — đồng bộ chất lượng và tránh link ảnh hỏng.

### Quy trình thông thường

1. **Media** → **tải ảnh lên** (chọn file trên máy; tuỳ cấu hình có giới hạn dung lượng và định dạng — làm theo dòng chữ gợi ý trên màn hình).
2. Khi soạn **Tour** hoặc **Blog**, chỗ **thumbnail / ảnh đại diện**, bạn **chọn từ thư viện** (nút kiểu “chọn ảnh”, “thư viện”) thay vì tự gõ đường link dài.

### Xóa ảnh

Nếu ảnh **đang được một tour hay bài viết dùng**, hệ thống có thể **không cho xóa** hoặc báo cảnh báo — đúng như không thể bỏ khung ảnh đang treo trên tường mà không tháo trước.

---

## 9. Cài đặt website và quản lý tài khoản nhân sự (chỉ quản trị viên đầy đủ)

### Cài đặt (Settings)

Mỗi mục con **Website / Contact / Social / Home why / Hero Banners** nhằm chỉnh **phần khách nhìn thấy** (logo, dòng giới thiệu, banner đầu trang, số hotline, Facebook…). Khi sửa:

- Đọc kỹ **nhãn** và **ví dụ** trên form (nếu có).
- Sau khi lưu, mở **trang chủ** hoặc **liên hệ** trên điện thoại để **kiểm tra hiển thị**.

### Người dùng (Users)

**Tạo nhân viên mới:**

1. **Users** → **Tạo** / **Thêm**.
2. Điền **họ tên**, **email**, **mật khẩu** (và xác nhận lại).
3. Tick **Quản trị viên** chỉ khi người đó **được tin cậy** và cần **sửa cài đặt website + quản lý user**.
4. **Trạng thái**: **Đang hoạt động** để họ đăng nhập ngay; **Đã tắt** nếu chỉ lưu hồ sơ hoặc khóa sau này.

**Quy tắc an toàn của hệ thống:**

- **Không** được **tắt** hoặc **xóa** **quản trị viên đang hoạt động cuối cùng** — để luôn còn ít nhất một người “mở được cửa” quản trị. Nếu cần đổi người phụ trách, hãy **tạo admin mới** hoặc **bật quản trị viên** cho tài khoản khác **trước**, rồi mới tắt tài khoản cũ.

---

## 10. Thói quen tốt, an toàn, và khi nào nhờ người kỹ thuật

### Thói quen nên có

- **Một người một tài khoản** — không share chung để biết ai đã sửa tour hay xác nhận đặt chỗ.
- **Đổi mật khẩu định kỳ** hoặc khi nghi ngờ lộ; dùng **Hồ sơ** để tự đổi.
- Trước khi **xóa** tour hoặc bài, hãy cân nhắc **Đã tắt** trước khi xóa hẳn.
- Giữ **tone và thông tin** đồng bộ với team sales (giá, điều kiện huỷ…) để khách không nhận hai lời khác nhau.

### Khi nên nhờ bạn **IT / người phụ trách kỹ thuật**

- Website **không vào được**, **chậm bất thường**, **lỗi trắng trang** — không tự sửa code hay database.
- Cần **đổi tên miền**, **email gửi tự động**, **backup**, **nâng cấp máy chủ** — nằm ngoài nội dung admin.
- Menu hoặc nút **khác hẳn** tài liệu này sau một đợt **nâng cấp lớn** — nhờ họ **cập nhật lại hướng dẫn** hoặc họp nội bộ 15 phút.

---

## Câu hỏi thường gặp

**Hỏi: Tôi sửa tour rồi mà khách vẫn thấy giá cũ?**  
**Đáp:** Thử **tải lại trang** hoặc mở **cửa sổ ẩn danh**. Đôi khi điện thoại hoặc máy khách **nhớ bản cũ**. Nếu vẫn sai sau vài giờ, báo kỹ thuật kiểm tra **cache máy chủ**.

**Hỏi: Trạng thái tour “Đã tắt” có ảnh hưởng đặt chỗ cũ không?**  
**Đáp:** Đặt chỗ đã lưu trong admin **vẫn nằm trong danh sách** để bạn tra cứu; khách **không đặt tour đó từ web** được như khi tour đang bật (tuỳ luồng form — nếu có link cũ thường không còn hợp lệ).

**Hỏi: Tôi là biên tập, có thấy cột “ai tạo bài” không?**  
**Đáp:** Thường **chỉ quản trị viên đầy đủ** thấy các cột kiểu đó — để đối chiếu nội bộ.

---

*Tài liệu này mô tả chức năng và ý nghĩa vận hành; chi tiết nút bấm có thể chỉnh theo từng đợt cập nhật giao diện.*
