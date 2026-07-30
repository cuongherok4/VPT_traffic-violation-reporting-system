<x-layouts.app title="Gửi báo cáo vi phạm">
    <!-- Header -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 mb-2">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Báo cáo công dân
            </span>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">Gửi báo cáo vi phạm giao thông</h1>
            <p class="text-slate-500 text-sm mt-1">Cung cấp thông tin và hình ảnh bằng chứng chính xác để cơ quan chức năng tiến hành xác minh.</p>
        </div>
        <div>
            <a href="{{ route('citizen.reports.index') }}" class="px-4 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-800 font-bold text-sm rounded-xl transition-all inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Báo cáo của tôi</span>
            </a>
        </div>
    </div>

    <!-- Main Form Panel -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 md:p-8">
        <form method="POST" action="{{ route('citizen.reports.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Block 1: Vehicle & Violation Details -->
            <div class="space-y-4">
                <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-blue-600 text-white text-xs font-black flex items-center justify-center">1</span>
                    Thông tin phương tiện & Hành vi
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="license_plate" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Biển số xe vi phạm <span class="text-rose-500">*</span></label>
                        <input id="license_plate" name="license_plate" value="{{ old('license_plate') }}" required
                               placeholder="Ví dụ: 30A-99999"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-semibold uppercase text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>

                    <div>
                        <label for="violation_type" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Loại hành vi vi phạm <span class="text-rose-500">*</span></label>
                        <input id="violation_type" name="violation_type" value="{{ old('violation_type') }}" list="violation_type_suggestions" required
                               placeholder="Ví dụ: Vượt đèn đỏ, Chạy quá tốc độ, Đi ngược chiều..."
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        <datalist id="violation_type_suggestions">
                            @foreach($violationTypes as $type)
                                <option value="{{ $type['name'] }}">
                            @endforeach
                        </datalist>
                        <p id="fine-suggestion" class="text-[11px] text-blue-600 font-bold mt-1 hidden"></p>
                    </div>

                    <div>
                        <label for="location" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Địa điểm xảy ra vi phạm <span class="text-rose-500">*</span></label>
                        <input id="location" name="location" value="{{ old('location') }}" required
                               placeholder="Ví dụ: Ngã tư Nguyễn Trãi - Khuất Duy Tiến, Hà Nội"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>

                    <div>
                        <label for="violated_at" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Thời gian xảy ra vi phạm <span class="text-rose-500">*</span></label>
                        <input id="violated_at" name="violated_at" type="datetime-local" value="{{ old('violated_at', now()->format('Y-m-d\\TH:i')) }}" max="{{ now()->format('Y-m-d\\TH:i') }}" required
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>
                </div>
            </div>

            <!-- Block 2: Description -->
            <div class="space-y-4 pt-2">
                <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-blue-600 text-white text-xs font-black flex items-center justify-center">2</span>
                    Mô tả diễn biến chi tiết
                </h2>

                <div>
                    <label for="description" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Nội dung mô tả <span class="text-rose-500">*</span></label>
                    <textarea id="description" name="description" rows="4" required
                              placeholder="Mô tả cụ thể hướng di chuyển của xe, đặc điểm phương tiện hoặc bất kỳ thông tin nhận dạng quan trọng nào..."
                              class="w-full p-4 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">{{ old('description') }}</textarea>
                </div>
            </div>

            <!-- Block 3: Evidence Upload with Live Preview JS -->
            <div class="space-y-4 pt-2">
                <h2 class="text-base font-bold text-slate-900 border-b border-slate-100 pb-3 flex items-center gap-2">
                    <span class="w-6 h-6 rounded-full bg-blue-600 text-white text-xs font-black flex items-center justify-center">3</span>
                    Hình ảnh bằng chứng
                </h2>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Tải lên ảnh chụp (JPEG, PNG, WEBP)</label>
                    
                    <div class="relative border-2 border-dashed border-slate-300 hover:border-blue-500 bg-slate-50 hover:bg-blue-50/30 rounded-2xl p-6 text-center transition-all cursor-pointer group"
                         onclick="document.getElementById('evidence').click()">
                        <input id="evidence" name="evidence" type="file" accept="image/jpeg,image/png,image/webp" class="hidden" onchange="previewImage(event)">
                        
                        <div id="upload-placeholder" class="space-y-2">
                            <div class="w-12 h-12 rounded-full bg-blue-100 text-blue-600 flex items-center justify-center mx-auto group-hover:scale-110 transition-transform">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <p class="text-sm font-bold text-slate-700">Nhấp vào đây để chọn ảnh hoặc kéo thả vào ô này</p>
                            <p class="text-xs text-slate-400">Dung lượng tối đa 10MB (.jpg, .png, .webp)</p>
                        </div>

                        <div id="image-preview-container" class="hidden space-y-2">
                            <img id="image-preview" src="#" alt="Ảnh xem trước" class="max-h-64 mx-auto rounded-xl shadow-md border border-slate-200 object-cover">
                            <p class="text-xs font-semibold text-emerald-600 flex items-center justify-center gap-1">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                <span>Đã chọn ảnh thành công. Nhấp để thay đổi ảnh khác.</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                <a href="{{ route('citizen.reports.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm rounded-xl transition-all">
                    Hủy bỏ
                </a>
                <button type="submit" class="px-8 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl shadow-lg shadow-blue-600/30 transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <span>Gửi báo cáo</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Live Preview Script -->
    <script>
        const violationFineMap = @json(collect($violationTypes)->pluck('fine_amount', 'name'));
        const violationTypeInput = document.getElementById('violation_type');
        const fineSuggestion = document.getElementById('fine-suggestion');

        function formatVnd(amount) {
            return new Intl.NumberFormat('vi-VN').format(amount) + ' VNĐ';
        }

        function updateFineSuggestion() {
            const fine = violationFineMap[violationTypeInput.value];

            fineSuggestion.textContent = fine ? 'Mức phạt gợi ý: ' + formatVnd(fine) : '';
            fineSuggestion.classList.toggle('hidden', !fine);
        }

        violationTypeInput.addEventListener('input', updateFineSuggestion);
        updateFineSuggestion();

        function previewImage(event) {
            const input = event.target;
            const placeholder = document.getElementById('upload-placeholder');
            const previewContainer = document.getElementById('image-preview-container');
            const previewImage = document.getElementById('image-preview');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    placeholder.classList.add('hidden');
                    previewContainer.classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-layouts.app>
