<script setup>
import { ref } from 'vue';
const pesan = ref('Vue berhasil terhubung!');
</script>

<template>
<main style="padding: 40px; font-family: sans-serif;">
    <h1>{{ pesan }}</h1>
    <p>Halaman percobaan Vue + Inertia pada Laravel.</p>

    <button
        @click="pesan = 'Interaksi Vue berhasil!'"
        style="padding: 10px 16px; margin-top: 12px; cursor: pointer;"
    >
        Tes Interaksi
    </button>
</main>

</template>
