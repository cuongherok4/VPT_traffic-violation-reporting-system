<x-layouts.app title="Tra cứu vi phạm giao thông">
    <!-- Page Header & Hero -->
    <div class="mb-8 bg-gradient-to-r from-slate-900 via-blue-950 to-slate-900 text-white p-6 md:p-8 rounded-2xl shadow-xl relative overflow-hidden">
        <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="relative z-10 max-w-3xl">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-500/20 text-blue-300 border border-blue-400/20 mb-3">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Hệ thống Tra cứu Quốc gia
            </span>
            <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight text-white mb-2">Tra cứu Vi phạm Giao thông</h1>
            <p class="text-slate-300 text-sm md:text-base leading-relaxed">
                Nhập mã báo cáo hoặc biển số phương tiện để kiểm tra chi tiết trạng thái xử lý, mức phạt và thông tin biên lai vi phạm giao thông.
            </p>
        </div>
    </div>

    <!-- Search Card Panel -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 mb-8">
        <form method="GET" action="{{ route('lookup.index') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <label for="report_id" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Mã báo cáo</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <span class="text-sm font-bold">#</span>
                    </div>
                    <input id="report_id" name="report_id" type="number" min="1" value="{{ request('report_id') }}" 
                           placeholder="Ví dụ: 105"
                           class="w-full pl-8 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                </div>
            </div>

            <div>
                <label for="license_plate" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Biển số xe</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1m-4-1a1 1 0 001 1h1"/></svg>
                    </div>
                    <input id="license_plate" name="license_plate" value="{{ request('license_plate') }}" 
                           placeholder="Ví dụ: 29A-12345"
                           class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 uppercase transition-all">
                </div>
            </div>

            <div class="md:col-span-2 flex items-center gap-3 pt-2">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl shadow-md shadow-blue-600/20 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <span>Tra cứu ngay</span>
                </button>
                <a href="{{ route('lookup.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm rounded-xl transition-all">
                    Làm mới
                </a>
            </div>
        </form>
    </div>

    <!-- Lookup Results -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50/50 flex items-center justify-between">
            <h2 class="text-base font-bold text-slate-800 flex items-center gap-2">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                {{ $hasLookup ? 'Kết quả tìm kiếm' : 'Vi phạm mới ghi nhận' }}
            </h2>
            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-blue-100 text-blue-700">
                {{ $reports->count() }} bản ghi
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 text-[11px] font-extrabold uppercase tracking-wider text-slate-500 bg-slate-100/60">
                        <th class="py-3.5 px-6">Mã BC</th>
                        <th class="py-3.5 px-4">Biển số xe</th>
                        <th class="py-3.5 px-4">Hành vi vi phạm</th>
                        <th class="py-3.5 px-4">Địa điểm</th>
                        <th class="py-3.5 px-4">Trạng thái</th>
                        <th class="py-3.5 px-4">Mức phạt</th>
                        <th class="py-3.5 px-6">Hạn nộp phạt</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($reports as $report)
                        @php
                            $statusVal = is_object($report->status) ? $report->status->value : $report->status;
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
                                'resolved' => 'Đã xử lý xong',
                                default => $statusVal,
                            };
                        @endphp
                        <tr class="hover:bg-blue-50/30 transition-colors">
                            <td class="py-4 px-6 font-bold text-blue-600">#{{ $report->id }}</td>
                            <td class="py-4 px-4">
                                <span class="inline-block px-2.5 py-1 bg-amber-50 text-amber-900 border border-amber-300 font-extrabold rounded-md text-xs font-mono shadow-xs">
                                    {{ $report->license_plate }}
                                </span>
                            </td>
                            <td class="py-4 px-4 font-semibold text-slate-800">{{ $report->violation_type }}</td>
                            <td class="py-4 px-4 text-slate-600 text-xs">{{ $report->location }}</td>
                            <td class="py-4 px-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-extrabold shadow-2xs {{ $badgeClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="py-4 px-4 font-bold text-slate-900">
                                {{ number_format($report->fine_amount, 0, ',', '.') }} VNĐ
                            </td>
                            <td class="py-4 px-6 text-slate-600 text-xs font-medium">
                                {{ $report->fineReceipt?->due_at ? $report->fineReceipt->due_at->format('d/m/Y') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 9.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <p class="font-semibold text-slate-600 text-base">Không tìm thấy thông tin vi phạm nào</p>
                                <p class="text-xs text-slate-400 mt-1">Vui lòng kiểm tra lại chính xác Mã báo cáo hoặc Biển số xe</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.app>
