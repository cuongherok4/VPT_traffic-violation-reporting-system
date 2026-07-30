<x-layouts.app title="Xử lý báo cáo #{{ $report->id }}">
    <!-- Topbar -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-2">
                <span class="px-2.5 py-0.5 rounded-full text-xs font-extrabold bg-blue-100 text-blue-800">
                    Báo cáo #{{ $report->id }}
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
            <a href="{{ route('admin.reports.index') }}" class="px-4 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-800 font-bold text-sm rounded-xl transition-all inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Quay lại hàng chờ</span>
            </a>
        </div>
    </div>

    <!-- Grid 2 Columns -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Details & Evidence (2 Columns) -->
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6">
                <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3 mb-4">Chi tiết thông tin báo cáo</h2>

                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                    <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                        <dt class="text-xs font-bold text-slate-500 uppercase tracking-wider">Người gửi báo cáo</dt>
                        <dd class="text-slate-900 font-semibold mt-1">
                            {{ $report->reporter?->name ?? 'Ẩn danh' }}
                            @if($report->reporter?->email)
                                <span class="text-slate-400 font-normal">({{ $report->reporter->email }})</span>
                            @endif
                        </dd>
                    </div>

                    <div class="bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                        <dt class="text-xs font-bold text-slate-500 uppercase tracking-wider">Thời gian vi phạm</dt>
                        <dd class="text-slate-900 font-semibold mt-1">{{ $report->violated_at->format('d/m/Y H:i') }}</dd>
                    </div>

                    <div class="sm:col-span-2 bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                        <dt class="text-xs font-bold text-slate-500 uppercase tracking-wider">Nội dung mô tả</dt>
                        <dd class="text-slate-800 font-medium mt-1 leading-relaxed">{{ $report->description }}</dd>
                    </div>

                    <div class="sm:col-span-2 bg-slate-50 p-3.5 rounded-xl border border-slate-100">
                        <dt class="text-xs font-bold text-slate-500 uppercase tracking-wider">Thông tin biên lai nộp phạt</dt>
                        <dd class="text-slate-900 font-semibold mt-1">
                            @if($report->fineReceipt)
                                <span class="text-blue-600 font-bold">{{ number_format($report->fineReceipt->amount, 0, ',', '.') }} VNĐ</span>
                                <span class="text-slate-400 mx-2">|</span>
                                <span class="text-emerald-700">Trạng thái: {{ $report->fineReceipt->payment_status }}</span>
                            @else
                                <span class="text-slate-400 italic">Chưa tạo biên lai phạt</span>
                            @endif
                        </dd>
                    </div>
                </dl>
            </div>

            <!-- Evidence Photo Container -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6">
                <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3 mb-4">Hình ảnh bằng chứng đính kèm</h2>
                @if($report->evidence_url)
                    <div class="overflow-hidden rounded-xl border border-slate-200 bg-slate-900 shadow-inner">
                        <img src="{{ $report->evidence_url }}" alt="Bằng chứng" class="w-full object-cover max-h-96">
                    </div>
                @else
                    <div class="p-8 bg-slate-50 rounded-xl border border-dashed border-slate-300 text-center text-slate-400">
                        <p class="text-xs font-semibold">Chưa có hình ảnh bằng chứng</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Action Form Card (1 Column) -->
        <div class="space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 sticky top-8">
                <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3 mb-4 flex items-center gap-2">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    Cập Nhật Trạng Thái & Mức Phạt
                </h2>

                <form method="POST" action="{{ route('admin.reports.update', $report) }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label for="status" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Trạng thái mới <span class="text-rose-500">*</span></label>
                        <select id="status" name="status" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-semibold text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @foreach($statuses as $status)
                                @php
                                    $labelSt = match($status) {
                                        'pending' => 'Chờ xác minh (pending)',
                                        'verified' => 'Đã xác minh (verified)',
                                        'rejected' => 'Bị từ chối (rejected)',
                                        'resolved' => 'Đã hoàn tất (resolved)',
                                        default => $status,
                                    };
                                @endphp
                                <option value="{{ $status }}" @selected($report->status->value === $status)>{{ $labelSt }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="fine_amount" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Mức tiền phạt (VNĐ) <span class="text-rose-500">*</span></label>
                        <input id="fine_amount" name="fine_amount" type="number" min="0" value="{{ old('fine_amount', $report->fine_amount) }}" required
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-extrabold text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <div class="mt-2 flex flex-wrap items-center gap-2">
                            <p id="fine-suggestion" class="text-[11px] text-blue-600 font-bold"></p>
                            <button id="apply-suggested-fine" type="button" class="hidden px-2.5 py-1 rounded-lg bg-blue-50 hover:bg-blue-100 text-[11px] font-extrabold text-blue-700">
                                Áp dụng mức gợi ý
                            </button>
                        </div>
                        <p class="text-[11px] text-slate-400 mt-1">Nhập 0 nếu không áp dụng mức phạt tiền</p>
                    </div>

                    <div>
                        <label for="description" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Nội dung mô tả <span class="text-rose-500">*</span></label>
                        <textarea id="description" name="description" rows="5" required
                                  class="w-full p-4 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description', $report->description) }}</textarea>
                        <p class="text-[11px] text-slate-400 mt-1">Ghi rõ hành vi, tình huống và mức phạt để tra cứu hiển thị đầy đủ.</p>
                    </div>

                    <div>
                        <label for="evidence" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Thay ảnh bằng chứng</label>
                        <input id="evidence" name="evidence" type="file" accept="image/jpeg,image/png,image/webp"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs font-semibold text-slate-700 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <p class="text-[11px] text-slate-400 mt-1">Chọn ảnh JPG, PNG hoặc WEBP tối đa 5MB. Bỏ trống nếu giữ ảnh hiện tại.</p>
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl shadow-md shadow-blue-600/30 transition-all flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Lưu Thay Đổi</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <script>
        const violationFineMap = @json(collect($violationTypes)->pluck('fine_amount', 'name'));
        const currentViolationType = @json($report->violation_type);
        const suggestedFine = violationFineMap[currentViolationType];
        const fineInput = document.getElementById('fine_amount');
        const fineSuggestion = document.getElementById('fine-suggestion');
        const applySuggestedFine = document.getElementById('apply-suggested-fine');

        function formatVnd(amount) {
            return new Intl.NumberFormat('vi-VN').format(amount) + ' VNĐ';
        }

        if (suggestedFine) {
            fineSuggestion.textContent = 'Mức phạt theo hành vi: ' + formatVnd(suggestedFine);
            applySuggestedFine.classList.remove('hidden');
            applySuggestedFine.addEventListener('click', () => {
                fineInput.value = suggestedFine;
            });
        }
    </script>
</x-layouts.app>
