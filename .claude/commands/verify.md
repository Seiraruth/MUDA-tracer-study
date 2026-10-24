Jalankan pemeriksaan proyek ini dengan urutan berikut:

1. Jalankan `php artisan test`.
2. Jika tes lulus, jalankan `npm run build`.
3. Jalankan `git diff --check`.
4. Laporkan hasil setiap perintah dengan jelas. Jika satu pemeriksaan gagal, tampilkan error yang relevan dan jangan menyatakan verifikasi berhasil.

Jangan menjalankan migrasi yang menghapus atau mengosongkan data, termasuk `migrate:fresh`, `db:wipe`, atau reset database.

Ikuti instruksi pengguna untuk commit dan push. Jika pengguna secara eksplisit meminta commit atau push, lanjutkan tanpa meminta persetujuan tambahan. Jika tidak ada instruksi eksplisit, minta persetujuan sebelum membuat commit atau mengirim perubahan ke remote. Jangan menjalankan operasi remote yang tidak diminta.
