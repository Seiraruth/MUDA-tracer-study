<script setup>
import {
    X,
    User,
    GraduationCap,
    Briefcase,
    Building2,
    Calendar,
    Phone,
    Mail,
    CheckCircle2,
    AlertCircle,
    Store,
    DollarSign,
    MessageSquareQuote
} from 'lucide-vue-next';

defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    response: {
        type: Object,
        default: null,
    },
});

defineEmits(['close']);

const getStatusBadge = (status) => {
    switch (status) {
        case 'Bekerja':
            return {
                label: 'Bekerja',
                class: 'bg-emerald-100 text-emerald-700 border-emerald-200',
            };
        case 'Kuliah':
            return {
                label: 'Melanjutkan (Kuliah)',
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
    <div
        v-if="show && response"
        class="fixed inset-0 z-50 overflow-y-auto bg-surface-900/70 backdrop-blur-sm flex items-center justify-center p-4"
        @click.self="$emit('close')"
    >
        <div class="bg-white rounded-2xl max-w-2xl w-full shadow-2xl border border-surface-200 overflow-hidden transform transition-all animate-in fade-in zoom-in-95 duration-200">
            <!-- Modal Header -->
            <div class="px-6 py-5 border-b border-surface-100 flex items-center justify-between bg-surface-50">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-primary-50 border border-primary-100 text-primary-600 flex items-center justify-center font-bold">
                        <User class="w-5 h-5 icon-pulse" />
                    </div>
                    <div>
                        <h3 class="font-bold text-base text-surface-900">
                            Detail Kuesioner Alumni
                        </h3>
                        <p class="text-xs text-surface-500">Tahun Pengisian: {{ response.tahun_tracer || new Date().getFullYear() }}</p>
                    </div>
                </div>
                <button
                    @click="$emit('close')"
                    class="p-2 text-surface-500 hover:text-surface-700 hover:bg-surface-100 rounded-lg transition-colors icon-pulse"
                >
                    <X class="w-5 h-5" />
                </button>
            </div>

            <!-- Modal Content -->
            <div class="p-6 space-y-6 max-h-[75vh] overflow-y-auto">
                <!-- Alumni Identity Card -->
                <div class="p-4 rounded-xl bg-surface-50 border border-surface-200">
                    <div class="flex flex-wrap items-center justify-between gap-2 pb-3 border-b border-surface-200">
                        <div>
                            <h4 class="text-base font-bold text-surface-900">
                                {{ response.alumni?.nama || '-' }}
                            </h4>
                            <p class="text-xs text-surface-500">NISN: {{ response.alumni?.nisn || '-' }}</p>
                        </div>
                        <span
                            class="text-xs font-bold px-3 py-1 rounded-full border"
                            :class="getStatusBadge(response.status_utama).class"
                        >
                            {{ getStatusBadge(response.status_utama).label }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 pt-3 text-xs">
                        <div>
                            <span class="text-surface-400 block">Jurusan / Program Keahlian:</span>
                            <span class="font-semibold text-surface-800">{{ response.alumni?.jurusan || '-' }}</span>
                        </div>
                        <div>
                            <span class="text-surface-400 block">Tahun Lulus (Angkatan):</span>
                            <span class="font-semibold text-surface-800">{{ response.alumni?.tahun_lulus || '-' }}</span>
                        </div>
                        <div>
                            <span class="text-surface-400 block">WhatsApp / No. HP:</span>
                            <span class="font-semibold text-surface-800">{{ response.alumni?.no_hp || '-' }}</span>
                        </div>
                        <div>
                            <span class="text-surface-400 block">Email:</span>
                            <span class="font-semibold text-surface-800">{{ response.alumni?.email || '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Condition 1: Bekerja -->
                <div v-if="response.status_utama === 'Bekerja'" class="space-y-4">
                    <h5 class="text-xs font-bold text-surface-500 uppercase tracking-wider flex items-center gap-2">
                        <Briefcase class="w-4 h-4 text-emerald-600 icon-pulse" />
                        <span>Data Pekerjaan</span>
                    </h5>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4 rounded-xl border border-emerald-100 bg-emerald-50/30 text-xs">
                        <div>
                            <span class="text-surface-500 block">Nama Perusahaan / Instansi:</span>
                            <span class="font-bold text-surface-900 text-sm">{{ response.nama_perusahaan || '-' }}</span>
                        </div>
                        <div>
                            <span class="text-surface-500 block">Jabatan / Posisi:</span>
                            <span class="font-bold text-surface-900 text-sm">{{ response.jabatan || '-' }}</span>
                        </div>
                        <div>
                            <span class="text-surface-500 block">Kisaran Pendapatan / Gaji:</span>
                            <span class="font-semibold text-emerald-700 bg-emerald-100/80 px-2.5 py-1 rounded-md inline-block mt-0.5">
                                {{ response.kisaran_gaji || 'Tidak dicantumkan' }}
                            </span>
                        </div>
                        <div>
                            <span class="text-surface-500 block">Kesesuaian dengan Jurusan SMK:</span>
                            <span
                                class="inline-flex items-center gap-1 font-bold mt-1 px-2.5 py-1 rounded-md"
                                :class="response.linear_dengan_jurusan ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800'"
                            >
                                <CheckCircle2 v-if="response.linear_dengan_jurusan" class="w-3.5 h-3.5" />
                                <AlertCircle v-else class="w-3.5 h-3.5" />
                                {{ response.linear_dengan_jurusan ? 'Linear (Sesuai)' : 'Tidak Linear (Lintas Bidang)' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Condition 2: Kuliah -->
                <div v-else-if="response.status_utama === 'Kuliah'" class="space-y-4">
                    <h5 class="text-xs font-bold text-surface-500 uppercase tracking-wider flex items-center gap-2">
                        <GraduationCap class="w-4 h-4 text-blue-600 icon-pulse" />
                        <span>Data Perguruan Tinggi</span>
                    </h5>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 p-4 rounded-xl border border-blue-100 bg-blue-50/30 text-xs">
                        <div>
                            <span class="text-surface-500 block">Nama Kampus / Universitas:</span>
                            <span class="font-bold text-surface-900 text-sm">{{ response.nama_kampus || '-' }}</span>
                        </div>
                        <div>
                            <span class="text-surface-500 block">Program Studi / Fakultas:</span>
                            <span class="font-bold text-surface-900 text-sm">{{ response.program_studi || '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Condition 3: Wirausaha -->
                <div v-else-if="response.status_utama === 'Wirausaha'" class="space-y-4">
                    <h5 class="text-xs font-bold text-surface-500 uppercase tracking-wider flex items-center gap-2">
                        <Store class="w-4 h-4 text-amber-600 icon-pulse" />
                        <span>Data Usaha Mandiri</span>
                    </h5>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 p-4 rounded-xl border border-amber-100 bg-amber-50/30 text-xs">
                        <div>
                            <span class="text-surface-500 block">Nama Usaha:</span>
                            <span class="font-bold text-surface-900 text-sm">{{ response.nama_usaha || '-' }}</span>
                        </div>
                        <div>
                            <span class="text-surface-500 block">Bidang Usaha:</span>
                            <span class="font-bold text-surface-900 text-sm">{{ response.bidang_usaha || '-' }}</span>
                        </div>
                        <div>
                            <span class="text-surface-500 block">Estimasi Omset Bulanan:</span>
                            <span class="font-semibold text-amber-800 bg-amber-100/80 px-2 py-0.5 rounded-md inline-block mt-0.5">
                                {{ response.omset_bulanan || '-' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Saran & Masukan untuk Sekolah -->
                <div class="space-y-2">
                    <h5 class="text-xs font-bold text-surface-500 uppercase tracking-wider flex items-center gap-2">
                        <MessageSquareQuote class="w-4 h-4 text-primary-600 icon-pulse" />
                        <span>Saran & Masukan untuk Almamater SMK</span>
                    </h5>
                    <div class="p-4 rounded-xl bg-surface-50 border border-surface-200 text-xs text-surface-700 italic leading-relaxed">
                        "{{ response.saran_sekolah || 'Tidak ada catatan atau saran tambahan.' }}"
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="px-6 py-4 border-t border-surface-100 bg-surface-50 flex items-center justify-end">
                <button
                    @click="$emit('close')"
                    class="px-4 py-2 text-xs font-semibold text-surface-700 bg-white border border-surface-300 rounded-xl hover:bg-surface-100 transition-colors"
                >
                    Tutup
                </button>
            </div>
        </div>
    </div>
</template>