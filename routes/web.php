<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserUjianController;
use App\Http\Controllers\UserUjianNewController;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\SoalController;
use App\Http\Controllers\SoalGambarController;
use App\Http\Controllers\UjianController;
use App\Http\Controllers\UjianUserController;
use App\Http\Controllers\TypeaheadController;
use App\Http\Controllers\HasilUjianController;

use App\Http\Controllers\JsonController;
use App\Http\Controllers\TokenController;

use App\Http\Controllers\ExportImportController;
use App\Http\Controllers\ExportImportSoalController;
use App\Http\Controllers\GrafikNilaiUjianController;
use App\Http\Controllers\JenisSoalController;
use App\Http\Controllers\TextInfolController;
use App\Http\Controllers\PelatihanController;
use App\Http\Controllers\PelatihanUserController;
use App\Http\Controllers\ProsesPelatihanController;
use App\Http\Controllers\DetailPelatihanController;

use App\Http\Controllers\ProsesPelatihanUjianController;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('auth.login');
});

Route::post('token/process', [TokenController::class, 'process'])->name('tokenProcess');

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index']);

Auth::routes();

Route::middleware(['auth'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');


    Route::middleware(['admin'])->group(function () {
        Route::get('admin', [AdminController::class, 'index']);

        Route::get('/user-admin', [AdminUserController::class, 'index']);
        Route::get('/user-pengajar', [AdminUserController::class, 'indexPengajar']);
        Route::get('/user-siswa', [AdminUserController::class, 'indexSiswa']);
        Route::get('/user-admin/create', [AdminUserController::class, 'create']);
        Route::post('/user-admin/store', [AdminUserController::class, 'store']);
        Route::get('/user-admin/edit/{id}', [AdminUserController::class, 'edit']);
        Route::post('/user-admin/update/{id}', [AdminUserController::class, 'update']);
        Route::delete('/user-admin/delete/{id}', [AdminUserController::class, 'destroy']);

        Route::get('/jenis-soal', [JenisSoalController::class, 'index']);
        Route::get('/jenis-soal/create', [JenisSoalController::class, 'create']);
        Route::post('/jenis-soal/store', [JenisSoalController::class, 'store']);
        Route::get('/jenis-soal/edit/{id}', [JenisSoalController::class, 'edit']);
        Route::post('/jenis-soal/update/{id}', [JenisSoalController::class, 'update']);
        Route::delete('/jenis-soal/delete/{id}', [JenisSoalController::class, 'destroy']);

        Route::get('/pelatihan', [PelatihanController::class, 'index'])->name('pelatihanView');
        Route::get('/pelatihan/create', [PelatihanController::class, 'create']);
        Route::post('/pelatihan/store', [PelatihanController::class, 'store']);
        Route::get('/pelatihan/edit/{id}', [PelatihanController::class, 'edit']);
        Route::post('/pelatihan/update/{id}', [PelatihanController::class, 'update']);
        Route::delete('/pelatihan/delete/{id}', [PelatihanController::class, 'destroy']);

        Route::get('/pelatihan/peserta/{id}', [PelatihanUserController::class, 'show']);
        Route::post('/pelatihan/peserta_create/{id}', [PelatihanUserController::class, 'store']);
        Route::delete('/pelatihan/peserta_delete/{id}/{jenis}', [PelatihanUserController::class, 'destroy']);
        Route::get('/pelatihan/pengajar/{id}', [PelatihanUserController::class, 'showPengajar']);


        Route::get('/proses-pelatihan', [ProsesPelatihanController::class, 'index'])->name('prosesPelatihanView');
        Route::get('/proses-pelatihan/presensi/{id}', [ProsesPelatihanController::class, 'presensi']
        )->name('pelatihan.presensi');
        Route::get('/proses-pelatihan/hasil-presensi/{id}', [ProsesPelatihanController::class, 'hasilPresensi']
        )->name('pelatihan.hasil_presensi');
        Route::post(
            '/pelatihan/{pelatihanId}/presensi-proses',
            [ProsesPelatihanController::class, 'presensi_proses']
        )->name('pelatihan.presensi.proses');


        Route::get('/proses-pelatihan/ujian/{id}', [ProsesPelatihanUjianController::class, 'index']
        )->name('pelatihan.ujian_view');
        Route::get('/proses-pelatihan/ujian-create/{id}', [ProsesPelatihanUjianController::class, 'create']
        )->name('pelatihan.ujian_create');
        Route::post('/proses-pelatihan/ujian-save/{id}', [ProsesPelatihanUjianController::class, 'save']
        )->name('pelatihan.ujian_save');
        Route::get('/proses-pelatihan/ujian-edit/{id}', [ProsesPelatihanUjianController::class, 'edit']
        )->name('pelatihan.ujian_edit');
        Route::post('/proses-pelatihan/ujian-update/{id}', [ProsesPelatihanUjianController::class, 'update']
        )->name('pelatihan.ujian_update');


        Route::get('/detail-pelatihan/{id}', [DetailPelatihanController::class, 'index']
        )->name('pelatihan.detail');



        Route::get('/soal', [SoalController::class, 'index']);
        Route::get('/soal/nogambar', [SoalController::class, 'nogambar']);
        Route::get('/soal/create', [SoalController::class, 'create']);
        Route::post('/soal/store', [SoalController::class, 'store']);
        Route::get('/soal/edit/{id}', [SoalController::class, 'edit']);
        Route::post('/soal/update/{id}', [SoalController::class, 'update']);
        Route::get('/soal/delete/{id}', [SoalController::class, 'destroy']);
        Route::get('/soal/export-to-json/{id}', [SoalController::class, 'jsonFileDownload']);
        Route::get('/soal/import/{id}', [SoalController::class, 'importView']);
        Route::post('/soal/import-process/{id}', [SoalController::class, 'importProcess']);

        Route::get('/text-info', [TextInfolController::class, 'index']);
        Route::post('/text-info/update', [TextInfolController::class, 'update']);

        Route::get('/soal/kecermatan', [SoalGambarController::class, 'index']);
        Route::get('/soal/create-kecermatan', [SoalGambarController::class, 'create']);
        Route::post('/soal/store-kecermatan', [SoalGambarController::class, 'store']);
        Route::get('/soal/edit-kecermatan/{id}', [SoalGambarController::class, 'edit']);
        Route::post('/soal/update-kecermatan/{id}', [SoalGambarController::class, 'update']);
        Route::delete('/soal/delete-kecermatan/{id}', [SoalGambarController::class, 'destroy']);
        Route::get('/soal/json-file-download', [SoalGambarController::class, 'jsonFileDownload']);

        Route::get('/soal/import-kecermatan', [SoalGambarController::class, 'importView']);
        Route::post('import-soal-kecermatan', [SoalGambarController::class, 'jsonFileUpload'])->name('import-soal-kecermatan');

        Route::get('import-soal-view', [ExportImportSoalController::class, 'importExportView']);
        Route::get('export-soal', [ExportImportSoalController::class, 'export'])->name('export-soal');
        Route::post('import-soal', [ExportImportSoalController::class, 'import'])->name('import-soal');

        Route::get('/ujian', [UjianController::class, 'index']);
        Route::get('/ujian/create', [UjianController::class, 'create']);
        Route::post('/ujian/store', [UjianController::class, 'store']);
        Route::get('/ujian/edit/{id}', [UjianController::class, 'edit']);
        Route::post('/ujian/update/{id}', [UjianController::class, 'update']);
        Route::delete('/ujian/delete/{id}', [UjianController::class, 'destroy']);

        Route::get('/hasil-ujian', [HasilUjianController::class, 'index']);
        Route::get('/hasil-ujian/peserta/{id}', [HasilUjianController::class, 'show']);

        Route::get('/ujian-user/show/{id}', [UjianUserController::class, 'show']);
        Route::get('/ujian-user/create/{id}', [UjianUserController::class, 'create']);
        Route::post('/ujian-user/store/{id}', [UjianUserController::class, 'store']);
        Route::delete('/ujian-user/delete/{id}', [UjianUserController::class, 'destroy']);

        Route::get('/ujian-soal/show/{id}', [UjianUserController::class, 'ujianSoalShow']);
        Route::get('/ujian-soal/create/{id}', [UjianUserController::class, 'create']);
        Route::post('/ujian-soal/store/{id}', [UjianUserController::class, 'ujianSoalStore']);
        Route::delete('/ujian-soal/delete/{id}', [UjianUserController::class, 'ujianSoalDestroy']);

        Route::get('export-token/{id}', [UjianUserController::class, 'exportToken'])->name('export-token-user');
        Route::get('export-hasil-ujian/{id}', [UjianUserController::class, 'exportHasilUjian'])->name('exportHasilUjian');

        Route::get('/autocomplete-search', [TypeaheadController::class, 'autocompleteSearch']);

        Route::get('import-user-view', [ExportImportController::class, 'importExportView']);
        Route::get('export-user', [ExportImportController::class, 'export'])->name('export');
        Route::post('import-user', [ExportImportController::class, 'import'])->name('import-user');

        Route::get('/grafik-nilai-ujian', [GrafikNilaiUjianController::class, 'index']);

    });

    Route::middleware(['user'])->group(function () {
        Route::get('user', [UserController::class, 'index']);
        Route::get('riwayat-ujian', [UserController::class, 'riwayat']);
        Route::get('riwayat-pelatihan', [UserController::class, 'riwayatPelatihan'])->name('riwayatPelatihan');
        Route::get('riwayat-pelatihan/detail/{id}', [UserController::class, 'riwayatPelatihanDetail']);

        Route::get('/ujian/info/{id}', [UserUjianController::class, 'info']);
        Route::get('/ujian/token/{id}', [UserUjianController::class, 'token']);
        Route::post('/ujian/token_check/{id}', [UserUjianController::class, 'token_check']);

        Route::get('/ujian/simulasi/{id}', [UserUjianController::class, 'simulasi']);
        Route::get('/ujian/mulai/{id}', [UserUjianController::class, 'mulai']);
        Route::post('/ujian/submit/{id}', [UserUjianController::class, 'submit']);

        Route::get('/ujian/selesai/{id}', [UserUjianController::class, 'selesai']);

        Route::get('/ujian/mulai-gambar/{id}', [UserUjianController::class, 'mulaiGambar']);
        Route::post('/ujian/submit-gambar/{id}', [UserUjianController::class, 'submitGambar']);

        Route::get('/ujian-info/{idUjianDetails}/{idUjianUser}', [UserUjianNewController::class, 'info']);
        Route::get('/ujian-mulai/{idUjianDetails}/{idUjianUser}', [UserUjianNewController::class, 'mulai']);
        Route::get('/ujian-mulai-kecermatan/{idUjianDetails}/{idUjianUser}', [UserUjianNewController::class, 'mulaiKecermatan']);
        Route::get('/ujian-simulasi/{idUjianDetails}/{idUjianUser}', [UserUjianNewController::class, 'simulasi']);
        Route::get('/ujian-simulasi-kecermatan/{idUjianDetails}/{idUjianUser}', [UserUjianNewController::class, 'simulasiKecermatan']);
        Route::post('/submit-jawaban', [UserUjianNewController::class, 'submitJawaban']);
        Route::post('/submit-ujian/{idUjianDetails}/{idUjianUser}', [UserUjianNewController::class, 'submitUjian']);
        Route::post('/submit-ujian-kecermatan/{idUjianDetails}/{idUjianUser}', [UserUjianNewController::class, 'submitUjianKecermatan']);
        Route::get('/ujian-selesai/{idUjianDetails}/{idUjianUser}/{fromApp}', [UserUjianNewController::class, 'selesai']);
        Route::get('/ujian-selesai-kecermatan/{idUjianDetails}/{idUjianUser}/{fromApp}', [UserUjianNewController::class, 'selesaiKecermatan']);
        Route::get('/sertifikat/{id}', [UserUjianNewController::class, 'sertifikat']);
        Route::get('/show-sertifikat/{id}', [UserUjianNewController::class, 'showSertifikat']);
    });

    Route::get('logout', function() {
        Auth::logout();
        redirect('/');
    });

});

