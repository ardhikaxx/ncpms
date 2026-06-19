<?php
$baseDir = __DIR__ . '/app/';
@mkdir($baseDir . 'Jobs', 0777, true);
@mkdir($baseDir . 'Policies', 0777, true);
@mkdir($baseDir . 'Http/Requests', 0777, true);
@mkdir($baseDir . 'Http/Controllers/Export', 0777, true);

// 1. Queue Job
$job = "<?php
namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\LaporanStatistik;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class GenerateLaporanBulananJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        Log::info('Memulai generasi laporan bulanan NCPMS...');
        
        \$start = Carbon::now()->subMonth()->startOfMonth();
        \$end = Carbon::now()->subMonth()->endOfMonth();

        LaporanStatistik::create([
            'tipe_laporan' => 'kinerja_harian',
            'periode_dari' => \$start->toDateString(),
            'periode_sampai' => \$end->toDateString(),
            'parameter' => json_encode(['otomatis' => true]),
            'data_laporan' => json_encode([
                'kunjungan_total' => \App\Models\Kunjungan::whereBetween('tanggal_kunjungan', [\$start, \$end])->count(),
            ]),
            'dibuat_oleh' => null, // Sistem
        ]);

        Log::info('Laporan bulanan berhasil digenerate.');
    }
}
";
file_put_contents($baseDir . 'Jobs/GenerateLaporanBulananJob.php', $job);

// 2. Policies
$policies = [
    'PasienPolicy' => "<?php
namespace App\Policies;
use App\Models\Pengguna;
use App\Models\Pasien;
use Illuminate\Auth\Access\HandlesAuthorization;

class PasienPolicy
{
    use HandlesAuthorization;

    public function viewAny(Pengguna \$user) { return in_array(\$user->peran, ['perawat', 'nutrisionis', 'dietisien', 'spgk']); }
    public function view(Pengguna \$user, Pasien \$pasien) { return in_array(\$user->peran, ['perawat', 'nutrisionis', 'dietisien', 'spgk']); }
    public function create(Pengguna \$user) { return in_array(\$user->peran, ['perawat', 'spgk']); }
    public function update(Pengguna \$user, Pasien \$pasien) { return in_array(\$user->peran, ['perawat', 'spgk']); }
    public function delete(Pengguna \$user, Pasien \$pasien) { return \$user->peran === 'spgk'; }
}
",
    'KunjunganPolicy' => "<?php
namespace App\Policies;
use App\Models\Pengguna;
use App\Models\Kunjungan;

class KunjunganPolicy
{
    public function update(Pengguna \$user, Kunjungan \$kunjungan) {
        if (\$kunjungan->dokumen_terkunci) return false;
        return in_array(\$user->peran, ['perawat', 'dietisien', 'spgk', 'nutrisionis']);
    }
    public function lock(Pengguna \$user, Kunjungan \$kunjungan) {
        return \$user->peran === 'spgk';
    }
}
",
];
foreach ($policies as $name => $content) file_put_contents($baseDir . 'Policies/' . $name . '.php', $content);

// 3. Register Policies in AppServiceProvider
$appServiceProviderPath = $baseDir . 'Providers/AppServiceProvider.php';
if (file_exists($appServiceProviderPath)) {
    $providerContent = file_get_contents($appServiceProviderPath);
    if (strpos($providerContent, 'Gate::policy') === false) {
        $replacement = "use Illuminate\\Support\\Facades\\Gate;\n\nclass AppServiceProvider";
        $providerContent = str_replace('class AppServiceProvider', $replacement, $providerContent);
        
        $bootReplacement = "public function boot(): void\n    {\n        Gate::policy(\\App\\Models\\Pasien::class, \\App\\Policies\\PasienPolicy::class);\n        Gate::policy(\\App\\Models\\Kunjungan::class, \\App\\Policies\\KunjunganPolicy::class);";
        $providerContent = str_replace("public function boot(): void\n    {", $bootReplacement, $providerContent);
        
        file_put_contents($appServiceProviderPath, $providerContent);
    }
}

// 4. Form Requests (Add few more complex ones)
$requests = [
    'StorePasienRequest' => "<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StorePasienRequest extends FormRequest {
    public function authorize() { return \$this->user()->can('create', \App\Models\Pasien::class); }
    public function rules() {
        return [
            'nik' => 'required|digits:16|unique:pasiens,nik',
            'nama_lengkap' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date|before:today',
            'jenis_kelamin' => 'required|in:L,P',
        ];
    }
}
"
];
foreach ($requests as $name => $content) file_put_contents($baseDir . 'Http/Requests/' . $name . '.php', $content);

// 5. Update Scheduler in routes/console.php
$consoleRoutes = __DIR__ . '/routes/console.php';
if (file_exists($consoleRoutes)) {
    $consoleContent = file_get_contents($consoleRoutes);
    if (strpos($consoleContent, 'GenerateLaporanBulananJob') === false) {
        $consoleContent .= "\n\nuse Illuminate\Support\Facades\Schedule;\nSchedule::job(new \App\Jobs\GenerateLaporanBulananJob)->monthlyOn(1, '00:00');\n";
        file_put_contents($consoleRoutes, $consoleContent);
    }
}

// 6. Export Controller using DomPDF and SimpleExcel
$exportController = "<?php
namespace App\Http\Controllers\Export;

use App\Http\Controllers\Controller;
use App\Models\Kunjungan;
use App\Models\Pasien;
use Barryvdh\DomPDF\Facade\Pdf;
use Spatie\SimpleExcel\SimpleExcelWriter;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function exportPasienPdf()
    {
        \$pasiens = Pasien::latest()->take(100)->get();
        \$pdf = Pdf::loadView('exports.pasien_pdf', compact('pasiens'));
        return \$pdf->download('Data_Pasien_NCPMS.pdf');
    }

    public function exportLaporanExcel()
    {
        \$pasiens = Pasien::with('kunjungans')->latest()->take(100)->get();
        \$writer = SimpleExcelWriter::streamDownload('Laporan_Pasien_NCPMS.xlsx');
        
        foreach (\$pasiens as \$p) {
            \$writer->addRow([
                'NRM' => decrypt(\$p->nomor_rekam_medis),
                'Nama' => decrypt(\$p->nama_lengkap),
                'Usia' => \$p->tanggal_lahir?->age,
                'Jenis Kelamin' => \$p->jenis_kelamin,
                'Total Kunjungan' => \$p->kunjungans->count(),
            ]);
        }
        
        return \$writer->toBrowser();
    }
}
";
file_put_contents($baseDir . 'Http/Controllers/Export/ExportController.php', $exportController);

// Update routes for exports
$routesContent = file_get_contents(__DIR__ . '/routes/web.php');
if (strpos($routesContent, 'ExportController') === false) {
    $routesContent = str_replace(
        "Route::get('/admin/pengguna', [PenggunaController::class, 'index'])->name('admin.pengguna');",
        "Route::get('/admin/pengguna', [PenggunaController::class, 'index'])->name('admin.pengguna');\n        Route::get('/export/pasien/pdf', [\App\Http\Controllers\Export\ExportController::class, 'exportPasienPdf'])->name('export.pasien.pdf');\n        Route::get('/export/laporan/excel', [\App\Http\Controllers\Export\ExportController::class, 'exportLaporanExcel'])->name('export.laporan.excel');",
        $routesContent
    );
    file_put_contents(__DIR__ . '/routes/web.php', $routesContent);
}

// Generate PDF View
@mkdir(__DIR__ . '/resources/views/exports', 0777, true);
$pdfView = "<!DOCTYPE html><html><head><title>Data Pasien</title><style>body { font-family: sans-serif; } table { width: 100%; border-collapse: collapse; } th, td { border: 1px solid #000; padding: 5px; text-align: left; }</style></head><body><h2>Laporan Data Pasien NCPMS</h2><table><thead><tr><th>NRM</th><th>Nama Pasien</th><th>Tanggal Lahir</th><th>L/P</th></tr></thead><tbody>@foreach(\$pasiens as \$p)<tr><td>{{ decrypt(\$p->nomor_rekam_medis) }}</td><td>{{ decrypt(\$p->nama_lengkap) }}</td><td>{{ \$p->tanggal_lahir?->format('d/m/Y') }}</td><td>{{ \$p->jenis_kelamin }}</td></tr>@endforeach</tbody></table></body></html>";
file_put_contents(__DIR__ . '/resources/views/exports/pasien_pdf.blade.php', $pdfView);

echo "Enterprise components generated.\n";
