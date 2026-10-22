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

    <AdminLayout title="Ringkasan Tracer Study">
        <div class="space-y-8 max-w-7xl mx-auto">
            <!-- Hero / Welcome Header -->
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-slate-900 via-indigo-950 to-slate-900 p-6 sm:p-8 text-white shadow-xl border border-slate-800">
                <div class="absolute -right-10 -bottom-10 w-72 h-72 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div class="space-y-2">
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 text-xs font-semibold">
                            <Sparkles class="w-3.5 h-3.5" />
                            <span>Portal Pemantauan & Penelusuran Tamatan SMK</span>
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-white">
                            Dashboard Tracer Study & BKK
                        </h2>
                        <p class="text-sm text-slate-300 max-w-2xl leading-relaxed">
                            Pantau keberhasilan lulusan dalam indikator BMW (Bekerja, Melanjutkan, Wirausaha), keselarasan kompetensi vokasi (Link & Match), dan rekrutmen bursa kerja.
                        </p>
                    </div>

                    <!-- Quick Action Buttons -->
                    <div class="flex flex-wrap items-center gap-3 shrink-0">
                        <Link
                            href="/admin/alumni"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition-all shadow-md shadow-indigo-600/30 hover:scale-[1.02] active:scale-[0.98]"
                        >
                            <Plus class="w-4 h-4" />
                            <span>Tambah Data Alumni</span>
                        </Link>
                        <Link
                            href="/admin/job-postings"
                            class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-200 border border-slate-700 text-xs font-bold transition-all hover:scale-[1.02] active:scale-[0.98]"
                        >
                            <Briefcase class="w-4 h-4" />
                            <span>Posting Loker</span>
                        </Link>
                    </div>
                </div>

                <!-- Secondary Sub-bar: Response Rate -->
                <div class="mt-6 pt-6 border-t border-slate-800/80 grid grid-cols-2 sm:grid-cols-4 gap-4 text-xs">
                    <div>
                        <span class="text-slate-400 block">Tingkat Pengisian (Response Rate):</span>
                        <span class="text-base font-bold text-indigo-300">{{ stats.responseRate }}% Selesai</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block">Total Kuesioner Masuk:</span>
                        <span class="text-base font-bold text-emerald-400">{{ stats.totalResponses }} Responden</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block">Alumni Belum Mengisi:</span>
                        <span class="text-base font-bold text-amber-300">
                            {{ Math.max(0, stats.totalAlumni - stats.totalResponses) }} Orang
                        </span>
                    </div>
                    <div>
                        <span class="text-slate-400 block">Tahun Tracer Aktif:</span>
                        <span class="text-base font-bold text-white">{{ new Date().getFullYear() }}</span>
                    </div>
                </div>
            </div>

            <!-- Metric Cards (Overview Stats) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
                <!-- 1. Total Alumni -->
                <StatCard
                    title="Total Alumni Terdata"
                    :value="stats.totalAlumni"
                    subtitle="Jumlah alumni tercatat di sistem"
                    badge="100%"
                    color="indigo"
                    :icon="Users"
                />

                <!-- 2. Bekerja -->
                <StatCard
                    title="Alumni Bekerja (B)"
                    :value="stats.bekerja"
                    :percentage="stats.bekerjaPct"
                    subtitle="Indikator BMW: Bekerja"
                    color="emerald"
                    :icon="Briefcase"
                />

                <!-- 3. Kuliah -->
                <StatCard
                    title="Alumni Kuliah (M)"
                    :value="stats.kuliah"
                    :percentage="stats.kuliahPct"
                    subtitle="Indikator BMW: Melanjutkan Studi"
                    color="blue"
                    :icon="GraduationCap"
                />

                <!-- 4. Wirausaha -->
                <StatCard
                    title="Alumni Wirausaha (W)"
                    :value="stats.wirausaha"
                    :percentage="stats.wirausahaPct"
                    subtitle="Indikator BMW: Usaha Mandiri"
                    color="amber"
                    :icon="Store"
                />
            </div>

            <!-- Charts Section: BMW Donut & Linearitas Alignment -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-stretch">
                <!-- BMW Donut Chart (7 cols on lg) -->
                <div class="lg:col-span-7 flex flex-col">
                    <BmwDonutChart
                        :bekerja="stats.bekerja"
                        :kuliah="stats.kuliah"
                        :wirausaha="stats.wirausaha"
                        :mencari-kerja="stats.mencariKerja"
                        class="h-full"
                    />
                </div>

                <!-- Linearitas Gauge (5 cols on lg) -->
                <div class="lg:col-span-5 flex flex-col">
                    <LinearitasBarChart
                        :linear-count="linearitas.linearCount"
                        :non-linear-count="linearitas.nonLinearCount"
                        :total-working="linearitas.totalWorking"
                        class="h-full"
                    />
                </div>
            </div>

            <!-- Jurusan Distribution Bar Breakdown (if data available) -->
            <div v-if="byJurusan && byJurusan.length > 0" class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
                    <div>
                        <h3 class="font-bold text-base text-slate-900 tracking-tight">
                            Sebaran Alumni & Partisipasi per Program Keahlian
                        </h3>
                        <p class="text-xs text-slate-500">Persentase respon tracer study berdasarkan jurusan SMK</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div
                        v-for="j in byJurusan"
                        :key="j.jurusan"
                        class="p-4 rounded-xl border border-slate-100 bg-slate-50/50 space-y-2"
                    >
                        <div class="flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-800 truncate">{{ j.jurusan }}</span>
                            <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-white border border-slate-200 text-indigo-600">
                                {{ j.rate }}% respon
                            </span>
                        </div>
                        <div class="h-2 w-full bg-slate-200 rounded-full overflow-hidden">
                            <div
                                class="h-full bg-indigo-600 rounded-full transition-all duration-500"
                                :style="{ width: `${j.rate}%` }"
                            ></div>
                        </div>
                        <div class="flex items-center justify-between text-[11px] text-slate-500 pt-1">
                            <span>Respon: <strong>{{ j.responses }}</strong></span>
                            <span>Total Alumni: <strong>{{ j.total }}</strong></span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Two Columns: Recent Tracer Table & Job Postings List -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
                <!-- Recent Responses (8 cols) -->
                <div class="lg:col-span-8">
                    <RecentResponsesTable
                        :responses="recentResponses"
                        @view="openResponseModal"
                    />
                </div>

                <!-- Job Postings BKK (4 cols) -->
                <div class="lg:col-span-4">
                    <JobPostingsList
                        :jobs="recentJobs"
                    />
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
