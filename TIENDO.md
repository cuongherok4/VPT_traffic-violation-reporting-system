# Tien Do Du An

Du an: VPT Traffic Violation Reporting System

Muc tieu: rebuild source PHP/MySQL cu thanh ung dung Laravel co cau truc ro rang, de bao tri, co test, co phan quyen, upload anh len AWS S3 va co quy trinh Git/GitHub de leader review.

## Nguyen Tac Thuc Hien Moi

| Quy tac | Trang thai | Ghi chu |
| --- | --- | --- |
| Backend/API/DB lam truoc | ✅ Bat buoc | Uu tien nghiep vu, validation, test |
| FE/UI lam cuoi cung | ✅ Bat buoc | Sau khi backend on dinh moi polish giao dien |
| Moi module co branch rieng | ✅ Bat buoc | Khong tron API va UI trong cung phase neu khong can |
| Khong push neu user chua dong y | ✅ Bat buoc | Commit local duoc, push phai hoi/xac nhan |

## Scope Nghiep Vu Chinh

| Module | Mo ta | Trang thai |
| --- | --- | --- |
| Bao cao vi pham kem hinh anh | Nguoi dan gui thong tin vi pham va tai anh bang chung | ✅ API done |
| Quan ly bien lai phat va thong bao | Co quan chuc nang nhap bien ban, tien phat va thong bao | ⏳ Dang lam |
| Tra cuu vi pham | Tra cuu theo ma bao cao, bien so, thong tin ca nhan, trang thai | ⏳ Todo |
| Mua thiet bi an toan giao thong | San pham, danh muc, don hang | ✅ API done |
| Thong ke khu vuc vi pham | Xac dinh diem nong giao thong theo dia diem | ✅ API mot phan |
| Thong ke trang thai xu ly | Thong ke report theo trang thai va user | ✅ API mot phan |

## Quy Tac Git Bat Buoc

| Quy tac | Trang thai | Ghi chu |
| --- | --- | --- |
| Khong thao tac Git neu chua duoc yeu cau | ✅ Bat buoc | Assistant chi `git add/commit/push/merge` khi user yeu cau ro |
| Khong develop truc tiep tren `main` | ✅ Bat buoc | Code moi phai lam tren `develop` hoac `feature/*` |
| `main` chi nhan code da hoan thanh | ✅ Bat buoc | Chi merge vao `main` khi user xac nhan |
| Moi phase nen co branch rieng | ✅ Bat buoc | Leader de review tung phan |
| Truoc khi xin merge phai pass test | ✅ Bat buoc | `php artisan test`, `vendor/bin/pint` |

## Trang Thai Tong Quan

| Hang muc | Branch de xuat | Trang thai | Commit hien co / de xuat | Ghi chu |
| --- | --- | --- | --- | --- |
| Chuyen repo sang GitHub ca nhan | `main` | ✅ Done | `5c9a23b chore: connect project to personal repository` | Da doi `origin` ve repo ca nhan |
| Rebuild Laravel foundation | `develop` | ✅ Done | `390ce3d refactor: rebuild project with laravel foundation` | Da tao Laravel app va tach legacy |
| Auth + role guard | `feature/auth-role` | ✅ Done | `67fb42a feat: add api authentication and role guard` | Da them Sanctum + role middleware |
| Product/news/order API | `feature/catalog-order-api` | ✅ Done | `feat: add catalog and order management api` | Da them catalog/news/order API |
| AWS S3 upload thuc te | `feature/aws-upload` | ⏳ Todo | `feat: store report evidence on s3` | Can AWS credentials/bucket |
| Fine receipt/notification API | `feature/fine-notification` | ✅ Code done | `feat: add fine receipt and notifications` | Backend done, FE sau |
| Violation lookup API | `feature/violation-lookup` | ✅ Code done | `feat: add violation lookup api` | Backend done |
| User UI | `feature/user-report-ui` | ⏸ De sau | `feat: add citizen report workflow` | FE lam cuoi |
| Admin UI | `feature/admin-dashboard-ui` | ⏸ De sau | `feat: add admin report dashboard` | FE lam cuoi |
| Safety shop UI | `feature/safety-shop-ui` | ⏸ De sau | `feat: add safety equipment shop ui` | FE lam cuoi |
| Portfolio docs | `docs/portfolio-readiness` | ⏳ Todo | `docs: add portfolio screenshots and erd` | README, screenshot, ERD |

## Branch Flow De Xuat

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
        +-- feature/user-report-ui
        +-- feature/admin-dashboard-ui
        +-- feature/safety-shop-ui
        +-- docs/portfolio-readiness
```

Quy trinh lam viec:

1. Tao branch tu `develop`.
2. Code trong branch `feature/*`.
3. Chay test va format.
4. Tao commit nho, ro nghia.
5. Push branch len GitHub khi duoc yeu cau.
6. Tao Pull Request vao `develop`.
7. Chi merge `develop` vao `main` khi tat ca phase chinh da hoan thanh va user xac nhan.

## Phase 0: Chuan Bi Repo

Branch: `main`

Trang thai: ✅ Done

Commit da co:

```text
5c9a23b chore: connect project to personal repository
```

Checklist:

- ✅ Tao GitHub repo ca nhan.
- ✅ Doi `origin` sang repo ca nhan.
- ✅ Giu repo goc trong `upstream`.
- ✅ Push code len GitHub ca nhan.

## Phase 1: Laravel Foundation

Branch nen dung: `develop`

Trang thai: ✅ Done

Commit da co:

```text
390ce3d refactor: rebuild project with laravel foundation
```

Muc tieu:

- Rebuild source cu thanh Laravel app.
- Giu source cu de tham khao, khong xoa mat lich su nghiep vu.
- Toi uu database bang migrations.
- Tao API nen tang cho report.

Checklist:

- ✅ Tao Laravel 12 project.
- ✅ Dua source PHP cu vao `legacy-php/`.
- ✅ Tao migrations moi.
- ✅ Tao models: `ViolationReport`, `Product`, `Order`, `NewsArticle`.
- ✅ Tao Form Request cho report.
- ✅ Tao `MediaStorage` service.
- ✅ Tao API report va dashboard.
- ✅ Them test cho report API.
- ✅ Cap nhat README.

Ket qua test:

```text
php artisan test
4 tests passed
```

## Phase 2: Auth + Role Guard

Branch nen dung: `feature/auth-role`

Trang thai: ✅ Done

Commit da co:

```text
67fb42a feat: add api authentication and role guard
```

Muc tieu:

- Them API authentication.
- Tach quyen `admin` va `citizen`.
- Bao ve route duyet report.

Checklist:

- ✅ Cai Laravel Sanctum.
- ✅ Tao bang `personal_access_tokens`.
- ✅ Them `HasApiTokens` vao `User`.
- ✅ Tao `AuthController`.
- ✅ Tao `LoginRequest`.
- ✅ Tao `RegisterRequest`.
- ✅ Tao middleware `EnsureUserHasRole`.
- ✅ Dang ky middleware alias `role`.
- ✅ Tao API register/login/me/logout.
- ✅ Bao ve `PATCH /api/reports/{id}/status` bang `auth:sanctum` va `role:admin`.
- ✅ Them test auth API.
- ✅ Them test citizen khong duoc duyet report.
- ✅ Them test admin duoc duyet report.

Ket qua test:

```text
php artisan test
8 tests passed, 23 assertions
```

## Phase 3: Catalog, News, Order API

Branch de xuat: `feature/catalog-order-api`

Trang thai: ✅ Done

Commit de xuat:

```text
feat: add catalog and order management api
test: cover product and order workflows
```

Muc tieu:

- Hoan thien API cho cua hang va tin tuc.
- Admin quan ly san pham/tin tuc.
- Citizen xem san pham, tao don hang.

Checklist can lam:

- ✅ Tao `ProductController`.
- ✅ Tao `ProductCategoryController`.
- ✅ Tao `NewsArticleController`.
- ✅ Tao `OrderController`.
- ✅ Tao Form Request cho product.
- ✅ Tao Form Request cho news.
- ✅ Tao Form Request cho order.
- ✅ Admin them/sua/xoa san pham.
- ✅ Admin them/sua/xoa tin tuc.
- ✅ Citizen xem danh sach san pham.
- ✅ Citizen tao order.
- ✅ Tinh tong tien order o backend, khong tin gia tu client.
- ✅ Tru ton kho khi tao order.
- ✅ Them transaction cho tao order.
- ✅ Them tests cho product/news/order.

Dieu kien hoan thanh:

- ✅ API co validation.
- ✅ Route admin duoc bao ve bang `role:admin`.
- ✅ Order dung DB transaction.
- ✅ Test pass.

Ket qua test hien tai:

```text
php artisan test
14 tests passed, 41 assertions
```

## Phase 4: AWS S3 Upload

Branch de xuat: `feature/aws-upload`

Trang thai: ⏳ Todo

Commit de xuat:

```text
feat: store report evidence on s3
test: fake s3 storage for evidence uploads
```

Muc tieu:

- Upload anh bang chung len AWS S3.
- Luu object path va URL vao database.
- Khong luu anh user upload trong repo.

Checklist can lam:

- ⏳ Tao AWS S3 bucket.
- ⏳ Tao IAM user co quyen toi thieu.
- ⏳ Cau hinh `.env` that.
- ⏳ Test upload anh report len S3.
- ⏳ Test upload anh product/news len S3.
- ⏳ Them file size limit.
- ⏳ Them MIME validation.
- ⏳ Xu ly loi upload that bai.
- ⏳ Rollback DB neu upload/ghi DB loi.
- ⏳ Them tests voi `Storage::fake('s3')`.

Dieu kien hoan thanh:

- ✅ Upload that len S3 thanh cong.
- ✅ DB chi luu `*_path` va `*_url`.
- ✅ Test upload pass.

## Phase 5: Fine Receipt And Notification API

Branch de xuat: `feature/fine-notification`

Trang thai: ✅ Code done

Commit de xuat:

```text
feat: add fine receipt and notifications
test: cover fine receipt workflow
```

Muc tieu:

- Co quan chuc nang lap bien lai phat sau khi report duoc xac minh.
- Luu tien phat, noi dung bien ban, han thanh toan va trang thai thanh toan.
- Tao thong bao cho cong dan khi co bien lai/trang thai moi.

Checklist can lam:

- ✅ Tao bang `fine_receipts`.
- ✅ Tao bang `user_notifications`.
- ✅ Tao model `FineReceipt`.
- ✅ Tao model `UserNotification`.
- ✅ Tao API admin tao/xem/cap nhat bien lai.
- ✅ Tao API citizen xem thong bao cua minh.
- ✅ Bao ve route admin bang `role:admin`.
- ✅ Them tests.

Ket qua test rieng:

```text
php artisan test --filter=FineReceiptApiTest
4 tests passed, 18 assertions
```

## Phase 6: Violation Lookup API

Branch de xuat: `feature/violation-lookup`

Trang thai: ✅ Code done

Muc tieu:

- Tra cuu vi pham theo ma bao cao, bien so, thong tin ca nhan hoac trang thai.
- Khong ro ri du lieu nhay cam cua nguoi khac.

Checklist can lam:

- ✅ Tao `ViolationLookupRequest`.
- ✅ Tao `ViolationLookupController`.
- ✅ Tao endpoint `GET /api/violations/lookup`.
- ✅ Public lookup theo `report_id` hoac `license_plate`.
- ✅ Authenticated lookup theo `email`.
- ✅ Public response chi tra du lieu an toan.
- ✅ Owner response tra them mo ta, anh bang chung va thong tin bien lai.
- ✅ Them optional Sanctum auth middleware.
- ✅ Them tests chong lo du lieu.

Ket qua test rieng:

```text
php artisan test --filter=ViolationLookupApiTest
4 tests passed, 12 assertions
```

## Phase 7: Citizen UI

Branch de xuat: `feature/user-report-ui`

Trang thai: ⏳ Todo

Commit de xuat:

```text
feat: add citizen report workflow
ui: build violation lookup page
```

Muc tieu:

- Tao giao dien nguoi dan de bao cao vi pham.
- Tao trang tra cuu bao cao theo bien so/trang thai.

Checklist can lam:

- ⏳ Tao layout Blade chung.
- ⏳ Tao trang dang ky/dang nhap.
- ⏳ Tao form gui bao cao vi pham.
- ⏳ Upload anh bang chung tu UI.
- ⏳ Tao trang danh sach report cua user.
- ⏳ Tao trang chi tiet report.
- ⏳ Tao trang tra cuu theo bien so.
- ⏳ Hien thi trang thai report ro rang.
- ⏳ Them validation error UI.
- ⏳ Them empty/loading/error states.

Dieu kien hoan thanh:

- ✅ Citizen co the dang nhap.
- ✅ Citizen gui report duoc.
- ✅ Citizen xem lai report duoc.
- ✅ UI responsive.

## Phase 8: Admin UI

Branch de xuat: `feature/admin-dashboard-ui`

Trang thai: ⏳ Todo

Commit de xuat:

```text
feat: add admin report dashboard
ui: add report review screen
```

Muc tieu:

- Tao dashboard admin de duyet va thong ke vi pham.

Checklist can lam:

- ⏳ Tao admin layout.
- ⏳ Tao dashboard thong ke tong quan.
- ⏳ Loc report theo status.
- ⏳ Loc report theo bien so.
- ⏳ Xem chi tiet report va anh bang chung.
- ⏳ Duyet report: `verified`.
- ⏳ Tu choi report: `rejected`.
- ⏳ Hoan tat xu ly: `resolved`.
- ⏳ Cap nhat tien phat.
- ⏳ Tao trang quan ly san pham.
- ⏳ Tao trang quan ly tin tuc.
- ⏳ Them UI guard neu khong phai admin.

Dieu kien hoan thanh:

- ✅ Admin duyet report duoc.
- ✅ Citizen khong vao admin UI duoc.
- ✅ Dashboard co so lieu dung.

## Phase 9: Safety Shop UI

Branch de xuat: `feature/safety-shop-ui`

Trang thai: ⏸ De sau

Muc tieu:

- Giao dien mua thiet bi an toan giao thong dua tren API catalog/order da co.

## Phase 10: Portfolio Readiness

Branch de xuat: `docs/portfolio-readiness`

Trang thai: ⏳ Todo

Commit de xuat:

```text
docs: add portfolio screenshots and erd
docs: document legacy-to-laravel improvements
```

Muc tieu:

- Lam repo san sang gui CV/intern.
- Leader/reviewer nhin vao thay du an co quy trinh va co chat luong.

Checklist can lam:

- ⏳ Cap nhat README voi screenshots moi.
- ⏳ Them ERD/database diagram.
- ⏳ Them API examples day du.
- ⏳ Them Postman collection hoac curl examples.
- ⏳ Them section "Legacy issues fixed".
- ⏳ Them section "Security improvements".
- ⏳ Them section "How to run locally".
- ⏳ Them section "Test account".
- ⏳ Them roadmap ngan gon.

Dieu kien hoan thanh:

- ✅ README doc ro.
- ✅ Co anh demo.
- ✅ Co test command.
- ✅ Co mo ta dong gop ca nhan.

## API Hien Tai

Public:

```http
GET /api/dashboard
GET /api/reports
GET /api/reports/{id}
POST /api/reports
POST /api/auth/register
POST /api/auth/login
GET /api/categories
GET /api/categories/{id}
GET /api/products
GET /api/products/{id}
GET /api/news-articles
GET /api/news-articles/{id}
```

Authenticated:

```http
GET /api/auth/me
POST /api/auth/logout
GET /api/orders
POST /api/orders
GET /api/orders/{id}
```

Admin only:

```http
PATCH /api/reports/{id}/status
POST /api/categories
POST /api/products
PATCH /api/products/{id}
DELETE /api/products/{id}
POST /api/news-articles
PATCH /api/news-articles/{id}
DELETE /api/news-articles/{id}
```

## Commit Convention

Dung Conventional Commits:

```text
feat: add api authentication
fix: reject invalid report status
refactor: extract media storage service
security: protect report review endpoint
test: cover admin report review
docs: update project progress
chore: configure laravel pint
```

Quy uoc:

- `feat`: tinh nang moi.
- `fix`: sua loi.
- `refactor`: sua cau truc code, khong doi hanh vi.
- `security`: sua bao mat.
- `test`: them/sua test.
- `docs`: tai lieu.
- `chore`: cau hinh, dependency, tooling.

## Checklist Truoc Khi Bao Leader Review

- ⏳ Kiem tra dung branch, khong lam tren `main`.
- ⏳ Chay `php artisan migrate:fresh --seed`.
- ⏳ Chay `php artisan test`.
- ⏳ Chay `vendor/bin/pint`.
- ⏳ Chay `php artisan route:list --except-vendor`.
- ⏳ Doc lai `git diff`.
- ⏳ Commit theo tung nhom nho.
- ⏳ Push branch khi user yeu cau.
- ⏳ Tao PR vao `develop`, khong merge truc tiep vao `main`.

## Ghi Chu Quan Trong

- Da co 2 commit refactor/auth tren `main` truoc khi thong nhat lai quy trinh. Tu thoi diem nay tro di, moi thay doi moi se lam theo branch flow o tren.
- Assistant khong duoc tu y thao tac Git neu user chua yeu cau.
- `main` chi la ban on dinh cuoi cung sau khi develop hoan thanh.
