<?php

use App\Models\Criteria;
use App\Models\Evaluation;
use App\Models\FavoritModel;
use App\Models\KategoriModel;
use App\Models\LogAktivitasModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use phpDocumentor\Reflection\Types\Null_;
use PhpParser\Node\Expr\FuncCall;

use function PHPUnit\Framework\isNull;


function calculateImt($tinggi, $berat)
{
    return round($berat / (($tinggi / 100) * ($tinggi / 100)), 2);
}

function getImtColor($kategori)
{
    if ($kategori == 'sangatkurus') {
        return 'bg-gradient-warning text-white';
    }
    if ($kategori == 'kurus') {
        return 'bg-gradient-info text-white';
    }

    if ($kategori == 'normal') {
        return 'bg-gradient-success text-white';
    }

    if ($kategori == 'gemuk') {
        return 'bg-gradient-danger text-white';
    }

    if ($kategori == 'sangatgemuk') {
        return 'bg-gradient-warning text-white';
    }
}

function getKategoriImt($imt)
{
    if ($imt < 17.0) {
        return 'sangatkurus';
    }
    if ($imt >= 17.0 && $imt <= 18.4) {
        return 'kurus';
    }
    if ($imt >= 18.5 && $imt <= 25.0) {
        return 'normal';
    }
    if ($imt >= 25.1 && $imt <= 27.0) {
        return 'gemuk';
    }
    if ($imt > 27) {
        return 'sangatgemuk';
    }
}

function getKeteranganImt($imt)
{
    if ($imt == 'sangatkurus') {
        return 'Anda sangat kurus. Ini bisa menjadi tanda bahwa tubuh Anda tidak mendapatkan nutrisi yang cukup. Sangat penting untuk mengonsumsi makanan yang kaya kalori dan nutrisi seperti kacang-kacangan, alpukat, dan makanan tinggi protein untuk meningkatkan berat badan Anda secara sehat. Konsultasikan dengan ahli gizi untuk mendapatkan panduan yang lebih spesifik.';
    }
    if ($imt == 'kurus') {
        return 'Anda berada dalam kategori kurus. Untuk mencapai berat badan yang sehat, Anda mungkin perlu menambahkan lebih banyak kalori ke dalam diet Anda. Fokuslah pada makanan yang kaya nutrisi seperti daging tanpa lemak, biji-bijian, dan produk susu tinggi lemak. Pertimbangkan untuk makan lebih sering dan dalam porsi yang lebih besar.';
    }

    if ($imt == 'normal') {
        return 'Berat badan Anda berada dalam kisaran normal. Ini berarti bahwa Anda mungkin memiliki keseimbangan yang baik antara asupan kalori dan aktivitas fisik. Untuk menjaga berat badan yang sehat, teruskan pola makan yang seimbang dan tetap aktif secara fisik. Pastikan untuk tetap memperhatikan asupan nutrisi agar tubuh tetap mendapatkan semua vitamin dan mineral yang dibutuhkan.';
    }

    if ($imt == 'gemuk') {
        return 'Anda berada dalam kategori gemuk. Ini bisa menjadi indikator bahwa tubuh Anda menyimpan lebih banyak lemak daripada yang diperlukan untuk kesehatan yang optimal. Mengurangi asupan kalori dari makanan tinggi lemak dan gula serta meningkatkan aktivitas fisik harian dapat membantu menurunkan berat badan. Konsultasikan dengan profesional kesehatan untuk rencana penurunan berat badan yang aman dan efektif.';
    }

    if ($imt == 'sangatgemuk') {
        return 'Anda sangat gemuk, yang dapat meningkatkan risiko berbagai masalah kesehatan serius seperti penyakit jantung, diabetes, dan tekanan darah tinggi. Sangat penting untuk mulai mengadopsi pola makan yang lebih sehat, mengurangi makanan tinggi kalori, dan meningkatkan aktivitas fisik. Dapatkan dukungan dari ahli gizi atau profesional kesehatan untuk membantu Anda dalam perjalanan menurunkan berat badan dan meningkatkan kesehatan keseluruhan.';
    }
}


function getImtDatas()
{
    return [
        'Sangat Kurus' => '<17.0',
        'Kurus' => '<17.0 - 18.4',
        'Normal' => '18.5 - 25.0',
        'Gemuk' => '25.1 - 27.0',
        'Sangat Gemuk' => '>27',
    ];
}

function getEvaluation($criteriaId, $alternativeId)
{
    return Evaluation::where('criteria_id', $criteriaId)->where('alternative_id', $alternativeId)->first()->value ?? 0;
}
function getEvaluationMax($criteriaId)
{
    return Evaluation::where('criteria_id', $criteriaId)->max('value');
}

function getEvaluationMin($criteriaId)
{
    return Evaluation::where('criteria_id', $criteriaId)->min('value');
}

function getWeight($criteriaId)
{
    return Criteria::where('id', $criteriaId)->first()->weight ?? 0;
}
function removeSpace($string)
{
    return str_replace(" ", "", $string);
}

function getUserRoleName($userRoleId)
{
    return DB::table('users')
        ->Join('role', 'role.id_role', '=', 'users.id_role')
        ->where('users.id_role', $userRoleId)
        ->select('nama_role')
        ->first()->nama_role;
}


function sweetAlert($pesan, $tipe)
{
    echo '<script>document.addEventListener("DOMContentLoaded", function() {
        Swal.fire(
            "' . $pesan . '",
            "proses berhasil di lakukan",
            "' . $tipe . '",
        );
    })</script>';
}
