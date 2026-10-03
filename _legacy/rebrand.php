<?php
$dir = __DIR__;
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
$extensions = ['php', 'vue', 'js', 'json', 'md', 'sql', 'html'];
$replacements = [
    'SMK' => 'SMK',
    'SMK' => 'SMK',
    'smk' => 'smk',
    'Tim Pengembang' => 'Tim Pengembang',
    'tim pengembang' => 'tim pengembang',
    'SMK' => 'SMK',
    'smk' => 'smk',
    'Sekolah' => 'Sekolah',
    'sekolah' => 'sekolah',
    'Sekolah' => 'Sekolah',
];

// Regex for bounded words to prevent messing up variables like "anonymous" -> "anonisn"
$regexReplacements = [
    '/\bMahasiswa\b/' => 'Siswa',
    '/\bmahasiswa\b/' => 'siswa',
    '/\bKampus\b/' => 'Sekolah',
    '/\bkampus\b/' => 'sekolah',
    '/\bProdi\b/' => 'Jurusan',
    '/\bprodi\b/' => 'jurusan',
    '/\bNIM\b/' => 'NISN',
    '/\bnim\b/' => 'nisn',
];

foreach ($files as $file) {
    if ($file->isDir()) continue;
    $path = $file->getPathname();
    if (strpos($path, '\\vendor\\') !== false || strpos($path, '\\node_modules\\') !== false || strpos($path, '\\.git\\') !== false || strpos($path, '\\storage\\') !== false) continue;
    
    $ext = pathinfo($path, PATHINFO_EXTENSION);
    if (!in_array($ext, $extensions)) continue;
    
    $content = file_get_contents($path);
    $newContent = $content;
    
    foreach ($replacements as $search => $replace) {
        $newContent = str_replace($search, $replace, $newContent);
    }
    foreach ($regexReplacements as $pattern => $replace) {
        $newContent = preg_replace($pattern, $replace, $newContent);
    }
    
    if ($newContent !== $content) {
        file_put_contents($path, $newContent);
        echo "Updated: $path\n";
    }
}
