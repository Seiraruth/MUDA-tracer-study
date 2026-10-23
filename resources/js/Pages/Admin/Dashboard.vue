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
            <div class="relative overflow-hidden rounded-2xl bg-primary-900 p-6 sm:p-8 text-white border border-primary-800">
                <div class="absolute right-0 top-0 h-full w-1.5 bg-cta" aria-hidden="true"></div>
                <div class="relative z-10 flex flex-col lg:flex-row lg:items-start justify-between gap-6">
                    <div class="space-y-3 max-w-2xl">
                        <p class="text-sm font-semibold text-primary-200">SMK Muhammadiyah 2 Cikampek · BKK</p>
                        <h2 class="text-2xl sm:text-3xl font-bold text-white">
                            Pantau perjalanan lulusan
                        </h2>
                        <p class="text-sm text-primary-100 leading-relaxed">
                            Lihat partisipasi tracer, hasil BMW, dan alumni yang perlu ditindaklanjuti.
                        </p>
                    </div>

                    <div class="flex flex-wrap items-center gap-2 shrink-0">
                        <Link
                            href="/admin/alumni"
                            class="inline-flex min-h-11 items-center gap-2 px-4 py-2 rounded-lg bg-cta text-surface-900 hover:bg-cta-600 hover:text-white text-sm font-bold transition-colors"
                        >
                            <Plus class="w-4 h-4" />
                            <span>Kelola Data Alumni</span>
                        </Link>
                        <Link
                            href="/admin/job-postings"
                            class="inline-flex min-h-11 items-center gap-2 px-4 py-2 rounded-lg bg-white/10 hover:bg-white/20 text-white border border-white/25 text-sm font-semibold transition-colors"
                        >
                            <Briefcase class="w-4 h-4" />
                            <span>Kelola Lowongan</span>
                        </Link>
                    </div>
                </div>

                <!-- Stats Row -->
                <div class="mt-7 pt-5 border-t border-white/15 grid grid-cols-2 sm:grid-cols-4 gap-x-5 gap-y-4">
                    <div>
                        <span class="text-primary-200 block text-xs">Tingkat respons</span>
                        <span class="text-2xl font-bold text-white tabular-nums">{{ stats.responseRate }}%</span>
                    </div>
                    <div>
                        <span class="text-primary-200 block text-xs">Jawaban tersimpan</span>
                        <span class="text-2xl font-bold text-white tabular-nums">{{ stats.totalResponses }}</span>
                    </div>
                    <div>
                        <span class="text-primary-200 block text-xs">Belum mengisi tahun ini</span>
                        <span class="text-2xl font-bold text-cta tabular-nums">{{ belumIsi.length }}</span>
                    </div>
                    <div>
                        <span class="text-primary-200 block text-xs">Tahun tracer aktif</span>
                        <span class="text-2xl font-bold text-white tabular-nums">{{ new Date().getFullYear() }}</span>
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

            <!-- Follow-up list is prominent because it directly supports the admin's next action. -->
            <section class="bg-white rounded-2xl border border-surface-200 overflow-hidden" aria-labelledby="pending-tracer-heading">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-5 py-4 border-b border-surface-200">
                    <div class="flex items-start gap-3">
                        <span class="w-10 h-10 rounded-lg bg-cta/25 text-primary-900 flex items-center justify-center shrink-0" aria-hidden="true">
                            <ClipboardCheck class="w-5 h-5" />
                        </span>
                        <div>
                            <h3 id="pending-tracer-heading" class="font-bold text-surface-900 text-base">Tindak lanjut tracer tahun ini</h3>
                            <p class="text-sm text-surface-600">Alumni tanpa jawaban untuk periode {{ new Date().getFullYear() }}.</p>
                        </div>
                    </div>
                    <span v-if="belumIsi.length" class="self-start sm:self-auto px-3 py-1.5 rounded-md bg-cta/30 text-surface-900 text-sm font-bold tabular-nums">
                        {{ belumIsi.length }} perlu dihubungi
                    </span>
                </div>

                <div v-if="belumIsi.length" class="divide-y divide-surface-100">
                    <div
                        v-for="alumni in belumIsi"
                        :key="alumni.id"
                        class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 px-5 py-4 hover:bg-surface-50 transition-colors"
                    >
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-9 h-9 rounded-lg bg-primary-100 text-primary-900 flex items-center justify-center font-bold text-sm shrink-0" aria-hidden="true">
                                {{ alumni.nama.charAt(0) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-semibold text-surface-900 truncate">{{ alumni.nama }}</p>
                                <p class="text-xs text-surface-600 truncate">{{ alumni.jurusan }} · Lulusan {{ alumni.tahun_lulus }}</p>
                            </div>
                        </div>
                        <span class="inline-flex min-h-11 items-center text-xs font-semibold text-surface-700">
                            Belum mengisi
                        </span>
                    </div>
                </div>
                <p v-else class="px-5 py-8 text-sm text-surface-700" role="status">
                    Tidak ada alumni yang perlu ditindaklanjuti untuk tahun ini.
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