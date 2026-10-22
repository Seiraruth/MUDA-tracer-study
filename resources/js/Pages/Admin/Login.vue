<script setup>
import { useForm, Head } from '@inertiajs/vue3';
import { GraduationCap, Lock, Mail, ArrowLeft, LogIn, AlertCircle } from 'lucide-vue-next';

const form = useForm({
    email: 'admin@tracer.smk.sch.id',
    password: 'password',
    remember: true,
});

const submit = () => {
    form.post('/admin/login', {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Masuk Admin Portal - Tracer Study SMK" />

    <div class="min-h-screen bg-slate-900 flex flex-col justify-center items-center p-4 relative overflow-hidden font-sans">
        <!-- Background Ambient Glow -->
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-indigo-600/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-blue-600/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="w-full max-w-md relative z-10">
            <!-- Brand Logo & Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-gradient-to-tr from-indigo-600 to-blue-500 text-white shadow-xl shadow-indigo-600/30 ring-1 ring-white/20 mb-4">
                    <GraduationCap class="w-7 h-7" />
                </div>
                <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">
                    Portal Admin BKK
                </h1>
                <p class="text-xs sm:text-sm text-slate-400 mt-1">
                    Tracer Study & Penelusuran Lulusan SMK
                </p>
            </div>

            <!-- Login Card -->
            <div class="bg-slate-800/80 backdrop-blur-md rounded-3xl p-6 sm:p-8 border border-slate-700/80 shadow-2xl">
                <!-- Error Alert -->
                <div
                    v-if="form.errors.email || form.errors.password"
                    class="mb-6 p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-xs flex items-start gap-3"
                >
                    <AlertCircle class="w-4 h-4 shrink-0 mt-0.5" />
                    <span>{{ form.errors.email || form.errors.password }}</span>
                </div>

                <form @submit.prevent="submit" class="space-y-4">
                    <!-- Email Field -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider">
                            Email Administrator
                        </label>
                        <div class="relative">
                            <Mail class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
                            <input
                                v-model="form.email"
                                type="email"
                                required
                                placeholder="admin@tracer.smk.sch.id"
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-900/80 border border-slate-700 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                            />
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-semibold text-slate-300 uppercase tracking-wider">
                            Kata Sandi
                        </label>
                        <div class="relative">
                            <Lock class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
                            <input
                                v-model="form.password"
                                type="password"
                                required
                                placeholder="••••••••"
                                class="w-full pl-10 pr-4 py-2.5 rounded-xl bg-slate-900/80 border border-slate-700 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                            />
                        </div>
                    </div>

                    <!-- Remember & Hint -->
                    <div class="flex items-center justify-between text-xs pt-1">
                        <label class="flex items-center gap-2 cursor-pointer text-slate-400 hover:text-slate-300">
                            <input
                                v-model="form.remember"
                                type="checkbox"
                                class="rounded bg-slate-900 border-slate-700 text-indigo-600 focus:ring-0 focus:ring-offset-0"
                            />
                            <span>Ingat sesi masuk</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full py-3 px-4 rounded-xl bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-500 hover:to-blue-500 text-white font-bold text-sm shadow-lg shadow-indigo-600/30 transition-all duration-150 flex items-center justify-center gap-2 hover:scale-[1.01] active:scale-[0.99] disabled:opacity-50 mt-2"
                    >
                        <LogIn class="w-4 h-4" />
                        <span>{{ form.processing ? 'Memproses...' : 'Masuk ke Dashboard' }}</span>
                    </button>
                </form>

                <!-- Quick Test Credentials Box -->
                <div class="mt-6 pt-5 border-t border-slate-700/60 text-center">
                    <p class="text-[11px] text-slate-400">
                        Default Demo: <span class="text-indigo-400 font-mono">admin@tracer.smk.sch.id</span> | Sandi: <span class="text-indigo-400 font-mono">password</span>
                    </p>
                </div>
            </div>

            <!-- Back to Public Site -->
            <div class="text-center mt-6">
                <a
                    href="/"
                    class="inline-flex items-center gap-2 text-xs font-medium text-slate-400 hover:text-white transition-colors"
                >
                    <ArrowLeft class="w-3.5 h-3.5" />
                    <span>Kembali ke Halaman Publik</span>
                </a>
            </div>
        </div>
    </div>
</template>
