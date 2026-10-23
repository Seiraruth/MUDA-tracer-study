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
    <div class="bg-white rounded-xl p-5 border border-surface-200/60 flex flex-col hover-lift stat-pulse-hover">
        <div class="flex items-center justify-between pb-3 border-b border-surface-100">
            <div>
                <h3 class="font-semibold text-surface-900 text-sm">
                    Linearitas Pekerjaan
                </h3>
                <p class="text-xs text-surface-500">Kesesuaian dengan kompetensi</p>
            </div>
            <span class="text-[10px] font-medium px-2 py-1 rounded bg-primary-50 text-primary-700 border border-primary-100">
                {{ total }} bekerja
            </span>
        </div>

        <div class="mt-4 space-y-4">
            <!-- Main Gauge -->
            <div class="flex items-center justify-between p-3 rounded-lg bg-surface-50 border border-surface-200/60">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-success/10 text-success flex items-center justify-center">
                        <Sparkles class="w-4 h-4" />
                    </div>
                    <div>
                        <span class="text-[10px] font-medium text-surface-500 uppercase tracking-wide">
                            Keselarasan
                        </span>
                        <div class="text-xl font-bold text-surface-900">
                            {{ linearPercentage }}%
                        </div>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-[10px] font-medium text-success">
                        {{ props.linearCount }} dari {{ total }}
                    </span>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="h-2 w-full rounded-full bg-surface-200 flex overflow-hidden">
                <div
                    class="h-full bg-success transition-all duration-300"
                    :style="{ width: `${linearPercentage}%` }"
                ></div>
                <div
                    class="h-full bg-warning transition-all duration-300"
                    :style="{ width: `${nonLinearPercentage}%` }"
                ></div>
            </div>

            <!-- Cards -->
            <div class="grid grid-cols-2 gap-2">
                <div class="p-2.5 rounded-lg border border-success/20 bg-success/5">
                    <div class="flex items-center gap-1.5 mb-1">
                        <CheckCircle2 class="w-3 h-3 text-success" />
                        <span class="text-[10px] font-medium text-surface-700">Sesuai</span>
                    </div>
                    <div class="text-lg font-semibold text-surface-900">
                        {{ props.linearCount }}
                    </div>
                    <div class="text-[9px] text-surface-500">{{ linearPercentage }}%</div>
                </div>

                <div class="p-2.5 rounded-lg border border-warning/20 bg-warning/5">
                    <div class="flex items-center gap-1.5 mb-1">
                        <AlertTriangle class="w-3 h-3 text-warning" />
                        <span class="text-[10px] font-medium text-surface-700">Lintas</span>
                    </div>
                    <div class="text-lg font-semibold text-surface-900">
                        {{ props.nonLinearCount }}
                    </div>
                    <div class="text-[9px] text-surface-500">{{ nonLinearPercentage }}%</div>
                </div>
            </div>
        </div>

        <div class="mt-3 pt-2.5 border-t border-surface-100 text-[10px] text-surface-500">
            Evaluasi link & match dengan DUDI
        </div>
    </div>
</template>
