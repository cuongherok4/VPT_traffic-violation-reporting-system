<x-layouts.app title="Thêm sản phẩm mới">
    <!-- Topbar -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-900 border border-amber-300 mb-2">
                Quản trị sản phẩm
            </span>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">Thêm Sản Phẩm An Toàn Mới</h1>
            <p class="text-slate-500 text-sm mt-1">Nhập thông tin sản phẩm thiết bị an toàn để phục vụ công dân đặt mua trên cửa hàng.</p>
        </div>
        <div>
            <a href="{{ route('admin.products.index') }}" class="px-4 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-800 font-bold text-sm rounded-xl transition-all inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Quay lại danh sách</span>
            </a>
        </div>
    </div>

    <!-- Form Panel -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 md:p-8 max-w-3xl mx-auto">
        <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Tên sản phẩm <span class="text-rose-500">*</span></label>
                    <input id="name" name="name" value="{{ old('name') }}" required placeholder="Ví dụ: Mũ bảo hiểm Royal chuẩn Cục CSGT"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-bold text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="category_id" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Danh mục sản phẩm <span class="text-rose-500">*</span></label>
                    <select id="category_id" name="category_id" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Chọn danh mục</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) old('category_id') === (string) $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="price" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Đơn giá (VNĐ) <span class="text-rose-500">*</span></label>
                    <input id="price" name="price" type="number" min="0" value="{{ old('price', 0) }}" required placeholder="250000"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-bold text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="stock" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Số lượng tồn kho <span class="text-rose-500">*</span></label>
                    <input id="stock" name="stock" type="number" min="0" value="{{ old('stock', 10) }}" required placeholder="10"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-bold text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="image" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Hình ảnh sản phẩm</label>
                    <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp"
                           class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="md:col-span-2">
                    <label for="description" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Mô tả sản phẩm</label>
                    <textarea id="description" name="description" rows="4" placeholder="Mô tả các thông số kỹ thuật, chất liệu, chứng nhận an toàn..."
                              class="w-full p-4 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">{{ old('description') }}</textarea>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end gap-3">
                <a href="{{ route('admin.products.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm rounded-xl">
                    Hủy bỏ
                </a>
                <button type="submit" class="px-8 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl shadow-md shadow-blue-600/30">
                    Lưu sản phẩm
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
