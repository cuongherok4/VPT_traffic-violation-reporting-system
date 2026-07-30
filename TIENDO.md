# Tien Do Du An

Du an: VPT Traffic Violation Reporting System

Muc tieu: chuyen source PHP/MySQL cu thanh ung dung Laravel co cau truc ro rang, de bao tri, co test, co quy trinh Git/GitHub minh bach va phu hop portfolio xin intern.

## Trang Thai Tong Quan

| Hang muc | Trang thai | Ghi chu |
| --- | --- | --- |
| Chuyen repo sang GitHub ca nhan | Done | `origin` tro ve repo GitHub cua cuongherok4 |
| Luu source cu | Done | Source PHP cu duoc dua vao `legacy-php/` |
| Khoi tao Laravel | Done | Laravel 12, PHP 8.2 |
| Toi uu database | Done | Dung migrations, foreign keys, indexes, timestamp |
| API bao cao vi pham | Done | CRUD doc/tao report va cap nhat trang thai |
| Upload anh AWS S3 | In progress | Da cau hinh code, can bo sung credentials AWS that |
| Test tu dong | Done | Da co feature tests cho report API va dashboard |
| UI nguoi dung/admin | Todo | Lam sau khi API on dinh |
| Auth + phan quyen | Done | Da them Sanctum token auth va middleware role |

## Da Thuc Hien

### 1. Git/GitHub

- Doi remote moi:
  - `origin`: repo GitHub ca nhan.
  - `upstream`: repo goc de tham khao lich su source ban dau.
- Giu quy uoc commit ngan gon, ro muc dich.
- Khong sua truc tiep source cu theo kieu pha vo lich su; source cu duoc tach rieng vao `legacy-php/`.

### 2. Kien Truc Laravel

- Tao Laravel app tai root repo.
- Tach domain logic theo cau truc:
  - `app/Models`
  - `app/Http/Controllers`
  - `app/Http/Requests`
  - `app/Services`
  - `database/migrations`
  - `routes/api.php`
- Tao service rieng `MediaStorage` de upload anh, giup controller ngan va de test.

### 3. Database

Source cu co mot so van de:

- Mat khau dang plain text.
- Ngay gio vi pham luu bang `varchar`.
- Trang thai dung so `0`, `1`, `2` kho doc.
- Bang `bao_cao` dung MyISAM, khong tan dung foreign key.
- Decimal qua lon cho tien phat.

Ban Laravel moi da cai tien:

- Dung bang `violation_reports`.
- Dung `timestamp` cho `violated_at`, `reviewed_at`.
- Dung enum status: `pending`, `verified`, `rejected`, `resolved`.
- Dung foreign keys cho user/reviewer/order/product.
- Them indexes cho `license_plate`, `status`, `violation_type`, `violated_at`.
- Dung `unsignedBigInteger` cho tien phat/gia tien.

### 4. API Hien Co

```http
GET /api/reports
POST /api/reports
GET /api/reports/{id}
PATCH /api/reports/{id}/status
GET /api/dashboard
POST /api/auth/register
POST /api/auth/login
GET /api/auth/me
POST /api/auth/logout
```

Ghi chu bao mat:

- `PATCH /api/reports/{id}/status` yeu cau `auth:sanctum` va role `admin`.
- User role `citizen` khong duoc phep duyet/cap nhat trang thai report.

### 5. AWS S3

Da cai package:

```bash
composer require league/flysystem-aws-s3-v3
```

Da cau hinh `.env.example`:

```env
FILESYSTEM_CLOUD=s3
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=ap-southeast-1
AWS_BUCKET=
AWS_URL=
AWS_ENDPOINT=
AWS_USE_PATH_STYLE_ENDPOINT=false
```

Anh bang chung se duoc upload qua Laravel Storage va chi luu `evidence_path`, `evidence_url` trong database.

## Kiem Tra Chat Luong

Da chay:

```bash
php artisan test
```

Ket qua hien tai:

```text
4 tests passed
9 assertions
```

Da chay:

```bash
php artisan migrate:fresh --seed
vendor/bin/pint
php artisan route:list --except-vendor
```

## Ke Hoach Tiep Theo

### Phase 1: Hoan thien backend nen tang

- Laravel auth: done.
- Middleware role `admin`, `citizen`: done.
- Bao ve API cap nhat trang thai chi cho admin: done.
- Them Form Request cho product/news/order.

### Phase 2: Hoan thien upload AWS

- Tao AWS S3 bucket that.
- Tao IAM user voi quyen toi thieu.
- Cau hinh `.env`.
- Test upload anh that len S3.
- Xu ly loi upload va rollback khi DB fail.

### Phase 3: UI portfolio

- Tao layout Blade cho user.
- Tao form bao cao vi pham.
- Tao trang tra cuu bien so/trang thai.
- Tao admin dashboard.
- Tao man hinh duyet report.

### Phase 4: Portfolio readiness

- Them screenshots moi.
- Them ERD/database diagram.
- Them API examples.
- Them seed data day du.
- Viet section "Improvements from legacy version" trong README.

## Quy Uoc Git De Leader Theo Doi

### Branch

- `main`: code on dinh, da test.
- `feature/auth-role`: auth va phan quyen.
- `feature/report-ui`: giao dien bao cao vi pham.
- `feature/aws-upload`: hoan thien upload S3.
- `fix/...`: sua loi nho.

### Commit Message

Dung Conventional Commits:

```text
feat: add violation report api
refactor: move legacy php source
security: hash user passwords
docs: add progress tracker
test: cover report creation api
chore: configure laravel pint
```

### Checklist Truoc Khi Push

- Chay `php artisan test`.
- Chay `vendor/bin/pint`.
- Kiem tra `git status`.
- Commit theo tung nhom thay doi ro rang.
- Push len GitHub de leader review.

## Ghi Chu

Day la giai doan refactor nen thay doi file rat lon. Ly do: source PHP cu duoc tach sang `legacy-php/`, root repo duoc chuyen thanh Laravel app chuan.
