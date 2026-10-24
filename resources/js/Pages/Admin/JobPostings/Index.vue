<script setup>
import { ref } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import {
    Briefcase,
    Plus,
    Search,
    Edit2,
    Trash2,
    Building,
    MapPin,
    Calendar,
    Link as LinkIcon,
    Phone,
    X,
    ExternalLink
} from 'lucide-vue-next';

const props = defineProps({
    jobs: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        default: () => ({ search: '', active: '' }),
    },
});

const search = ref(props.filters.search);
const selectedActive = ref(props.filters.active);

const isModalOpen = ref(false);
const editingJob = ref(null);

const form = useForm({
    judul: '',
    perusahaan: '',
    posisi: '',
    lokasi: '',
    persyaratan: '',
    link_pendaftaran: '',
    kontak: '',
    deadline: '',
    is_active: true,
});

const openCreateModal = () => {
    editingJob.value = null;
    form.reset();
    form.clearErrors();
    form.is_active = true;
    isModalOpen.value = true;
};

const openEditModal = (job) => {
    editingJob.value = job;
    form.clearErrors();
    form.judul = job.judul;
    form.perusahaan = job.perusahaan;
    form.posisi = job.posisi;
    form.lokasi = job.lokasi || '';
    form.persyaratan = job.persyaratan;
    form.link_pendaftaran = job.link_pendaftaran || '';
    form.kontak = job.kontak || '';
    form.deadline = job.deadline || '';
    form.is_active = Boolean(job.is_active);
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    editingJob.value = null;
    form.reset();
};

const submitForm = () => {
    if (editingJob.value) {
        form.put(`/admin/job-postings/${editingJob.value.id}`, {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post('/admin/job-postings', {
            onSuccess: () => closeModal(),
        });
    }
};

const deleteJob = (job) => {
    if (confirm(`Hapus informasi lowongan "${job.judul}"?`)) {
        router.delete(`/admin/job-postings/${job.id}`);
    }
};

const applyFilters = () => {
    router.get('/admin/job-postings', {
        search: search.value || undefined,
        active: selectedActive.value !== '' ? selectedActive.value : undefined,
    }, {
        preserveState: true,
        replace: true,
    });
};

const resetFilters = () => {
    search.value = '';
    selectedActive.value = '';
    applyFilters();
};
</script>

<template>
    <Head title="Kelola Lowongan Kerja BKK - Admin" />

    <AdminLayout title="Lowongan Kerja BKK">
        <div class="space-y-6 max-w-7xl mx-auto">
            <!-- Header bar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-7 rounded-2xl border border-surface-200 shadow-md animate-in">
                <div>
                    <h2 class="text-2xl font-extrabold text-surface-900 tracking-tight flex items-center gap-3">
                        <Briefcase class="w-6 h-6 text-primary-600" />
                        <span>Lowongan Kerja BKK</span>
                    </h2>
                    <p class="text-sm text-surface-600 mt-2 font-medium">
                        Bursa Kerja Khusus untuk alumni dan calon lulusan
                    </p>
                </div>
                <button
                    @click="openCreateModal"
                    class="inline-flex items-center gap-2.5 px-5 py-3 rounded-xl bg-primary-600 hover:bg-primary-700 text-white text-sm font-bold transition-all shadow-lg hover-lift"
                >
                    <Plus class="w-5 h-5" />
                    <span>Tambah Lowongan</span>
                </button>
            </div>

            <!-- Filters Bar -->
            <div class="bg-white p-5 rounded-2xl border border-surface-200 shadow-sm grid grid-cols-1 sm:grid-cols-12 gap-3 animate-in animate-in-delay-1">
                <div class="sm:col-span-7 relative">
                    <Search class="w-4 h-4 text-surface-400 absolute left-4 top-1/2 -translate-y-1/2" />
                    <input
                        v-model="search"
                        @keyup.enter="applyFilters"
                        type="text"
                        placeholder="Cari judul, perusahaan, atau posisi"
                        class="w-full pl-11 pr-4 py-2.5 text-sm rounded-xl bg-surface-50 border border-surface-200 focus:bg-white focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 transition-all text-surface-900 font-medium placeholder:text-surface-400 placeholder:font-normal"
                    />
                </div>

                <div class="sm:col-span-3">
                    <select
                        v-model="selectedActive"
                        @change="applyFilters"
                        class="w-full px-4 py-2.5 text-sm rounded-xl bg-surface-50 border border-surface-200 focus:bg-white focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 text-surface-800 font-medium transition-all"
                    >
                        <option value="">Semua Status</option>
                        <option value="1">Aktif</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>

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
                                <th class="px-6 py-4 text-xs">Judul & Perusahaan</th>
                                <th class="px-6 py-4 text-xs">Posisi & Lokasi</th>
                                <th class="px-6 py-4 text-xs">Batas Pendaftaran</th>
                                <th class="px-6 py-4 text-xs">Status</th>
                                <th class="px-6 py-4 text-right text-xs">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-surface-100">
                            <tr
                                v-for="job in jobs.data"
                                :key="job.id"
                                class="hover:bg-surface-50/80 transition-all group"
                            >
                                <td class="px-6 py-4">
                                    <div class="font-extrabold text-surface-900">{{ job.judul }}</div>
                                    <div class="text-xs text-primary-600 font-bold mt-0.5">{{ job.perusahaan }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="font-bold text-surface-800">{{ job.posisi }}</div>
                                    <div class="text-xs text-surface-600 font-medium mt-0.5">{{ job.lokasi || 'Lokasi tidak ditentukan' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span v-if="job.deadline" class="inline-flex items-center px-2.5 py-1 rounded-lg bg-surface-100 text-surface-800 text-xs font-bold">
                                        {{ new Date(job.deadline).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) }}
                                    </span>
                                    <span v-else class="text-surface-500 text-xs font-medium">Tidak ada batas</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        v-if="job.is_active"
                                        class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-extrabold bg-emerald-100 text-emerald-800 border border-emerald-200 shadow-sm"
                                    >
                                        Aktif
                                    </span>
                                    <span
                                        v-else
                                        class="inline-flex items-center px-3 py-1.5 rounded-lg text-xs font-bold bg-surface-100 text-surface-600"
                                    >
                                        Nonaktif
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a
                                            v-if="job.link_pendaftaran"
                                            :href="job.link_pendaftaran"
                                            target="_blank"
                                            class="p-2 text-surface-400 hover:text-primary-600 hover:bg-primary-50 rounded-xl transition-all group-hover:scale-105"
                                            title="Buka Link"
                                        >
                                            <ExternalLink class="w-4 h-4" />
                                        </a>
                                        <button
                                            @click="openEditModal(job)"
                                            class="p-2 text-surface-500 hover:text-primary-600 hover:bg-primary-50 rounded-xl transition-all group-hover:scale-105"
                                            title="Edit"
                                        >
                                            <Edit2 class="w-4 h-4" />
                                        </button>
                                        <button
                                            @click="deleteJob(job)"
                                            class="p-2 text-surface-500 hover:text-error hover:bg-rose-50 rounded-xl transition-all group-hover:scale-105"
                                            title="Hapus"
                                        >
                                            <Trash2 class="w-4 h-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="jobs.data.length === 0">
                                <td colspan="5" class="px-6 py-16 text-center text-surface-500 font-medium">
                                    Tidak ada lowongan yang sesuai dengan filter pencarian
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination footer -->
                <div v-if="jobs.links && jobs.links.length > 3" class="px-6 py-4 border-t border-surface-200 flex items-center justify-between bg-surface-50/50">
                    <span class="text-sm text-surface-600 font-medium">
                        Menampilkan <strong class="text-surface-900">{{ jobs.from || 0 }} - {{ jobs.to || 0 }}</strong> dari <strong class="text-surface-900">{{ jobs.total }}</strong> data
                    </span>
                    <div class="flex items-center gap-1">
                        <component
                            :is="link.url ? 'a' : 'span'"
                            v-for="(link, index) in jobs.links"
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

        <!-- Add / Edit Modal -->
        <div
            v-if="isModalOpen"
            class="fixed inset-0 z-50 overflow-y-auto bg-surface-900/80 backdrop-blur-sm flex items-center justify-center p-4"
            @click.self="closeModal"
        >
            <div class="bg-white rounded-2xl max-w-2xl w-full shadow-2xl border border-surface-200 overflow-hidden animate-in">
                <div class="px-7 py-6 border-b border-surface-200 flex items-center justify-between bg-gradient-to-r from-primary-50 to-white">
                    <h3 class="font-extrabold text-lg text-surface-900">
                        {{ editingJob ? 'Edit Lowongan' : 'Tambah Lowongan Baru' }}
                    </h3>
                    <button @click="closeModal" class="p-2 text-surface-500 hover:text-surface-900 hover:bg-surface-100 rounded-xl transition-all">
                        <X class="w-5 h-5" />
                    </button>
                </div>

                <form @submit.prevent="submitForm" class="p-7 space-y-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 text-sm">
                        <div class="space-y-2">
                            <label class="font-bold text-surface-700">Judul Lowongan *</label>
                            <input
                                v-model="form.judul"
                                type="text"
                                required
                                placeholder="Contoh: Rekrutmen Teknisi Jaringan"
                                class="w-full px-4 py-2.5 rounded-xl bg-surface-50 border border-surface-200 focus:bg-white focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 text-surface-900 font-medium transition-all"
                            />
                            <p v-if="form.errors.judul" class="text-error text-xs font-medium">{{ form.errors.judul }}</p>
                        </div>

                        <div class="space-y-2">
                            <label class="font-bold text-surface-700">Nama Perusahaan *</label>
                            <input
                                v-model="form.perusahaan"
                                type="text"
                                required
                                placeholder="Contoh: PT Telkom Indonesia"
                                class="w-full px-4 py-2.5 rounded-xl bg-surface-50 border border-surface-200 focus:bg-white focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 text-surface-900 font-medium transition-all"
                            />
                            <p v-if="form.errors.perusahaan" class="text-error text-xs font-medium">{{ form.errors.perusahaan }}</p>
                        </div>

                        <div class="space-y-2">
                            <label class="font-bold text-surface-700">Posisi / Jabatan *</label>
                            <input
                                v-model="form.posisi"
                                type="text"
                                required
                                placeholder="Contoh: Junior Network Engineer"
                                class="w-full px-4 py-2.5 rounded-xl bg-surface-50 border border-surface-200 focus:bg-white focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 text-surface-900 font-medium transition-all"
                            />
                            <p v-if="form.errors.posisi" class="text-error text-xs font-medium">{{ form.errors.posisi }}</p>
                        </div>

                        <div class="space-y-2">
                            <label class="font-bold text-surface-700">Lokasi Kerja</label>
                            <input
                                v-model="form.lokasi"
                                type="text"
                                placeholder="Contoh: Jakarta / Remote"
                                class="w-full px-4 py-2.5 rounded-xl bg-surface-50 border border-surface-200 focus:bg-white focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 text-surface-900 font-medium transition-all"
                            />
                        </div>

                        <div class="sm:col-span-2 space-y-2">
                            <label class="font-bold text-surface-700">Persyaratan & Kualifikasi *</label>
                            <textarea
                                v-model="form.persyaratan"
                                rows="4"
                                required
                                placeholder="Tuliskan kualifikasi, syarat pendidikan, dan keahlian yang dibutuhkan"
                                class="w-full px-4 py-2.5 rounded-xl bg-surface-50 border border-surface-200 focus:bg-white focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 text-surface-900 font-medium transition-all"
                            ></textarea>
                            <p v-if="form.errors.persyaratan" class="text-error text-xs font-medium">{{ form.errors.persyaratan }}</p>
                        </div>

                        <div class="space-y-2">
                            <label class="font-bold text-surface-700">Link Pendaftaran</label>
                            <input
                                v-model="form.link_pendaftaran"
                                type="url"
                                placeholder="https://"
                                class="w-full px-4 py-2.5 rounded-xl bg-surface-50 border border-surface-200 focus:bg-white focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 text-surface-900 font-medium transition-all"
                            />
                        </div>

                        <div class="space-y-2">
                            <label class="font-bold text-surface-700">Batas Waktu</label>
                            <input
                                v-model="form.deadline"
                                type="date"
                                class="w-full px-4 py-2.5 rounded-xl bg-surface-50 border border-surface-200 focus:bg-white focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 text-surface-900 font-medium transition-all"
                            />
                        </div>

                        <div class="sm:col-span-2 space-y-2">
                            <label class="font-bold text-surface-700">Kontak HRD</label>
                            <input
                                v-model="form.kontak"
                                type="text"
                                placeholder="WhatsApp atau Email"
                                class="w-full px-4 py-2.5 rounded-xl bg-surface-50 border border-surface-200 focus:bg-white focus:outline-none focus:border-primary-500 focus:ring-2 focus:ring-primary-500/20 text-surface-900 font-medium transition-all"
                            />
                        </div>

                        <div class="sm:col-span-2 pt-2">
                            <label class="flex items-center gap-3 cursor-pointer font-bold text-surface-700 p-4 rounded-xl bg-surface-50 hover:bg-surface-100 transition-colors">
                                <input
                                    v-model="form.is_active"
                                    type="checkbox"
                                    class="w-5 h-5 rounded-lg text-primary-600 focus:ring-primary-500 focus:ring-2"
                                />
                                <span>Publikasikan sebagai lowongan aktif</span>
                            </label>
                        </div>
                    </div>

                    <div class="pt-5 border-t border-surface-200 flex items-center justify-end gap-3">
                        <button
                            type="button"
                            @click="closeModal"
                            class="px-5 py-2.5 text-sm font-bold text-surface-700 bg-white border-2 border-surface-300 rounded-xl hover:bg-surface-50 transition-all"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-5 py-2.5 text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 rounded-xl transition-all shadow-lg hover:shadow-xl disabled:opacity-50 disabled:cursor-not-allowed"
                        >
                            {{ editingJob ? 'Simpan Perubahan' : 'Tambah Lowongan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>