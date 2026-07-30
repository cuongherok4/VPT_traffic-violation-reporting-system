<x-layouts.app title="Bảng điều khiển Quản trị">
    <!-- Topbar -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-900 border border-amber-300 mb-2">
                Quản trị viên
            </span>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">Bảng Điều Khiển Xử Lý Vi Phạm</h1>
            <p class="text-slate-500 text-sm mt-1">Tổng quan báo cáo vi phạm, khu vực điểm nóng và hàng chờ xử lý toàn hệ thống.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <a href="{{ route('admin.reports.index') }}" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl shadow-md shadow-blue-600/30 transition-all inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <span>Mở Hàng Cho Duyệt</span>
            </a>
            <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 bg-amber-600 hover:bg-amber-700 text-white font-bold text-sm rounded-xl shadow-md shadow-amber-600/30 transition-all inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                <span>Quản lý sản phẩm</span>
            </a>
        </div>
    </div>

    <!-- Summary KPI Cards -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        @foreach($summary as $label => $value)
            @php
                $translatedLabel = match($label) {
                    'Total' => 'Tổng báo cáo',
                    'Pending' => 'Chờ duyệt',
                    'Verified' => 'Đã xác minh',
                    'Resolved' => 'Đã xử lý xong',
                    'Rejected' => 'Bị từ chối',
                    default => $label,
                };
                $cardStyle = match($label) {
                    'Total' => 'border-blue-200 bg-blue-50/20 text-blue-700',
                    'Pending' => 'border-amber-200 bg-amber-50/20 text-amber-700',
                    'Verified' => 'border-emerald-200 bg-emerald-50/20 text-emerald-700',
                    'Resolved' => 'border-indigo-200 bg-indigo-50/20 text-indigo-700',
                    'Rejected' => 'border-rose-200 bg-rose-50/20 text-rose-700',
                    default => 'border-slate-200 bg-slate-50 text-slate-700',
                };
            @endphp
            <div class="bg-white rounded-2xl p-5 border shadow-xs flex items-center justify-between {{ $cardStyle }}">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">{{ $translatedLabel }}</p>
                    <p class="text-2xl md:text-3xl font-extrabold text-slate-900 mt-1">{{ number_format($value) }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-lg bg-white shadow-xs border border-slate-200">
                    #
                </div>
            </div>
        @endforeach
    </div>

    <!-- Grid 2 Columns: Top Hotspots & Recent Queue -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Top Locations (Hotspots) -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6">
            <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                Điểm Nóng Vi Phạm Nhiều Nhất
            </h2>

            <div class="space-y-4">
                @php $maxVal = $topLocations->max('total') ?: 1; @endphp
                @forelse($topLocations as $location)
                    @php $pct = round(($location->total / $maxVal) * 100); @endphp
                    <div>
                        <div class="flex items-center justify-between text-sm font-semibold text-slate-800 mb-1">
                            <span class="truncate max-w-xs">{{ $location->location }}</span>
                            <span class="font-bold text-blue-600">{{ $location->total }} vụ</span>
                        </div>
                        <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden">
                            <div class="bg-gradient-to-r from-blue-500 to-rose-500 h-2.5 rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @empty
                    <div class="py-8 text-center text-slate-400">
                        <p class="text-sm">Chưa có dữ liệu thống kê địa điểm</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Recent Reports Queue -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3 mb-4">
                <h2 class="text-base font-bold text-slate-900 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Báo Cáo Mới Tiếp Nhận
                </h2>
                <a href="{{ route('admin.reports.index') }}" class="text-xs font-bold text-blue-600 hover:text-blue-800">Xem tất cả &rarr;</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="border-b border-slate-200 text-[10px] font-extrabold uppercase text-slate-400 bg-slate-50">
                            <th class="py-2.5 px-3">Mã</th>
                            <th class="py-2.5 px-3">Biển số</th>
                            <th class="py-2.5 px-3">Trạng thái</th>
                            <th class="py-2.5 px-3 text-right">Xử lý</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($recentReports as $report)
                            @php
                                $statusVal = $report->status->value;
                                $badgeClass = match($statusVal) {
                                    'pending' => 'badge-pending',
                                    'verified' => 'badge-verified',
                                    'rejected' => 'badge-rejected',
                                    'resolved' => 'badge-resolved',
                                    default => 'bg-slate-100 text-slate-700',
                                };
                                $statusLabel = match($statusVal) {
                                    'pending' => 'Chờ duyệt',
                                    'verified' => 'Đã xác minh',
                                    'rejected' => 'Bị từ chối',
                                    'resolved' => 'Đã xử lý',
                                    default => $statusVal,
                                };
                            @endphp
                            <tr class="hover:bg-slate-50">
                                <td class="py-3 px-3 font-bold text-slate-600">#{{ $report->id }}</td>
                                <td class="py-3 px-3">
                                    <span class="px-2 py-0.5 bg-amber-50 text-amber-900 font-bold rounded text-xs font-mono border border-amber-200">
                                        {{ $report->license_plate }}
                                    </span>
                                </td>
                                <td class="py-3 px-3">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold {{ $badgeClass }}">
                                        {{ $statusLabel }}
                                    </span>
                                </td>
                                <td class="py-3 px-3 text-right">
                                    <a href="{{ route('admin.reports.show', $report) }}" class="text-xs font-bold text-blue-600 hover:text-blue-800 bg-blue-50 px-2.5 py-1 rounded">
                                        Duyệt &rarr;
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="py-6 text-center text-slate-400 text-xs">Không có báo cáo mới</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-layouts.app>
