# Clinic Management REST API (Hệ thống Quản lý Phòng khám)

Hệ thống REST API quản lý phòng khám đơn (Single-clinic) được phát triển bằng **Laravel**, **PostgreSQL 16** và **Docker Compose**, tuân thủ mô hình **RBAC Global theo Controller@action** và kiến trúc phân tầng chuyên nghiệp.

---

## 1. Môi trường & Hướng dẫn cài đặt (Setup Guide)

### Yêu cầu hệ thống (Requirements)

* OS: Ubuntu 24
* Docker Engine (tested: `v29.7.1`)
* Docker Compose Plugin (tested: `v5.3.1`)
* PostgreSQL 16
* PHP 8.2+ / Laravel 11.x

### Docker Services

| Service | Description | Port |
| --- | --- | --- |
| `app` | Laravel Application Service (PHP-FPM / Server) | `8000:8000` |
| `db` | PostgreSQL 16 Database | `5432:5432` |

*Dữ liệu PostgreSQL được lưu trữ bền vững (persist) qua Docker volume `pgdata`.*

### Các bước khởi chạy dự án (How to Run)

```bash
# 1. Clone repository
git clone https://github.com/thiennam01/intern_training_project.git
cd nam-laravel

# 2. Tạo file cấu hình môi trường
cp .env.example .env

# 3. Build và khởi chạy các Docker containers
docker compose up -d --build

# 4. Sinh App Key cho Laravel
docker compose exec app php artisan key:generate

# 5. Chạy Database Migrations & Seeders (Tạo Schema & dữ liệu RBAC)
docker compose exec app php artisan migrate --seed

# 6. Chạy Feature Tests
docker compose exec app php artisan test

```

Địa điểm truy cập API: `http://localhost:8000/api/...` 

---

## 2. Cấu hình Biến môi trường (Environment Variables)

Các biến môi trường chính trong file `.env`:

| Biến môi trường | Mô tả | Giá trị mẫu |
| --- | --- | --- |
| `DB_CONNECTION` | Kết nối cơ sở dữ liệu | `pgsql` |
| `DB_HOST` | Tên service PostgreSQL trong Docker | `db` |
| `DB_PORT` | Cổng kết nối PostgreSQL | `5432` |
| `DB_DATABASE` | Tên cơ sở dữ liệu | `clinic_app` |
| `DB_USERNAME` | Tài khoản truy cập DB | `clinic` |
| `DB_PASSWORD` | Mật khẩu DB | `secret` |
| `EXAMINATION_FEE` | Phí khám mặc định (VND) | `100000` |
| `PAYPAL_MODE` | Môi trường PayPal | `sandbox` |
| `PAYPAL_CLIENT_ID` | Client ID PayPal Sandbox | `your-sandbox-client-id` |
| `PAYPAL_CLIENT_SECRET` | Client Secret PayPal Sandbox | `your-sandbox-client-secret` |
| `PAYPAL_CURRENCY` | Đơn vị tiền tệ thanh toán | `USD` |


---

## 3. Kiến trúc đã chọn (Selected Architecture)

Hệ thống thống nhất áp dụng **Kiến trúc C: Controller - Service - Repository Pattern**.

Toàn bộ nguồn mã trong dự án được triển khai nhất quán 100% theo mô hình này, tuân thủ nghiêm ngặt quy định **Cấm Fat Controller**.

---

## 4. Lý do lựa chọn Kiến trúc C

1. **Tuân thủ nguyên lý Separation of Concerns (Tách biệt trách nhiệm):**
* **Thin Controller:** Chỉ tiếp nhận HTTP Request, chuyển qua Form Request Validation, gọi Service và trả về kết quả dưới dạng API Resource.
* **Service Layer:** Tập trung 100% Business Logic của ứng dụng (điều phối quy trình thanh toán PayPal Sandbox, tính toán hóa đơn, xử lý quy trình khám chữa bệnh).
* **Repository Layer:** Đóng gói toàn bộ các truy vấn cơ sở dữ liệu PostgreSQL (Eager loading chống lỗi N+1, khóa bản ghi `lockForUpdate`, câu lệnh aggregate).


2. **Quản lý DB Transaction đa bước an toàn:**
* Các nghiệp vụ phức tạp như *Kê đơn thuốc tự động trừ kho (`medicines.stock`)* hoặc *Tạo hóa đơn & Capture PayPal Sandbox* đòi hỏi tính toàn vẹn dữ liệu cao. Dùng Repository giúp tách biệt logic query/locking ra khỏi Service, giúp khối lệnh `DB::transaction()` ở tầng Service trong sáng và dễ kiểm soát.


3. **Tối ưu cho Feature Testing & Unit Testing:**
* Việc giao tiếp qua các **Repository Interface** cho phép Mock dữ liệu dễ dàng khi viết Unit Test cho Service mà không cần phụ thuộc vào kết nối Database thực tế.


4. **Tương thích cao với cơ chế RBAC Custom:**
* Phân tách Controller thành "Thin Controller" giúp Middleware `CheckPermission` kiểm tra quyền theo dạng `CONTROLLER.ACTION` (ví dụ `PATIENTS.FINDALL`) hoạt động độc lập ngay tại tầng HTTP.



---

## 5. Sơ đồ luồng Request (Request Flow Diagram)

Khi Client gửi request tới API, luồng xử lý sẽ đi qua các tầng theo thứ tự:

```text
 Client (Postman / Frontend)
           │
           │  1. HTTP Request (API Token Sanctum)
           ▼
┌─────────────────────────────────────────────────────────┐
│ CheckPermission Middleware                              │
│ - Tự động map và check quyền CONTROLLER.ACTION          │
│ - Nếu không có quyền -> Trả về HTTP 403 Forbidden       │
└──────────────────────────┬──────────────────────────────┘
                           │
                           │  2. Chuyển Request hợp lệ
                           ▼
┌─────────────────────────────────────────────────────────┐
│ Thin Controller (e.g. AuthController, PatientController) │
│ - Validate dữ liệu đầu vào qua Form Request             │
│ - Điều hướng request tới Service tương ứng              │
└──────────────────────────┬──────────────────────────────┘
                           │
                           │  3. Gọi phương thức Business Logic
                           ▼
┌─────────────────────────────────────────────────────────┐
│ Service Layer (e.g. AuthService, ExaminationService)    │
│ - Quản lý Business Logic & DB Transactions              │
│ - Ra lệnh cho Repository tương tác với Data             │
└──────────────────────────┬──────────────────────────────┘
                           │
                           │  4. Gọi phương thức Repository
                           ▼
┌─────────────────────────────────────────────────────────┐
│ Repository Layer                                        │
│ - Thực thi câu lệnh Query (Eloquent ORM / SQL)          │
│ - Eager Loading, Pessimistic Locking (lockForUpdate)    │
└──────────────────────────┬──────────────────────────────┘
                           │
                           │  5. Truy vấn / Cập nhật
                           ▼
┌─────────────────────────────────────────────────────────┐
│ PostgreSQL 16 Database (`db` container)                 │
└─────────────────────────────────────────────────────────┘

```


