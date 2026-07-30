<!doctype html>
<html lang="vi">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VPT Traffic Violation</title>
    <style>
        :root {
            color-scheme: light;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: #18212f;
            background: #eef2f5;
        }

        body {
            margin: 0;
        }

        main {
            min-height: 100vh;
            display: grid;
            grid-template-columns: minmax(280px, 420px) 1fr;
        }

        aside {
            background: #101820;
            color: #f7fafc;
            padding: 32px;
        }

        h1 {
            margin: 0 0 12px;
            font-size: 32px;
            line-height: 1.15;
        }

        p {
            margin: 0;
            color: #cbd5df;
            line-height: 1.7;
        }

        section {
            padding: 32px;
            display: grid;
            align-content: start;
            gap: 16px;
        }

        .panel {
            background: #fff;
            border: 1px solid #d8e0e7;
            border-radius: 8px;
            padding: 20px;
        }

        .panel h2 {
            margin: 0 0 12px;
            font-size: 18px;
        }

        code {
            display: block;
            padding: 12px;
            overflow-x: auto;
            border-radius: 6px;
            color: #0f172a;
            background: #f2f5f8;
        }

        @media (max-width: 760px) {
            main {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
<main>
    <aside>
        <h1>VPT Traffic Violation</h1>
        <p>Laravel API cho hệ thống tiếp nhận, xác minh và thống kê báo cáo vi phạm giao thông. Ảnh bằng chứng được thiết kế lưu trên AWS S3.</p>
    </aside>

    <section>
        <div class="panel">
            <h2>Core API</h2>
            <code>GET /api/reports</code>
            <code>POST /api/reports</code>
            <code>PATCH /api/reports/{id}/status</code>
            <code>GET /api/dashboard</code>
        </div>

        <div class="panel">
            <h2>Demo Account</h2>
            <code>admin@vpt.local / password</code>
            <code>citizen@vpt.local / password</code>
        </div>
    </section>
</main>
</body>
</html>
