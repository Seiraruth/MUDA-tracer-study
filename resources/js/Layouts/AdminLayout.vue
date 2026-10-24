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
    BarChart3,
    MessageSquare,
    Handshake
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
    },
    {
        name: 'Mitra DUDI',
        href: '/admin/mitras',
        routePattern: '/admin/mitras',
        icon: Handshake,
        badge: null
    },
    {
        name: 'Saran Alumni',
        href: '/admin/saran',
        routePattern: '/admin/saran',
        icon: MessageSquare,
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
    <div class="min-h-screen bg-surface-100 font-sans text-surface-900 flex">
        <!-- Mobile Sidebar Backdrop -->
        <div
            v-if="sidebarOpen"
            @click="sidebarOpen = false"
            class="fixed inset-0 z-40 bg-surface-900/70 lg:hidden"
        ></div>

        <!-- Sidebar -->
        <aside
            :class="[
                'sticky top-0 h-screen lg:h-dvh left-0 z-50 w-64 bg-primary-900 text-white flex flex-col transition-transform duration-200',
                sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'
            ]"
        >
            <!-- Logo -->
            <div class="h-16 flex items-center justify-between px-4 border-b border-primary-800/80">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-cta to-cta-600 flex items-center justify-center shadow-lg">
                        <GraduationCap class="w-5 h-5 text-primary-950" />
                    </div>
                    <div class="space-y-0">
                        <div class="text-base font-extrabold text-white">Tracer Study</div>
                        <div class="text-[10px] text-primary-400 font-semibold uppercase tracking-wider">Admin Panel</div>
                    </div>
                </div>
                <button
                    @click="sidebarOpen = false"
                    class="lg:hidden p-1.5 text-primary-200 hover:text-white hover:bg-white/10 rounded-md transition-colors"
                >
                    <X class="w-4 h-4" />
                </button>
            </div>

            <!-- System Status -->
            <div class="p-3">
                <div class="bg-primary-800/60 rounded-xl p-3 border border-primary-700/60 flex items-center gap-3 shadow-lg">
                    <div class="relative flex-shrink-0">
                        <div class="w-2.5 h-2.5 rounded-full bg-success animate-pulse"></div>
                        <div class="absolute inset-0 w-2.5 h-2.5 rounded-full bg-success opacity-30 animate-ping"></div>
                    </div>
                    <div class="space-y-0.5 min-w-0">
                        <p class="text-sm font-bold text-white">Periode {{ new Date().getFullYear() }}</p>
                        <p class="text-[10px] text-primary-400 font-medium uppercase tracking-wide">Sistem Aktif</p>
                    </div>
                </div>
            </div>

            <!-- Navigation -->
            <div class="flex-1 px-3 py-2 overflow-y-auto">
                <div class="px-3 pb-2.5 mb-3 border-b border-primary-800/80">
                    <div class="text-[10px] font-extrabold text-primary-400 uppercase tracking-widest">
                        Navigasi
                    </div>
                </div>
                <nav class="space-y-1">
                    <Link
                        v-for="item in navigation"
                        :key="item.name"
                        :href="item.href"
                        :class="[
                            'group flex items-center gap-3 px-3.5 py-2.5 rounded-xl text-sm font-bold transition-all duration-200',
                            isCurrentRoute(item)
                                ? 'bg-white text-primary-900 shadow-lg'
                                : 'text-primary-100 hover:bg-white/15 hover:text-white'
                        ]"
                    >
                        <component
                            :is="item.icon"
                            :class="[
                                'w-5 h-5 transition-transform',
                                isCurrentRoute(item) ? 'text-primary-600' : 'text-primary-300 group-hover:text-white group-hover:scale-110'
                            ]"
                        />
                        <span>{{ item.name }}</span>
                    </Link>
                </nav>

                <div class="pt-4 px-2 pb-2 mt-4 border-t border-primary-800">
                    <div class="text-[10px] font-semibold text-primary-400 uppercase tracking-wide px-1">
                        Tautan
                    </div>
                </div>
                <a
                    href="/"
                    target="_blank"
                    class="group flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm font-medium text-primary-100 hover:bg-white/10 hover:text-white transition-all duration-150"
                >
                    <ExternalLink class="w-4 h-4 text-primary-300 group-hover:text-white" />
                    <span>Situs Publik</span>
                </a>
            </div>

            <!-- User Profile -->
            <div class="p-3 border-t border-primary-800">
                <div class="flex items-center justify-between p-2 rounded-lg bg-primary-800/50 border border-primary-700/50">
                    <div class="flex items-center gap-2.5 overflow-hidden">
                        <div class="w-7 h-7 rounded-md bg-primary-600 text-white font-medium flex items-center justify-center text-sm shrink-0">
                            {{ user.name ? user.name.charAt(0).toUpperCase() : 'A' }}
                        </div>
                        <div class="truncate min-w-0">
                            <p class="text-xs font-semibold text-white truncate">{{ user.name }}</p>
                            <p class="text-[10px] text-primary-300 truncate">{{ user.email }}</p>
                        </div>
                    </div>
                    <Link
                        href="/admin/logout"
                        method="post"
                        as="button"
                        title="Keluar"
                        class="p-1.5 rounded-md text-primary-300 hover:text-white hover:bg-white/10 transition-colors"
                    >
                        <LogOut class="w-3.5 h-3.5" />
                    </Link>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Header -->
            <header class="h-16 bg-white border-b border-surface-200 flex items-center justify-between px-4 sm:px-6 z-30 sticky top-0 shadow-sm">
                <div class="flex items-center gap-4">
                    <button
                        @click="sidebarOpen = true"
                        class="lg:hidden p-2 text-surface-600 hover:text-surface-900 hover:bg-surface-100 rounded-xl transition-colors"
                    >
                        <Menu class="w-5 h-5" />
                    </button>
                    <div>
                        <h1 class="text-xl font-extrabold text-surface-900 tracking-tight">
                            {{ title }}
                        </h1>
                        <p class="text-xs text-surface-600 hidden sm:block font-medium">Kelola data tracer study</p>
                    </div>
                </div>

                <div class="flex items-center gap-2">
                    <a
                        href="/"
                        target="_blank"
                        class="hidden sm:inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-medium text-surface-600 hover:text-surface-900 hover:bg-surface-100 rounded-lg transition-colors"
                    >
                        <ExternalLink class="w-3.5 h-3.5" />
                        <span>Buka Situs</span>
                    </a>

                    <!-- User Menu -->
                    <div class="relative">
                        <button
                            @click="userDropdownOpen = !userDropdownOpen"
                            class="flex items-center gap-2 p-1.5 sm:px-2.5 sm:py-1.5 rounded-lg text-surface-700 hover:bg-surface-100 border border-transparent hover:border-surface-200 transition-all text-xs font-medium"
                        >
                            <div class="w-6 h-6 rounded-md bg-primary-600 text-white font-medium flex items-center justify-center text-xs">
                                {{ user.name ? user.name.charAt(0).toUpperCase() : 'A' }}
                            </div>
                            <span class="hidden md:inline font-medium text-surface-800">{{ user.name }}</span>
                            <ChevronDown class="w-3.5 h-3.5 text-surface-400" />
                        </button>

                        <!-- Dropdown -->
                        <div
                            v-if="userDropdownOpen"
                            @click="userDropdownOpen = false"
                            class="absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-lg border border-surface-200 py-1.5 z-50"
                        >
                            <div class="px-3 py-2 border-b border-surface-100">
                                <p class="text-[10px] text-surface-400 font-medium uppercase tracking-wide">Masuk sebagai</p>
                                <p class="text-sm font-semibold text-surface-900 truncate">{{ user.name }}</p>
                                <p class="text-xs text-surface-500 truncate">{{ user.email }}</p>
                            </div>
                            <div class="py-1">
                                <a href="/admin/alumni" class="flex items-center gap-2 px-3 py-1.5 text-xs text-surface-700 hover:bg-surface-50 rounded-md transition-colors">
                                    <Users class="w-3.5 h-3.5 text-surface-400" />
                                    <span>Kelola Alumni</span>
                                </a>
                                <a href="/admin/tracer-responses" class="flex items-center gap-2 px-3 py-1.5 text-xs text-surface-700 hover:bg-surface-50 rounded-md transition-colors">
                                    <FileSpreadsheet class="w-3.5 h-3.5 text-surface-400" />
                                    <span>Hasil Tracer</span>
                                </a>
                                <a href="/admin/job-postings" class="flex items-center gap-2 px-3 py-1.5 text-xs text-surface-700 hover:bg-surface-50 rounded-md transition-colors">
                                    <Briefcase class="w-3.5 h-3.5 text-surface-400" />
                                    <span>Lowongan Kerja</span>
                                </a>
                            </div>
                            <div class="border-t border-surface-100 py-1">
                                <Link href="/admin/logout" method="post" as="button" class="w-full flex items-center gap-2 px-3 py-1.5 text-xs text-error hover:bg-red-50 rounded-md transition-colors font-medium text-left">
                                    <LogOut class="w-3.5 h-3.5" />
                                    <span>Keluar</span>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Flash Messages -->
            <div v-if="flash.success" class="px-4 sm:px-6 pt-4">
                <div class="p-3 rounded-lg bg-success-light border border-success/20 text-success text-sm flex items-center gap-2.5">
                    <CheckCircle class="w-4 h-4 shrink-0" />
                    <span>{{ flash.success }}</span>
                </div>
            </div>
            <div v-if="flash.error" class="px-4 sm:px-6 pt-4">
                <div class="p-3 rounded-lg bg-error-light border border-error/20 text-error text-sm flex items-center gap-2.5">
                    <AlertCircle class="w-4 h-4 shrink-0" />
                    <span>{{ flash.error }}</span>
                </div>
            </div>

            <!-- Main Content -->
            <main class="flex-1 overflow-y-auto p-4 sm:p-6">
                <slot />
            </main>
        </div>
    </div>
</template>