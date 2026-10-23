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
    <Head title="Masuk Admin - Tracer Study" />

    <div class="min-h-screen bg-surface-900 flex flex-col justify-center items-center p-4">
        <div class="w-full max-w-sm relative z-10">
            <!-- Header -->
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-12 h-12 rounded-xl bg-primary-600/20 border border-primary-500/30 text-primary-400 mb-3">
                    <GraduationCap class="w-5 h-5" />
                </div>
                <h1 class="text-xl font-semibold text-white">
                    Admin Portal
                </h1>
                <p class="text-xs text-surface-400 mt-0.5">
                    Tracer Study & Bursa Kerja
                </p>
            </div>

            <!-- Login Card -->
            <div class="bg-surface-800/60 backdrop-blur-sm rounded-xl p-5 border border-surface-700/60 animate-in">
                <!-- Error Alert -->
                <div
                    v-if="form.errors.email || form.errors.password"
                    class="mb-4 p-3 rounded-lg bg-error/10 border border-error/20 text-error text-xs flex items-start gap-2"
                >
                    <AlertCircle class="w-3.5 h-3.5 shrink-0 mt-0.5" />
                    <span>{{ form.errors.email || form.errors.password }}</span>
                </div>

                <form @submit.prevent="submit" class="space-y-3">
                    <!-- Email Field -->
                    <div class="space-y-1">
                        <label class="block text-[11px] font-medium text-surface-400">
                            Email
                        </label>
                        <div class="relative">
                            <Mail class="w-4 h-4 text-surface-500 absolute left-3 top-1/2 -translate-y-1/2" />
                            <input
                                v-model="form.email"
                                type="email"
                                required
                                placeholder="admin@email.com"
                                class="w-full pl-9 pr-3 py-2 rounded-lg bg-surface-900/60 border border-surface-700 text-sm text-white placeholder-surface-500 focus:outline-none focus:border-primary-500 transition-colors"
                            />
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-1">
                        <label class="block text-[11px] font-medium text-surface-400">
                            Password
                        </label>
                        <div class="relative">
                            <Lock class="w-4 h-4 text-surface-500 absolute left-3 top-1/2 -translate-y-1/2" />
                            <input
                                v-model="form.password"
                                type="password"
                                required
                                placeholder="••••••••"
                                class="w-full pl-9 pr-3 py-2 rounded-lg bg-surface-900/60 border border-surface-700 text-sm text-white placeholder-surface-500 focus:outline-none focus:border-primary-500 transition-colors"
                            />
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="w-full py-2 px-3 rounded-lg bg-primary-600 hover:bg-primary-500 text-white font-medium text-sm transition-colors flex items-center justify-center gap-2 disabled:opacity-50 mt-2"
                    >
                        <LogIn class="w-4 h-4" />
                        <span>{{ form.processing ? 'Memproses...' : 'Masuk' }}</span>
                    </button>
                </form>

                <!-- Demo Credentials -->
                <div class="mt-4 pt-3 border-t border-surface-700/50 text-center">
                    <p class="text-[10px] text-surface-500">
                        Demo: admin@tracer.smk.sch.id | password
                    </p>
                </div>
            </div>

            <!-- Back Link -->
            <div class="text-center mt-4">
                <a
                    href="/"
                    class="inline-flex items-center gap-1 text-xs font-medium text-surface-400 hover:text-white transition-colors"
                >
                    <ArrowLeft class="w-3 h-3" />
                    <span>Kembali</span>
                </a>
            </div>
        </div>
    </div>
</template>
