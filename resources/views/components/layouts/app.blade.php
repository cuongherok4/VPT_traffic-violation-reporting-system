<!doctype html>
<html lang="vi" class="h-full bg-slate-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'VPT Traffic Violation' }} - Cổng Báo Cáo Vi Phạm Giao Thông</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        .badge-pending { background-color: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
        .badge-verified { background-color: #ecfdf5; color: #047857; border: 1px solid #a7f3d0; }
        .badge-rejected { background-color: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
        .badge-resolved { background-color: #eff6ff; color: #1d4ed8; border: 1px solid #bfdbfe; }
    </style>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="h-full font-sans antialiased text-slate-800 bg-slate-100 selection:bg-blue-600 selection:text-white flex flex-col min-h-screen">

    <!-- Top Sticky Header Navigation Bar -->
    <header class="bg-slate-900 text-white sticky top-0 z-50 shadow-md border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16 gap-4">
                
                <!-- Left: Brand Logo & Title -->
                <div class="flex items-center gap-3 shrink-0">
                    <a href="{{ route('lookup.index') }}" class="flex items-center gap-3 group">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-extrabold text-base shadow-lg shadow-blue-500/20 ring-1 ring-white/20 group-hover:scale-105 transition-transform">
                            VPT
                        </div>
                        <div>
                            <span class="font-extrabold text-white text-base leading-tight block tracking-tight">An Toàn Giao Thông</span>
                            <span class="text-[11px] text-blue-400 font-medium block">Cổng Báo Cáo Quốc Gia</span>
                        </div>
                    </a>
                </div>

                <!-- Center: Navigation Menu -->
                <nav class="hidden md:flex items-center gap-1">
                    <a href="{{ route('lookup.index') }}" 
                       class="px-3.5 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-2 {{ request()->routeIs('lookup.*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        <span>Tra cứu vi phạm</span>
                    </a>

                    <a href="{{ route('shop.index') }}" 
                       class="px-3.5 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-2 {{ request()->routeIs('shop.*') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 11h14l1 12H4L5 11z"/></svg>
                        <span>Cửa hàng an toàn</span>
                    </a>

                    @auth
                        @if(auth()->user()->role === 'admin')
                            <span class="h-4 w-px bg-slate-800 mx-1"></span>
                            <a href="{{ route('admin.dashboard') }}" 
                               class="px-3.5 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-2 {{ request()->routeIs('admin.dashboard') ? 'bg-amber-600 text-white shadow-sm' : 'text-amber-400 hover:bg-slate-800' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                                <span>Dashboard Admin</span>
                            </a>
                            <a href="{{ route('admin.reports.index') }}" 
                               class="px-3.5 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-2 {{ request()->routeIs('admin.reports.*') ? 'bg-amber-600 text-white shadow-sm' : 'text-amber-400 hover:bg-slate-800' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span>Duyệt báo cáo</span>
                            </a>
                            <a href="{{ route('admin.products.index') }}" 
                               class="px-3.5 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-2 {{ request()->routeIs('admin.products.*') ? 'bg-amber-600 text-white shadow-sm' : 'text-amber-400 hover:bg-slate-800' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                <span>Quản lý sản phẩm</span>
                            </a>
                        @else
                            <a href="{{ route('citizen.reports.create') }}" 
                               class="px-3.5 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-2 {{ request()->routeIs('citizen.reports.create') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                <span>Gửi báo cáo</span>
                            </a>
                            <a href="{{ route('citizen.reports.index') }}" 
                               class="px-3.5 py-2 rounded-lg text-xs font-bold transition-all flex items-center gap-2 {{ request()->routeIs('citizen.reports.index') || request()->routeIs('citizen.reports.show') ? 'bg-blue-600 text-white shadow-sm' : 'text-slate-300 hover:text-white hover:bg-slate-800' }}">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                <span>Báo cáo của tôi</span>
                            </a>
                        @endif
                    @endauth
                </nav>

                <!-- Right: Auth & Profile Actions -->
                <div class="flex items-center gap-3">
                    @auth
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full bg-blue-500/20 text-blue-400 font-bold flex items-center justify-center border border-blue-500/30 text-xs shrink-0">
                                {{ mb_strtoupper(mb_substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <div class="hidden sm:block text-left">
                                <p class="text-xs font-semibold text-white leading-none">{{ auth()->user()->name }}</p>
                                <span class="text-[10px] text-slate-400 font-medium">
                                    {{ auth()->user()->role === 'admin' ? 'Quản trị viên' : 'Công dân' }}
                                </span>
                            </div>
                        </div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" title="Đăng xuất" class="px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-300 hover:text-white bg-slate-800 hover:bg-slate-700 transition-colors">
                                Đăng xuất
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="px-3.5 py-1.5 text-xs font-bold text-slate-300 hover:text-white bg-slate-800 hover:bg-slate-700 rounded-lg transition-colors">
                            Đăng nhập
                        </a>
                        <a href="{{ route('register') }}" class="px-3.5 py-1.5 text-xs font-bold text-white bg-blue-600 hover:bg-blue-500 rounded-lg shadow-sm transition-colors">
                            Đăng ký
                        </a>
                    @endauth
                </div>

            </div>

            <!-- Mobile Sub-Header Links -->
            <div class="md:hidden border-t border-slate-800 py-2 flex items-center justify-around text-xs font-semibold text-slate-300 overflow-x-auto">
                <a href="{{ route('lookup.index') }}" class="px-2 py-1">Tra cứu</a>
                <a href="{{ route('shop.index') }}" class="px-2 py-1">Cửa hàng</a>
                @auth
                    @if(auth()->user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="px-2 py-1 text-amber-400">Dashboard</a>
                        <a href="{{ route('admin.reports.index') }}" class="px-2 py-1 text-amber-400">Duyệt BC</a>
                        <a href="{{ route('admin.products.index') }}" class="px-2 py-1 text-amber-400">Sản phẩm</a>
                    @else
                        <a href="{{ route('citizen.reports.create') }}" class="px-2 py-1">Gửi BC</a>
                        <a href="{{ route('citizen.reports.index') }}" class="px-2 py-1">BC của tôi</a>
                    @endif
                @endauth
            </div>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="flex-1 w-full max-w-7xl mx-auto p-4 md:p-8">
        <!-- Flash Status & Error Messages -->
        @if(session('status'))
            <div class="bg-emerald-500 text-white px-6 py-3.5 rounded-2xl shadow-sm flex items-center justify-between text-sm font-semibold mb-6 animate-fade-in">
                <div class="flex items-center gap-2">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>{{ session('status') }}</span>
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-rose-600 text-white px-6 py-3.5 rounded-2xl shadow-sm text-sm font-medium mb-6 animate-fade-in">
                <p class="font-bold flex items-center gap-2 mb-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span>Vui lòng kiểm tra lại thông tin:</span>
                </p>
                <ul class="list-disc list-inside space-y-0.5 text-rose-100 text-xs">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="mt-auto border-t border-slate-200 bg-white py-6 px-6 text-center text-xs text-slate-500">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between gap-4">
            <p>&copy; {{ date('Y') }} Cổng Báo Cáo Vi Phạm Giao Thông VPT. Bảo lưu mọi quyền.</p>
            <div class="flex items-center gap-4 text-slate-400">
                <span class="inline-flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> Hệ thống hoạt động tốt</span>
            </div>
        </div>
    </footer>

</body>
</html>
