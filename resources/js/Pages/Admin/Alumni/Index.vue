<script setup>
import { ref, computed } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import {
    Users,
    Plus,
    Search,
    Filter,
    Edit2,
    Trash2,
    Calendar,
    Phone,
    Mail,
    X,
    ChevronLeft,
    ChevronRight,
    GraduationCap,
    Download,
    Upload,
    FileSpreadsheet,
    FileUp,
    AlertCircle,
    CheckCircle2,
    Info
} from 'lucide-vue-next';

const props = defineProps({
    alumni: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        default: () => ({ search: '', jurusan: '', tahun: '' }),
    },
    jurusanOptions: {
        type: Array,
        default: () => [],
    },
    tahunOptions: {
        type: Array,
        default: () => [],
    },
});

const search = ref(props.filters.search);
const selectedJurusan = ref(props.filters.jurusan);
const selectedTahun = ref(props.filters.tahun);

// Modal Create / Edit state
const isModalOpen = ref(false);
const editingAlumni = ref(null);

const form = useForm({
    nisn: '',
    nama: '',
    tanggal_lahir: '',
    jurusan: 'Teknik Komputer dan Jaringan',
    tahun_lulus: new Date().getFullYear(),
    no_hp: '',
    email: '',
    alamat: '',
});

// Modal Import state
const isImportModalOpen = ref(false);
const fileInput = ref(null);
const selectedFileName = ref('');

const importForm = useForm({
    file: null,
});

const openCreateModal = () => {
    editingAlumni.value = null;
    form.reset();
    form.clearErrors();
    form.tahun_lulus = new Date().getFullYear();
    form.jurusan = props.jurusanOptions[0] || 'Teknik Komputer dan Jaringan';
    isModalOpen.value = true;
};

const openEditModal = (item) => {
    editingAlumni.value = item;
    form.clearErrors();
    form.nisn = item.nisn;
    form.nama = item.nama;
    form.tanggal_lahir = item.tanggal_lahir;
    form.jurusan = item.jurusan;
    form.tahun_lulus = item.tahun_lulus;
    form.no_hp = item.no_hp || '';
    form.email = item.email || '';
    form.alamat = item.alamat || '';
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    editingAlumni.value = null;
    form.reset();
};

const submitForm = () => {
    if (editingAlumni.value) {
        form.put(`/admin/alumni/${editingAlumni.value.id}`, {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post('/admin/alumni', {
            onSuccess: () => closeModal(),
        });
    }
};

const deleteAlumni = (item) => {
    if (confirm(`Apakah Anda yakin ingin menghapus data alumni "${item.nama}"?`)) {
        router.delete(`/admin/alumni/${item.id}`);
    }
};

const applyFilters = () => {
    router.get('/admin/alumni', {
        search: search.value || undefined,
        jurusan: selectedJurusan.value || undefined,
        tahun: selectedTahun.value || undefined,
    }, {
        preserveState: true,
        replace: true,
    });
};

const resetFilters = () => {
    search.value = '';
    selectedJurusan.value = '';
    selectedTahun.value = '';
    applyFilters();
};

// Export URL with current filters
const exportUrl = computed(() => {
    const params = new URLSearchParams();
    if (search.value) params.append('search', search.value);
    if (selectedJurusan.value) params.append('jurusan', selectedJurusan.value);
    if (selectedTahun.value) params.append('tahun', selectedTahun.value);
    const qs = params.toString();
    return `/admin/alumni/export${qs ? '?' + qs : ''}`;
});

// Import Handling
const openImportModal = () => {
    importForm.reset();
    importForm.clearErrors();
    selectedFileName.value = '';
    if (fileInput.value) fileInput.value.value = '';
    isImportModalOpen.value = true;
};

const closeImportModal = () => {
    isImportModalOpen.value = false;
    importForm.reset();
    selectedFileName.value = '';
};

const handleFileSelect = (event) => {
    const file = event.target.files[0];
    if (file) {
        importForm.file = file;
        selectedFileName.value = `${file.name} (${(file.size / 1024).toFixed(1)} KB)`;
    }
};

const submitImport = () => {
    if (!importForm.file) {
        importForm.setError('file', 'Silakan pilih file Excel terlebih dahulu.');
        return;
    }
    importForm.post('/admin/alumni/import', {
        forceFormData: true,
        onSuccess: () => closeImportModal(),
    });
};
</script>

<template>
    <Head title="Kelola Data Alumni - Admin" />

    <AdminLayout title="Data Alumni SMK">
        <div class="space-y-6 max-w-7xl mx-auto">
            <!-- Header bar with Actions -->
            <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-surface-200 animate-in">
                <div>
                    <h2 class="text-xl font-bold text-surface-900 tracking-tight flex items-center gap-2">
                        <Users class="w-5 h-5 text-primary-600 icon-pulse" />
                        <span>Database Alumni</span>
                    </h2>
                    <p class="text-xs text-surface-600 mt-1">
                        Total {{ alumni.total }} alumni tercatat di sistem tracer study.
                    </p>
                </div>

                <!-- Action Buttons: Export, Import, Add -->
                <div class="flex flex-wrap items-center gap-2.5">
                    <!-- Export Button -->
                    <a
                        :href="exportUrl"
                        class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-white hover:bg-surface-50 text-surface-700 border border-surface-200 text-xs font-semibold transition-all hover-lift"
                        title="Unduh seluruh atau data hasil filter ke file Excel"
                    >
                        <Download class="w-4 h-4 text-success icon-pulse" />
                        <span>Ekspor Excel</span>
                    </a>

                    <!-- Import Button -->
                    <button
                        @click="openImportModal"
                        class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-primary-50 hover:bg-primary-100 text-primary-700 border border-primary-200 text-xs font-semibold transition-all hover-lift"
                    >
                        <Upload class="w-4 h-4 text-primary-600 icon-pulse" />
                        <span>Impor Excel</span>
                    </button>

                    <!-- Add Alumni Button -->
                    <button
                        @click="openCreateModal"
                        class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-700 text-white text-xs font-bold transition-all shadow-md hover-lift"
                    >
                        <Plus class="w-4 h-4" />
                        <span>Tambah Alumni Baru</span>
                    </button>
                </div>
            </div>

            <!-- Filters Bar -->
            <div class="bg-white p-4 rounded-2xl border border-surface-200 grid grid-cols-1 sm:grid-cols-12 gap-3 animate-in animate-in-delay-1">
                <!-- Search Box -->
                <div class="sm:col-span-5 relative">
                    <Search class="w-4 h-4 text-surface-500 absolute left-3.5 top-1/2 -translate-y-1/2" />
                    <input
                        v-model="search"
                        @keyup.enter="applyFilters"
                        type="text"
                        placeholder="Cari nama, NISN, atau email..."
                        class="w-full pl-10 pr-4 py-2 text-xs rounded-xl bg-surface-50 border border-surface-200 focus:bg-white focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-colors text-surface-900"
                    />
                </div>

                <!-- Jurusan Dropdown -->
                <div class="sm:col-span-3">
                    <select
                        v-model="selectedJurusan"
                        @change="applyFilters"
                        class="w-full px-3 py-2 text-xs rounded-xl bg-surface-50 border border-surface-200 focus:bg-white focus:outline-none focus:border-primary-500 text-surface-800"
                    >
                        <option value="">Semua Jurusan</option>
                        <option v-for="jurusan in jurusanOptions" :key="jurusan" :value="jurusan">
                            {{ jurusan }}
                        </option>
                    </select>
                </div>

                <!-- Tahun Lulus Dropdown -->
                <div class="sm:col-span-2">
                    <select
                        v-model="selectedTahun"
                        @change="applyFilters"
                        class="w-full px-3 py-2 text-xs rounded-xl bg-surface-50 border border-surface-200 focus:bg-white focus:outline-none focus:border-primary-500 text-surface-800"
                    >
                        <option value="">Semua Tahun</option>
                        <option v-for="tahun in tahunOptions" :key="tahun" :value="tahun">
                            Angkatan {{ tahun }}
                        </option>
                    </select>
                </div>

                <!-- Reset Button -->
                <div class="sm:col-span-2 flex items-center gap-2">
                    <button
                        @click="applyFilters"
                        class="flex-1 py-2 px-3 text-xs font-semibold bg-primary-600 hover:bg-primary-700 text-white rounded-xl transition-colors text-center"
                    >
                        Cari
                    </button>
                    <button
                        @click="resetFilters"
                        class="py-2 px-3 text-xs font-semibold bg-surface-100 hover:bg-surface-200 text-surface-700 rounded-xl transition-colors"
                        title="Reset Filter"
                    >
                        Reset
                    </button>
                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-2xl border border-surface-200 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-surface-50 border-b border-surface-200 text-surface-600 uppercase tracking-wider font-semibold">
                            <tr>
                                <th class="px-6 py-3.5">NISN & Nama</th>
                                <th class="px-6 py-3.5">Jurusan</th>
                                <th class="px-6 py-3.5">Angkatan</th>
                                <th class="px-6 py-3.5">Kontak</th>
                                <th class="px-6 py-3.5">Status Kuesioner</th>
                                <th class="px-6 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-surface-100">
                            <tr
                                v-for="item in alumni.data"
                                :key="item.id"
                                class="hover:bg-surface-50 transition-colors"
                            >
                                <td class="px-6 py-4">
                                    <div class="font-bold text-surface-900">{{ item.nama }}</div>
                                    <div class="text-[11px] text-surface-500">NISN: {{ item.nisn }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-medium text-surface-800">{{ item.jurusan }}</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 py-0.5 rounded-full bg-primary-100 font-semibold text-primary-700">
                                        {{ item.tahun_lulus }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <div v-if="item.no_hp" class="flex items-center gap-1.5 text-surface-700">
                                        <Phone class="w-3 h-3 text-surface-500" />
                                        <span>{{ item.no_hp }}</span>
                                    </div>
                                    <div v-if="item.email" class="flex items-center gap-1.5 text-surface-500 text-[11px]">
                                        <Mail class="w-3 h-3" />
                                        <span>{{ item.email }}</span>
                                    </div>
                                    <span v-if="!item.no_hp && !item.email" class="text-surface-400">-</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        v-if="(item.responses && item.responses.length > 0) || (item.tracer_responses && item.tracer_responses.length > 0)"
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800"
                                    >
                                        Sudah Mengisi ({{ (item.responses?.[0] || item.tracer_responses?.[0])?.status_utama }})
                                    </span>
                                    <span
                                        v-else
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-amber-100 text-amber-800"
                                    >
                                        Belum Mengisi
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button
                                            @click="openEditModal(item)"
                                            class="p-1.5 text-surface-500 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors icon-pulse"
                                            title="Edit Alumni"
                                        >
                                            <Edit2 class="w-4 h-4" />
                                        </button>
                                        <button
                                            @click="deleteAlumni(item)"
                                            class="p-1.5 text-surface-500 hover:text-error hover:bg-rose-50 rounded-lg transition-colors icon-pulse"
                                            title="Hapus Alumni"
                                        >
                                            <Trash2 class="w-4 h-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="alumni.data.length === 0">
                                <td colspan="6" class="px-6 py-12 text-center text-surface-400">
                                    Tidak ada data alumni yang cocok dengan pencarian.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination footer -->
                <div v-if="alumni.links && alumni.links.length > 3" class="px-6 py-4 border-t border-surface-100 flex items-center justify-between">
                    <span class="text-xs text-surface-500">
                        Menampilkan {{ alumni.from || 0 }} - {{ alumni.to || 0 }} dari {{ alumni.total }} alumni
                    </span>
                    <div class="flex items-center gap-1">
                        <component
                            :is="link.url ? 'a' : 'span'"
                            v-for="(link, index) in alumni.links"
                            :key="index"
                            :href="link.url"
                            v-html="link.label"
                            :class="[
                                'px-3 py-1.5 text-xs rounded-lg transition-colors',
                                link.active
                                    ? 'bg-primary-600 text-white font-bold'
                                    : link.url
                                    ? 'text-surface-700 hover:bg-surface-100'
                                    : 'text-surface-300 pointer-events-none'
                            ]"
                        />
                    </div>
                </div>
            </div>
        </div>

        <!-- Add / Edit Modal -->
        <div
            v-if="isModalOpen"
            class="fixed inset-0 z-50 overflow-y-auto bg-surface-900/70 backdrop-blur-sm flex items-center justify-center p-4"
            @click.self="closeModal"
        >
            <div class="bg-white rounded-2xl max-w-xl w-full shadow-2xl border border-surface-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-surface-100 flex items-center justify-between bg-surface-50">
                    <h3 class="font-bold text-base text-surface-900">
                        {{ editingAlumni ? 'Edit Data Alumni' : 'Tambah Alumni Baru' }}
                    </h3>
                    <button @click="closeModal" class="p-2 text-surface-500 hover:text-surface-700 rounded-lg transition-colors icon-pulse">
                        <X class="w-5 h-5" />
                    </button>
                </div>

                <form @submit.prevent="submitForm" class="p-6 space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div class="space-y-1">
                            <label class="font-semibold text-surface-700">NISN (Nomor Induk Siswa Nasional) *</label>
                            <input
                                v-model="form.nisn"
                                type="text"
                                required
                                class="w-full px-3 py-2 rounded-xl bg-surface-50 border border-surface-200 focus:bg-white focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 text-surface-900"
                            />
                            <p v-if="form.errors.nisn" class="text-error text-[11px]">{{ form.errors.nisn }}</p>
                        </div>

                        <div class="space-y-1">
                            <label class="font-semibold text-surface-700">Nama Lengkap *</label>
                            <input
                                v-model="form.nama"
                                type="text"
                                required
                                class="w-full px-3 py-2 rounded-xl bg-surface-50 border border-surface-200 focus:bg-white focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 text-surface-900"
                            />
                            <p v-if="form.errors.nama" class="text-error text-[11px]">{{ form.errors.nama }}</p>
                        </div>

                        <div class="space-y-1">
                            <label class="font-semibold text-surface-700">Tanggal Lahir *</label>
                            <input
                                v-model="form.tanggal_lahir"
                                type="date"
                                required
                                class="w-full px-3 py-2 rounded-xl bg-surface-50 border border-surface-200 focus:bg-white focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 text-surface-900"
                            />
                            <p v-if="form.errors.tanggal_lahir" class="text-error text-[11px]">{{ form.errors.tanggal_lahir }}</p>
                        </div>

                        <div class="space-y-1">
                            <label class="font-semibold text-surface-700">Tahun Lulus (Angkatan) *</label>
                            <input
                                v-model="form.tahun_lulus"
                                type="number"
                                required
                                class="w-full px-3 py-2 rounded-xl bg-surface-50 border border-surface-200 focus:bg-white focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 text-surface-900"
                            />
                            <p v-if="form.errors.tahun_lulus" class="text-error text-[11px]">{{ form.errors.tahun_lulus }}</p>
                        </div>

                        <div class="sm:col-span-2 space-y-1">
                            <label class="font-semibold text-surface-700">Jurusan / Program Keahlian *</label>
                            <input
                                v-model="form.jurusan"
                                type="text"
                                required
                                placeholder="Contoh: Teknik Komputer dan Jaringan"
                                class="w-full px-3 py-2 rounded-xl bg-surface-50 border border-surface-200 focus:bg-white focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 text-surface-900"
                            />
                            <p v-if="form.errors.jurusan" class="text-error text-[11px]">{{ form.errors.jurusan }}</p>
                        </div>

                        <div class="space-y-1">
                            <label class="font-semibold text-surface-700">No. WhatsApp / HP</label>
                            <input
                                v-model="form.no_hp"
                                type="text"
                                placeholder="08xxxxxxxx"
                                class="w-full px-3 py-2 rounded-xl bg-surface-50 border border-surface-200 focus:bg-white focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 text-surface-900"
                            />
                        </div>

                        <div class="space-y-1">
                            <label class="font-semibold text-surface-700">Email</label>
                            <input
                                v-model="form.email"
                                type="email"
                                placeholder="alumni@email.com"
                                class="w-full px-3 py-2 rounded-xl bg-surface-50 border border-surface-200 focus:bg-white focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 text-surface-900"
                            />
                        </div>

                        <div class="sm:col-span-2 space-y-1">
                            <label class="font-semibold text-surface-700">Alamat Tempat Tinggal</label>
                            <textarea
                                v-model="form.alamat"
                                rows="2"
                                class="w-full px-3 py-2 rounded-xl bg-surface-50 border border-surface-200 focus:bg-white focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 text-surface-900"
                            ></textarea>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-surface-100 flex items-center justify-end gap-3">
                        <button
                            type="button"
                            @click="closeModal"
                            class="px-4 py-2 text-xs font-semibold text-surface-700 bg-white border border-surface-300 rounded-xl hover:bg-surface-50 transition-colors"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-4 py-2 text-xs font-bold text-white bg-primary-600 hover:bg-primary-700 rounded-xl transition-all shadow-md disabled:opacity-50 hover-lift"
                        >
                            {{ editingAlumni ? 'Simpan Perubahan' : 'Tambahkan Alumni' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Import Modal -->
        <div
            v-if="isImportModalOpen"
            class="fixed inset-0 z-50 overflow-y-auto bg-surface-900/70 backdrop-blur-sm flex items-center justify-center p-4"
            @click.self="closeImportModal"
        >
            <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl border border-surface-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-surface-100 flex items-center justify-between bg-surface-50">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-lg bg-primary-50 text-primary-600 flex items-center justify-center">
                            <Upload class="w-4 h-4 icon-pulse" />
                        </div>
                        <h3 class="font-bold text-base text-surface-900">
                            Impor Data Alumni dari Excel
                        </h3>
                    </div>
                    <button @click="closeImportModal" class="p-2 text-surface-500 hover:text-surface-700 rounded-lg transition-colors icon-pulse">
                        <X class="w-5 h-5" />
                    </button>
                </div>

                <form @submit.prevent="submitImport" class="p-6 space-y-5">
                    <!-- Template Download Hint -->
                    <div class="p-4 rounded-xl bg-surface-50 border border-surface-200 flex items-center justify-between text-xs">
                        <div class="flex items-center gap-2.5">
                            <FileSpreadsheet class="w-5 h-5 text-success shrink-0" />
                            <div>
                                <p class="font-bold text-surface-800">Format Template Excel</p>
                                <p class="text-[11px] text-surface-500">Gunakan format kolom yang sudah disesuaikan sistem</p>
                            </div>
                        </div>
                        <a
                            href="/admin/alumni/template"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white border border-surface-200 text-primary-600 hover:bg-primary-50 font-semibold text-xs transition-colors shrink-0 hover-lift"
                        >
                            <Download class="w-3.5 h-3.5" />
                            <span>Unduh Template</span>
                        </a>
                    </div>

                    <!-- File Drop Area -->
                    <div class="space-y-2">
                        <label class="block text-xs font-semibold text-surface-700">
                            Pilih Berkas Excel (.xlsx) atau CSV (.csv) *
                        </label>
                        <div
                            @click="$refs.fileInput.click()"
                            class="border-2 border-dashed border-surface-200 hover:border-primary-400 rounded-2xl p-6 text-center cursor-pointer transition-colors bg-surface-50 hover:bg-primary-50/30 group"
                        >
                            <input
                                ref="fileInput"
                                type="file"
                                accept=".xlsx,.csv"
                                class="hidden"
                                @change="handleFileSelect"
                            />
                            <div class="w-12 h-12 rounded-xl bg-primary-50 text-primary-600 flex items-center justify-center mx-auto mb-3 group-hover:scale-105 transition-transform">
                                <FileUp class="w-6 h-6" />
                            </div>
                            <div v-if="selectedFileName" class="text-xs font-bold text-primary-600 bg-primary-50 px-3 py-1.5 rounded-lg inline-block">
                                {{ selectedFileName }}
                            </div>
                            <div v-else>
                                <p class="text-xs font-bold text-surface-800">Klik untuk memilih berkas dari komputer</p>
                                <p class="text-[11px] text-surface-500 mt-1">Mendukung format .xlsx dan .csv hingga 10MB</p>
                            </div>
                        </div>
                        <p v-if="importForm.errors.file" class="text-error text-[11px] font-medium flex items-center gap-1 mt-1">
                            <AlertCircle class="w-3.5 h-3.5 shrink-0" />
                            <span>{{ importForm.errors.file }}</span>
                        </p>
                    </div>

                    <!-- Rules / Instructions -->
                    <div class="text-[11px] text-surface-600 space-y-1 bg-surface-50 p-3.5 rounded-xl border border-surface-200">
                        <p class="font-bold text-surface-700 flex items-center gap-1.5">
                            <Info class="w-3.5 h-3.5 text-primary-600" />
                            <span>Petunjuk Pengisian:</span>
                        </p>
                        <ul class="list-disc list-inside space-y-0.5 text-surface-600 pl-1">
                            <li>Baris pertama adalah judul kolom (header) dan akan dilewati otomatis.</li>
                            <li>Kolom Wajib: <strong>NISN, Nama Lengkap, Tanggal Lahir (YYYY-MM-DD), Jurusan, Tahun Lulus</strong>.</li>
                            <li>Jika NISN alumni sudah terdaftar di database, data alumni tersebut akan diperbarui otomatis (*update*).</li>
                        </ul>
                    </div>

                    <!-- Modal Actions -->
                    <div class="pt-2 flex items-center justify-end gap-3 border-t border-surface-100">
                        <button
                            type="button"
                            @click="closeImportModal"
                            class="px-4 py-2 text-xs font-semibold text-surface-700 bg-white border border-surface-300 rounded-xl hover:bg-surface-50 transition-colors"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            :disabled="importForm.processing || !importForm.file"
                            class="inline-flex items-center gap-2 px-4 py-2 text-xs font-bold text-white bg-primary-600 hover:bg-primary-700 rounded-xl transition-all shadow-md disabled:opacity-50 hover-lift"
                        >
                            <Upload class="w-3.5 h-3.5" />
                            <span>{{ importForm.processing ? 'Mengimpor Data...' : 'Mulai Impor' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
