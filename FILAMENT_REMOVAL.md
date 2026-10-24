# Filament Removal Documentation

**Tanggal**: 2026-10-24

## Alasan
Aplikasi ini **tidak menggunakan Filament** untuk admin panel. Admin panel dibangun dengan:
- **Inertia.js** + **Vue 3** (Composition API)
- **Laravel 12** backend dengan Inertia controllers
- **Tailwind CSS v4** untuk styling

## File yang Dihapus

### 1. Direktori Filament Resources
```
app/Filament/Resources/
├── AlumniResource.php
├── AlumniResource/Pages/
├── JobPostingResource.php
├── JobPostingResource/Pages/
├── TracerResponseResource.php
└── TracerResponseResource/Pages/
```

### 2. Direktori Filament Widgets
```
app/Filament/Widgets/
├── BmwChartWidget.php
└── StatsOverview.php
```

### 3. Filament Provider
```
app/Providers/Filament/
└── AdminPanelProvider.php
```

## Perubahan composer.json

### Dependency Dihapus
```json
"filament/filament": "^3.2"
```

### Script Dihapus
```json
"@php artisan filament:upgrade"
```

## Admin Panel yang Sebenarnya Digunakan

### Routes (web.php)
```
/admin                  → DashboardController
/admin/alumni           → AlumniController
/admin/tracer-responses → TracerResponseController
/admin/job-postings     → JobPostingController
/admin/mitras           → MitraController
/admin/saran            → SaranController
```

### Vue Components Lokasi
```
resources/js/Pages/Admin/
├── Dashboard.vue
├── Alumni/Index.vue
├── TracerResponses/Index.vue
├── JobPostings/Index.vue
├── Mitras/Index.vue (jika ada)
└── Saran/Index.vue (jika ada)

resources/js/Layouts/
└── AdminLayout.vue

resources/js/Components/Admin/
├── StatCard.vue
├── BmwDonutChart.vue
├── LinearitasBarChart.vue
├── RecentResponsesTable.vue
└── JobPostingsList.vue
```

## Catatan untuk Developer

**JANGAN** menggunakan atau mencari file Filament untuk modifikasi admin panel.

Untuk mengubah tampilan admin:
1. Edit file Vue di `resources/js/Pages/Admin/`
2. Edit layout di `resources/js/Layouts/AdminLayout.vue`
3. Edit styling di `resources/css/app.css`
4. Edit controller di `app/Http/Controllers/Admin/`

## Next Steps (Manual)

Karena PHP version mismatch (8.2 vs 8.4), langkah berikut perlu dilakukan manual:

1. **Update PHP** ke versi 8.4+ atau
2. **Downgrade Laravel dependencies** yang compatible dengan PHP 8.2
3. Jalankan: `composer dump-autoload`
4. Jalankan: `composer update` (optional, untuk clean up)

---

**Status**: ✅ File Filament sudah dihapus dari repository
**Warning**: Composer autoload belum di-regenerate karena PHP version mismatch
