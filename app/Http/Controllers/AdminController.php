<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    // Tampilkan semua admin
    public function index()
    {
        $admins = Admin::with('user')->get();
        return view('admin.index', compact('admins'));
    }

    // Tampilkan form tambah admin
    public function create()
    {
        return view('admin.create');
    }

    // Simpan data admin baru (user + admin)
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'email_admin' => 'nullable|email',
            'catatan' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Buat user baru dengan role admin
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'admin'
        ]);

        // Handle upload foto
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = time() . '.' . $request->foto->extension();
            $request->foto->move(public_path('images/admin'), $fotoPath);
        }

        // Simpan ke tabel admins
        Admin::create([
            'user_id' => $user->id,
            'nama' => $request->name,
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
            'email' => $request->email_admin,
            'catatan' => $request->catatan,
            'foto' => $fotoPath,
        ]);

        return redirect()->route('admin.index')->with('success', 'Admin berhasil ditambahkan.');
    }

    // Tampilkan detail admin
    public function show($id)
    {
        $admin = Admin::with('user')->findOrFail($id);
        return view('admin.show', compact('admin'));
    }

    // Tampilkan form edit admin
    public function edit($id)
    {
        $admin = Admin::with('user')->findOrFail($id);
        return view('admin.edit', compact('admin'));
    }

    // Update data admin
    public function update(Request $request, $id)
    {
        $admin = Admin::with('user')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'email_admin' => 'nullable|email',
            'catatan' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Update data user (users table)
        $user = $admin->user;
        $user->name = $request->name;
        $user->save();

        // Update foto jika ada
        $fotoPath = $admin->foto;
        if ($request->hasFile('foto')) {
            // Hapus foto lama
            if ($fotoPath && file_exists(public_path('images/admin/'.$fotoPath))) {
                unlink(public_path('images/admin/'.$fotoPath));
            }

            $fotoPath = time().'.'.$request->foto->extension();
            $request->foto->move(public_path('images/admin'), $fotoPath);
        }

        // Update tabel admins
        $admin->update([
            'nama' => $request->name,
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
            'email' => $request->email_admin,
            'catatan' => $request->catatan,
            'foto' => $fotoPath,
        ]);

        return redirect()->route('admin.index')->with('success', 'Data admin berhasil diperbarui.');
    }

    // Hapus admin (hapus juga user)
    public function destroy($id)
    {
        $admin = Admin::with('user')->findOrFail($id);

        // Hapus foto jika ada
        if ($admin->foto && file_exists(public_path('images/admin/' . $admin->foto))) {
            unlink(public_path('images/admin/' . $admin->foto));
        }

        // Hapus user (admin ikut terhapus jika relasi cascade)
        if ($admin->user) {
            $admin->user->delete();
        } else {
            // Jika tidak ada user, hapus admin langsung
            $admin->delete();
        }

        return redirect()->route('admin.index')->with('success', 'Admin berhasil dihapus.');
    }


    // Hapus banyak admin
    public function deleteMultiple(Request $request)
    {
        $ids = $request->input('ids');

        if (!empty($ids)) {
            $admins = Admin::with('user')->whereIn('id', $ids)->get();

            foreach ($admins as $admin) {
                // Hapus foto jika ada
                if ($admin->foto && file_exists(public_path('images/admin/' . $admin->foto))) {
                    unlink(public_path('images/admin/' . $admin->foto));
                }

                // Hapus user jika ada, admin ikut terhapus via relasi cascade
                if ($admin->user) {
                    $admin->user->delete();
                } else {
                    $admin->delete(); // Jika tidak punya user
                }
            }

            return redirect()->route('admin.index')->with('success', 'Data admin berhasil dihapus.');
        }

        return redirect()->route('admin.index')->with('error', 'Tidak ada admin yang dipilih.');
    }


    public function export()
    {
        $admins = Admin::with('user')->get();

        $csvData = [];
        $csvData[] = ['Nama', 'Email', 'Telepon', 'Alamat', 'Catatan'];

        foreach ($admins as $admin) {
            $csvData[] = [
                $admin->nama,
                $admin->user->email ?? '-',
                $admin->telepon,
                $admin->alamat,
                $admin->catatan
            ];
        }

        $filename = 'data_admin_' . now()->format('Ymd_His') . '.csv';
        $handle = fopen('php://temp', 'r+');

        foreach ($csvData as $line) {
            fputcsv($handle, $line);
        }

        rewind($handle);
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
        ];

        return response()->stream(function () use ($handle) {
            fpassthru($handle);
        }, 200, $headers);
    }

}