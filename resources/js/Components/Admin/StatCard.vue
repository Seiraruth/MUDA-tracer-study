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
    <div class="bg-white rounded-2xl p-5 border border-surface-200 shadow-premium shadow-premium-hover hover-lift stat-glow group">
        <div class="flex items-start justify-between mb-4">
            <div class="space-y-1">
                <span class="text-xs font-bold text-surface-600 uppercase tracking-wider block">
                    {{ title }}
                </span>
                <div class="flex items-baseline gap-2">
                    <span class="text-3xl font-extrabold text-surface-900 tabular-nums">
                        {{ value }}
                    </span>
                    <span
                        v-if="percentage !== null"
                        class="text-xs font-extrabold px-2 py-1 rounded-lg"
                        :class="colorClasses.badge"
                    >
                        {{ percentage }}%
                    </span>
                </div>
            </div>
            <div
                v-if="icon"
                class="w-12 h-12 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform"
                :class="colorClasses.bg"
            >
                <component :is="icon" class="w-6 h-6" />
            </div>
        </div>

        <div class="flex items-center justify-between text-xs">
            <span class="text-surface-600 font-medium">{{ subtitle }}</span>
            <span v-if="badge" class="font-bold" :class="colorClasses.indicator">
                {{ badge }}
            </span>
        </div>
    </div>
</template>