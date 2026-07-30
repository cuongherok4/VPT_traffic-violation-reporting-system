# VPT - He Thong Bao Cao Vi Pham Giao Thong

Du an Laravel dung de tiep nhan bao cao vi pham giao thong tu nguoi dan, ho tro co quan chuc nang xac minh, quan ly muc phat, tra cuu vi pham va cung cap cua hang thiet bi an toan giao thong.

Source ban dau la PHP/MySQL cu. Du an nay duoc rebuild lai theo cau truc Laravel ro rang hon: co migration, model, request validation, service upload anh, API, giao dien Blade, phan quyen admin/citizen va test tu dong.

## Anh Demo

### Trang chu / Tra cuu vi pham

![Trang tra cuu](public/img/home.png)

### Dang nhap

![Dang nhap](public/img/login.png)

### Dang ky

![Dang ky](public/img/register.png)

### Gui bao cao vi pham

![Gui bao cao](public/img/Submit%20a%20violation%20report.png)

### Danh sach bao cao

![Danh sach bao cao](public/img/Submit%20a%20violation%20report_list.png)

### Quan ly bao cao

![Quan ly bao cao](public/img/reports.png)

### Cua hang thiet bi an toan

![Cua hang](public/img/shop.png)

## Chuc Nang Chinh

- Nguoi dan dang ky, dang nhap va gui bao cao vi pham giao thong.
- Upload anh bang chung cho bao cao; local dung `public` storage, production san sang dung AWS S3.
- Goi y loai hanh vi vi pham khi nguoi dan nhap bao cao.
- Hien thi muc phat goi y theo tung hanh vi vi pham.
- Tra cuu vi pham theo ma bao cao hoac bien so xe.
- Admin xem danh sach bao cao, loc theo trang thai/bien so va cap nhat trang thai xu ly.
- Admin sua mo ta, muc phat va thay anh bang chung cua bao cao.
- Admin quan ly san pham cua hang: them, sua, xoa, upload anh, gia va ton kho.
- Nguoi dan xem cua hang thiet bi an toan va dat mua san pham.
- API cho auth, report, lookup, fine receipt, notification, product, order, news va statistics.
- Dashboard thong ke tong quan, khu vuc vi pham nhieu, trang thai xu ly va xu huong theo ngay.

## Tai Khoan Demo

Sau khi chay seed:

```text
Admin
Email: admin@vpt.local
Password: password

Citizen
Email: citizen@vpt.local
Password: password
```

## Cong Nghe Su Dung

- PHP 8.2
- Laravel 12
- Laravel Sanctum
- Blade + Tailwind CSS
- SQLite/MySQL
- Laravel Storage + AWS S3-ready
- PHPUnit
- Laravel Pint

## Cau Truc Chinh

```text
app/
  Http/Controllers/      API controllers
  Http/Controllers/Web/  Web UI controllers
  Http/Requests/         Form request validation
  Models/                Eloquent models
  Services/              Upload/media services
config/
  traffic_violations.php Danh sach hanh vi va muc phat goi y
database/
  migrations/            Database schema
  seeders/               Demo data
resources/views/         Blade UI
routes/
  api.php                API routes
  web.php                Web routes
legacy-php/              Source cu giu lai de tham khao
```

## Cai Dat Local

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

Mo trinh duyet:

```text
http://127.0.0.1:8000
```

Neu port `8000` dang ban:

```bash
php artisan serve --host=127.0.0.1 --port=8001
```

## Cau Hinh Upload Anh

Mac dinh local co the dung:

```env
FILESYSTEM_CLOUD=public
```

Khi AWS S3 da san sang, doi lai:

```env
FILESYSTEM_CLOUD=s3
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_URL=
AWS_ENDPOINT=
AWS_USE_PATH_STYLE_ENDPOINT=false
```

Upload duoc dong goi trong:

```text
App\Services\MediaStorage
```

Database chi luu:

```text
evidence_path / evidence_url
image_path / image_url
```

## API Tieu Bieu

```http
POST /api/auth/register
POST /api/auth/login
GET  /api/auth/me
POST /api/auth/logout

GET  /api/reports
POST /api/reports
GET  /api/reports/{report}
PATCH /api/reports/{report}/status

GET /api/violations/lookup

GET /api/products
POST /api/products
GET /api/orders
POST /api/orders

POST /api/reports/{report}/fine-receipt
GET  /api/notifications

GET /api/statistics/overview
GET /api/statistics/locations
GET /api/statistics/statuses
GET /api/statistics/users
GET /api/statistics/trend
```

## Diem Cai Tien So Voi Source Cu

- Chuyen code PHP thu cong sang Laravel MVC.
- Tach ro API controller va Web controller.
- Them migration va khoa ngoai thay vi thao tac database roi rac.
- Dung enum cho trang thai bao cao: `pending`, `verified`, `rejected`, `resolved`.
- Validation nam trong Form Request, controller ngan gon hon.
- Upload anh qua service rieng, de doi giua local va AWS S3.
- Tinh tong tien don hang o backend, khong tin gia tu client.
- Xu ly order bang transaction va tru ton kho an toan.
- Them role middleware de bao ve route admin.
- Them test tu dong cho cac workflow quan trong.

## Kiem Tra Chat Luong

```bash
php artisan test
vendor/bin/pint
php artisan view:cache
php artisan view:clear
```

Ket qua gan nhat:

```text
php artisan test --filter=WebFrontendTest
8 passed

vendor/bin/pint
passed
```

## Git Workflow

Quy trinh lam viec cua du an:

```text
main
  |
  +-- develop
        |
        +-- feature/auth-role
        +-- feature/catalog-order-api
        +-- feature/aws-upload
        +-- feature/fine-notification
        +-- feature/violation-lookup
        +-- feature/report-statistics
        +-- feature/frontend-app
```

Quy tac:

- Khong code truc tiep tren `main`.
- Tinh nang moi lam tren `feature/*`.
- Merge vao `develop` sau khi test pass.
- Chi merge `develop` vao `main` khi du an da hoan thien va duoc xac nhan.
- Theo doi tien do chi tiet trong [TIENDO.md](TIENDO.md).
