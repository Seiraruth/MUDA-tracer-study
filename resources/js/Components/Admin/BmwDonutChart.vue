<script setup>
import { computed, ref } from 'vue';
import { Briefcase, GraduationCap, Store, Search } from 'lucide-vue-next';

const props = defineProps({
    bekerja: {
        type: Number,
        default: 0,
    },
    kuliah: {
        type: Number,
        default: 0,
    },
    wirausaha: {
        type: Number,
        default: 0,
    },
    mencariKerja: {
        type: Number,
        default: 0,
    },
});

const activeHover = ref(null);

const total = computed(() => {
    return props.bekerja + props.kuliah + props.wirausaha + props.mencariKerja;
});

const categories = computed(() => {
    const t = total.value > 0 ? total.value : 1;
    return [
        {
            key: 'bekerja',
            label: 'Bekerja',
            desc: 'Karyawan / Pegawai Swasta / BUMN / PNS',
            count: props.bekerja,
            percentage: ((props.bekerja / t) * 100).toFixed(1),
            color: '#10B981', // emerald-500
            bgColor: 'bg-emerald-500',
            bgLight: 'bg-emerald-50 text-emerald-700 border-emerald-200',
            icon: Briefcase,
        },
        {
            key: 'kuliah',
            label: 'Melanjutkan (Kuliah)',
            desc: 'Perguruan Tinggi Negeri / Swasta',
            count: props.kuliah,
            percentage: ((props.kuliah / t) * 100).toFixed(1),
            color: '#3B82F6', // blue-500
            bgColor: 'bg-blue-500',
            bgLight: 'bg-blue-50 text-blue-700 border-blue-200',
            icon: GraduationCap,
        },
        {
            key: 'wirausaha',
            label: 'Wirausaha',
            desc: 'Usaha Mandiri / UMKM / Freelance',
            count: props.wirausaha,
            percentage: ((props.wirausaha / t) * 100).toFixed(1),
            color: '#F59E0B', // amber-500
            bgColor: 'bg-amber-500',
            bgLight: 'bg-amber-50 text-amber-700 border-amber-200',
            icon: Store,
        },
        {
            key: 'mencariKerja',
            label: 'Mencari Kerja',
            desc: 'Sedang Proses Seleksi / Menunggu',
            count: props.mencariKerja,
            percentage: ((props.mencariKerja / t) * 100).toFixed(1),
            color: '#EF4444', // rose-500
            bgColor: 'bg-rose-500',
            bgLight: 'bg-rose-50 text-rose-700 border-rose-200',
            icon: Search,
        },
    ];
});

// SVG Donut Calculations
const radius = 68;
const circumference = 2 * Math.PI * radius; // ~427.256

const slices = computed(() => {
    let accumulated = 0;
    const t = total.value > 0 ? total.value : 1;

    return categories.value.map((cat) => {
        const ratio = total.value > 0 ? cat.count / t : 0;
        const strokeLength = ratio * circumference;
        const strokeDasharray = `${strokeLength} ${circumference - strokeLength}`;
        const strokeDashoffset = -accumulated;
        accumulated += strokeLength;

        return {
            ...cat,
            strokeDasharray,
            strokeDashoffset,
        };
    });
});
</script>

<template>
    <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs flex flex-col justify-between">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div>
                <h3 class="font-bold text-base text-slate-900 tracking-tight flex items-center gap-2">
                    Grafik Distribusi BMW
                </h3>
                <p class="text-xs text-slate-500">Bekerja, Melanjutkan Pendidikan, & Wirausaha</p>
            </div>
            <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-slate-100 text-slate-700">
                Total: {{ total }} Responden
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-6 items-center py-6">
            <!-- Donut Visual -->
            <div class="md:col-span-5 flex flex-col items-center justify-center relative">
                <div class="relative w-48 h-48 sm:w-52 sm:h-52">
                    <svg class="w-full h-full transform -rotate-90" viewBox="0 0 160 160">
                        <!-- Background Circle -->
                        <circle
                            cx="80"
                            cy="80"
                            :r="radius"
                            stroke="#F1F5F9"
                            stroke-width="18"
                            fill="transparent"
                        />
                        <!-- Slices -->
                        <circle
                            v-for="slice in slices"
                            :key="slice.key"
                            cx="80"
                            cy="80"
                            :r="radius"
                            :stroke="slice.color"
                            stroke-width="18"
                            fill="transparent"
                            :stroke-dasharray="slice.strokeDasharray"
                            :stroke-dashoffset="slice.strokeDashoffset"
                            stroke-linecap="round"
                            class="transition-all duration-300 cursor-pointer hover:opacity-90"
                            :style="{
                                transformOrigin: 'center',
                                transform: activeHover === slice.key ? 'scale(1.04)' : 'scale(1)',
                            }"
                            @mouseenter="activeHover = slice.key"
                            @mouseleave="activeHover = null"
                        />
                    </svg>

                    <!-- Center Label -->
                    <div class="absolute inset-0 flex flex-col items-center justify-center text-center pointer-events-none">
                        <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">
                            Respon Masuk
                        </span>
                        <span class="text-3xl font-extrabold text-slate-900">
                            {{ total }}
                        </span>
                        <span class="text-[11px] font-medium text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full mt-0.5">
                            Alumni Terdata
                        </span>
                    </div>
                </div>
            </div>

            <!-- Legend & Stats Cards -->
            <div class="md:col-span-7 space-y-2.5">
                <div
                    v-for="cat in categories"
                    :key="cat.key"
                    class="p-3 rounded-xl border transition-all duration-150 flex items-center justify-between"
                    :class="[
                        activeHover === cat.key
                            ? 'border-indigo-300 bg-indigo-50/40 shadow-xs'
                            : 'border-slate-100 hover:border-slate-200 bg-slate-50/50',
                    ]"
                    @mouseenter="activeHover = cat.key"
                    @mouseleave="activeHover = null"
                >
                    <div class="flex items-center gap-3">
                        <div
                            class="w-8 h-8 rounded-lg flex items-center justify-center shrink-0 text-white shadow-xs"
                            :class="cat.bgColor"
                        >
                            <component :is="cat.icon" class="w-4 h-4" />
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-bold text-slate-800">{{ cat.label }}</span>
                            </div>
                            <p class="text-[11px] text-slate-500 line-clamp-1">{{ cat.desc }}</p>
                        </div>
                    </div>

                    <div class="text-right shrink-0">
                        <span class="text-sm font-bold text-slate-900">{{ cat.count }} Org</span>
                        <div class="text-[11px] font-semibold text-slate-500">
                            {{ cat.percentage }}%
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
            <span>Standar Indikator Keberhasilan Lulusan Vokasi SMK</span>
            <span class="text-indigo-600 font-semibold">BMW Tracer Study</span>
        </div>
    </div>
</template>
