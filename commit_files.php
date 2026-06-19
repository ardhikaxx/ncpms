<?php
$output = shell_exec('git status -s');
$lines = explode("\n", trim($output));

foreach ($lines as $line) {
    if (empty(trim($line))) continue;

    $parts = preg_split('/\s+/', trim($line), 2);
    if (count($parts) < 2) continue;
    
    $status = $parts[0];
    $file = $parts[1];

    if (strpos($file, 'generate_') !== false) {
        continue;
    }

    // Determine type of commit
    $prefix = 'feat:';
    if (strpos($file, 'Middleware') !== false || strpos($file, 'Enums') !== false || strpos($file, 'Services') !== false) {
        $prefix = 'feat:';
    } elseif ($status == 'M') {
        $prefix = 'fix:';
    }

    $msg = "{$prefix} penambahan/perbaikan pada {$file}";
    if ($status == 'D') {
        continue;
    }

    if (is_dir($file)) {
        // if directory, add the whole dir
        shell_exec('git add ' . escapeshellarg($file));
    } else {
        shell_exec('git add ' . escapeshellarg($file));
    }

    $cmd = 'git commit -m ' . escapeshellarg($msg);
    shell_exec($cmd);
}

shell_exec('git push origin main');
echo "All files committed and pushed.";
