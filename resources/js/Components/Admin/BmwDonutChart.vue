<script setup>
import { computed, ref } from 'vue';
import { Briefcase, GraduationCap, Store, Search } from 'lucide-vue-next';

const props = defineProps({
    bekerja: { type: Number, default: 0 },
    kuliah: { type: Number, default: 0 },
    wirausaha: { type: Number, default: 0 },
    mencariKerja: { type: Number, default: 0 },
});

const activeHover = ref(null);

const total = computed(() => props.bekerja + props.kuliah + props.wirausaha + props.mencariKerja);

const categories = computed(() => {
    const t = total.value > 0 ? total.value : 1;
    return [
        { key: 'bekerja', label: 'Bekerja', desc: 'Karyawan / Pegawai', count: props.bekerja, percentage: ((props.bekerja / t) * 100).toFixed(1), color: '#059669', bgColor: 'bg-emerald-500', icon: Briefcase },
        { key: 'kuliah', label: 'Kuliah', desc: 'Perguruan Tinggi', count: props.kuliah, percentage: ((props.kuliah / t) * 100).toFixed(1), color: '#2563eb', bgColor: 'bg-blue-500', icon: GraduationCap },
        { key: 'wirausaha', label: 'Wirausaha', desc: 'UMKM / Freelance', count: props.wirausaha, percentage: ((props.wirausaha / t) * 100).toFixed(1), color: '#d97706', bgColor: 'bg-amber-500', icon: Store },
        { key: 'mencariKerja', label: 'Mencari', desc: 'Sedang Mencari', count: props.mencariKerja, percentage: ((props.mencariKerja / t) * 100).toFixed(1), color: '#dc2626', bgColor: 'bg-rose-500', icon: Search },
    ];
});

const radius = 68;
const circumference = 2 * Math.PI * radius;

const slices = computed(() => {
    let accumulated = 0;
    const t = total.value > 0 ? total.value : 1;
    return categories.value.map((cat) => {
        const ratio = total.value > 0 ? cat.count / t : 0;
        const strokeLength = ratio * circumference;
        const strokeDasharray = `${strokeLength} ${circumference - strokeLength}`;
        const strokeDashoffset = -accumulated;
        accumulated += strokeLength;
        return { ...cat, strokeDasharray, strokeDashoffset };
    });
});
</script>

<template>
    <div class="bg-white rounded-xl p-5 border border-surface-200 flex flex-col hover-lift stat-pulse-hover">
        <div class="flex items-center justify-between pb-3 border-b border-surface-100">
            <div>
                <h3 class="font-semibold text-surface-900 text-sm">Distribusi BMW</h3>
                <p class="text-xs text-surface-500">Bekerja, Kuliah, Wirausaha</p>
            </div>
            <span class="text-[10px] font-semibold px-2 py-1 rounded bg-primary-100 text-primary-700">{{ total }} responden</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 py-4">
            <!-- Donut Chart -->
            <div class="md:col-span-5 flex items-center justify-center">
                <div class="relative w-36 h-36 sm:w-40 sm:h-40">
                    <svg class="w-full h-full transform -rotate-90" viewBox="0 0 160 160">
                        <circle cx="80" cy="80" :r="radius" stroke="#e4e4e7" stroke-width="16" fill="transparent" />
                        <circle v-for="slice in slices" :key="slice.key" cx="80" cy="80" :r="radius" :stroke="slice.color" stroke-width="16" fill="transparent" :stroke-dasharray="slice.strokeDasharray" :stroke-dashoffset="slice.strokeDashoffset" stroke-linecap="round" class="transition-all duration-200" @mouseenter="activeHover = slice.key" @mouseleave="activeHover = null" />
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center text-center">
                        <span class="text-[10px] font-medium text-surface-400">Total</span>
                        <span class="text-2xl font-bold text-surface-900">{{ total }}</span>
                    </div>
                </div>
            </div>

            <!-- Legend -->
            <div class="md:col-span-7 space-y-2">
                <div v-for="cat in categories" :key="cat.key" class="p-2.5 rounded-lg border transition-colors flex items-center justify-between" :class="activeHover === cat.key ? 'border-primary-300 bg-primary-50' : 'border-surface-100 hover:border-surface-200'" @mouseenter="activeHover = cat.key" @mouseleave="activeHover = null">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-md flex items-center justify-center text-white" :class="cat.bgColor">
                            <component :is="cat.icon" class="w-3.5 h-3.5 icon-pulse" />
                        </div>
                        <div>
                            <span class="text-xs font-medium text-surface-800">{{ cat.label }}</span>
                            <p class="text-[9px] text-surface-500">{{ cat.desc }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-sm font-bold text-surface-900">{{ cat.count }}</span>
                        <span class="text-[10px] text-surface-500 ml-1">({{ cat.percentage }}%)</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="pt-3 border-t border-surface-100 flex items-center justify-between text-[10px] text-surface-500">
            <span>Indikator keberhasilan lulusan</span>
            <span class="text-primary-600 font-semibold">BMW</span>
        </div>
    </div>
</template>