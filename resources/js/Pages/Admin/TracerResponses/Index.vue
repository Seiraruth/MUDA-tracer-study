<script setup>
import { ref } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import ResponseDetailModal from '@/Components/Admin/ResponseDetailModal.vue';
import {
    FileSpreadsheet,
    Search,
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
                class: 'bg-emerald-100 text-emerald-700 border-emerald-200',
            };
        case 'Kuliah':
            return {
                label: 'Kuliah',
                class: 'bg-blue-100 text-blue-700 border-blue-200',
            };
        case 'Wirausaha':
            return {
                label: 'Wirausaha',
                class: 'bg-amber-100 text-amber-700 border-amber-200',
            };
        default:
            return {
                label: 'Mencari Kerja',
                class: 'bg-rose-100 text-rose-700 border-rose-200',
            };
    }
};
</script>

<template>
    <Head title="Hasil Tracer Study - Admin" />

    <AdminLayout title="Hasil Tracer Study">
        <div class="space-y-6 max-w-7xl mx-auto">
            <!-- Header bar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-7 rounded-2xl border border-surface-200 shadow-md animate-in">
                <div>
                    <h2 class="text-2xl font-extrabold text-surface-900 tracking-tight flex items-center gap-3">
                        <FileSpreadsheet class="w-6 h-6 text-primary-600" />
                        <span>Data Responden Tracer Study</span>
                    </h2>
                    <p class="text-sm text-surface-600 mt-2 font-medium">
                        Total <strong class="text-surface-900">{{ responses.total }}</strong> kuesioner tersimpan dalam database
                    </p>
                </div>
            </div>

            <!-- Filters Bar -->
            <div class="bg-white p-5 rounded-2xl border border-surface-200 shadow-sm grid grid-cols-1 sm:grid-cols-12 gap-3 animate-in animate-in-delay-1">
                <!-- Search Box -->
                <div class="sm:col-span-5 relative">
                    <Search class="w-4 h-4 text-surface-400 absolute left-4 top-1/2 -translate-y-1/2" />
                    <input
                        v-model="search"
                        @keyup.enter="applyFilters"
                        type="text"
                        placeholder="Cari nama alumni, instansi, atau kampus"
                        class="w-full pl-11 pr-4 py-2.5 text-sm rounded-xl bg-surface-50 border border-surface-200 focus:bg-white focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all text-surface-900 font-medium placeholder:text-surface-400 placeholder:font-normal"
                    />
                </div>

                <!-- Status BMW Dropdown -->
                <div class="sm:col-span-3">
                    <select
                        v-model="selectedStatus"
                        @change="applyFilters"
                        class="w-full px-4 py-2.5 text-sm rounded-xl bg-surface-50 border border-surface-200 focus:bg-white focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 text-surface-800 font-medium transition-all"
                    >
                        <option value="">Semua Status</option>
                        <option value="Bekerja">Bekerja</option>
                        <option value="Kuliah">Kuliah</option>
                        <option value="Wirausaha">Wirausaha</option>
                        <option value="Mencari Kerja">Mencari Kerja</option>
                    </select>
                </div>

                <!-- Tahun Tracer Dropdown -->
                <div class="sm:col-span-2">
                    <select
                        v-model="selectedTahun"
                        @change="applyFilters"
                        class="w-full px-4 py-2.5 text-sm rounded-xl bg-surface-50 border border-surface-200 focus:bg-white focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 text-surface-800 font-medium transition-all"
                    >
                        <option value="">Semua Tahun</option>
                        <option v-for="tahun in tahunOptions" :key="tahun" :value="tahun">
                            {{ tahun }}
                        </option>
                    </select>
                </div>

                <!-- Buttons -->
                <div class="sm:col-span-2 flex items-center gap-2">
                    <button
                        @click="applyFilters"
                        class="flex-1 py-2.5 px-4 text-sm font-bold bg-primary-600 hover:bg-primary-700 text-white rounded-xl transition-all shadow-sm hover:shadow-md"
                    >
                        Terapkan
                    </button>
                    <button
                        @click="resetFilters"
                        class="py-2.5 px-4 text-sm font-bold bg-surface-100 hover:bg-surface-200 text-surface-700 rounded-xl transition-all"
                        title="Reset Filter"
                    >
                        Reset
                    </button>
                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-2xl border border-surface-200 overflow-hidden shadow-md animate-in animate-in-delay-2">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead class="bg-gradient-to-r from-surface-50 to-white border-b border-surface-200 text-surface-700 uppercase tracking-wider font-extrabold">
                            <tr>
                                <th class="px-6 py-4 text-xs">Nama & Jurusan</th>
                                <th class="px-6 py-4 text-xs">Status BMW</th>
                                <th class="px-6 py-4 text-xs">Detail Aktivitas</th>
                                <th class="px-6 py-4 text-xs">Linearitas</th>
                                <th class="px-6 py-4 text-xs">Tahun Tracer</th>
                                <th class="px-6 py-4 text-right text-xs">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-surface-100">
                            <tr
                                v-for="res in responses.data"
                                :key="res.id"
                                class="hover:bg-surface-50/80 transition-all group"
                            >
                                <td class="px-6 py-4">
                                    <div class="font-extrabold text-surface-900">{{ res.alumni?.nama || '-' }}</div>
                                    <div class="text-xs text-surface-600 font-medium mt-0.5">{{ res.alumni?.jurusan || '-' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-extrabold border shadow-sm"
                                        :class="getStatusBadge(res.status_utama).class"
                                    >
                                        {{ getStatusBadge(res.status_utama).label }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 max-w-xs">
                                    <div v-if="res.status_utama === 'Bekerja'" class="text-surface-800">
                                        <div class="font-bold truncate">{{ res.nama_perusahaan || '-' }}</div>
                                        <div class="text-xs text-surface-600 font-medium truncate mt-0.5">{{ res.jabatan || 'Staff' }} · {{ res.kisaran_gaji || '-' }}</div>
                                    </div>
                                    <div v-else-if="res.status_utama === 'Kuliah'" class="text-surface-800">
                                        <div class="font-bold truncate">{{ res.nama_kampus || '-' }}</div>
                                        <div class="text-xs text-surface-600 font-medium truncate mt-0.5">{{ res.program_studi || '-' }}</div>
                                    </div>
                                    <div v-else-if="res.status_utama === 'Wirausaha'" class="text-surface-800">
                                        <div class="font-bold truncate">{{ res.nama_usaha || '-' }}</div>
                                        <div class="text-xs text-surface-600 font-medium truncate mt-0.5">{{ res.bidang_usaha || '-' }} · {{ res.omset_bulanan || '-' }}</div>
                                    </div>
                                    <div v-else class="text-surface-600 italic font-medium">
                                        Sedang mencari kerja
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        v-if="res.status_utama === 'Bekerja'"
                                        class="inline-flex items-center gap-1.5 text-xs font-extrabold px-2.5 py-1 rounded-lg"
                                        :class="res.linear_dengan_jurusan ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800'"
                                    >
                                        <CheckCircle2 v-if="res.linear_dengan_jurusan" class="w-3.5 h-3.5" />
                                        <AlertCircle v-else class="w-3.5 h-3.5" />
                                        {{ res.linear_dengan_jurusan ? 'Linear' : 'Lintas' }}
                                    </span>
                                    <span v-else class="text-surface-400 text-xs font-medium">-</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-surface-100 text-surface-800 text-xs font-bold">
                                        {{ res.tahun_tracer }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button
                                            @click="openDetail(res)"
                                            class="p-2 text-surface-500 hover:text-primary-600 hover:bg-primary-50 rounded-xl transition-all group-hover:scale-105"
                                            title="Lihat Detail"
                                        >
                                            <Eye class="w-4 h-4" />
                                        </button>
                                        <button
                                            @click="deleteResponse(res)"
                                            class="p-2 text-surface-500 hover:text-error hover:bg-rose-50 rounded-xl transition-all group-hover:scale-105"
                                            title="Hapus"
                                        >
                                            <Trash2 class="w-4 h-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="responses.data.length === 0">
                                <td colspan="6" class="px-6 py-16 text-center text-surface-500 font-medium">
                                    Tidak ada data yang sesuai dengan filter pencarian
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination footer -->
                <div v-if="responses.links && responses.links.length > 3" class="px-6 py-4 border-t border-surface-200 flex items-center justify-between bg-surface-50/50">
                    <span class="text-sm text-surface-600 font-medium">
                        Menampilkan <strong class="text-surface-900">{{ responses.from || 0 }} - {{ responses.to || 0 }}</strong> dari <strong class="text-surface-900">{{ responses.total }}</strong> data
                    </span>
                    <div class="flex items-center gap-1">
                        <component
                            :is="link.url ? 'a' : 'span'"
                            v-for="(link, index) in responses.links"
                            :key="index"
                            :href="link.url"
                            v-html="link.label"
                            :class="[
                                'px-3.5 py-2 text-sm rounded-lg transition-all font-bold',
                                link.active
                                    ? 'bg-primary-600 text-white shadow-md'
                                    : link.url
                                    ? 'text-surface-700 hover:bg-surface-100'
                                    : 'text-surface-300 pointer-events-none'
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