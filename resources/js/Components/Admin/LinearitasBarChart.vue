<script setup>
import { computed } from 'vue';
import { CheckCircle2, AlertTriangle, Sparkles } from 'lucide-vue-next';

const props = defineProps({
    linearCount: {
        type: Number,
        default: 0,
    },
    nonLinearCount: {
        type: Number,
        default: 0,
    },
    totalWorking: {
        type: Number,
        default: 0,
    },
});

const total = computed(() => {
    return props.totalWorking > 0 ? props.totalWorking : props.linearCount + props.nonLinearCount;
});

const linearPercentage = computed(() => {
    if (total.value === 0) return 0;
    return Math.round((props.linearCount / total.value) * 100);
});

const nonLinearPercentage = computed(() => {
    if (total.value === 0) return 0;
    return Math.round((props.nonLinearCount / total.value) * 100);
});
</script>

<template>
    <div class="bg-white rounded-2xl p-6 border border-slate-200/80 shadow-xs flex flex-col justify-between">
        <div>
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div>
                    <h3 class="font-bold text-base text-slate-900 tracking-tight flex items-center gap-2">
                        Linearitas Bidang Pekerjaan
                    </h3>
                    <p class="text-xs text-slate-500">Kesesuaian pekerjaan alumni dengan jurusan SMK</p>
                </div>
                <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100">
                    {{ total }} Alumni Bekerja
                </span>
            </div>

            <div class="mt-6 space-y-5">
                <!-- Main Percentage Gauge -->
                <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 border border-slate-200/60">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold">
                            <Sparkles class="w-5 h-5" />
                        </div>
                        <div>
                            <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Indeks Keselarasan Vokasi
                            </span>
                            <div class="text-2xl font-extrabold text-slate-900">
                                {{ linearPercentage }}%
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="inline-flex items-center gap-1 text-xs font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800">
                            {{ props.linearCount }} dari {{ total }} Sesuai
                        </span>
                        <p class="text-[11px] text-slate-400 mt-1">Relevan dengan Kompetensi Keahlian</p>
                    </div>
                </div>

                <!-- Dual Segment Progress Bar -->
                <div>
                    <div class="h-3 w-full rounded-full bg-slate-100 flex overflow-hidden p-0.5">
                        <div
                            class="h-full bg-gradient-to-r from-emerald-500 to-teal-400 rounded-full transition-all duration-500"
                            :style="{ width: `${linearPercentage}%` }"
                        ></div>
                        <div
                            class="h-full bg-gradient-to-r from-amber-400 to-amber-500 rounded-full transition-all duration-500 ml-0.5"
                            :style="{ width: `${nonLinearPercentage}%` }"
                        ></div>
                    </div>
                </div>

                <!-- Breakdown Comparison Cards -->
                <div class="grid grid-cols-2 gap-3 pt-1">
                    <div class="p-3.5 rounded-xl border border-emerald-100 bg-emerald-50/50">
                        <div class="flex items-center gap-2 mb-1">
                            <CheckCircle2 class="w-4 h-4 text-emerald-600" />
                            <span class="text-xs font-bold text-emerald-900">Linear / Sesuai</span>
                        </div>
                        <div class="text-xl font-bold text-emerald-950">
                            {{ props.linearCount }} <span class="text-xs font-normal text-emerald-700">alumni</span>
                        </div>
                        <p class="text-[11px] text-emerald-600 font-semibold">{{ linearPercentage }}% dari yang bekerja</p>
                    </div>

                    <div class="p-3.5 rounded-xl border border-amber-100 bg-amber-50/50">
                        <div class="flex items-center gap-2 mb-1">
                            <AlertTriangle class="w-4 h-4 text-amber-600" />
                            <span class="text-xs font-bold text-amber-900">Lintas Bidang</span>
                        </div>
                        <div class="text-xl font-bold text-amber-950">
                            {{ props.nonLinearCount }} <span class="text-xs font-normal text-amber-700">alumni</span>
                        </div>
                        <p class="text-[11px] text-amber-600 font-semibold">{{ nonLinearPercentage }}% dari yang bekerja</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 pt-3 border-t border-slate-100 text-xs text-slate-500 flex items-center justify-between">
            <span>Evaluasi Link and Match DUDI (Dunia Usaha & Dunia Industri)</span>
        </div>
    </div>
</template>
