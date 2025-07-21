<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\User;
use App\Models\AktivitasRiwayat;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $query = Admin::with('user');

        // Add search functionality
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama', 'like', '%'.$search.'%')
                  ->orWhere('telepon', 'like', '%'.$search.'%')
                  ->orWhere('email', 'like', '%'.$search.'%')
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('email', 'like', '%'.$search.'%');
                  });
            });
        }

        $admins = $query->orderBy('nama')->paginate(10);
        return view('admin.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.create');
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'nama' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6|confirmed',
        'telepon' => 'nullable|string|max:20',
        'alamat' => 'nullable|string',
        'catatan' => 'nullable|string',
        'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    try {
        DB::beginTransaction();

        // Create user
        $user = User::create([
            'name' => $validated['nama'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'admin'
        ]);

        // Handle photo upload
        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = time() . '.' . $request->foto->extension();
            $request->foto->move(public_path('images/admin'), $fotoPath);
        }

        // Create admin
        $admin = Admin::create([
            'user_id' => $user->id,
            'nama' => $validated['nama'],
            'telepon' => $validated['telepon'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'email' => $validated['email'],
            'catatan' => $validated['catatan'] ?? null,
            'foto' => $fotoPath,
        ]);

        // Record activity
        AktivitasRiwayat::create([
            'user_id' => auth()->id(),
            'tipe_aktivitas' => 'create',
            'subjek_tipe' => 'admin',
            'subjek_id' => $admin->id,
            'deskripsi' => 'Menambahkan admin baru: ' . $admin->nama,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        DB::commit();

        return redirect()->route('admin.index')->with('success', 'Admin berhasil ditambahkan.');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->withInput()->with('error', 'Gagal menambahkan admin: ' . $e->getMessage());
    }
}

    public function show($id)
    {
        $admin = Admin::with(['user', 'aktivitas'])->findOrFail($id);
        return view('admin.show', compact('admin'));
    }

    public function edit($id)
    {
        $admin = Admin::with('user')->findOrFail($id);
        return view('admin.edit', compact('admin'));
    }

    public function update(Request $request, $id)
    {
        $admin = Admin::with('user')->findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:100',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'email' => 'nullable|email',
            'catatan' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        DB::transaction(function () use ($request, $admin) {
            $oldData = $admin->toArray();
            
            // Update user data
            $user = $admin->user;
            $user->name = $request->nama;
            $user->email = $request->email;
            $user->save();

            // Handle photo update
            $fotoPath = $admin->foto;
            if ($request->hasFile('foto')) {
                // Delete old photo if exists
                if ($fotoPath && file_exists(public_path('images/admin/'.$fotoPath))) {
                    unlink(public_path('images/admin/'.$fotoPath));
                }

                $fotoPath = time().'.'.$request->foto->extension();
                $request->foto->move(public_path('images/admin'), $fotoPath);
            }

            // Update admin data
            $admin->update([
                'nama' => $request->nama,
                'telepon' => $request->telepon,
                'alamat' => $request->alamat,
                'email' => $request->email,
                'catatan' => $request->catatan,
                'foto' => $fotoPath,
            ]);

            // Record activity
            AktivitasRiwayat::create([
                'user_id' => auth()->id(),
                'tipe_aktivitas' => 'update',
                'subjek_tipe' => 'admin',
                'subjek_id' => $admin->id,
                'deskripsi' => 'Memperbarui data admin: ' . $admin->nama,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        });

        return redirect()->route('admin.index')->with('success', 'Data admin berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $admin = Admin::with('user')->findOrFail($id);

        DB::transaction(function () use ($admin) {
            // Record activity before deletion
            AktivitasRiwayat::create([
                'user_id' => auth()->id(),
                'tipe_aktivitas' => 'delete',
                'subjek_tipe' => 'admin',
                'subjek_id' => $admin->id,
                'deskripsi' => 'Menghapus admin: ' . $admin->nama,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            // Delete photo if exists
            if ($admin->foto && file_exists(public_path('images/admin/' . $admin->foto))) {
                unlink(public_path('images/admin/' . $admin->foto));
            }

            // Delete user (admin will be deleted via cascade)
            if ($admin->user) {
                $admin->user->delete();
            } else {
                $admin->delete();
            }
        });

        return redirect()->route('admin.index')->with('success', 'Admin berhasil dihapus.');
    }

    public function deleteMultiple(Request $request)
    {
        $ids = $request->input('ids');

        if (!empty($ids)) {
            DB::transaction(function () use ($ids) {
                $admins = Admin::with('user')->whereIn('id', $ids)->get();

                foreach ($admins as $admin) {
                    // Record activity for each deletion
                    AktivitasRiwayat::create([
                        'user_id' => auth()->id(),
                        'tipe_aktivitas' => 'delete',
                        'subjek_tipe' => 'admin',
                        'subjek_id' => $admin->id,
                        'deskripsi' => 'Menghapus admin (multiple): ' . $admin->nama,
                        'ip_address' => request()->ip(),
                        'user_agent' => request()->userAgent(),
                    ]);

                    // Delete photo if exists
                    if ($admin->foto && file_exists(public_path('images/admin/' . $admin->foto))) {
                        unlink(public_path('images/admin/' . $admin->foto));
                    }

                    // Delete user (admin will be deleted via cascade)
                    if ($admin->user) {
                        $admin->user->delete();
                    } else {
                        $admin->delete();
                    }
                }
            });

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