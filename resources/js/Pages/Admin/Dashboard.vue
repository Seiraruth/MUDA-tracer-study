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
    TrendingUp,
    ClipboardCheck
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
        <div class="space-y-7 max-w-7xl mx-auto">
            <!-- Header identifies the school's tracer-study work; the yellow accent marks the primary action. -->
            <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-primary-900 via-primary-800 to-primary-900 p-7 sm:p-9 text-white border border-primary-700/50 shadow-xl">
                <div class="absolute right-0 top-0 h-full w-2 bg-gradient-to-b from-cta via-cta-600 to-cta-700" aria-hidden="true"></div>
                <div class="relative z-10 flex flex-col lg:flex-row lg:items-start justify-between gap-6">
                    <div class="space-y-3.5 max-w-2xl">
                        <p class="text-sm font-bold text-primary-200 tracking-wide">SMK MUHAMMADIYAH 2 CIKAMPEK · BKK</p>
                        <h2 class="text-3xl sm:text-4xl font-extrabold text-white leading-tight">
                            Dashboard Tracer Study
                        </h2>
                        <p class="text-base text-primary-100 leading-relaxed">
                            Pantau partisipasi responden, capaian BMW, dan tindak lanjut alumni.
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-3 shrink-0">
                        <Link
                            href="/admin/alumni"
                            class="inline-flex min-h-12 items-center gap-2.5 px-5 py-3 rounded-xl bg-cta text-primary-950 hover:bg-cta-600 hover:shadow-lg text-sm font-bold transition-all press-effect"
                        >
                            <Plus class="w-5 h-5" />
                            <span>Kelola Alumni</span>
                        </Link>
                        <Link
                            href="/admin/job-postings"
                            class="inline-flex min-h-12 items-center gap-2.5 px-5 py-3 rounded-xl bg-white/10 hover:bg-white/20 text-white border border-white/30 text-sm font-bold transition-all press-effect"
                        >
                            <Briefcase class="w-5 h-5" />
                            <span>Lowongan BKK</span>
                        </Link>
                    </div>
                </div>

                <!-- Stats Row -->
                <div class="mt-8 pt-6 border-t border-white/20 grid grid-cols-2 sm:grid-cols-4 gap-x-6 gap-y-5">
                    <div>
                        <span class="text-primary-300 block text-xs font-semibold uppercase tracking-wide mb-1.5">Tingkat Respons</span>
                        <span class="text-3xl font-extrabold text-white tabular-nums">{{ stats.responseRate }}%</span>
                    </div>
                    <div>
                        <span class="text-primary-300 block text-xs font-semibold uppercase tracking-wide mb-1.5">Total Responden</span>
                        <span class="text-3xl font-extrabold text-white tabular-nums">{{ stats.totalResponses }}</span>
                    </div>
                    <div>
                        <span class="text-primary-300 block text-xs font-semibold uppercase tracking-wide mb-1.5">Belum Mengisi</span>
                        <span class="text-3xl font-extrabold text-cta tabular-nums">{{ belumIsi.length }}</span>
                    </div>
                    <div>
                        <span class="text-primary-300 block text-xs font-semibold uppercase tracking-wide mb-1.5">Periode Aktif</span>
                        <span class="text-3xl font-extrabold text-white tabular-nums">{{ new Date().getFullYear() }}</span>
                    </div>
                </div>
            </div>

            <!-- BMW counts are secondary to the response follow-up workflow above. -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
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
            <div v-if="byJurusan && byJurusan.length > 0" class="bg-white rounded-2xl p-6 border border-surface-200 shadow-md animate-in animate-in-delay-3">
                <div class="flex items-center justify-between pb-4 mb-5 border-b border-surface-200">
                    <div>
                        <h3 class="font-extrabold text-surface-900 text-base">
                            Partisipasi per Jurusan
                        </h3>
                        <p class="text-xs text-surface-600 font-medium mt-0.5">Tingkat respons berdasarkan program keahlian</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    <div
                        v-for="j in byJurusan"
                        :key="j.jurusan"
                        class="p-4 rounded-xl border border-surface-200 bg-gradient-to-br from-surface-50 to-white hover-lift group"
                    >
                        <div class="flex items-center justify-between mb-3">
                            <span class="text-xs font-bold text-surface-900 truncate">{{ j.jurusan }}</span>
                            <span class="text-xs font-extrabold px-2.5 py-1 rounded-lg bg-primary-100 text-primary-700">
                                {{ j.rate }}%
                            </span>
                        </div>
                        <div class="h-2 w-full bg-surface-200 rounded-full overflow-hidden mb-3">
                            <div
                                class="h-full bg-gradient-to-r from-primary-500 to-primary-600 rounded-full transition-all duration-700 ease-out"
                                :style="{ width: `${j.rate}%` }"
                            ></div>
                        </div>
                        <div class="flex items-center justify-between text-xs text-surface-600 font-medium">
                            <span>Responden: <strong class="text-surface-900">{{ j.responses }}</strong></span>
                            <span>Total: <strong class="text-surface-900">{{ j.total }}</strong></span>
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

            <!-- Follow-up list is prominent because it directly supports the admin's next action. -->
            <section class="bg-white rounded-2xl border border-surface-200 overflow-hidden shadow-md" aria-labelledby="pending-tracer-heading">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 px-6 py-5 bg-gradient-to-r from-cta/10 to-white border-b border-surface-200">
                    <div class="flex items-start gap-3.5">
                        <span class="w-12 h-12 rounded-xl bg-gradient-to-br from-cta to-cta-600 text-primary-950 flex items-center justify-center shrink-0 shadow-lg" aria-hidden="true">
                            <ClipboardCheck class="w-6 h-6" />
                        </span>
                        <div>
                            <h3 id="pending-tracer-heading" class="font-extrabold text-surface-900 text-lg">Tindak Lanjut Periode Ini</h3>
                            <p class="text-sm text-surface-600 font-medium mt-1">Alumni belum mengisi kuesioner tahun {{ new Date().getFullYear() }}</p>
                        </div>
                    </div>
                    <span v-if="belumIsi.length" class="self-start sm:self-auto px-4 py-2 rounded-xl bg-cta text-primary-950 text-sm font-extrabold tabular-nums shadow-md">
                        {{ belumIsi.length }} alumni
                    </span>
                </div>

                <div v-if="belumIsi.length" class="divide-y divide-surface-100">
                    <div
                        v-for="alumni in belumIsi"
                        :key="alumni.id"
                        class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-6 py-4 hover:bg-surface-50 transition-all group"
                    >
                        <div class="flex items-center gap-3.5 min-w-0">
                            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-primary-100 to-primary-200 text-primary-900 flex items-center justify-center font-extrabold text-base shrink-0 group-hover:scale-105 transition-transform" aria-hidden="true">
                                {{ alumni.nama.charAt(0) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-surface-900 truncate">{{ alumni.nama }}</p>
                                <p class="text-xs text-surface-600 font-medium truncate mt-0.5">{{ alumni.jurusan }} · Angkatan {{ alumni.tahun_lulus }}</p>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-amber-100 text-amber-800 text-xs font-bold">
                            Belum Isi
                        </span>
                    </div>
                </div>
                <p v-else class="px-6 py-10 text-center text-sm text-surface-700 font-medium" role="status">
                    Semua alumni sudah mengisi kuesioner untuk periode ini.
                </p>
            </section>
        </div>

        <!-- Detail Modal -->
        <ResponseDetailModal
            :show="showDetailModal"
            :response="selectedResponse"
            @close="closeResponseModal"
        />
    </AdminLayout>
</template>