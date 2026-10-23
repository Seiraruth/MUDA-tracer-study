<script setup>
import { computed } from 'vue';

const props = defineProps({
    title: { type: String, required: true },
    value: { type: [Number, String], required: true },
    subtitle: { type: String, default: '' },
    percentage: { type: [Number, String], default: null },
    badge: { type: String, default: null },
    color: { type: String, default: 'primary' },
    icon: { type: [Object, Function], default: null },
});

const colorClasses = computed(() => {
    switch (props.color) {
        case 'emerald':
            return {
                bg: 'bg-emerald-100 text-emerald-600',
                badge: 'bg-emerald-100 text-emerald-700',
                indicator: 'text-emerald-600',
            };
        case 'blue':
            return {
                bg: 'bg-blue-100 text-blue-600',
                badge: 'bg-blue-100 text-blue-700',
                indicator: 'text-blue-600',
            };
        case 'amber':
            return {
                bg: 'bg-amber-100 text-amber-600',
                badge: 'bg-amber-100 text-amber-700',
                indicator: 'text-amber-600',
            };
        case 'rose':
            return {
                bg: 'bg-rose-100 text-rose-600',
                badge: 'bg-rose-100 text-rose-700',
                indicator: 'text-rose-600',
            };
        case 'primary':
        default:
            return {
                bg: 'bg-primary-100 text-primary-600',
                badge: 'bg-primary-100 text-primary-700',
                indicator: 'text-primary-600',
            };
    }
});
</script>

<template>
    <div class="bg-white rounded-xl p-4 border border-surface-200 shadow-premium shadow-premium-hover hover-lift stat-glow">
        <div class="flex items-start justify-between">
            <div class="space-y-0.5">
                <span class="text-[11px] font-medium text-surface-500 uppercase tracking-wide">
                    {{ title }}
                </span>
                <div class="flex items-baseline gap-1.5">
                    <span class="text-2xl font-bold text-surface-900">
                        {{ value }}
                    </span>
                    <span
                        v-if="percentage !== null"
                        class="text-[10px] font-semibold px-1.5 py-0.5 rounded"
                        :class="colorClasses.badge"
                    >
                        {{ percentage }}%
                    </span>
                </div>
            </div>
            <div
                v-if="icon"
                class="w-10 h-10 rounded-lg flex items-center justify-center"
                :class="colorClasses.bg"
            >
                <component :is="icon" class="w-5 h-5 icon-pulse" />
            </div>
        </div>

        <div class="mt-3 pt-2.5 border-t border-surface-100 flex items-center justify-between text-[11px]">
            <span class="text-surface-500">{{ subtitle }}</span>
            <span v-if="badge" class="font-semibold" :class="colorClasses.indicator">
                {{ badge }}
            </span>
        </div>
    </div>
</template>