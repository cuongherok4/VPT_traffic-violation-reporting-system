<x-layouts.app title="Đăng ký tài khoản">
    <div class="max-w-xl mx-auto my-6 md:my-10">
        <div class="bg-white rounded-2xl shadow-xl border border-slate-200/80 overflow-hidden">
            <!-- Header Card -->
            <div class="bg-gradient-to-r from-slate-900 to-blue-950 p-6 text-white text-center relative overflow-hidden">
                <div class="w-12 h-12 rounded-2xl bg-blue-600/30 border border-blue-400/30 flex items-center justify-center mx-auto mb-3 text-white font-black text-xl shadow-lg">
                    VPT
                </div>
                <h1 class="text-2xl font-extrabold tracking-tight">Tạo Tài Khoản Công Dân</h1>
                <p class="text-slate-300 text-xs mt-1">Đăng ký để gửi báo cáo vi phạm và theo dõi kết quả xử lý</p>
            </div>

            <!-- Form Body -->
            <div class="p-6 md:p-8">
                <form method="POST" action="{{ route('register.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @csrf
                    <div class="md:col-span-2">
                        <label for="name" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Họ và tên</label>
                        <input id="name" name="name" value="{{ old('name') }}" required
                               placeholder="Nguyễn Văn A"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>

                    <div>
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email') }}" required
                               placeholder="email@example.com"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>

                    <div>
                        <label for="phone" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Số điện thoại</label>
                        <input id="phone" name="phone" value="{{ old('phone') }}"
                               placeholder="0912345678"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>

                    <div class="md:col-span-2">
                        <label for="address" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Địa chỉ liên hệ</label>
                        <input id="address" name="address" value="{{ old('address') }}"
                               placeholder="Số 10, Đường ABC, Quận XYZ, Hà Nội"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Mật khẩu</label>
                        <input id="password" name="password" type="password" required
                               placeholder="••••••••"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Xác nhận mật khẩu</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                               placeholder="••••••••"
                               class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                    </div>

                    <div class="md:col-span-2 pt-2">
                        <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl shadow-md shadow-blue-600/30 transition-all flex items-center justify-center gap-2">
                            <span>Đăng ký ngay</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </div>
                </form>

                <div class="mt-6 pt-6 border-t border-slate-100 text-center">
                    <p class="text-xs text-slate-500 mb-2">Đã có tài khoản hệ thống?</p>
                    <a href="{{ route('login') }}" class="inline-block px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs rounded-lg transition-all">
                        Đăng nhập tại đây
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
