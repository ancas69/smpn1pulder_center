<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\WaliMurid;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function halaman_home(){
        return view('admin.home');
    }

    public function halaman_pengguna(){
        return view('admin.pengguna');
    }

    public function tampilkan_semua_pengguna()
    {
        // Eager Loading
        $users = User::with(['student', 'guru', 'wali'])
                    ->orderBy('id', 'desc')
                    ->get();
                    
        return response()->json($users);
    }

    public function tampilkan_murid(Request $form)
    {
        // Ambil murid yang BelUMAH punya wali (belum di-assign)
        $murid = Student::whereNull('wali_id')
                        ->join('users', 'students.user_id', '=', 'users.id')
                        ->select('students.nisn', 'users.name')
                        ->get();
        return response()->json($murid);
    }

    public function tambah_satu_pengguna(Request $form)
    {
        // 1. Validasi input
        $validated = $form->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'required|in:admin,guru,murid,wali',
            'nisn' => 'nullable|string|max:20|unique:students',
            'wa' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8',
            'anak' => 'nullable',
        ]);

        // 2. Buat user utama
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'] ? bcrypt($validated['password']) : bcrypt(Str::random(16)),
            'role' => $validated['role'],
            'blokir' => false
        ]);

        // 3. Buat data terkait berdasarkan role
        switch ($validated['role']) {
            case 'guru':
                Teacher::create([
                    'user_id' => $user->id,
                    'nip' => $validated['nisn'] ?? null,
                    'no_whatsapp' => $validated['wa'] ?? null,
                ]);
                
                break;

            case 'murid':
                Student::create([
                    'user_id' => $user->id,
                    'nisn' => $validated['nisn'] ?? null,
                    'kelas' => 'default',
                    'qr_code_token' => 'default'.$user->id,
                ]);
                break;

            case 'wali':
                $waliMurid = WaliMurid::create([
                    'user_id' => $user->id,
                    'nama_wali' => $validated['name'],
                    'no_whatsapp' => $validated['wa'] ?? null,
                    'hubungan' => 'Orang Tua',
                ]);

                $nisn = $validated['anak'];
                if($nisn) {
                    $student = Student::where('nisn', $nisn)
                        ->whereNull('wali_id')
                        ->first();
                    if ($student) {
                        $student->update(['wali_id' => $waliMurid->id]);
                    }
                }
                break;
        }

        // 4. Kembalikan respons JSON
        return response()->json([
            'success' => true,
            'message' => 'Pengguna berhasil ditambahkan!',
            'data' => $user,
        ]);
    }

    public function hapus_satu_user(Request $form)
    {
       
        // 1. Dapatkan ID user dari input atau route parameter
        $userId = $form->input('id') ?? $form->input('user_id') ?? $form->route('id');

        if (!$userId) { // jika id kosong
            return response()->json([
                'success' => false,
                'message' => 'ID pengguna tidak lengkap.',
            ], 400);
        }

        // 2. Cari user
        $user = User::find($userId);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Pengguna tidak ditemukan.',
            ], 404);
        }

        // cek turunan data
        $data_teacher = Teacher::find($userId);
        $data_murid = Student::find($userId);
        $data_wali = WaliMurid::find($userId);
        
        // jika data user ada di salah satu dari 3 tabel ini, hapus, jika tidak lewatkan
        if($data_teacher) Teacher::where('user_id', $userId)->delete();
        if($data_murid) Student::where('user_id', $userId)->delete();
        if($data_wali) WaliMurid::where('user_id', $userId)->delete();

        // 4. Hapus user utama
        $user->delete();

        // 5. Kembalikan respons
        return response()->json([
            'success' => true,
            'message' => 'Pengguna berhasil dihapus.',
        ]);
    }

    public function ubah_satu_data_pengguna(Request $request)
    {
        $userId = $request->route('id') ?? $request->input('id');

        if (!$userId) {
            return response()->json([
                'success' => false,
                'message' => 'ID pengguna tidak ditemukan.',
            ], 400);
        }

        $targetUser = User::find($userId);

        if (!$targetUser) {
            return response()->json([
                'success' => false,
                'message' => 'Pengguna tidak ditemukan.',
            ], 404);
        }

        $student = $targetUser->student;

        // 1. Validasi input
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $targetUser->id,
            'role' => 'required|in:admin,guru,murid,wali',
            'nisn' => 'nullable|string|max:20' . ($student ? '|unique:students,nisn,' . $student->id : ''),
            'wa' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:8',
            'anak' => 'nullable',
            'children' => 'nullable|array',
        ]);

        $nisn = ($validated['nisn'] ?? null) === '-' ? null : ($validated['nisn'] ?? null);
        $wa = ($validated['wa'] ?? null) === '-' ? null : ($validated['wa'] ?? null);

        // 2. Update user utama
        $targetUser->name = $validated['name'];
        $targetUser->email = $validated['email'];
        $targetUser->role = $validated['role'];

        if (!empty($validated['password'])) {
            $targetUser->password = bcrypt($validated['password']);
        }

        $targetUser->save();

        // 3. Update data terkait berdasarkan role
        switch ($validated['role']) {
            case 'guru':
                if ($targetUser->student) $targetUser->student()->delete();
                if ($targetUser->wali) $targetUser->wali()->delete();

                Teacher::updateOrCreate(
                    ['user_id' => $targetUser->id],
                    [
                        'nip' => $nisn,
                        'no_whatsapp' => $wa,
                    ]
                );
                break;

            case 'murid':
                if ($targetUser->guru) $targetUser->guru()->delete();
                if ($targetUser->wali) $targetUser->wali()->delete();

                Student::updateOrCreate(
                    ['user_id' => $targetUser->id],
                    [
                        'nisn' => $nisn,
                        'kelas' => $targetUser->student->kelas ?? 'default',
                        'qr_code_token' => $targetUser->student->qr_code_token ?? ('default' . $targetUser->id),
                    ]
                );
                break;

            case 'wali':
                if ($targetUser->guru) $targetUser->guru()->delete();
                if ($targetUser->student) $targetUser->student()->delete();

                $waliMurid = WaliMurid::updateOrCreate(
                    ['user_id' => $targetUser->id],
                    [
                        'nama_wali' => $validated['name'],
                        'no_whatsapp' => $wa,
                        'hubungan' => $targetUser->wali->hubungan ?? 'Orang Tua',
                    ]
                );

                // Sinkronisasi anak murid
                $childrenList = [];
                if (!empty($validated['children']) && is_array($validated['children'])) {
                    $childrenList = $validated['children'];
                } elseif (!empty($validated['anak'])) {
                    $childrenList = [$validated['anak']];
                }

                if (!empty($childrenList)) {
                    Student::where('wali_id', $waliMurid->id)->update(['wali_id' => null]);
                    Student::whereIn('nisn', $childrenList)->update(['wali_id' => $waliMurid->id]);
                }
                break;

            case 'admin':
                if ($targetUser->guru) $targetUser->guru()->delete();
                if ($targetUser->student) $targetUser->student()->delete();
                if ($targetUser->wali) $targetUser->wali()->delete();
                break;
        }

        // 4. Return response JSON
        return response()->json([
            'success' => true,
            'message' => 'Data pengguna berhasil diperbarui!',
            'data' => $targetUser->fresh(['student', 'guru', 'wali']),
        ]);
    }
}
