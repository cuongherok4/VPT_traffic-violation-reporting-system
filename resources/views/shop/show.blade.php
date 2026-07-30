<x-layouts.app title="{{ $product->name }}">
    <!-- Topbar Header -->
    <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 mb-2">
                {{ $product->category?->name ?? 'Thiết bị an toàn' }}
            </span>
            <h1 class="text-2xl md:text-3xl font-extrabold text-slate-900 tracking-tight">{{ $product->name }}</h1>
            <p class="text-slate-500 text-sm mt-1">{{ $product->description }}</p>
        </div>
        <div>
            <a href="{{ route('shop.index') }}" class="px-4 py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-800 font-bold text-sm rounded-xl transition-all inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                <span>Quay lại cửa hàng</span>
            </a>
        </div>
    </div>

    <!-- Product Detail & Order Grid -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200/80 p-6 md:p-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-start">
            
            <!-- Left: Product Image -->
            <div class="bg-slate-100 rounded-2xl overflow-hidden aspect-square border border-slate-200 flex items-center justify-center relative">
                @if($product->image_url)
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                @else
                    <div class="text-center text-slate-400 p-8">
                        <svg class="w-16 h-16 mx-auto mb-2 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 11h14l1 12H4L5 11z"/></svg>
                        <p class="text-sm font-semibold">Hình ảnh sản phẩm VPT</p>
                    </div>
                @endif
                <span class="absolute top-4 right-4 px-3 py-1 bg-emerald-500 text-white font-extrabold text-xs rounded-full shadow-md">
                    Còn kho: {{ $product->stock }}
                </span>
            </div>

            <!-- Right: Order Form & Price Card -->
            <div class="space-y-6">
                <div class="p-6 bg-slate-50 rounded-2xl border border-slate-200">
                    <span class="text-xs font-bold uppercase tracking-wider text-slate-500 block mb-1">Giá bán niêm yết</span>
                    <p class="text-3xl font-black text-blue-600">
                        {{ number_format($product->price, 0, ',', '.') }} VNĐ
                    </p>
                </div>

                <form method="POST" action="{{ route('shop.order', $product) }}" class="space-y-4">
                    @csrf

                    <div>
                        <label for="quantity" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Số lượng đặt mua</label>
                        <input id="quantity" name="quantity" type="number" value="1" min="1" max="{{ max($product->stock, 1) }}"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-bold text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="payment_method" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Hình thức thanh toán</label>
                        <select id="payment_method" name="payment_method" required class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium text-slate-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="cod">Thanh toán khi nhận hàng (COD)</option>
                            <option value="bank_transfer">Chuyển khoản ngân hàng</option>
                        </select>
                    </div>

                    <div class="pt-4">
                        @auth
                            <button type="submit" @disabled($product->stock < 1) class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 disabled:bg-slate-300 text-white font-bold text-base rounded-xl shadow-md shadow-blue-600/30 transition-all flex items-center justify-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 11h14l1 12H4L5 11z"/></svg>
                                <span>Xác nhận đặt hàng</span>
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="w-full py-3.5 bg-slate-900 hover:bg-slate-800 text-white font-bold text-sm rounded-xl shadow-md transition-all flex items-center justify-center gap-2">
                                <span>Đăng nhập để đặt hàng</span>
                            </a>
                        @endauth
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-layouts.app>
