<script setup>
import { computed } from 'vue';

const props = defineProps({
    title: {
        type: String,
        required: true,
    },
    value: {
        type: [Number, String],
        required: true,
    },
    subtitle: {
        type: String,
        default: '',
    },
    percentage: {
        type: [Number, String],
        default: null,
    },
    badge: {
        type: String,
        default: null,
    },
    color: {
        type: String,
        default: 'indigo', // 'indigo', 'emerald', 'blue', 'amber', 'rose'
    },
    icon: {
        type: [Object, Function],
        default: null,
    },
});

const colorClasses = computed(() => {
    switch (props.color) {
        case 'emerald':
            return {
                bg: 'bg-emerald-50 text-emerald-600 border-emerald-100',
                ring: 'hover:border-emerald-200',
                badge: 'bg-emerald-100/80 text-emerald-700',
                bar: 'bg-emerald-500',
                indicator: 'text-emerald-600',
            };
        case 'blue':
            return {
                bg: 'bg-blue-50 text-blue-600 border-blue-100',
                ring: 'hover:border-blue-200',
                badge: 'bg-blue-100/80 text-blue-700',
                bar: 'bg-blue-500',
                indicator: 'text-blue-600',
            };
        case 'amber':
            return {
                bg: 'bg-amber-50 text-amber-600 border-amber-100',
                ring: 'hover:border-amber-200',
                badge: 'bg-amber-100/80 text-amber-700',
                bar: 'bg-amber-500',
                indicator: 'text-amber-600',
            };
        case 'rose':
            return {
                bg: 'bg-rose-50 text-rose-600 border-rose-100',
                ring: 'hover:border-rose-200',
                badge: 'bg-rose-100/80 text-rose-700',
                bar: 'bg-rose-500',
                indicator: 'text-rose-600',
            };
        case 'indigo':
        default:
            return {
                bg: 'bg-indigo-50 text-indigo-600 border-indigo-100',
                ring: 'hover:border-indigo-200',
                badge: 'bg-indigo-100/80 text-indigo-700',
                bar: 'bg-indigo-500',
                indicator: 'text-indigo-600',
            };
    }
});
</script>

<template>
    <div
        class="bg-white rounded-2xl p-5 border border-slate-200/80 shadow-xs hover:shadow-md transition-all duration-200 flex flex-col justify-between"
    >
        <div class="flex items-start justify-between">
            <div class="space-y-1">
                <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">
                    {{ title }}
                </span>
                <div class="flex items-baseline gap-2 pt-0.5">
                    <span class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
                        {{ value }}
                    </span>
                    <span
                        v-if="percentage !== null"
                        class="text-xs font-bold px-2 py-0.5 rounded-full"
                        :class="colorClasses.badge"
                    >
                        {{ percentage }}%
                    </span>
                </div>
            </div>
            <div
                v-if="icon"
                class="w-12 h-12 rounded-xl flex items-center justify-center border transition-transform duration-200 group-hover:scale-105"
                :class="colorClasses.bg"
            >
                <component :is="icon" class="w-6 h-6" />
            </div>
        </div>

        <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
            <span class="text-slate-500">{{ subtitle }}</span>
            <span v-if="badge" class="font-semibold" :class="colorClasses.indicator">
                {{ badge }}
            </span>
        </div>
    </div>
</template>
