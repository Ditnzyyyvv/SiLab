<!doctype html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body class="min-h-screen bg-slate-100 flex items-center justify-center p-4">
    <div class="w-full max-w-5xl rounded-[2rem] bg-white shadow-[0_25px_80px_rgba(15,23,42,0.12)] overflow-hidden">
        <div class="grid lg:grid-cols-2 min-h-[700px]">
            <div
                class="hidden lg:flex bg-gradient-to-br from-sky-700 via-blue-800 to-indigo-950 p-10 text-white items-center justify-center">
                <div class="max-w-md">
                    <p class="text-sm uppercase tracking-[0.35em] text-blue-200">SiLab</p>
                    <h1 class="mt-6 text-4xl font-bold leading-tight">Pencatatan & Pemeliharaan Aset</h1>
                    <p class="mt-4 text-blue-100 text-base">Buat pekerjaan Anda menjadi lebih efisien dengan sistem yang
                        terorganisir.</p>
                </div>
            </div>

            <div class="flex items-center justify-center bg-white p-8 md:p-12">
                <div class="w-full max-w-md">
                    <div class="mb-8">
                        <h2 class="text-3xl font-bold text-slate-900">Welcome Back</h2>
                    </div>

                    <form class="space-y-6">
                        <div>
                            <label class="block text-base font-semibold text-slate-800 mb-2">Email</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                    <i class="fa-solid fa-envelope"></i>
                                </span>
                                <input type="email" placeholder="Masukkan Email Anda"
                                    class="w-full rounded-2xl border border-slate-300 bg-slate-50 py-3 pl-12 pr-4 text-base text-slate-800 placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                            </div>
                        </div>

                        <div>
                            <label class="block text-base font-semibold text-slate-800 mb-2">Password</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.8">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M7 10V7a5 5 0 0110 0v3m-9 0h10a2 2 0 012 2v6a2 2 0 01-2 2H8a2 2 0 01-2-2v-6a2 2 0 012-2z" />
                                    </svg>
                                </span>
                                <input type="password" placeholder="Enter your Password"
                                    class="w-full rounded-2xl border border-slate-300 bg-slate-50 py-3 pl-12 pr-4 text-base text-slate-800 placeholder:text-slate-400 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-200">
                            </div>
                        </div>

                        <div class="flex items-center justify-between">
                            <label class="inline-flex items-center">
                                <input type="checkbox"
                                    class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                <span class="ml-2 text-sm text-slate-700">Remember me</span>
                            </label>
                            <a href="#" class="text-sm font-medium text-blue-600 hover:underline">Forgot
                                password?</a>
                        </div>

                        <button type="submit"
                            class="w-full rounded-2xl bg-slate-900 px-4 py-3 text-lg font-semibold text-white shadow-lg shadow-slate-900/20 transition hover:bg-slate-800">
                            Sign In
                        </button>
                    </form>

                    <div class="mt-6 text-center">
                        <p class="text-sm text-slate-600">
                            Don’t have an account?
                            <a href="#" class="font-semibold text-blue-600 hover:underline">Sign Up</a>
                        </p>
                    </div>

                    <div class="mt-8">
                        <div class="relative flex items-center justify-center">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-slate-200"></div>
                            </div>
                            <span class="relative bg-white px-3 text-sm text-slate-400">Or With</span>
                        </div>

                        <div class="mt-5 grid grid-cols-1 gap-3">
                            <button type="button"
                                class="flex items-center justify-center gap-3 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-base font-medium text-slate-700 shadow-sm hover:bg-slate-50">
                                <svg viewBox="0 0 48 48" class="h-5 w-5" aria-hidden="true">
                                    <path fill="#EA4335"
                                        d="M24 9.5c3.54 0 6.7 1.22 9.19 3.63l6.85-6.85C35.96 2.37 30.59 0 24 0 14.64 0 6.65 5.38 2.6 13.22l7.98 6.19C12.36 13.48 17.53 9.5 24 9.5Z" />
                                    <path fill="#4285F4"
                                        d="M46.5 24.55c0-1.64-.15-3.21-.43-4.73H24v9h12.82c-.55 2.98-2.24 5.5-4.77 7.2l7.72 5.99C43.78 37.04 46.5 31.62 46.5 24.55Z" />
                                    <path fill="#FBBC05"
                                        d="M32.05 36.02c-2.15 1.44-4.9 2.3-8.05 2.3-6.47 0-11.96-4.37-13.9-10.24l-8.06 6.26C4.54 42.72 13.59 48 24 48c6.17 0 11.35-2.03 15.13-5.5l-7.08-6.48Z" />
                                    <path fill="#34A853"
                                        d="M10.1 28.08A14.48 14.48 0 019.5 24c0-1.56.27-3.07.75-4.52L2.6 13.22A23.88 23.88 0 000 24c0 3.8.9 7.39 2.5 10.54l7.6-6.46Z" />
                                </svg>
                                Google
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
