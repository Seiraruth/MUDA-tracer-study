<script setup>
import { ref } from 'vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import {
    Handshake,
    Plus,
    Search,
    Edit2,
    Trash2,
    Building2,
    MapPin,
    Phone,
    Mail,
    User,
    X,
    ExternalLink
} from 'lucide-vue-next';

const props = defineProps({
    mitras: {
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
const editingMitra = ref(null);

const form = useForm({
    nama_perusahaan: '',
    bidang_usaha: '',
    alamat: '',
    kota: '',
    telepon: '',
    email: '',
    kontak_person: '',
    keterangan: '',
    is_active: true,
});

const openCreateModal = () => {
    editingMitra.value = null;
    form.reset();
    form.clearErrors();
    form.is_active = true;
    isModalOpen.value = true;
};

const openEditModal = (mitra) => {
    editingMitra.value = mitra;
    form.clearErrors();
    form.nama_perusahaan = mitra.nama_perusahaan;
    form.bidang_usaha = mitra.bidang_usaha || '';
    form.alamat = mitra.alamat || '';
    form.kota = mitra.kota || '';
    form.telepon = mitra.telepon || '';
    form.email = mitra.email || '';
    form.kontak_person = mitra.kontak_person || '';
    form.keterangan = mitra.keterangan || '';
    form.is_active = Boolean(mitra.is_active);
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    editingMitra.value = null;
    form.reset();
};

const submitForm = () => {
    if (editingMitra.value) {
        form.put(`/admin/mitras/${editingMitra.value.id}`, {
            onSuccess: () => closeModal(),
        });
    } else {
        form.post('/admin/mitras', {
            onSuccess: () => closeModal(),
        });
    }
};

const deleteMitra = (mitra) => {
    if (confirm(`Hapus mitra "${mitra.nama_perusahaan}"?`)) {
        router.delete(`/admin/mitras/${mitra.id}`);
    }
};

const applyFilters = () => {
    router.get('/admin/mitras', {
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
    <Head title="Kelola Mitra DUDI - Admin" />

    <AdminLayout title="Mitra DUDI">
        <div class="space-y-6 max-w-7xl mx-auto">
            <!-- Header bar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-surface-200 animate-in">
                <div>
                    <h2 class="text-xl font-bold text-surface-900 tracking-tight flex items-center gap-2">
                        <Handshake class="w-5 h-5 text-primary-600 icon-pulse" />
                        <span>Database Mitra DUDI</span>
                    </h2>
                    <p class="text-xs text-surface-600 mt-1">
                        Daftar perusahaan & mitra yang pernah terima alumni.
                    </p>
                </div>
                <button
                    @click="openCreateModal"
                    class="inline-flex items-center gap-2 px-4 py-2.5 rounded-xl bg-primary-600 hover:bg-primary-700 text-white text-xs font-bold transition-all shadow-md hover-lift"
                >
                    <Plus class="w-4 h-4" />
                    <span>Tambah Mitra</span>
                </button>
            </div>

            <!-- Filters Bar -->
            <div class="bg-white p-4 rounded-2xl border border-surface-200 grid grid-cols-1 sm:grid-cols-12 gap-3 animate-in animate-in-delay-1">
                <div class="sm:col-span-7 relative">
                    <Search class="w-4 h-4 text-surface-500 absolute left-3.5 top-1/2 -translate-y-1/2" />
                    <input
                        v-model="search"
                        @keyup.enter="applyFilters"
                        type="text"
                        placeholder="Cari nama perusahaan, bidang usaha, atau kota..."
                        class="w-full pl-10 pr-4 py-2 text-xs rounded-xl bg-surface-50 border border-surface-200 focus:bg-white focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-colors text-surface-900"
                    />
                </div>

                <div class="sm:col-span-3">
                    <select
                        v-model="selectedActive"
                        @change="applyFilters"
                        class="w-full px-3 py-2 text-xs rounded-xl bg-surface-50 border border-surface-200 focus:bg-white focus:outline-none focus:border-primary-500 text-surface-800"
                    >
                        <option value="">Semua Status</option>
                        <option value="1">Aktif Saja</option>
                        <option value="0">Nonaktif</option>
                    </select>
                </div>

                <div class="sm:col-span-2 flex items-center gap-2">
                    <button
                        @click="applyFilters"
                        class="flex-1 py-2 px-3 text-xs font-semibold bg-primary-600 hover:bg-primary-700 text-white rounded-xl transition-colors text-center"
                    >
                        Filter
                    </button>
                    <button
                        @click="resetFilters"
                        class="py-2 px-3 text-xs font-semibold bg-surface-100 hover:bg-surface-200 text-surface-700 rounded-xl transition-colors"
                    >
                        Reset
                    </button>
                </div>
            </div>

            <!-- Table Card -->
            <div class="bg-white rounded-2xl border border-surface-200 overflow-hidden animate-in animate-in-delay-2">
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs">
                        <thead class="bg-surface-50 border-b border-surface-200 text-surface-600 uppercase tracking-wider font-semibold">
                            <tr>
                                <th class="px-6 py-3.5">Perusahaan</th>
                                <th class="px-6 py-3.5">Bidang & Lokasi</th>
                                <th class="px-6 py-3.5">Kontak</th>
                                <th class="px-6 py-3.5">Status</th>
                                <th class="px-6 py-3.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-surface-100">
                            <tr
                                v-for="mitra in mitras.data"
                                :key="mitra.id"
                                class="hover:bg-surface-50 transition-colors"
                            >
                                <td class="px-6 py-4">
                                    <div class="font-bold text-surface-900">{{ mitra.nama_perusahaan }}</div>
                                    <div class="text-[11px] text-surface-500">{{ mitra.kontak_person || '-' }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="font-medium text-surface-800">{{ mitra.bidang_usaha || '-' }}</span>
                                    <div class="text-[11px] text-surface-500 flex items-center gap-1">
                                        <MapPin class="w-3 h-3" />
                                        {{ mitra.kota || 'Tidak ditentukan' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div v-if="mitra.telepon" class="text-surface-700 text-[11px] flex items-center gap-1">
                                        <Phone class="w-3 h-3" />
                                        {{ mitra.telepon }}
                                    </div>
                                    <div v-if="mitra.email" class="text-surface-700 text-[11px] flex items-center gap-1">
                                        <Mail class="w-3 h-3" />
                                        {{ mitra.email }}
                                    </div>
                                    <span v-if="!mitra.telepon && !mitra.email" class="text-surface-400">-</span>
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        v-if="mitra.is_active"
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800"
                                    >
                                        Aktif
                                    </span>
                                    <span
                                        v-else
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-medium bg-surface-100 text-surface-600"
                                    >
                                        Nonaktif
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <button
                                            @click="openEditModal(mitra)"
                                            class="p-1.5 text-surface-500 hover:text-primary-600 hover:bg-primary-50 rounded-lg transition-colors icon-pulse"
                                            title="Edit Mitra"
                                        >
                                            <Edit2 class="w-4 h-4" />
                                        </button>
                                        <button
                                            @click="deleteMitra(mitra)"
                                            class="p-1.5 text-surface-500 hover:text-error hover:bg-rose-50 rounded-lg transition-colors icon-pulse"
                                            title="Hapus Mitra"
                                        >
                                            <Trash2 class="w-4 h-4" />
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="mitras.data.length === 0">
                                <td colspan="5" class="px-6 py-12 text-center text-surface-400">
                                    Belum ada data mitra DUDI.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination footer -->
                <div v-if="mitras.links && mitras.links.length > 3" class="px-6 py-4 border-t border-surface-100 flex items-center justify-between">
                    <span class="text-xs text-surface-500">
                        Menampilkan {{ mitras.from || 0 }} - {{ mitras.to || 0 }} dari {{ mitras.total }} mitra
                    </span>
                    <div class="flex items-center gap-1">
                        <component
                            :is="link.url ? 'a' : 'span'"
                            v-for="(link, index) in mitras.links"
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
                        {{ editingMitra ? 'Edit Mitra DUDI' : 'Tambah Mitra DUDI Baru' }}
                    </h3>
                    <button @click="closeModal" class="p-2 text-surface-500 hover:text-surface-700 rounded-lg transition-colors icon-pulse">
                        <X class="w-5 h-5" />
                    </button>
                </div>

                <form @submit.prevent="submitForm" class="p-6 space-y-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                        <div class="sm:col-span-2 space-y-1">
                            <label class="font-semibold text-surface-700">Nama Perusahaan *</label>
                            <input
                                v-model="form.nama_perusahaan"
                                type="text"
                                required
                                placeholder="Contoh: PT Telkom Indonesia"
                                class="w-full px-3 py-2 rounded-xl bg-surface-50 border border-surface-200 focus:bg-white focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 text-surface-900"
                            />
                            <p v-if="form.errors.nama_perusahaan" class="text-error text-[11px]">{{ form.errors.nama_perusahaan }}</p>
                        </div>

                        <div class="space-y-1">
                            <label class="font-semibold text-surface-700">Bidang Usaha</label>
                            <input
                                v-model="form.bidang_usaha"
                                type="text"
                                placeholder="Contoh: Telekomunikasi"
                                class="w-full px-3 py-2 rounded-xl bg-surface-50 border border-surface-200 focus:bg-white focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 text-surface-900"
                            />
                        </div>

                        <div class="space-y-1">
                            <label class="font-semibold text-surface-700">Kota</label>
                            <input
                                v-model="form.kota"
                                type="text"
                                placeholder="Contoh: Jakarta"
                                class="w-full px-3 py-2 rounded-xl bg-surface-50 border border-surface-200 focus:bg-white focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 text-surface-900"
                            />
                        </div>

                        <div class="sm:col-span-2 space-y-1">
                            <label class="font-semibold text-surface-700">Alamat</label>
                            <textarea
                                v-model="form.alamat"
                                rows="2"
                                placeholder="Alamat lengkap perusahaan..."
                                class="w-full px-3 py-2 rounded-xl bg-surface-50 border border-surface-200 focus:bg-white focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 text-surface-900"
                            ></textarea>
                        </div>

                        <div class="space-y-1">
                            <label class="font-semibold text-surface-700">Telepon</label>
                            <input
                                v-model="form.telepon"
                                type="text"
                                placeholder="Contoh: 021-1234567"
                                class="w-full px-3 py-2 rounded-xl bg-surface-50 border border-surface-200 focus:bg-white focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 text-surface-900"
                            />
                        </div>

                        <div class="space-y-1">
                            <label class="font-semibold text-surface-700">Email</label>
                            <input
                                v-model="form.email"
                                type="email"
                                placeholder="hrd@perusahaan.com"
                                class="w-full px-3 py-2 rounded-xl bg-surface-50 border border-surface-200 focus:bg-white focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 text-surface-900"
                            />
                        </div>

                        <div class="sm:col-span-2 space-y-1">
                            <label class="font-semibold text-surface-700">Kontak Person</label>
                            <input
                                v-model="form.kontak_person"
                                type="text"
                                placeholder="Nama CP di perusahaan"
                                class="w-full px-3 py-2 rounded-xl bg-surface-50 border border-surface-200 focus:bg-white focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 text-surface-900"
                            />
                        </div>

                        <div class="sm:col-span-2 space-y-1">
                            <label class="font-semibold text-surface-700">Keterangan</label>
                            <textarea
                                v-model="form.keterangan"
                                rows="2"
                                placeholder="Catatan tambahan..."
                                class="w-full px-3 py-2 rounded-xl bg-surface-50 border border-surface-200 focus:bg-white focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 text-surface-900"
                            ></textarea>
                        </div>

                        <div class="sm:col-span-2 space-y-1 flex items-center pt-2">
                            <label class="flex items-center gap-2 cursor-pointer font-semibold text-surface-700">
                                <input
                                    v-model="form.is_active"
                                    type="checkbox"
                                    class="rounded text-primary-600 focus:ring-primary-500"
                                />
                                <span>Mitra aktif</span>
                            </label>
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
                            {{ editingMitra ? 'Simpan Perubahan' : 'Tambah Mitra' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AdminLayout>
</template>