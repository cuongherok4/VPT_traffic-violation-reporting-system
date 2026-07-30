<x-layouts.app title="Quản lý sản phẩm an toàn">
    <!-- Topbar -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-900 border border-amber-300 mb-2">
                Quản trị danh mục
            </span>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">Quản Lý Sản Phẩm An Toàn</h1>
            <p class="text-slate-500 text-sm mt-1">Thêm mới, sửa thông tin, giá bán và quản lý tồn kho các thiết bị an toàn giao thông.</p>
        </div>
        <div>
            <a href="{{ route('admin.products.create') }}" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl shadow-md shadow-blue-600/30 transition-all inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Thêm sản phẩm mới</span>
            </a>
        </div>
    </div>

    <!-- Data Panel -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden">
        <!-- Filter Form -->
        <div class="p-6 border-b border-slate-200 bg-slate-50/50">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
                <div>
                    <label for="q" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Tên sản phẩm</label>
                    <input id="q" name="q" value="{{ request('q') }}" placeholder="Tìm theo tên..."
                           class="w-full px-4 py-2 bg-white border border-slate-300 rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label for="category_id" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Danh mục</label>
                    <select id="category_id" name="category_id" class="w-full px-4 py-2 bg-white border border-slate-300 rounded-xl text-sm font-medium text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">Tất cả danh mục</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) request('category_id') === (string) $category->id)>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <button type="submit" class="w-full px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl transition-all shadow-sm">
                        Lọc danh sách
                    </button>
                </div>
            </form>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-200 text-[11px] font-extrabold uppercase tracking-wider text-slate-500 bg-slate-100/60">
                        <th class="py-3.5 px-6">ID</th>
                        <th class="py-3.5 px-4">Tên sản phẩm</th>
                        <th class="py-3.5 px-4">Danh mục</th>
                        <th class="py-3.5 px-4">Đơn giá</th>
                        <th class="py-3.5 px-4">Tồn kho</th>
                        <th class="py-3.5 px-6 text-right">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse($products as $product)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-4 px-6 font-bold text-slate-500">#{{ $product->id }}</td>
                            <td class="py-4 px-4 font-bold text-slate-900">
                                {{ $product->name }}
                            </td>
                            <td class="py-4 px-4 text-xs font-semibold text-slate-600">
                                <span class="px-2.5 py-1 bg-slate-100 rounded-md">
                                    {{ $product->category?->name ?? 'Không phân loại' }}
                                </span>
                            </td>
                            <td class="py-4 px-4 font-extrabold text-blue-600">
                                {{ number_format($product->price, 0, ',', '.') }} VNĐ
                            </td>
                            <td class="py-4 px-4 font-bold">
                                @if($product->stock > 0)
                                    <span class="text-emerald-700">{{ $product->stock }} cái</span>
                                @else
                                    <span class="text-rose-600">Hết hàng</span>
                                @endif
                            </td>
                            <td class="py-4 px-6 text-right space-x-2">
                                <a href="{{ route('admin.products.edit', $product) }}" class="inline-flex items-center gap-1 text-xs font-bold text-slate-700 hover:text-blue-600 bg-slate-100 hover:bg-blue-50 px-3 py-1.5 rounded-lg transition-colors">
                                    <span>Sửa</span>
                                </a>
                                <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="inline-block" onsubmit="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs font-bold text-rose-600 hover:text-rose-800 bg-rose-50 hover:bg-rose-100 px-3 py-1.5 rounded-lg transition-colors">
                                        Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                <p class="font-semibold text-slate-600 text-base">Chưa có sản phẩm nào trong danh mục</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="p-4 border-t border-slate-200 bg-slate-50">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</x-layouts.app>
