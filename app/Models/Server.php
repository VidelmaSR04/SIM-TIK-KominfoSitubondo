<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    use HasFactory;

    protected $fillable = [
        'status_kelengkapan',
        'user_id',
        'nama_perangkat',
        'jenis_perangkat',
        'serial_number',
        'merk_perangkat',
        'type',
        'kondisi_tipe',
        'kondisi_status',
        'spesifikasi',
        'tipe_perangkat',
        'status_kepemilikan',
        'pemilik_perangkat',
        'ip_server',
        'ip_vps',
        'status',
        'ukuran_hdd',
        'ukuran_ram',
        'nomor_rack',
        'gambar_rack',
        'jumlah_core',
        'peruntukan',
        'nama_pengirim',
        'nama_penerima',
        'jam_pengisian',
        'tanggal_input'
    ];

    protected $casts = [
        'jam_pengisian' => 'datetime',
    ];

    public function aplikasis()
    {
        return $this->belongsToMany(Aplikasi::class, 'server_aplikasi')
            ->withPivot('ip_local', 'ip_public', 'url')
            ->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * True kalau data sudah ditandai "lengkap" oleh admin -> QR boleh aktif.
     */
    public function getIsLengkapAttribute(): bool
    {
        return $this->status_kelengkapan === 'lengkap';
    }

    /**
     * Nama OPD turunan dari status_kepemilikan dan pemilik_perangkat
     */
    public function getNamaOpdAttribute()
    {
        return $this->status_kepemilikan === 'Colocation' ? $this->pemilik_perangkat : 'Kominfo';
    }

    /**
     * Sinkronkan field status berdasarkan status_kelengkapan,
     * kecuali jika status sudah dikunci manual oleh admin (status_locked = true).
     *
     * Aturan sinkronisasi:
     * - Jika status_locked = true → JANGAN ubah status (biarkan pilihan admin)
     * - Jika status_locked = false →
     *   * status_kelengkapan = 'lengkap'    lengkap'    → status = 'Aktif'
     *   * status_kelengkapan IN ('pending', 'dilengkapi') → status = 'Pending'
     *
     * Method ini harus dipanggIL setelah setiap kali status_kelengkapan diubah
     * (baik oleh user maupun admin) untuk menjaga konsistensi status.
     */
    public function syncStatusFromKelengkapan(): void
    {
        // Jika status sudah dikunci manual oleh admin, jangan ubah apa-apa
        if ($this->status_locked) {
            return;
        }

        // Sync status berdasarkan status_kelengkapan
        if ($this->status_kelengkapan === 'lengkap') {
            $this->status = 'Aktif';
        } else {
            // 'pending' atau 'dilengkapi' -> status = 'Pending'
            $this->status = 'Pending';
        }
    }

    /**
     * Field yang wajib terisi sebelum data boleh dianggap "lengkap".
     * Field 'status' tidak termasuk karena akan diisi otomatis berdasarkan status_kelengkapan.
     */
    public static function fieldWajibLengkap(): array
    {
        return [
            'serial_number', 'ip_server', 'nomor_rack',
            'ukuran_ram', 'ukuran_hdd', 'jumlah_core',
            'kondisi_tipe',
        ];
    }

    /**
     * Get list of required fields that are still empty for this server
     * @return array List of field labels that are missing
     */
    public function getMissingRequiredFields(): array
    {
        $requiredFields = static::fieldWajibLengkap();
        $missingFields = [];

        foreach ($requiredFields as $field) {
            $value = $this->{$field} ?? null;

            // Check if field is filled with valid value
            $isFilled = false;
            if (is_numeric($value)) {
                // For numeric fields, 0 is considered NOT valid (must be > 0)
                $isFilled = ((float)$value > 0);
            } else {
                // For string fields, empty string is considered NOT filled
                $isFilled = (is_string($value) && trim($value) !== '');
                // Handle nullable non-string fields that are not null
                if (!is_string($value)) {
                    $isFilled = ($value !== null);
                }
            }

            if (!$isFilled) {
                $missingFields[] = $this->getFieldLabel($field);
            }
        }

        return $missingFields;
    }

    /**
     * Get human-readable label for a field
     * @param string $field Field name
     * @return string Human-readable label
     */
    public function getFieldLabel(string $field): string
    {
        $fieldLabels = [
            'serial_number' => 'Nomor Seri',
            'ip_server' => 'IP Server',
            'nomor_rack' => 'Nomor RACK',
            'ukuran_ram' => 'Kapasitas RAM',
            'ukuran_hdd' => 'Kapasitas Penyimpanan',
            'jumlah_core' => 'Jumlah Core',
            'kondisi_tipe' => 'Tipe Kondisi',
        ];

        return $fieldLabels[$field] ?? ucfirst(str_replace('_', ' ', $field));
    }

    /**
     * Get alternative jenis value for display in form
     * Returns the actual value if it's not in master data (custom/Lainnya),
     * otherwise returns empty string
     */
    public function getJenisLainnyaAttribute()
    {
        $jenisList = MasterData::forSelect('jenis_perangkat');
        if (empty($jenisList)) {
            $jenisList = ['router' => 'Router', 'switch' => 'Switch', 'server' => 'Server'];
        }

        // If the current value is not in the master data list, it's a custom value
        if (!array_key_exists($this->jenis_perangkat, $jenisList)) {
            return $this->jenis_perangkat;
        }

        return '';
    }

    /**
     * Get alternative merk value for display in form
     * Returns the actual value if it's not in master data (custom/Lainnya),
     * otherwise returns empty string
     */
    public function getMerkLainnyaAttribute()
    {
        $merkList = MasterData::forSelect('merk_perangkat');
        if (empty($merkList)) {
            $merkList = [
                'MIKROTIK' => 'MIKROTIK',
                'CISCO' => 'CISCO',
                'DELL' => 'DELL',
                'HP' => 'HP',
                'LENOVO' => 'LENOVO',
                'HUAWEI' => 'HUAWEI',
            ];
        }

        // If the current value is not in the master data list, it's a custom value
        if (!array_key_exists($this->merk_perangkat, $merkList)) {
            return $this->merk_perangkat;
        }

        return '';
    }

    /**
     * Hitung status_kelengkapan otomatis berdasarkan field yang sudah terisi di $data.
     *
     * Nilai yang dihasilkan:
     * - 'pending'    = belum ada data teknis yang diisi sama sekali (0 field wajib terisi)
     * - 'dilengkapi' = sebagian field wajib sudah diisi, tapi belum semua (1-6 field wajib terisi)
     * - 'lengkap'    = semua field wajib sudah terisi (7 field wajib terisi)
     */
    public static function hitungStatusKelengkapan(array $data): string
    {
        $requiredFields = static::fieldWajibLengkap();
        $filledCount = 0;

        foreach ($requiredFields as $field) {
            $value = $data[$field] ?? null;

            // Cek apakah field terisi dengan nilai yang valid
            // Untuk field numerik, 0 dianggap sebagai TIDAK valid (harus > 0)
            // Untuk field string, empty string dianggap sebagai TIDAK terisi
            if (is_numeric($value)) {
                // Field numerik: harus lebih dari 0
                if ((float)$value > 0) {
                    $filledCount++;
                }
            } else {
                // Field string: tidak boleh empty setelah trim
                if (is_string($value) && trim($value) !== '') {
                    $filledCount++;
                }
                // Field nullable yang diisi dengan nilai non-null non-empty juga terhitung
                elseif (!is_string($value) && $value !== null) {
                    $filledCount++;
                }
            }
        }

        if ($filledCount === 0) {
            return 'pending';
        } elseif ($filledCount < count($requiredFields)) {
            return 'dilengkapi';
        } else {
            return 'lengkap';
        }
    }

    /**
     * Generate kode perangkat berdasarkan status kepemilikan, tanggal, dan urutan harian
     * Format: [KO|CO][YYMMDD][A-Z|Z1-Z2-Z3...]
     * - KO untuk Kominfo, CO untuk Colocation (OPD)
     * - YYMMDD: tahun 2 digit, bulan 2 digit, tanggal 2 digit
     * - Urutan harian: mulai dari A, B, C, ..., Z, Z1, Z2, Z3, ...
     *
     * @param string $statusKepemilikan 'Colocation' atau 'Kominfo'
     * @param string|null $createdAt Tanggal pembuatan (format: Y-m-d), null untuk hari ini
     * @return string Kode perangkat yang di-generate
     */
    public static function generateKodePerangkat(string $statusKepemilikan, ?string $createdAt = null): string
    {
        // Tentukan prefix berdasarkan status kepemilikan
        // KO untuk Kominfo, CO untuk Colocation (OPD)
        $prefix = $statusKepemilikan === 'Kominfo' ? 'KO' : 'CO';

        // Tentukan tanggal
        if ($createdAt === null) {
            $date = \Illuminate\Support\Carbon::now();
        } else {
            $date = \Illuminate\Support\Carbon::parse($createdAt);
        }
        $datePart = $date->format('ymd'); // YYMMDD format

        // Hitung urutan harian berdasarkan kode yang sudah ada untuk hari ini dan prefix yang sama
        $todayStart = $date->copy()->startOfDay();
        $todayEnd = $date->copy()->endOfDay();

        $existingCodesToday = self::whereBetween('created_at', [$todayStart, $todayEnd])
            ->whereNotNull('kode_perangkat')
            ->get()
            ->pluck('kode_perangkat')
            ->toArray();

        // Ekstrak bagian urutan dari kode yang sudah ada dengan prefix yang sesuai
        $usedSequences = [];
        foreach ($existingCodesToday as $code) {
            // Format: [KO|CO][YYMMDD][sequence]
            // Ambil bagian setelah prefix (2 karakter) dan tanggal (6 karakter)
            if (strlen($code) >= 9 && substr($code, 0, 2) === $prefix) { // minimal KO/CO(2) + YYMMDD(6) = 8, plus at least 1 sequence char
                $sequencePart = substr($code, 8); // setelah 8 karakter pertama (prefix+date)
                $usedSequences[] = $sequencePart;
            }
        }

        // Generate urutan berikutnya
        $sequence = self::getNextSequence($usedSequences);

        return $prefix . $datePart . $sequence;
    }

    /**
     * Dapatkan urutan berikutnya yang belum digunakan
     * Urutan: A, B, C, ..., Z, Z1, Z2, Z3, ...
     *
     * @param array $usedSequences Array of sequences yang sudah digunakan hari ini
     * @return string Urutan berikutnya
     */
    protected static function getNextSequence(array $usedSequences): string
    {
        // Urutan standar: A-Z
        $standardChars = range('A', 'Z');

        // Cek urutan standar pertama yang belum digunakan
        foreach ($standardChars as $char) {
            if (!in_array($char, $usedSequences, true)) {
                return $char;
            }
        }

        // Jika semua A-Z sudah digunakan, gunakan format Z1, Z2, Z3, ...
        $i = 1;
        while (true) {
            $sequence = 'Z' . $i;
            if (!in_array($sequence, $usedSequences, true)) {
                return $sequence;
            }
            $i++;
        }
    }
}
