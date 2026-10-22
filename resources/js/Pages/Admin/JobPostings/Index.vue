<script setup>
import { ref } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import {
    Briefcase,
    Plus,
    Search,
    Filter,
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

    <AdminLayout title="Bursa Kerja Khusus (BKK)">
        <div class="space-y-6 max-w-7xl mx-auto">
            <!-- Header bar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/80 shadow-xs">
                <div>
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight flex items-center gap-2">
                        <Briefcase class="w-5 h-5 text-indigo-600" />
                        <span>Informasi Lowongan Kerja</span>
                    </h2>
                    <p class="text-xs text-slate-500 mt-1">
                        Dikelola oleh unit Bursa Kerja Khusus (BKK) SMK untuk alumni & calon lulusan.
                    </p>
                </div>
                <button
                    @click="openCreateModal"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold transition-all shadow-md shadow-indigo-600/30 hover:scale-[1.02] active:scale-[0.98]"
                >
                    <Plus class="w-4 h-4" />
                    <span>Posting Lowongan Baru</span>
                </button>
            </div>

            <!-- Filters Bar -->
            <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-xs grid grid-cols-1 sm:grid-cols-12 gap-3">
                <div class="sm:col-span-7 relative">
                    <Search class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" />
                    <input
                        v-model="search"
                        @keyup.enter="applyFilters"
                        type="text"
                        placeholder="Cari judul lowongan, perusahaan, posisi..."
                        class="w-full pl-10 pr-4 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-colors"
                    />
                </div>

                <div class="sm:col-span-3">
                    <select
                        v-model="selectedActive"
                        @change="applyFilters"
                        class="w-full px-3 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:border-indigo-500 text-slate-700"
                    >
                        <option value="">Semua Status</option>
                        <option value="1">Aktif Saja</option>
                        <option value="0">Tutup / Nonaktif</option>
                    </select>
                </div>

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
                                <th class="px-6 py-3.5">Judul & Perusahaan</th>
                                <th class="px-6 py-3.5">Posisi & Lokasi</th>
                                <th class="px-6 py-3.5">Batas Pendaftaran</th>
                                <th class="px-6 py-3.5">Status</th>
                                <th class="px-6 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            <tr
                                v-for="job in jobs.data"
                                :key="job.id"
                                class="hover:bg-slate-50/60 transition-colors"
                            >
                                <td class="px-6 py-4">
                                    <div class="font-bold text-slate-900">{{ job.judul }}</div>
                                    <div class="text-[11px] text-indigo-600 font-semibold">{{ job.perusahaan }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-medium text-slate-800">{{ job.posisi }}</span>
                                    <div class="text-[11px] text-slate-400">{{ job.lokasi || 'Tidak ditentukan' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span v-if="job.deadline" class="font-medium text-slate-700">
                                        {{ new Date(job.deadline).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' }) }}
                                    </span>
                                    <span v-else class="text-slate-400">Tidak ada batas</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        v-if="job.is_active"
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800"
                                    >
                                        Aktif
                                    </span>
                                    <span
                                        v-else
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-slate-100 text-slate-600"
                                    >
                                        Ditutup
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a
                                            v-if="job.link_pendaftaran"
                                            :href="job.link_pendaftaran"
                                            target="_blank"
                                            class="p-1.5 text-slate-400 hover:text-indigo-600 rounded-lg transition-colors"
                                            title="Buka Link Pendaftaran"
                                        >
                                            <ExternalLink class="w-4 h-4" />
                                        </a>
                                        <button
                                            @click="openEditModal(job)"
                                            class="p-1.5 text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors"
                                            title="Edit Lowongan"
                                        >
                                            <Edit2 class="w-4 h-4" />
                                        </button>
                                        <button
                                            @click="deleteJob(job)"
                                            class="p-1.5 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors"
                                            title="Hapus Lowongan"
                                        >
                                            <Trash2 class="w-4 h-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="jobs.data.length === 0">
                                <td colspan="5" class="px-6 py-12 text-center text-slate-400">
                                    Tidak ada lowongan kerja yang ditemukan.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination footer -->
                <div v-if="jobs.links && jobs.links.length > 3" class="px-6 py-4 border-t border-slate-100 flex items-center justify-between">
                    <span class="text-xs text-slate-500">
                        Menampilkan {{ jobs.from || 0 }} - {{ jobs.to || 0 }} dari {{ jobs.total }} lowongan
                    </span>
                    <div class="flex items-center gap-1">
                        <component
                            :is="link.url ? 'a' : 'span'"
                            v-for="(link, index) in jobs.links"
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

        <!-- Add / Edit Modal -->
        <div
            v-if="isModalOpen"
            class="fixed inset-0 z-50 overflow-y-auto bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4"
            @click.self="closeModal"
        >
            <div class="bg-white rounded-2xl max-w-xl w-full shadow-2xl border border-slate-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <h3 class="font-bold text-base text-slate-900">
                        {{ editingJob ? 'Edit Informasi Lowongan' : 'Posting Lowongan Kerja Baru' }}
                    </h3>
                    <button @click="closeModal" class="p-2 text-slate-400 hover:text-slate-600 rounded-lg">
                        <X class="w-5 h-5" />
                    </button>
                </div>

                <form @submit.prevent="submitForm" class="p-6 space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div class="space-y-1">
                            <label class="font-semibold text-slate-700">Judul Lowongan *</label>
                            <input
                                v-model="form.judul"
                                type="text"
                                required
                                placeholder="Contoh: Rekrutmen Teknisi Jaringan"
                                class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:border-indigo-500"
                            />
                            <p v-if="form.errors.judul" class="text-rose-600 text-[11px]">{{ form.errors.judul }}</p>
                        </div>

                        <div class="space-y-1">
                            <label class="font-semibold text-slate-700">Nama Perusahaan / Mitra *</label>
                            <input
                                v-model="form.perusahaan"
                                type="text"
                                required
                                placeholder="Contoh: PT Telkom Indonesia"
                                class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:border-indigo-500"
                            />
                            <p v-if="form.errors.perusahaan" class="text-rose-600 text-[11px]">{{ form.errors.perusahaan }}</p>
                        </div>

                        <div class="space-y-1">
                            <label class="font-semibold text-slate-700">Posisi / Jabatan *</label>
                            <input
                                v-model="form.posisi"
                                type="text"
                                required
                                placeholder="Contoh: Junior Network Engineer"
                                class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:border-indigo-500"
                            />
                            <p v-if="form.errors.posisi" class="text-rose-600 text-[11px]">{{ form.errors.posisi }}</p>
                        </div>

                        <div class="space-y-1">
                            <label class="font-semibold text-slate-700">Lokasi Kerja</label>
                            <input
                                v-model="form.lokasi"
                                type="text"
                                placeholder="Contoh: Jakarta / WFH"
                                class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:border-indigo-500"
                            />
                        </div>

                        <div class="sm:col-span-2 space-y-1">
                            <label class="font-semibold text-slate-700">Persyaratan & Kualifikasi *</label>
                            <textarea
                                v-model="form.persyaratan"
                                rows="3"
                                required
                                placeholder="Tuliskan kualifikasi, syarat pendidikan SMK, dan keahlian yang dibutuhkan..."
                                class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:border-indigo-500"
                            ></textarea>
                            <p v-if="form.errors.persyaratan" class="text-rose-600 text-[11px]">{{ form.errors.persyaratan }}</p>
                        </div>

                        <div class="space-y-1">
                            <label class="font-semibold text-slate-700">Link Pendaftaran / Form Online</label>
                            <input
                                v-model="form.link_pendaftaran"
                                type="url"
                                placeholder="https://..."
                                class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:border-indigo-500"
                            />
                        </div>

                        <div class="space-y-1">
                            <label class="font-semibold text-slate-700">Batas Waktu (Deadline)</label>
                            <input
                                v-model="form.deadline"
                                type="date"
                                class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:border-indigo-500"
                            />
                        </div>

                        <div class="space-y-1">
                            <label class="font-semibold text-slate-700">Kontak Person / HRD</label>
                            <input
                                v-model="form.kontak"
                                type="text"
                                placeholder="WhatsApp atau Email HRD"
                                class="w-full px-3 py-2 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:border-indigo-500"
                            />
                        </div>

                        <div class="space-y-1 flex items-center pt-5">
                            <label class="flex items-center gap-2 cursor-pointer font-semibold text-slate-700">
                                <input
                                    v-model="form.is_active"
                                    type="checkbox"
                                    class="rounded text-indigo-600 focus:ring-indigo-500"
                                />
                                <span>Publikasikan sebagai lowongan aktif</span>
                            </label>
                        </div>
                    </div>

                    <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
                        <button
                            type="button"
                            @click="closeModal"
                            class="px-4 py-2 text-xs font-semibold text-slate-700 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 transition-colors"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="px-4 py-2 text-xs font-bold text-white bg-indigo-600 hover:bg-indigo-500 rounded-xl transition-all shadow-md shadow-indigo-600/30 disabled:opacity-50"
                        >
                            {{ editingJob ? 'Simpan Perubahan' : 'Posting Lowongan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>
