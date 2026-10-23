<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import StatCard from '@/Components/Admin/StatCard.vue';
import BmwDonutChart from '@/Components/Admin/BmwDonutChart.vue';
import LinearitasBarChart from '@/Components/Admin/LinearitasBarChart.vue';
import RecentResponsesTable from '@/Components/Admin/RecentResponsesTable.vue';
import JobPostingsList from '@/Components/Admin/JobPostingsList.vue';
import ResponseDetailModal from '@/Components/Admin/ResponseDetailModal.vue';
import {
    Users,
    Briefcase,
    GraduationCap,
    Store,
    Search,
    Plus,
    FileSpreadsheet,
    Sparkles,
    Calendar,
    ArrowUpRight,
    TrendingUp
} from 'lucide-vue-next';

const props = defineProps({
    belumIsi: {
        type: Array,
        default: () => [],
    },
    stats: {
        type: Object,
        default: () => ({
            totalAlumni: 0,
            totalResponses: 0,
            responseRate: 0,
            bekerja: 0,
            bekerjaPct: 0,
            kuliah: 0,
            kuliahPct: 0,
            wirausaha: 0,
            wirausahaPct: 0,
            mencariKerja: 0,
            mencariKerjaPct: 0,
        }),
    },
    linearitas: {
        type: Object,
        default: () => ({
            linearCount: 0,
            nonLinearCount: 0,
            totalWorking: 0,
        }),
    },
    recentResponses: {
        type: Array,
        default: () => [],
    },
    recentJobs: {
        type: Array,
        default: () => [],
    },
    byJurusan: {
        type: Array,
        default: () => [],
    },
});

const selectedResponse = ref(null);
const showDetailModal = ref(false);

const openResponseModal = (res) => {
    selectedResponse.value = res;
    showDetailModal.value = true;
};

const closeResponseModal = () => {
    showDetailModal.value = false;
    selectedResponse.value = null;
};
</script>

<template>
    <Head title="Dashboard Admin Tracer Study" />

    <AdminLayout title="Ringkasan">
        <div class="space-y-5 max-w-7xl mx-auto">
            <!-- Welcome Header -->
            <div class="relative overflow-hidden rounded-xl bg-primary-900 p-5 sm:p-6 text-white border border-primary-800 animate-in">
                <div class="absolute inset-0 bg-gradient-to-br from-primary-800/50 to-transparent pointer-events-none"></div>
                <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <div class="space-y-1">
                        <div class="inline-flex items-center gap-1.5 px-2 py-1 rounded-md bg-white/10 text-primary-200 border border-white/10 text-xs font-medium">
                            <Sparkles class="w-3 h-3" />
                            <span>Portal Penelusuran Tamatan</span>
                        </div>
                        <h2 class="text-xl sm:text-2xl font-bold text-white">
                            Dashboard Tracer Study & BKK
                        </h2>
                        <p class="text-sm text-primary-200 max-w-xl">
                            Pantau keberhasilan lulusan: indikator BMW, keselarasan kompetensi, dan bursa kerja.
                        </p>
                    </div>

                    <!-- Quick Actions -->
                    <div class="flex items-center gap-2 shrink-0">
                        <Link
                            href="/admin/alumni"
                            class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-white text-primary-900 hover:bg-primary-50 text-xs font-semibold transition-colors icon-pulse"
                        >
                            <Plus class="w-3.5 h-3.5" />
                            <span>Tambah Alumni</span>
                        </Link>
                        <Link
                            href="/admin/job-postings"
                            class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-white/10 hover:bg-white/20 text-white border border-white/20 text-xs font-medium transition-colors"
                        >
                            <Briefcase class="w-3.5 h-3.5" />
                            <span>Posting Loker</span>
                        </Link>
                    </div>
                </div>

                <!-- Stats Row -->
                <div class="mt-4 pt-4 border-t border-white/10 grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                    <div>
                        <span class="text-primary-300 block text-[10px]">Response Rate</span>
                        <span class="text-lg font-bold text-white">{{ stats.responseRate }}%</span>
                    </div>
                    <div>
                        <span class="text-primary-300 block text-[10px]">Total Responden</span>
                        <span class="text-lg font-bold text-success-light">{{ stats.totalResponses }}</span>
                    </div>
                    <div>
                        <span class="text-primary-300 block text-[10px]">Belum Mengisi</span>
                        <span class="text-lg font-bold text-warning-light">
                            {{ Math.max(0, stats.totalAlumni - stats.totalResponses) }}
                        </span>
                    </div>
                    <div>
                        <span class="text-primary-300 block text-[10px]">Tahun Aktif</span>
                        <span class="text-lg font-bold text-white">{{ new Date().getFullYear() }}</span>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 stagger-children">
                <StatCard
                    title="Total Alumni"
                    :value="stats.totalAlumni"
                    subtitle="Terdata di sistem"
                    color="primary"
                    :icon="Users"
                />
                <StatCard
                    title="Bekerja"
                    :value="stats.bekerja"
                    :percentage="stats.bekerjaPct"
                    subtitle="Indikator BMW"
                    color="emerald"
                    :icon="Briefcase"
                />
                <StatCard
                    title="Kuliah"
                    :value="stats.kuliah"
                    :percentage="stats.kuliahPct"
                    subtitle="Melanjutkan"
                    color="blue"
                    :icon="GraduationCap"
                />
                <StatCard
                    title="Wirausaha"
                    :value="stats.wirausaha"
                    :percentage="stats.wirausahaPct"
                    subtitle="Usaha mandiri"
                    color="amber"
                    :icon="Store"
                />
            </div>

            <!-- Charts Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 stagger-children">
                <div class="lg:col-span-7">
                    <BmwDonutChart
                        :bekerja="stats.bekerja"
                        :kuliah="stats.kuliah"
                        :wirausaha="stats.wirausaha"
                        :mencari-kerja="stats.mencariKerja"
                    />
                </div>
                <div class="lg:col-span-5">
                    <LinearitasBarChart
                        :linear-count="linearitas.linearCount"
                        :non-linear-count="linearitas.nonLinearCount"
                        :total-working="linearitas.totalWorking"
                    />
                </div>
            </div>

            <!-- Jurusan Section -->
            <div v-if="byJurusan && byJurusan.length > 0" class="bg-white rounded-xl p-5 border border-surface-200 animate-in animate-in-delay-3">
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-surface-100">
                    <div>
                        <h3 class="font-semibold text-surface-900 text-sm">
                            Partisipasi per Program Keahlian
                        </h3>
                        <p class="text-xs text-surface-500">Tingkat respons tracer study</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    <div
                        v-for="j in byJurusan"
                        :key="j.jurusan"
                        class="p-3 rounded-lg border border-surface-100 bg-surface-50 hover-lift"
                    >
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-medium text-surface-800 truncate">{{ j.jurusan }}</span>
                            <span class="text-[10px] font-semibold px-2 py-0.5 rounded bg-primary-100 text-primary-700">
                                {{ j.rate }}%
                            </span>
                        </div>
                        <div class="h-1.5 w-full bg-surface-200 rounded-full overflow-hidden">
                            <div
                                class="h-full bg-primary-500 rounded-full"
                                :style="{ width: `${j.rate}%` }"
                            ></div>
                        </div>
                        <div class="flex items-center justify-between text-[10px] text-surface-500 mt-2">
                            <span>Respon: <strong class="text-surface-700">{{ j.responses }}</strong></span>
                            <span>Total: <strong class="text-surface-700">{{ j.total }}</strong></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 stagger-children">
                <div class="lg:col-span-8">
                    <RecentResponsesTable
                        :responses="recentResponses"
                        @view="openResponseModal"
                    />
                </div>
                <div class="lg:col-span-4">
                    <JobPostingsList
                        :jobs="recentJobs"
                    />
                </div>
            </div>

            <!-- Alumni Belum Isi Tracer -->
            <div v-if="belumIsi && belumIsi.length > 0" class="bg-white rounded-xl p-5 border border-surface-200 animate-in animate-in-delay-4">
                <div class="flex items-center justify-between pb-3 mb-4 border-b border-surface-100">
                    <div>
                        <h3 class="font-semibold text-surface-900 text-sm">
                            Alumni Belum Mengisi Tracer Study
                        </h3>
                        <p class="text-xs text-surface-500">Perlu pengingat/follow-up</p>
                    </div>
                    <span class="text-[10px] font-semibold px-2 py-1 rounded bg-amber-100 text-amber-700">
                        {{ belumIsi.length }} belum isi
                    </span>
                </div>

                <div class="space-y-2">
                    <div
                        v-for="alumni in belumIsi"
                        :key="alumni.id"
                        class="flex items-center justify-between p-2.5 rounded-lg bg-surface-50 hover:bg-amber-50/50 transition-colors"
                    >
                        <div class="flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center font-semibold text-xs">
                                {{ alumni.nama.charAt(0) }}
                            </div>
                            <div>
                                <span class="text-xs font-medium text-surface-900">{{ alumni.nama }}</span>
                                <p class="text-[10px] text-surface-500">{{ alumni.jurusan }} • Thn Lulus {{ alumni.tahun_lulus }}</p>
                            </div>
                        </div>
                        <span class="text-[10px] text-amber-600 font-medium">Belum isi</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Modal -->
        <ResponseDetailModal
            :show="showDetailModal"
            :response="selectedResponse"
            @close="closeResponseModal"
        />
    </AdminLayout>
</template>