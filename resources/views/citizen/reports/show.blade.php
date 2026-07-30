<x-layouts.app title="Chi tiết báo cáo #{{ $report->id }}">
    <!-- Topbar -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-blue-100 text-blue-800">
                    Mã Báo Cáo: #{{ $report->id }}
                </span>
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
                <span class="px-2.5 py-0.5 rounded-full text-xs font-extrabold {{ $badgeClass }}">
                    {{ $statusLabel }}
                </span>
            </div>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight flex items-center gap-3">
                <span class="px-3 py-1 bg-amber-50 text-amber-900 border border-amber-300 font-extrabold rounded-lg text-lg font-mono">
                    {{ $report->license_plate }}
                </span>
                <span>{{ $report->violation_type }}</span>
            </h1>
            <p class="text-slate-500 text-sm mt-1">Xảy ra tại: {{ $report->location }}</p>
        </div>
        <div>
            <a href="{{ route('citizen.reports.index') }}" class="px-4 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-800 font-bold text-sm rounded-xl transition-all inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Quay lại danh sách</span>
            </a>
        </div>
    </div>

    <!-- Details Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Info (2 Columns) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6">
                <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3 mb-4">Thông tin xử lý chi tiết</h2>
                
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                        <dt class="text-xs font-bold text-slate-500 uppercase tracking-wider">Thời gian vi phạm</dt>
                        <dd class="text-slate-900 font-semibold mt-1">{{ $report->violated_at->format('d/m/Y H:i') }}</dd>
                    </div>

                    <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                        <dt class="text-xs font-bold text-slate-500 uppercase tracking-wider">Mức phạt quy định</dt>
                        <dd class="text-slate-900 font-extrabold mt-1 text-base text-blue-600">
                            {{ number_format($report->fine_amount, 0, ',', '.') }} VNĐ
                        </dd>
                    </div>

                    <div class="sm:col-span-2 bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                        <dt class="text-xs font-bold text-slate-500 uppercase tracking-wider">Nội dung mô tả</dt>
                        <dd class="text-slate-800 font-medium mt-1 leading-relaxed">{{ $report->description }}</dd>
                    </div>

                    <div class="sm:col-span-2 bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                        <dt class="text-xs font-bold text-slate-500 uppercase tracking-wider">Thông tin biên lai xử phạt</dt>
                        <dd class="text-slate-900 font-semibold mt-1">
                            @if($report->fineReceipt)
                                <span class="text-emerald-700">Trạng thái: {{ $report->fineReceipt->payment_status }}</span>
                                <span class="text-slate-400 mx-2">|</span>
                                <span>Hạn nộp: {{ $report->fineReceipt->due_at?->format('d/m/Y') }}</span>
                            @else
                                <span class="text-slate-400 italic">Chưa phát hành biên lai phạt</span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Evidence Sidebar (1 Column) -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6">
                <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3 mb-4">Hình ảnh bằng chứng</h2>
                
                @if($report->evidence_url)
                    <div class="overflow-hidden rounded-xl border border-slate-200 bg-slate-900 shadow-inner group relative">
                        <img src="{{ $report->evidence_url }}" alt="Bằng chứng vi phạm" class="w-full object-cover max-h-96 group-hover:scale-105 transition-transform duration-300">
                    </div>
                @else
                    <div class="p-8 bg-slate-50 rounded-xl border border-dashed border-slate-300 text-center text-slate-400">
                        <svg class="w-10 h-10 mx-auto mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <p class="text-xs font-semibold">Chưa có ảnh bằng chứng</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app>
