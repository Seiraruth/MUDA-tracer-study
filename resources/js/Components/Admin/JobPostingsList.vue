<script setup>
import { Link } from '@inertiajs/vue3';
import { Briefcase, Building, MapPin, Calendar, ArrowUpRight, Plus } from 'lucide-vue-next';

defineProps({
    jobs: {
        type: Array,
        default: () => [],
    },
});
</script>

<template>
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs flex flex-col justify-between">
        <div>
            <div class="p-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-base text-slate-900 tracking-tight flex items-center gap-2">
                        Bursa Kerja Khusus (BKK)
                    </h3>
                    <p class="text-xs text-slate-500">Lowongan kerja aktif untuk alumni & siswa</p>
                </div>
                <Link
                    href="/admin/job-postings"
                    class="p-1.5 text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
                    title="Kelola Lowongan"
                >
                    <ArrowUpRight class="w-4 h-4" />
                </Link>
            </div>

            <div class="p-6 space-y-3.5">
                <div
                    v-for="job in jobs"
                    :key="job.id"
                    class="p-4 rounded-xl border border-slate-100 hover:border-indigo-200 bg-slate-50/40 hover:bg-indigo-50/20 transition-all flex flex-col justify-between gap-3"
                >
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <h4 class="font-bold text-slate-900 text-xs sm:text-sm">
                                {{ job.judul }}
                            </h4>
                            <p class="text-xs font-semibold text-indigo-600 flex items-center gap-1.5 mt-0.5">
                                <Building class="w-3.5 h-3.5" />
                                <span>{{ job.perusahaan }}</span>
                            </p>
                        </div>
                        <span
                            v-if="job.is_active"
                            class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-emerald-100 text-emerald-800 shrink-0"
                        >
                            Aktif
                        </span>
                        <span
                            v-else
                            class="px-2 py-0.5 text-[10px] font-bold rounded-full bg-slate-100 text-slate-600 shrink-0"
                        >
                            Tutup
                        </span>
                    </div>

                    <div class="flex flex-wrap items-center gap-y-1 gap-x-4 text-[11px] text-slate-500 border-t border-slate-200/60 pt-2.5">
                        <span class="flex items-center gap-1">
                            <Briefcase class="w-3 h-3 text-slate-400" />
                            {{ job.posisi }}
                        </span>
                        <span v-if="job.lokasi" class="flex items-center gap-1">
                            <MapPin class="w-3 h-3 text-slate-400" />
                            {{ job.lokasi }}
                        </span>
                        <span v-if="job.deadline" class="flex items-center gap-1 text-rose-600 font-medium">
                            <Calendar class="w-3 h-3" />
                            Hingga {{ new Date(job.deadline).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) }}
                        </span>
                    </div>
                </div>

                <div v-if="jobs.length === 0" class="py-8 text-center text-xs text-slate-400">
                    Belum ada informasi lowongan kerja yang ditambahkan.
                </div>
            </div>
        </div>

        <div class="p-4 border-t border-slate-100 bg-slate-50/40 flex items-center justify-between">
            <span class="text-xs text-slate-500">Total Lowongan Aktif</span>
            <Link
                href="/admin/job-postings"
                class="inline-flex items-center gap-1 text-xs font-bold text-indigo-600 hover:text-indigo-800"
            >
                <Plus class="w-3.5 h-3.5" />
                <span>Posting Lowongan Baru</span>
            </Link>
        </div>
    </div>
</template>
