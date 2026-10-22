<script setup>
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ResponseDetailModal from '@/Components/Admin/ResponseDetailModal.vue';
import {
    FileSpreadsheet,
    Search,
    Filter,
    Eye,
    Trash2,
    Building,
    GraduationCap,
    Store,
    Calendar,
    CheckCircle2,
    AlertCircle
} from 'lucide-vue-next';

const props = defineProps({
    responses: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        default: () => ({ search: '', status: '', tahun: '' }),
    },
    tahunOptions: {
        type: Array,
        default: () => [],
    },
});

const search = ref(props.filters.search);
const selectedStatus = ref(props.filters.status);
const selectedTahun = ref(props.filters.tahun);

const selectedResponse = ref(null);
const showDetailModal = ref(false);

const openDetail = (res) => {
    selectedResponse.value = res;
    showDetailModal.value = true;
};

const closeDetail = () => {
    showDetailModal.value = false;
    selectedResponse.value = null;
};

const deleteResponse = (res) => {
    if (confirm(`Apakah Anda yakin ingin menghapus data kuesioner dari "${res.alumni?.nama}"?`)) {
        router.delete(`/admin/tracer-responses/${res.id}`);
    }
};

const applyFilters = () => {
    router.get('/admin/tracer-responses', {
        search: search.value || undefined,
        status: selectedStatus.value || undefined,
        tahun: selectedTahun.value || undefined,
    }, {
        preserveState: true,
        replace: true,
    });
};

const resetFilters = () => {
    search.value = '';
    selectedStatus.value = '';
    selectedTahun.value = '';
    applyFilters();
};

const getStatusBadge = (status) => {
    switch (status) {
        case 'Bekerja':
            return {
                label: 'Bekerja',
                class: 'bg-emerald-50 text-emerald-700 border-emerald-200',
            };
        case 'Kuliah':
            return {
                label: 'Kuliah',
                class: 'bg-blue-50 text-blue-700 border-blue-200',
            };
        case 'Wirausaha':
            return {
                label: 'Wirausaha',
                class: 'bg-amber-50 text-amber-700 border-amber-200',
            };
        default:
            return {
                label: 'Mencari Kerja',
                class: 'bg-rose-50 text-rose-700 border-rose-200',
            };
    }
};
</script>

<template>
    <Head title="Hasil Tracer Study - Admin" />

    <AdminLayout title="Hasil Kuesioner Tracer Study">
        <div class="space-y-6 max-w-7xl mx-auto">
            <!-- Header bar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
                <div>
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                        <FileSpreadsheet class="w-5 h-5 text-indigo-600" />
                        <span>Daftar Kuesioner Terverifikasi</span>
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">
                        Total {{ responses.total }} respon kuesioner tracer study telah tersimpan.
                    </p>
                </div>
            </div>

            <!-- Filters Bar -->
            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs grid grid-cols-1 sm:grid-cols-12 gap-3">
                <!-- Search Box -->
                <div class="sm:col-span-5 relative">
                    <Search class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
                    <input
                        v-model="search"
                        @keyup.enter="applyFilters"
                        type="text"
                        placeholder="Cari nama alumni, instansi, atau kampus..."
                        class="w-full pl-10 pr-4 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                    />
                </div>

                <!-- Status BMW Dropdown -->
                <div class="sm:col-span-3">
                    <select
                        v-model="selectedStatus"
                        @change="applyFilters"
                        class="w-full px-3 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:border-indigo-500 text-slate-700"
                    >
                        <option value="">Semua Status BMW</option>
                        <option value="Bekerja">Bekerja</option>
                        <option value="Kuliah">Kuliah (Melanjutkan)</option>
                        <option value="Wirausaha">Wirausaha</option>
                        <option value="Mencari Kerja">Mencari Kerja</option>
                    </select>
                </div>

                <!-- Tahun Tracer Dropdown -->
                <div class="sm:col-span-2">
                    <select
                        v-model="selectedTahun"
                        @change="applyFilters"
                        class="w-full px-3 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:border-indigo-500 text-slate-700"
                    >
                        <option value="">Semua Tahun Tracer</option>
                        <option v-for="tahun in tahunOptions" :key="tahun" :value="tahun">
                            Tahun {{ tahun }}
                        </option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="sm:col-span-2 flex items-center gap-2">
                    <button
                        @click="applyFilters"
                        class="flex-1 py-2 px-3 text-xs font-semibold bg-slate-900 hover:bg-slate-800 text-white rounded-xl transition-colors text-center"
                    >
                        Filter
                    </button>
                    <button
                        @click="resetFilters"
                        class="py-2 px-3 text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-colors"
                        title="Reset Filter"
                    >
                        Reset
                    </button>
                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-2xl border border-slate-200/80 shadow-xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-slate-50/80 border-b border-slate-200 text-slate-500 uppercase tracking-wider font-semibold">
                            <tr>
                                <th class="px-6 py-3.5">Nama & Jurusan</th>
                                <th class="px-6 py-3.5">Status BMW</th>
                                <th class="px-6 py-3.5">Detail Aktivitas</th>
                                <th class="px-6 py-3.5">Linearitas</th>
                                <th class="px-6 py-3.5">Tahun Tracer</th>
                                <th class="px-6 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr
                                v-for="res in responses.data"
                                :key="res.id"
                                class="hover:bg-slate-50/60 transition-colors"
                            >
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-900">{{ res.alumni?.nama || '-' }}</div>
                                    <div class="text-[11px] text-slate-500">{{ res.alumni?.jurusan || '-' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-full text-[11px] font-bold border"
                                        :class="getStatusBadge(res.status_utama).class"
                                    >
                                        {{ getStatusBadge(res.status_utama).label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 max-w-xs truncate">
                                    <div v-if="res.status_utama === 'Bekerja'" class="text-slate-800">
                                        <div class="font-semibold">{{ res.nama_perusahaan || '-' }}</div>
                                        <div class="text-[11px] text-slate-400">{{ res.jabatan || 'Staff' }} • {{ res.kisaran_gaji || '-' }}</div>
                                    </div>
                                    <div v-else-if="res.status_utama === 'Kuliah'" class="text-slate-800">
                                        <div class="font-semibold">{{ res.nama_kampus || '-' }}</div>
                                        <div class="text-[11px] text-slate-400">{{ res.program_studi || '-' }}</div>
                                    </div>
                                    <div v-else-if="res.status_utama === 'Wirausaha'" class="text-slate-800">
                                        <div class="font-semibold">{{ res.nama_usaha || '-' }}</div>
                                        <div class="text-[11px] text-slate-400">{{ res.bidang_usaha || '-' }} • Omset: {{ res.omset_bulanan || '-' }}</div>
                                    </div>
                                    <div v-else class="text-slate-500 italic">
                                        Sedang mencari kerja
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        v-if="res.status_utama === 'Bekerja'"
                                        class="inline-flex items-center gap-1 text-[11px] font-bold px-2 py-0.5 rounded-md"
                                        :class="res.linear_dengan_jurusan ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700'"
                                    >
                                        <CheckCircle2 v-if="res.linear_dengan_jurusan" class="w-3.5 h-3.5" />
                                        <AlertCircle v-else class="w-3.5 h-3.5" />
                                        {{ res.linear_dengan_jurusan ? 'Sesuai' : 'Lintas' }}
                                    </span>
                                    <span v-else class="text-slate-400 text-[11px]">-</span>
                                </td>
                                <td class="px-6 py-4 text-slate-600 font-medium">
                                    {{ res.tahun_tracer }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button
                                            @click="openDetail(res)"
                                            class="p-1.5 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
                                            title="Lihat Detail Kuesioner"
                                        >
                                            <Eye class="w-4 h-4" />
                                        </button>
                                        <button
                                            @click="deleteResponse(res)"
                                            class="p-1.5 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors"
                                            title="Hapus Data"
                                        >
                                            <Trash2 class="w-4 h-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="responses.data.length === 0">
                                <td colspan="6" class="px-6 py-12 text-center text-slate-400">
                                    Belum ada hasil kuesioner yang sesuai kriteria pencarian.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination footer -->
                <div v-if="responses.links && responses.links.length > 3" class="px-6 py-4 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs text-slate-500">
                        Menampilkan {{ responses.from || 0 }} - {{ responses.to || 0 }} dari {{ responses.total }} kuesioner
                    </span>
                    <div class="flex items-center gap-1">
                        <component
                            :is="link.url ? 'a' : 'span'"
                            v-for="(link, index) in responses.links"
                            :key="index"
                            :href="link.url"
                            v-html="link.label"
                            :class="[
                                'px-3 py-1.5 text-xs rounded-lg transition-colors',
                                link.active
                                    ? 'bg-indigo-600 text-white font-bold'
                                    : link.url
                                    ? 'text-slate-600 hover:bg-slate-100'
                                    : 'text-slate-300 pointer-events-none'
                            ]"
                        />
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Modal -->
        <ResponseDetailModal
            :show="showDetailModal"
            :response="selectedResponse"
            @close="closeDetail"
        />
    </AdminLayout>
</template>
