<x-layouts.app title="Cửa hàng an toàn giao thông">
    <!-- Hero Banner -->
    <div class="mb-8 bg-gradient-to-r from-slate-900 via-blue-950 to-slate-900 text-white p-6 md:p-8 rounded-2xl shadow-xl relative overflow-hidden">
        <div class="relative z-10 max-w-3xl">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-500/20 text-emerald-300 border border-emerald-400/20 mb-3">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 11h14l1 12H4L5 11z"/></svg>
                Thiết Bị Đạt Chuẩn
            </span>
            <h1 class="text-2xl md:text-3xl font-extrabold tracking-tight text-white mb-2">Cửa Hàng Thiết Bị An Toàn Giao Thông</h1>
            <p class="text-slate-300 text-sm md:text-base leading-relaxed">
                Cung cấp mũ bảo hiểm chuẩn Cục CSGT, camera hành trình, gương phản quang và thiết bị bảo vệ chính hãng dành cho công dân.
            </p>
        </div>
    </div>

    <!-- Search & Category Filter -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 mb-8">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">
            <div class="md:col-span-2">
                <label for="q" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Tìm kiếm sản phẩm</label>
                <input id="q" name="q" value="{{ request('q') }}" placeholder="Mũ bảo hiểm, camera hành trình..."
                       class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div>
                <label for="category_id" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Danh mục sản phẩm</label>
                <select id="category_id" name="category_id" class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <option value="">Tất cả danh mục</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" @selected((string) request('category_id') === (string) $category->id)>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-3 flex justify-end">
                <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl shadow-md shadow-blue-600/20 transition-all">
                    Lọc sản phẩm
                </button>
            </div>
        </form>
    </div>

    <!-- Product E-Commerce Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        @forelse($products as $product)
            <article class="bg-white rounded-2xl shadow-sm border border-slate-200/80 overflow-hidden flex flex-col hover:shadow-md transition-shadow">
                <!-- Product Thumbnail Container -->
                <div class="bg-slate-100 aspect-video relative flex items-center justify-center border-b border-slate-100 overflow-hidden">
                    @if($product->image_url)
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="text-slate-300 flex flex-col items-center">
                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 11h14l1 12H4L5 11z"/></svg>
                            <span class="text-xs mt-1 font-semibold">VPT Safety</span>
                        </div>
                    @endif
                    <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-[11px] font-extrabold bg-slate-900/80 text-white backdrop-blur-md">
                        {{ $product->category?->name ?? 'An toàn' }}
                    </span>
                </div>

                <!-- Content Body -->
                <div class="p-5 flex-1 flex flex-col justify-between space-y-4">
                    <div>
                        <h2 class="font-bold text-base text-slate-900 leading-snug hover:text-blue-600 transition-colors">
                            {{ $product->name }}
                        </h2>
                        <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed">
                            {{ $product->description }}
                        </p>
                    </div>

                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between">
                        <div>
                            <span class="text-xs text-slate-400 font-semibold block">Đơn giá</span>
                            <span class="text-lg font-black text-blue-600">
                                {{ number_format($product->price, 0, ',', '.') }} đ
                            </span>
                        </div>

                        <a href="{{ route('shop.show', $product) }}" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-xs transition-all">
                            Xem & Đặt Mua
                        </a>
                    </div>
                </div>
            </article>
        @empty
            <div class="col-span-full py-16 text-center bg-white rounded-2xl border border-slate-200">
                <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                <p class="font-semibold text-slate-600">Chưa tìm thấy sản phẩm phù hợp</p>
                <p class="text-xs text-slate-400 mt-1">Vui lòng thử lại với từ khóa tìm kiếm hoặc danh mục khác</p>
            </div>
        @endforelse
    </div>

    @if($products->hasPages())
        <div class="p-4 bg-white rounded-2xl border border-slate-200">
            {{ $products->links() }}
        </div>
    @endif
</x-layouts.app>
