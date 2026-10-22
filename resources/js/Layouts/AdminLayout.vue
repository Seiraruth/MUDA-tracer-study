<script setup>
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import {
    LayoutDashboard,
    Users,
    GraduationCap,
    Briefcase,
    FileSpreadsheet,
    Menu,
    X,
    LogOut,
    ExternalLink,
    Bell,
    CheckCircle,
    AlertCircle,
    UserCircle,
    ChevronDown,
    Building2,
    BarChart3
} from 'lucide-vue-next';

defineProps({
    title: {
        type: String,
        default: 'Dashboard Admin'
    }
});

const page = usePage();
const user = computed(() => page.props.auth?.user || { name: 'Admin BKK SMK', email: 'admin@tracer.smk.sch.id' });
const flash = computed(() => page.props.flash || {});

const sidebarOpen = ref(false);
const userDropdownOpen = ref(false);

const navigation = [
    {
        name: 'Dashboard',
        href: '/admin',
        routePattern: '/admin',
        exact: true,
        icon: LayoutDashboard,
        badge: null
    },
    {
        name: 'Data Alumni',
        href: '/admin/alumni',
        routePattern: '/admin/alumni',
        icon: Users,
        badge: null
    },
    {
        name: 'Hasil Tracer Study',
        href: '/admin/tracer-responses',
        routePattern: '/admin/tracer-responses',
        icon: FileSpreadsheet,
        badge: null
    },
    {
        name: 'Lowongan Kerja (BKK)',
        href: '/admin/job-postings',
        routePattern: '/admin/job-postings',
        icon: Briefcase,
        badge: null
    }
];

const isCurrentRoute = (item) => {
    const currentPath = window.location.pathname;
    if (item.exact) {
        return currentPath === item.href || currentPath === `${item.href}/dashboard`;
    }
    return currentPath.startsWith(item.routePattern);
};
</script>

<template>
    <div class="min-h-screen bg-slate-50 font-sans flex text-slate-800">
        <!-- Mobile Sidebar Backdrop -->
        <div
            v-if="sidebarOpen"
            @click="sidebarOpen = false"
            class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-xs lg:hidden transition-opacity"
        ></div>

        <!-- Sidebar Navigation -->
        <aside
            :class="[
                'fixed inset-y-0 left-0 z-50 w-72 bg-slate-900 text-slate-200 flex flex-col transition-transform duration-300 ease-in-out lg:static lg:translate-x-0',
                sidebarOpen ? 'translate-x-0 shadow-2xl' : '-translate-x-full'
            ]"
        >
            <!-- Sidebar Header / Logo -->
            <div class="h-20 flex items-center justify-between px-6 border-b border-slate-800/80 bg-slate-950/40">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-indigo-600 to-blue-500 flex items-center justify-center text-white shadow-lg shadow-indigo-500/25 ring-1 ring-white/20">
                        <GraduationCap class="w-5 h-5" />
                    </div>
                    <div>
                        <div class="font-bold text-base text-white tracking-tight flex items-center gap-1.5">
                            BKK Tracer Study
                        </div>
                        <p class="text-xs text-slate-400 font-medium">SMK Pusat Keunggulan</p>
                    </div>
                </div>
                <button
                    @click="sidebarOpen = false"
                    class="lg:hidden p-1.5 text-slate-400 hover:text-white rounded-lg hover:bg-slate-800 transition-colors"
                >
                    <X class="w-5 h-5" />
                </button>
            </div>

            <!-- Quick Badge / System Status -->
            <div class="px-6 py-4">
                <div class="bg-slate-800/60 rounded-xl p-3 border border-slate-700/50 flex items-center gap-3">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                    </span>
                    <div class="text-xs">
                        <p class="text-slate-300 font-semibold">Tahun Aktif Tracer {{ new Date().getFullYear() }}</p>
                        <p class="text-slate-400 text-[11px]">Database Terhubung & Sinkron</p>
                    </div>
                </div>
            </div>

            <!-- Nav Links -->
            <div class="flex-1 px-4 py-2 space-y-1.5 overflow-y-auto">
                <div class="px-3 pb-2 text-[11px] font-semibold text-slate-400 uppercase tracking-wider">
                    Menu Utama
                </div>
                <Link
                    v-for="item in navigation"
                    :key="item.name"
                    :href="item.href"
                    :class="[
                        'group flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-medium transition-all duration-200',
                        isCurrentRoute(item)
                            ? 'bg-indigo-600 text-white shadow-md shadow-indigo-600/30'
                            : 'text-slate-300 hover:bg-slate-800/80 hover:text-white'
                    ]"
                >
                    <div class="flex items-center gap-3">
                        <component
                            :is="item.icon"
                            :class="[
                                'w-5 h-5 transition-transform duration-200 group-hover:scale-105',
                                isCurrentRoute(item) ? 'text-white' : 'text-slate-400 group-hover:text-slate-200'
                            ]"
                        />
                        <span>{{ item.name }}</span>
                    </div>
                    <span
                        v-if="item.badge"
                        class="px-2 py-0.5 text-xs font-semibold rounded-full bg-slate-800 text-indigo-300 border border-indigo-400/20"
                    >
                        {{ item.badge }}
                    </span>
                </Link>

                <div class="pt-6 px-3 pb-2 text-[11px] font-semibold text-slate-400 uppercase tracking-wider">
                    Tautan Eksternal
                </div>
                <a
                    href="/"
                    target="_blank"
                    class="group flex items-center justify-between px-3.5 py-2.5 rounded-xl text-sm font-medium text-slate-300 hover:bg-slate-800/80 hover:text-white transition-all duration-200"
                >
                    <div class="flex items-center gap-3">
                        <ExternalLink class="w-5 h-5 text-slate-400 group-hover:text-slate-200" />
                        <span>Portal Web Publik</span>
                    </div>
                    <span class="text-[11px] px-1.5 py-0.5 rounded bg-slate-800 text-slate-400">Baru</span>
                </a>
            </div>

            <!-- Sidebar Footer / User Profile -->
            <div class="p-4 border-t border-slate-800/80 bg-slate-950/30">
                <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-800/40 border border-slate-700/40">
                    <div class="flex items-center gap-3 overflow-hidden">
                        <div class="w-9 h-9 rounded-lg bg-indigo-500/20 border border-indigo-500/30 text-indigo-300 font-bold flex items-center justify-center shrink-0">
                            {{ user.name ? user.name.charAt(0).toUpperCase() : 'A' }}
                        </div>
                        <div class="truncate">
                            <p class="text-xs font-semibold text-white truncate">{{ user.name }}</p>
                            <p class="text-[11px] text-slate-400 truncate">{{ user.email }}</p>
                        </div>
                    </div>
                    <Link
                        href="/admin/logout"
                        method="post"
                        as="button"
                        title="Keluar"
                        class="p-1.5 rounded-lg text-slate-400 hover:text-rose-400 hover:bg-slate-700/50 transition-colors"
                    >
                        <LogOut class="w-4 h-4" />
                    </Link>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Top Bar -->
            <header class="h-20 bg-white border-b border-slate-200/80 flex items-center justify-between px-4 sm:px-8 z-30 sticky top-0 shadow-xs">
                <div class="flex items-center gap-4">
                    <button
                        @click="sidebarOpen = true"
                        class="lg:hidden p-2 text-slate-600 hover:text-slate-900 rounded-lg hover:bg-slate-100 transition-colors"
                    >
                        <Menu class="w-6 h-6" />
                    </button>
                    <div>
                        <h1 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                            <span>{{ title }}</span>
                        </h1>
                        <p class="text-xs text-slate-500 hidden sm:block">Panel Administrasi & Pengolahan Data Tracer Study</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Link to Public Website -->
                    <a
                        href="/"
                        target="_blank"
                        class="hidden sm:inline-flex items-center gap-2 px-3.5 py-2 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-xl transition-colors border border-slate-200"
                    >
                        <ExternalLink class="w-3.5 h-3.5 text-slate-500" />
                        <span>Kunjungi Situs Publik</span>
                    </a>

                    <!-- Admin Profile Dropdown Button -->
                    <div class="relative">
                        <button
                            @click="userDropdownOpen = !userDropdownOpen"
                            class="flex items-center gap-2.5 p-1.5 sm:px-3 sm:py-2 rounded-xl text-slate-700 hover:bg-slate-100 border border-slate-200/70 transition-all text-xs font-medium"
                        >
                            <div class="w-7 h-7 rounded-lg bg-indigo-600 text-white font-bold flex items-center justify-center text-xs">
                                {{ user.name ? user.name.charAt(0).toUpperCase() : 'A' }}
                            </div>
                            <span class="hidden md:inline font-semibold text-slate-800">{{ user.name }}</span>
                            <ChevronDown class="w-3.5 h-3.5 text-slate-400" />
                        </button>

                        <!-- Dropdown Menu -->
                        <div
                            v-if="userDropdownOpen"
                            @click="userDropdownOpen = false"
                            class="absolute right-0 mt-2 w-56 bg-white rounded-2xl shadow-xl border border-slate-200/80 py-2 z-50 animate-in fade-in zoom-in-95 duration-150"
                        >
                            <div class="px-4 py-2 border-b border-slate-100">
                                <p class="text-xs text-slate-400 font-medium">Masuk Sebagai</p>
                                <p class="text-sm font-bold text-slate-900 truncate">{{ user.name }}</p>
                                <p class="text-xs text-slate-500 truncate">{{ user.email }}</p>
                            </div>
                            <div class="p-1">
                                <a
                                    href="/admin/alumni"
                                    class="flex items-center gap-2.5 px-3 py-2 text-xs text-slate-700 hover:bg-slate-50 rounded-lg transition-colors"
                                >
                                    <Users class="w-4 h-4 text-slate-400" />
                                    <span>Kelola Data Alumni</span>
                                </a>
                                <a
                                    href="/admin/tracer-responses"
                                    class="flex items-center gap-2.5 px-3 py-2 text-xs text-slate-700 hover:bg-slate-50 rounded-lg transition-colors"
                                >
                                    <FileSpreadsheet class="w-4 h-4 text-slate-400" />
                                    <span>Hasil Tracer Study</span>
                                </a>
                                <a
                                    href="/admin/job-postings"
                                    class="flex items-center gap-2.5 px-3 py-2 text-xs text-slate-700 hover:bg-slate-50 rounded-lg transition-colors"
                                >
                                    <Briefcase class="w-4 h-4 text-slate-400" />
                                    <span>Lowongan Kerja BKK</span>
                                </a>
                            </div>
                            <div class="border-t border-slate-100 p-1">
                                <Link
                                    href="/admin/logout"
                                    method="post"
                                    as="button"
                                    class="w-full flex items-center gap-2.5 px-3 py-2 text-xs text-rose-600 hover:bg-rose-50 rounded-lg transition-colors font-medium text-left"
                                >
                                    <LogOut class="w-4 h-4 text-rose-500" />
                                    <span>Keluar dari Akun</span>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Flash Alert Messages -->
            <div v-if="flash.success" class="px-6 sm:px-8 pt-6">
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-3">
                        <CheckCircle class="w-5 h-5 text-emerald-600 shrink-0" />
                        <span>{{ flash.success }}</span>
                    </div>
                </div>
            </div>
            <div v-if="flash.error" class="px-6 sm:px-8 pt-6">
                <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-3">
                        <AlertCircle class="w-5 h-5 text-rose-600 shrink-0" />
                        <span>{{ flash.error }}</span>
                    </div>
                </div>
            </div>

            <!-- Page Body Slot -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-8">
                <slot />
            </main>
        </div>
    </div>
</template>
