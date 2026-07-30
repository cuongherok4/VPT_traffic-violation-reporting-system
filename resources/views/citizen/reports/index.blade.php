<x-layouts.app title="Báo cáo của tôi">
    <!-- Topbar -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 mb-2">
                Danh mục quản lý
            </span>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">Danh sách Báo cáo của tôi</h1>
            <p class="text-slate-500 text-sm mt-1">Theo dõi tiến độ xác minh và kết quả xử lý đối với các vi phạm giao thông bạn đã đóng góp.</p>
        </div>
        <div>
            <a href="{{ route('citizen.reports.create') }}" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl shadow-md shadow-blue-600/30 transition-all inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Gửi báo cáo mới</span>
            </a>
        </div>
    </div>

    <!-- Summary KPI Cards Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        @foreach($summary as $label => $value)
            @php
                $translatedLabel = match($label) {
                    'Total' => 'Tổng số báo cáo',
                    'Pending' => 'Chờ xác minh',
                    'Verified' => 'Đã xác minh',
                    'Resolved' => 'Đã hoàn tất',
                    'Rejected' => 'Bị từ chối',
                    default => $label,
                };
                $iconColor = match($label) {
                    'Total' => 'bg-blue-500/10 text-blue-600',
                    'Pending' => 'bg-amber-500/10 text-amber-600',
                    'Verified' => 'bg-emerald-500/10 text-emerald-600',
                    'Resolved' => 'bg-indigo-500/10 text-indigo-600',
                    'Rejected' => 'bg-rose-500/10 text-rose-600',
                    default => 'bg-slate-100 text-slate-600',
                };
            @endphp
            <div class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-500">{{ $translatedLabel }}</p>
                    <p class="text-2xl md:text-3xl font-extrabold text-slate-900 mt-1">{{ number_format($value) }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl flex items-center justify-center font-bold text-lg {{ $iconColor }}">
                    #
                </div>
            </div>
        @endforeach
    </div>

    <!-- Filter & Data Table Panel -->
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
                        <th class="py-3.5 px-4">Hành vi vi phạm</th>
                        <th class="py-3.5 px-4">Thời gian xảy ra</th>
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
                            <td class="py-4 px-4 font-semibold text-slate-800">{{ $report->violation_type }}</td>
                            <td class="py-4 px-4 text-slate-600 text-xs">{{ $report->violated_at->format('d/m/Y H:i') }}</td>
                            <td class="py-4 px-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-extrabold {{ $badgeClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <a href="{{ route('citizen.reports.show', $report) }}" class="inline-flex items-center gap-1 text-xs font-bold text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-colors">
                                    <span>Xem chi tiết</span>
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                <p class="font-semibold text-slate-600 text-base">Bạn chưa gửi báo cáo nào</p>
                                <p class="text-xs text-slate-400 mt-1">Gửi báo cáo ngay nếu phát hiện hành vi vi phạm giao thông</p>
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
