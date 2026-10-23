<script setup>
import { Link } from '@inertiajs/vue3';
import { Briefcase, Building, MapPin, Calendar, ArrowUpRight, Plus, Clock } from 'lucide-vue-next';

defineProps({
    jobs: { type: Array, default: () => [] },
});
</script>

<template>
    <div class="bg-white rounded-xl border border-surface-200 flex flex-col">
        <div class="p-4 border-b border-surface-100 flex items-center justify-between">
            <div>
                <h3 class="font-semibold text-surface-900 text-sm flex items-center gap-1.5">
                    <Briefcase class="w-4 h-4 text-primary-600 icon-pulse" />
                    Lowongan Kerja (BKK)
                </h3>
                <p class="text-xs text-surface-500">Lowongan aktif</p>
            </div>
            <Link href="/admin/job-postings" class="p-1 text-primary-600 hover:bg-primary-50 rounded-md transition-colors icon-pulse">
                <ArrowUpRight class="w-3.5 h-3.5" />
            </Link>
        </div>

        <div class="p-4 space-y-2.5">
            <div v-for="job in jobs" :key="job.id" class="p-3 rounded-lg border border-surface-100 hover:border-primary-200 hover:bg-primary-50/30 transition-colors hover-lift">
                <div class="flex items-start justify-between gap-2">
                    <div class="min-w-0 flex-1">
                        <h4 class="font-medium text-surface-900 text-xs truncate">{{ job.judul }}</h4>
                        <p class="text-[10px] font-medium text-primary-600 flex items-center gap-1 mt-0.5">
                            <Building class="w-2.5 h-2.5" />
                            <span class="truncate">{{ job.perusahaan }}</span>
                        </p>
                    </div>
                    <span v-if="job.is_active" class="px-1.5 py-0.5 text-[9px] font-semibold rounded bg-success text-white">Aktif</span>
                    <span v-else class="px-1.5 py-0.5 text-[9px] font-medium rounded bg-surface-200 text-surface-600">Tutup</span>
                </div>
                <div class="flex flex-wrap items-center gap-x-2 gap-y-0.5 text-[10px] text-surface-500 mt-2 pt-2 border-t border-surface-100">
                    <span class="flex items-center gap-0.5">
                        <Briefcase class="w-2.5 h-2.5" />
                        {{ job.posisi }}
                    </span>
                    <span v-if="job.lokasi" class="flex items-center gap-0.5">
                        <MapPin class="w-2.5 h-2.5" />
                        {{ job.lokasi }}
                    </span>
                    <span v-if="job.deadline" class="flex items-center gap-0.5 text-error font-medium">
                        <Calendar class="w-2.5 h-2.5" />
                        {{ new Date(job.deadline).toLocaleDateString('id-ID', { day: 'numeric', month: 'short' }) }}
                    </span>
                </div>
            </div>
            <div v-if="jobs.length === 0" class="py-6 text-center text-xs text-surface-400">Belum ada lowongan kerja.</div>
        </div>

        <div class="p-3 border-t border-surface-100 bg-surface-50 flex items-center justify-between">
            <span class="text-[10px] text-surface-500">{{ jobs.length }} lowongan aktif</span>
            <Link href="/admin/job-postings" class="inline-flex items-center gap-1 text-[10px] font-medium text-primary-600 hover:text-primary-800 transition-colors">
                <Plus class="w-3 h-3" />
                <span>Posting</span>
            </Link>
        </div>
    </div>
</template>