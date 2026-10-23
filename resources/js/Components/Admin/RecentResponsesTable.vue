<script setup>
import { Link } from '@inertiajs/vue3';
import { Eye, ArrowUpRight, FileSpreadsheet, Building, GraduationCap, Store, Search } from 'lucide-vue-next';

defineProps({
    responses: { type: Array, default: () => [] },
});

defineEmits(['view']);

const getStatusBadge = (status) => {
    switch (status) {
        case 'Bekerja':
            return { label: 'Bekerja', class: 'bg-emerald-100 text-emerald-700 border-emerald-200', icon: Building };
        case 'Kuliah':
            return { label: 'Kuliah', class: 'bg-blue-100 text-blue-700 border-blue-200', icon: GraduationCap };
        case 'Wirausaha':
            return { label: 'Wirausaha', class: 'bg-amber-100 text-amber-700 border-amber-200', icon: Store };
        default:
            return { label: 'Mencari Kerja', class: 'bg-rose-100 text-rose-700 border-rose-200', icon: Search };
    }
};

const getDetailSummary = (res) => {
    if (res.status_utama === 'Bekerja') return `${res.nama_perusahaan || '-'} (${res.jabatan || 'Staff'})`;
    if (res.status_utama === 'Kuliah') return `${res.nama_kampus || '-'} - ${res.program_studi || '-'}`;
    if (res.status_utama === 'Wirausaha') return `${res.nama_usaha || '-'} (${res.bidang_usaha || 'Usaha'})`;
    return 'Sedang mencari lowongan';
};
</script>

<template>
    <div class="bg-white rounded-xl border border-surface-200 overflow-hidden">
        <div class="p-4 border-b border-surface-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div>
                <h3 class="font-semibold text-surface-900 text-sm flex items-center gap-1.5">
                    <FileSpreadsheet class="w-4 h-4 text-primary-600 icon-pulse" />
                    Responden Terbaru
                </h3>
                <p class="text-xs text-surface-500">Laporan kuesioner masuk</p>
            </div>
            <Link href="/admin/tracer-responses" class="inline-flex items-center gap-1 text-xs font-medium text-primary-600 hover:text-primary-800 transition-colors">
                <span>Lihat Semua</span>
                <ArrowUpRight class="w-3 h-3" />
            </Link>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-surface-50 border-b border-surface-200 text-surface-600 font-medium">
                    <tr>
                        <th class="px-4 py-2.5">Nama</th>
                        <th class="px-4 py-2.5">Jurusan</th>
                        <th class="px-4 py-2.5">Status</th>
                        <th class="px-4 py-2.5">Detail</th>
                        <th class="px-4 py-2.5 text-right"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-100">
                    <tr v-for="res in responses" :key="res.id" class="hover:bg-surface-50 transition-colors">
                        <td class="px-4 py-3">
                            <div class="font-medium text-surface-900">{{ res.alumni?.nama || '-' }}</div>
                            <div class="text-[10px] text-surface-400">NISN: {{ res.alumni?.nisn || '-' }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-surface-700 font-medium text-xs">{{ res.alumni?.jurusan || '-' }}</span>
                            <div class="text-[10px] text-surface-400">{{ res.alumni?.tahun_lulus || '-' }}</div>
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-medium border" :class="getStatusBadge(res.status_utama).class">
                                <component :is="getStatusBadge(res.status_utama).icon" class="w-2.5 h-2.5" />
                                {{ getStatusBadge(res.status_utama).label }}
                            </span>
                        </td>
                        <td class="px-4 py-3 max-w-[140px] truncate text-surface-600 text-xs">
                            {{ getDetailSummary(res) }}
                        </td>
                        <td class="px-4 py-3 text-right">
                            <button @click="$emit('view', res)" class="inline-flex items-center gap-1 px-2 py-1 rounded text-[10px] font-medium text-surface-600 hover:text-primary-600 hover:bg-primary-50 transition-colors icon-pulse">
                                <Eye class="w-3 h-3" />
                            </button>
                        </td>
                    </tr>
                    <tr v-if="responses.length === 0">
                        <td colspan="5" class="px-4 py-8 text-center text-surface-400 text-xs">
                            Belum ada data kuesioner.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>