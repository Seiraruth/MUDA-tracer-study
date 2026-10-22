<script setup>
import { Link } from '@inertiajs/vue3';
import { Eye, ArrowUpRight, FileSpreadsheet, Building, GraduationCap, Store, Search } from 'lucide-vue-next';

defineProps({
    responses: {
        type: Array,
        default: () => [],
    },
});

defineEmits(['view']);

const getStatusBadge = (status) => {
    switch (status) {
        case 'Bekerja':
            return {
                label: 'Bekerja',
                class: 'bg-emerald-50 text-emerald-700 border-emerald-200',
                icon: Building,
            };
        case 'Kuliah':
            return {
                label: 'Kuliah',
                class: 'bg-blue-50 text-blue-700 border-blue-200',
                icon: GraduationCap,
            };
        case 'Wirausaha':
            return {
                label: 'Wirausaha',
                class: 'bg-amber-50 text-amber-700 border-amber-200',
                icon: Store,
            };
        default:
            return {
                label: 'Mencari Kerja',
                class: 'bg-rose-50 text-rose-700 border-rose-200',
                icon: Search,
            };
    }
};

const getDetailSummary = (res) => {
    if (res.status_utama === 'Bekerja') {
        return `${res.nama_perusahaan || '-'} (${res.jabatan || 'Staff'})`;
    } else if (res.status_utama === 'Kuliah') {
        return `${res.nama_kampus || '-'} - ${res.program_studi || '-'}`;
    } else if (res.status_utama === 'Wirausaha') {
        return `${res.nama_usaha || '-'} (${res.bidang_usaha || 'Usaha'})`;
    }
    return 'Sedang mencari lowongan kerja yang relevan';
};
</script>

<template>
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h3 class="font-bold text-base text-slate-900 tracking-tight flex items-center gap-2">
                    Responden Tracer Terbaru
                </h3>
                <p class="text-xs text-slate-500">Laporan kuesioner kelulusan yang baru masuk ke sistem</p>
            </div>
            <Link
                href="/admin/tracer-responses"
                class="inline-flex items-center gap-1.5 text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition-colors"
            >
                <span>Lihat Semua Kuesioner</span>
                <ArrowUpRight class="w-3.5 h-3.5" />
            </Link>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50/75 border-b border-slate-200/80 text-slate-500 uppercase tracking-wider font-semibold">
                    <tr>
                        <th class="px-6 py-3.5">Nama & NISN</th>
                        <th class="px-6 py-3.5">Jurusan</th>
                        <th class="px-6 py-3.5">Status BMW</th>
                        <th class="px-6 py-3.5">Aktivitas / Penempatan</th>
                        <th class="px-6 py-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <tr
                        v-for="res in responses"
                        :key="res.id"
                        class="hover:bg-slate-50/70 transition-colors"
                    >
                        <td class="px-6 py-4">
                            <div class="font-bold text-slate-900">{{ res.alumni?.nama || '-' }}</div>
                            <div class="text-[11px] text-slate-400">NISN: {{ res.alumni?.nisn || '-' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="text-slate-700 font-medium">{{ res.alumni?.jurusan || '-' }}</span>
                            <div class="text-[11px] text-slate-400">Angkatan: {{ res.alumni?.tahun_lulus || '-' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span
                                class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[11px] font-bold border"
                                :class="getStatusBadge(res.status_utama).class"
                            >
                                <component :is="getStatusBadge(res.status_utama).icon" class="w-3 h-3" />
                                {{ getStatusBadge(res.status_utama).label }}
                            </span>
                        </td>
                        <td class="px-6 py-4 max-w-xs truncate text-slate-600">
                            {{ getDetailSummary(res) }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button
                                @click="$emit('view', res)"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-indigo-50 hover:text-indigo-600 transition-colors border border-slate-200"
                            >
                                <Eye class="w-3.5 h-3.5" />
                                <span>Detail</span>
                            </button>
                        </td>
                    </tr>
                    <tr v-if="responses.length === 0">
                        <td colspan="5" class="px-6 py-10 text-center text-slate-400">
                            Belum ada data kuesioner tracer yang masuk.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>
