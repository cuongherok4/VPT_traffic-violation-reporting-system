<x-layouts.app title="Quản lý báo cáo vi phạm">
    <!-- Topbar -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-900 border border-amber-300 mb-2">
                Hàng chờ phê duyệt
            </span>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">Quản Lý Báo Cáo Vi Phạm</h1>
            <p class="text-slate-500 text-sm mt-1">Lọc danh sách, mở chi tiết ảnh bằng chứng và cập nhật trạng thái xử lý cho cơ quan chức năng.</p>
        </div>
    </div>

    <!-- Panel & Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
        <!-- Filter Form -->
        <div class="p-6 border-b border-slate-200 bg-slate-50/50">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <div>
                    <label for="license_plate" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Biển số xe</label>
                    <input id="license_plate" name="license_plate" value="{{ request('license_plate') }}" placeholder="Lọc theo biển số..."
                           class="w-full px-4 py-2 bg-white border border-slate-300 rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="status" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Trạng thái xử lý</label>
                    <select id="status" name="status" class="w-full px-4 py-2 bg-white border border-slate-300 rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Tất cả trạng thái</option>
                        @foreach($statuses as $status)
                            @php
                                $labelSt = match($status) {
                                    'pending' => 'Chờ xác minh',
                                    'verified' => 'Đã xác minh',
                                    'rejected' => 'Bị từ chối',
                                    'resolved' => 'Đã xử lý xong',
                                    default => $status,
                                };
                            @endphp
                            <option value="{{ $status }}" @selected(request('status') === $status)>{{ $labelSt }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <button type="submit" class="w-full px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl transition-all shadow-sm">
                        Lọc báo cáo
                    </button>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 text-[11px] font-extrabold uppercase tracking-wider text-slate-500 bg-slate-100/60">
                        <th class="py-3.5 px-6">Mã BC</th>
                        <th class="py-3.5 px-4">Biển số xe</th>
                        <th class="py-3.5 px-4">Người báo cáo</th>
                        <th class="py-3.5 px-4">Địa điểm</th>
                        <th class="py-3.5 px-4">Trạng thái</th>
                        <th class="py-3.5 px-6 text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($reports as $report)
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
                                'pending' => 'Chờ xác minh',
                                'verified' => 'Đã xác minh',
                                'rejected' => 'Bị từ chối',
                                'resolved' => 'Đã hoàn tất',
                                default => $statusVal,
                            };
                        @endphp
                        <tr class="hover:bg-blue-50/30 transition-colors">
                            <td class="py-4 px-6 font-bold text-blue-600">#{{ $report->id }}</td>
                            <td class="py-4 px-4">
                                <span class="inline-block px-2.5 py-1 bg-amber-50 text-amber-900 border border-amber-300 font-extrabold rounded-md text-xs font-mono">
                                    {{ $report->license_plate }}
                                </span>
                            </td>
                            <td class="py-4 px-4 font-medium text-slate-800">
                                {{ $report->reporter?->name ?? 'Ẩn danh' }}
                            </td>
                            <td class="py-4 px-4 text-slate-600 text-xs">{{ $report->location }}</td>
                            <td class="py-4 px-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-extrabold {{ $badgeClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <a href="{{ route('admin.reports.show', $report) }}" class="inline-flex items-center gap-1 text-xs font-bold text-white bg-blue-600 hover:bg-blue-700 px-3 py-1.5 rounded-lg shadow-xs transition-colors">
                                    <span>Chi tiết & Duyệt</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                <p class="font-semibold text-slate-600 text-base">Không tìm thấy báo cáo nào</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($reports->hasPages())
            <div class="p-4 border-t border-slate-200 bg-slate-50">
                {{ $reports->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
