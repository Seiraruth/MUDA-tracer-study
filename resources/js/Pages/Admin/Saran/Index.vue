<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';
import {
    MessageSquare,
    Search,
    Filter,
    User,
    Calendar,
    ChevronDown,
    CheckCircle2,
    AlertCircle
} from 'lucide-vue-next';

const props = defineProps({
    sarans: {
        type: Object,
        required: true,
    },
    filters: {
        type: Object,
        default: () => ({ search: '', tahun: '' }),
    },
    tahunOptions: {
        type: Array,
        default: () => [],
    },
});

const search = ref(props.filters.search);
const selectedTahun = ref(props.filters.tahun);

const selectedSaran = ref(null);
const showDetailModal = ref(false);

const openDetail = (saran) => {
    selectedSaran.value = saran;
    showDetailModal.value = true;
};

const closeDetail = () => {
    showDetailModal.value = false;
    selectedSaran.value = null;
};

const applyFilters = () => {
    // For now, filtering is handled server-side via query params
};

const resetFilters = () => {
    search.value = '';
    selectedTahun.value = '';
    applyFilters();
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('id-ID', {
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });
};
</script>

<template>
    <Head title="Saran Alumni - Admin" />

    <AdminLayout title="Saran Alumni">
        <div class="space-y-6 max-w-7xl mx-auto">
            <!-- Header bar -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-surface-200 animate-in">
                <div>
                    <h2 class="text-xl font-bold text-surface-900 tracking-tight flex items-center gap-2">
                        <MessageSquare class="w-5 h-5 text-primary-600 icon-pulse" />
                        <span>Saran & Masukan Alumni</span>
                    </h2>
                    <p class="text-xs text-surface-600 mt-1">
                        Tanggapan alumni untuk pengembangan sekolah.
                    </p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-surface-500">
                        Total {{ sarans.total }} saran masuk
                    </span>
                </div>
            </div>

            <!-- Filters Bar -->
            <div class="bg-white p-4 rounded-2xl border border-surface-200 grid grid-cols-1 sm:grid-cols-12 gap-3 animate-in animate-in-delay-1">
                <div class="sm:col-span-7 relative">
                    <Search class="w-4 h-4 text-surface-500 absolute left-3.5 top-1/2 -translate-y-1/2" />
                    <input
                        v-model="search"
                        @keyup.enter="applyFilters"
                        type="text"
                        placeholder="Cari nama alumni atau isi saran..."
                        class="w-full pl-10 pr-4 py-2 text-xs rounded-xl bg-surface-50 border border-surface-200 focus:bg-white focus:outline-none focus:border-primary-500 focus:ring-1 focus:ring-primary-500 transition-colors text-surface-900"
                    />
                </div>

                <div class="sm:col-span-3">
                    <select
                        v-model="selectedTahun"
                        @change="applyFilters"
                        class="w-full px-3 py-2 text-xs rounded-xl bg-surface-50 border border-surface-200 focus:bg-white focus:outline-none focus:border-primary-500 text-surface-800"
                    >
                        <option value="">Semua Tahun</option>
                        <option v-for="tahun in tahunOptions" :key="tahun" :value="tahun">
                            Tahun {{ tahun }}
                        </option>
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

            <!-- Saran List -->
            <div class="bg-white rounded-2xl border border-surface-200 overflow-hidden animate-in animate-in-delay-2">
                <div class="divide-y divide-surface-100">
                    <div
                        v-for="saran in sarans.data"
                        :key="saran.id"
                        class="p-4 hover:bg-surface-50 transition-colors cursor-pointer"
                        @click="openDetail(saran)"
                    >
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <User class="w-3.5 h-3.5 text-surface-400" />
                                    <span class="text-xs font-semibold text-surface-900">
                                        {{ saran.alumni?.nama || 'Alumni' }}
                                    </span>
                                    <span class="text-[10px] text-surface-400">•</span>
                                    <span class="text-[10px] text-surface-500">
                                        {{ saran.alumni?.jurusan || '-' }}
                                    </span>
                                </div>
                                <p class="text-sm text-surface-700 line-clamp-2">
                                    {{ saran.saran_sekolah }}
                                </p>
                                <div class="flex items-center gap-3 mt-2 text-[10px] text-surface-400">
                                    <span class="flex items-center gap-1">
                                        <Calendar class="w-3 h-3" />
                                        {{ formatDate(saran.created_at) }}
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <span class="px-1.5 py-0.5 rounded bg-primary-50 text-primary-700 font-medium">
                                            Th {{ saran.tahun_tracer }}
                                        </span>
                                    </span>
                                </div>
                            </div>
                            <ChevronDown class="w-4 h-4 text-surface-300 shrink-0" />
                        </div>
                    </div>

                    <div v-if="sarans.data.length === 0" class="p-12 text-center text-surface-400">
                        <MessageSquare class="w-8 h-8 mx-auto mb-2 opacity-50" />
                        <p>Belum ada saran dari alumni.</p>
                    </div>
                </div>

                <!-- Pagination footer -->
                <div v-if="sarans.links && sarans.links.length > 3" class="px-6 py-4 border-t border-surface-100 flex items-center justify-between">
                    <span class="text-xs text-surface-500">
                        Menampilkan {{ sarans.from || 0 }} - {{ sarans.to || 0 }} dari {{ sarans.total }} saran
                    </span>
                    <div class="flex items-center gap-1">
                        <component
                            :is="link.url ? 'a' : 'span'"
                            v-for="(link, index) in sarans.links"
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

        <!-- Detail Modal -->
        <div
            v-if="showDetailModal && selectedSaran"
            class="fixed inset-0 z-50 overflow-y-auto bg-surface-900/70 backdrop-blur-sm flex items-center justify-center p-4"
            @click.self="closeDetail"
        >
            <div class="bg-white rounded-2xl max-w-lg w-full shadow-2xl border border-surface-200 overflow-hidden">
                <div class="px-6 py-5 border-b border-surface-100 flex items-center justify-between bg-surface-50">
                    <h3 class="font-bold text-base text-surface-900">Detail Saran</h3>
                    <button @click="closeDetail" class="p-2 text-surface-500 hover:text-surface-700 rounded-lg transition-colors icon-pulse">
                        <ChevronDown class="w-5 h-5 rotate-45" />
                    </button>
                </div>

                <div class="p-6">
                    <!-- Alumni Info -->
                    <div class="bg-surface-50 rounded-xl p-4 mb-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center font-semibold">
                                {{ selectedSaran.alumni?.nama?.charAt(0) || 'A' }}
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-surface-900">{{ selectedSaran.alumni?.nama || 'Alumni' }}</p>
                                <p class="text-xs text-surface-500">{{ selectedSaran.alumni?.jurusan || '-' }} • Th {{ selectedSaran.tahun_tracer }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Saran Content -->
                    <div>
                        <p class="text-[10px] font-medium text-surface-400 uppercase tracking-wide mb-2">Saran untuk Sekolah</p>
                        <p class="text-sm text-surface-700 whitespace-pre-wrap">{{ selectedSaran.saran_sekolah }}</p>
                    </div>

                    <!-- Meta -->
                    <div class="mt-4 pt-4 border-t border-surface-100 flex items-center justify-between text-xs text-surface-400">
                        <span>Dibuat: {{ formatDate(selectedSaran.created_at) }}</span>
                        <span>Diupdate: {{ formatDate(selectedSaran.updated_at) }}</span>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-surface-100 bg-surface-50 flex justify-end">
                    <button
                        @click="closeDetail"
                        class="px-4 py-2 text-xs font-semibold text-surface-700 bg-white border border-surface-300 rounded-xl hover:bg-surface-50 transition-colors"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>