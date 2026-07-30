<x-layouts.app title="Đăng nhập">
    <div class="max-w-md mx-auto my-6 md:my-12">
        <div class="bg-white rounded-2xl shadow-xl border border-slate-200/80 overflow-hidden">
            <!-- Header Card -->
            <div class="bg-gradient-to-r from-slate-900 to-blue-950 p-6 text-white text-center relative overflow-hidden">
                <div class="w-12 h-12 rounded-2xl bg-blue-600/30 border border-blue-400/30 flex items-center justify-center mx-auto mb-3 text-white font-black text-xl shadow-lg">
                    VPT
                </div>
                <h1 class="text-2xl font-extrabold tracking-tight">Đăng nhập Hệ thống</h1>
                <p class="text-slate-300 text-xs mt-1">Cổng dịch vụ báo cáo & xử lý vi phạm giao thông</p>
            </div>

            <!-- Form Body -->
            <div class="p-6 md:p-8">
                <form method="POST" action="{{ route('login.store') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label for="email" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Địa chỉ Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/></svg>
                            </div>
                            <input id="email" name="email" type="email" value="{{ old('email') }}" required autocomplete="email"
                                   placeholder="email@example.com"
                                   class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-bold uppercase tracking-wider text-slate-600 mb-1.5">Mật khẩu</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            </div>
                            <input id="password" name="password" type="password" required autocomplete="current-password"
                                   placeholder="••••••••"
                                   class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-sm font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-1">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input name="remember" type="checkbox" value="1" class="w-4 h-4 rounded text-blue-600 focus:ring-blue-500 border-slate-300">
                            <span class="text-xs text-slate-600 font-medium">Ghi nhớ đăng nhập</span>
                        </label>
                    </div>

                    <button type="submit" class="w-full py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold text-sm rounded-xl shadow-md shadow-blue-600/30 transition-all flex items-center justify-center gap-2">
                        <span>Đăng nhập</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </form>

                <div class="mt-6 pt-6 border-t border-slate-100 text-center">
                    <p class="text-xs text-slate-500 mb-3">Chưa có tài khoản công dân?</p>
                    <a href="{{ route('register') }}" class="inline-block px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-800 font-bold text-xs rounded-lg transition-all">
                        Tạo tài khoản mới
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
