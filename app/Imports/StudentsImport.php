<?php
// app/Imports/StudentsImport.php

namespace App\Imports;

use App\Models\User;
use App\Models\Classes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToCollection
{
    protected $classId;
    protected $importedCount = 0;
    protected $skippedCount = 0;

    public function __construct($classId = null)
    {
        $this->classId = $classId;
    }

    public function getImportedCount()
    {
        return $this->importedCount;
    }

    public function getSkippedCount()
    {
        return $this->skippedCount;
    }

    protected function getMatchingClass($kelasName)
    {
        if (empty($kelasName)) {
            return null;
        }
        
        $className = trim($kelasName);
        
        // 1. Try exact match first (prioritize active class)
        $class = Classes::where('name', $className)->where('is_active', true)->first();
        if ($class) {
            return $class;
        }
        
        $class = Classes::where('name', $className)->first();
        if ($class) {
            return $class;
        }
        
        // 2. Try Roman/Number normalization match
        // Map of numeric-to-roman conversions
        $romanMap = [
            '10' => 'X',
            '11' => 'XI',
            '12' => 'XII',
        ];
        
        // Try to replace starting digits (e.g. "11 RPL 1" -> "XI RPL 1")
        foreach ($romanMap as $num => $roman) {
            if (strpos($className, $num . ' ') === 0) {
                $convertedName = preg_replace('/^' . $num . '\s+/', $roman . ' ', $className);
                $class = Classes::where('name', $convertedName)->where('is_active', true)->first() 
                      ?? Classes::where('name', $convertedName)->first();
                if ($class) {
                    return $class;
                }
            }
            
            // Try replacing without spaces
            if (strpos($className, $num) === 0) {
                $convertedName = preg_replace('/^' . $num . '/', $roman, $className);
                $class = Classes::where('name', $convertedName)->where('is_active', true)->first() 
                      ?? Classes::where('name', $convertedName)->first();
                if ($class) {
                    return $class;
                }
            }
        }
        
        // Try the reverse (e.g. "XI RPL 1" -> "11 RPL 1")
        foreach ($romanMap as $num => $roman) {
            if (strpos($className, $roman . ' ') === 0) {
                $convertedName = preg_replace('/^' . $roman . '\s+/', $num . ' ', $className);
                $class = Classes::where('name', $convertedName)->where('is_active', true)->first() 
                      ?? Classes::where('name', $convertedName)->first();
                if ($class) {
                    return $class;
                }
            }
            
            if (strpos($className, $roman) === 0) {
                $convertedName = preg_replace('/^' . $roman . '/', $num, $className);
                $class = Classes::where('name', $convertedName)->where('is_active', true)->first() 
                      ?? Classes::where('name', $convertedName)->first();
                if ($class) {
                    return $class;
                }
            }
        }
        
        return null;
    }

    public function collection(Collection $rows)
    {
        if ($rows->isEmpty()) {
            throw new \Exception("File Excel tidak memiliki data untuk diimpor.");
        }

        // Optimasi: Hash password default cukup sekali saja di luar perulangan (mempercepat proses import hingga 100x)
        $defaultPasswordHash = Hash::make('password');

        $firstRow = $rows->first()->toArray();
        $isStandardTemplate = false;
        
        $namaColIndex = null;
        $nisColIndex = null;
        $genderColIndex = null;
        $nisnColIndex = null;
        $tempatLahirColIndex = null;
        $tanggalLahirColIndex = null;
        $alamatColIndex = null;
        $rtColIndex = null;
        $rwColIndex = null;
        $kelurahanColIndex = null;
        $kecamatanColIndex = null;
        $phoneColIndex = null;
        $emailColIndex = null;
        $kelasColIndex = null;
        
        // Cek baris pertama untuk mencocokkan header kolom
        foreach ($firstRow as $index => $value) {
            if ($value === null) continue;
            
            $val = strtolower(trim($value));
            // Hapus karakter non-printable, BOM UTF-8, dsb.
            $val = preg_replace('/[\x00-\x1F\x7F-\x9F\xEF\xBB\xBF]/', '', $val);
            
            if ($val === 'nama' || $val === 'nama siswa' || $val === '1') {
                $namaColIndex = $index;
            } elseif ($val === 'nis' || $val === '2') {
                $nisColIndex = $index;
            } elseif ($val === 'nisn' || $val === '4') {
                $nisnColIndex = $index;
            } elseif ($val === 'kelas' || $val === '42') {
                $kelasColIndex = $index;
            } elseif ($val === 'gender' || $val === 'jenis kelamin' || $val === 'l/p' || $val === 'jenis_kelamin' || $val === '3') {
                $genderColIndex = $index;
            } elseif ($val === 'alamat' || $val === '9') {
                $alamatColIndex = $index;
            } elseif ($val === 'tempat lahir' || $val === 'tempat_lahir' || $val === '5') {
                $tempatLahirColIndex = $index;
            } elseif ($val === 'tanggal lahir' || $val === 'tanggal_lahir' || $val === '6') {
                $tanggalLahirColIndex = $index;
            } elseif ($val === 'rt' || $val === '10') {
                $rtColIndex = $index;
            } elseif ($val === 'rw' || $val === '11') {
                $rwColIndex = $index;
            } elseif ($val === 'kelurahan' || $val === '13') {
                $kelurahanColIndex = $index;
            } elseif ($val === 'kecamatan' || $val === '14') {
                $kecamatanColIndex = $index;
            } elseif (in_array($val, ['no telepon', 'no_telepon', 'telepon', 'phone', 'no telp', 'no. telp', '19'])) {
                $phoneColIndex = $index;
            } elseif ($val === 'email' || $val === '20') {
                $emailColIndex = $index;
            }
        }
        
        // Dianggap standard template jika minimal memiliki kolom NAMA dan NIS
        if ($namaColIndex !== null && $nisColIndex !== null) {
            $isStandardTemplate = true;
        }

        // Jika bukan standard template (tanpa header seperti file user), wajib memilih kelas tujuan di modal
        if (!$isStandardTemplate && !$this->classId) {
            throw new \Exception("Format file Excel Anda tidak memiliki judul kolom standar (NAMA, NIS, KELAS). Silakan pilih 'Kelas Tujuan' pada form import terlebih dahulu.");
        }

        foreach ($rows as $rowIndex => $row) {
            $rowArray = $row->toArray();
            
            // Skip baris pertama (jika terdeteksi sebagai header)
            if ($rowIndex === 0) {
                // Di template kustom atau template standar, kita lewati baris ke-1 jika memiliki header
                if ($isStandardTemplate || (isset($rowArray[0]) && strtolower(trim($rowArray[0])) === 'no')) {
                    continue;
                }
            }

            if ($isStandardTemplate) {
                $nama = $rowArray[$namaColIndex] ?? null;
                $nis = $rowArray[$nisColIndex] ?? null;
                $gender = $rowArray[$genderColIndex] ?? null;
                $kelasName = $kelasColIndex !== null ? ($rowArray[$kelasColIndex] ?? null) : null;
                $alamat = $alamatColIndex !== null ? ($rowArray[$alamatColIndex] ?? null) : null;
                
                $nisn = $nisnColIndex !== null ? ($rowArray[$nisnColIndex] ?? null) : null;
                $tempat_lahir = $tempatLahirColIndex !== null ? ($rowArray[$tempatLahirColIndex] ?? null) : null;
                $tanggal_lahir_raw = $tanggalLahirColIndex !== null ? ($rowArray[$tanggalLahirColIndex] ?? null) : null;
                $rt = $rtColIndex !== null ? ($rowArray[$rtColIndex] ?? null) : null;
                $rw = $rwColIndex !== null ? ($rowArray[$rwColIndex] ?? null) : null;
                $kelurahan = $kelurahanColIndex !== null ? ($rowArray[$kelurahanColIndex] ?? null) : null;
                $kecamatan = $kecamatanColIndex !== null ? ($rowArray[$kecamatanColIndex] ?? null) : null;
                $phone = $phoneColIndex !== null ? ($rowArray[$phoneColIndex] ?? null) : null;
                $email = $emailColIndex !== null ? ($rowArray[$emailColIndex] ?? null) : null;
            } else {
                // Menggunakan indeks posisi (Excel Kustom User)
                // Kolom B (index 1) = NAMA
                // Kolom C (index 2) = NIS
                // Kolom D (index 3) = GENDER
                $nama = $rowArray[1] ?? null;
                $nis = $rowArray[2] ?? null;
                $gender = $rowArray[3] ?? null;
                
                // Cek jika jumlah kolom besar, kemungkinan ini format Dapodik tetapi tanpa baris header yang dikenali
                if (count($rowArray) >= 15) {
                    $nisn = $rowArray[4] ?? null;
                    $tempat_lahir = $rowArray[5] ?? null;
                    $tanggal_lahir_raw = $rowArray[6] ?? null;
                    $alamat = $rowArray[7] ?? null;
                    $rt = $rowArray[8] ?? null;
                    $rw = $rowArray[9] ?? null;
                    $kelurahan = $rowArray[10] ?? null;
                    $kecamatan = $rowArray[11] ?? null;
                    $phone = $rowArray[12] ?? null;
                    $email = $rowArray[13] ?? null;
                    $kelasName = $rowArray[14] ?? null;
                } else {
                    $kelasName = null;
                    $alamat = $rowArray[7] ?? null;
                    
                    $nisn = null;
                    $tempat_lahir = null;
                    $tanggal_lahir_raw = null;
                    $rt = null;
                    $rw = null;
                    $kelurahan = null;
                    $kecamatan = null;
                    $phone = null;
                    $email = null;
                }
            }

            // Bersihkan data string
            $nama = $nama !== null ? trim($nama) : null;
            
            if ($nis !== null) {
                $nis = trim($nis);
                if (strpos($nis, '.') !== false && substr($nis, -2) === '.0') {
                    $nis = substr($nis, 0, -2);
                }
            }
            
            if ($nisn !== null) {
                $nisn = trim($nisn);
                if (strpos($nisn, '.') !== false && substr($nisn, -2) === '.0') {
                    $nisn = substr($nisn, 0, -2);
                }
            }
            if ($nisn === '-' || empty($nisn)) {
                $nisn = null;
            }

            $tempat_lahir = $tempat_lahir !== null ? trim($tempat_lahir) : null;
            if ($tempat_lahir === '-' || empty($tempat_lahir)) {
                $tempat_lahir = null;
            }
            
            if ($rt !== null) {
                $rt = trim($rt);
                if (strpos($rt, '.') !== false && substr($rt, -2) === '.0') {
                    $rt = substr($rt, 0, -2);
                }
                if ($rt !== '-' && $rt !== '0' && !empty($rt)) {
                    $rt = str_pad($rt, 2, '0', STR_PAD_LEFT);
                } else {
                    $rt = null;
                }
            }
            
            if ($rw !== null) {
                $rw = trim($rw);
                if (strpos($rw, '.') !== false && substr($rw, -2) === '.0') {
                    $rw = substr($rw, 0, -2);
                }
                if ($rw !== '-' && $rw !== '0' && !empty($rw)) {
                    $rw = str_pad($rw, 2, '0', STR_PAD_LEFT);
                } else {
                    $rw = null;
                }
            }
            
            $kelurahan = $kelurahan !== null ? trim($kelurahan) : null;
            if ($kelurahan === '-' || empty($kelurahan)) {
                $kelurahan = null;
            }
            
            $kecamatan = $kecamatan !== null ? trim($kecamatan) : null;
            if ($kecamatan === '-' || empty($kecamatan)) {
                $kecamatan = null;
            }
            
            if ($phone !== null) {
                $phone = trim($phone);
                if (strpos($phone, '.') !== false && substr($phone, -2) === '.0') {
                    $phone = substr($phone, 0, -2);
                }
            }
            if ($phone === '-' || empty($phone)) {
                $phone = null;
            }

            $emailVal = !empty($email) ? trim($email) : ($nis . '@agenda.local');

            // Parse tanggal lahir secara fleksibel
            $tanggal_lahir = null;
            if (!empty($tanggal_lahir_raw)) {
                $tanggal_lahir_raw = trim($tanggal_lahir_raw);
                if (is_numeric($tanggal_lahir_raw)) {
                    try {
                        $tanggal_lahir = date('Y-m-d', ($tanggal_lahir_raw - 25569) * 86400);
                    } catch (\Exception $e) {
                        $tanggal_lahir = null;
                    }
                } else {
                    $timestamp = strtotime(str_replace('/', '-', $tanggal_lahir_raw));
                    if ($timestamp !== false) {
                        $tanggal_lahir = date('Y-m-d', $timestamp);
                    }
                }
            }

            // Skip baris kosong (ghost rows dari Excel)
            if (empty($nama) && empty($nis)) {
                continue;
            }

            // Jika salah satu dari Nama atau NIS kosong, skip baris ini
            if (empty($nama) || empty($nis)) {
                $this->skippedCount++;
                continue;
            }

            // Skip jika NIS sudah terdaftar
            if (User::where('nis', $nis)->exists()) {
                $this->skippedCount++;
                continue;
            }

            // Tentukan ID kelas (Memprioritaskan Kelas Tujuan dari dropdown jika dipilih)
            $targetClassId = null;
            if (!empty($this->classId)) {
                $targetClassId = $this->classId;
            } elseif (!empty($kelasName)) {
                $className = trim($kelasName);
                
                // Cari kelas berdasarkan nama dengan pencocokan cerdas (Romawi & Angka)
                $class = $this->getMatchingClass($className);
                if ($class) {
                    $targetClassId = $class->id;
                } else {
                    // Kelas terisi di Excel tetapi TIDAK ditemukan di database: skip siswa ini agar tidak salah masuk kelas
                    $this->skippedCount++;
                    continue;
                }
            } else {
                // Jika tidak ada kelas di excel dan tidak ada di dropdown, skip
                $this->skippedCount++;
                continue;
            }

            // Bersihkan data gender
            $genderStr = strtoupper(trim($gender));
            $genderVal = 'L'; // Default
            if ($genderStr === 'P' || $genderStr === 'PEREMPUAN' || $genderStr === 'L/P') {
                $genderVal = 'P';
            }

            $student = User::create([
                'name' => $nama,
                'email' => $emailVal,
                'nis' => $nis,
                'gender' => $genderVal,
                'class_id' => $targetClassId,
                'phone' => $phone,
                'address' => $alamat !== null ? trim($alamat) : null,
                'password' => $defaultPasswordHash,
                'email_verified_at' => now(),
                'nisn' => $nisn,
                'tempat_lahir' => $tempat_lahir,
                'tanggal_lahir' => $tanggal_lahir,
                'rt' => $rt,
                'rw' => $rw,
                'kelurahan' => $kelurahan,
                'kecamatan' => $kecamatan,
            ]);
            
            $student->assignRole('siswa');
            $this->importedCount++;
        }
    }
}