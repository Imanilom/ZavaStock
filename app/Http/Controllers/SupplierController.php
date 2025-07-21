<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\User;
use App\Models\AktivitasRiwayat;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $query = Supplier::with('user');

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

        $suppliers = $query->orderBy('nama')->paginate(10);
        return view('supplier.index', compact('suppliers'));
    }

        public function store(Request $request)
        {
            \Log::info('Store method called', ['request' => $request->all()]);
            
            try {
                $validated = $request->validate([
                    'nama' => 'required|string|max:255',
                    'email' => 'required|email|unique:users,email',
                    'telepon' => 'nullable|string|max:20',
                    'alamat' => 'nullable|string',
                    'catatan' => 'nullable|string',
                    'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                ]);

                \Log::info('Validation passed');

                DB::beginTransaction();
                \Log::info('Transaction started');

                // Create user
                $user = User::create([
                    'name' => $validated['nama'],
                    'email' => $validated['email'],
                    'password' => Hash::make("password"), // Default password, should be changed later
                    'role' => 'user'
                ]);
                \Log::info('User created', ['user_id' => $user->id]);

                // Handle photo upload
                $fotoPath = null;
                if ($request->hasFile('foto')) {
                    $fotoPath = time() . '.' . $request->foto->extension();
                    $request->foto->move(public_path('images/supplier'), $fotoPath);
                    \Log::info('Photo uploaded', ['path' => $fotoPath]);
                }

                // Create supplier
                $supplier = Supplier::create([
                    'user_id' => $user->id,
                    'nama' => $validated['nama'],
                    'telepon' => $validated['telepon'] ?? null,
                    'alamat' => $validated['alamat'] ?? null,
                    'email' => $validated['email'],
                    'catatan' => $validated['catatan'] ?? null,
                    'foto' => $fotoPath,
                ]);
                \Log::info('Supplier created', ['supplier_id' => $supplier->id]);

                // Record activity
                AktivitasRiwayat::create([
                    'user_id' => auth()->id(),
                    'tipe_aktivitas' => 'create',
                    'subjek_tipe' => 'supplier',
                    'subjek_id' => $supplier->id,
                    'deskripsi' => 'Menambahkan supplier baru: ' . $supplier->nama,
                    'ip_address' => request()->ip(),
                    'user_agent' => request()->userAgent(),
                ]);
                
                DB::commit();
                \Log::info('Transaction committed');

                return redirect()->route('supplier.index')->with('success', 'Supplier berhasil ditambahkan.');
            } catch (\Exception $e) {
                DB::rollBack();
                \Log::error('Error creating supplier: ' . $e->getMessage(), [
                    'trace' => $e->getTraceAsString()
                ]);
                return back()->withInput()->with('error', 'Gagal menambahkan supplier: ' . $e->getMessage());
            }
        }
        
    public function show($id)
    {
        $supplier = Supplier::with(['user', 'aktivitas'])->findOrFail($id);
        return view('supplier.show', compact('supplier'));
    }

    public function edit($id)
    {
        $supplier = Supplier::with('user')->findOrFail($id);
        return view('supplier.edit', compact('supplier'));
    }

    public function update(Request $request, $id)
    {
        $supplier = Supplier::with('user')->findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:100',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'email' => 'nullable|email',
            'catatan' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        DB::transaction(function () use ($request, $supplier) {
            $oldData = $supplier->toArray();
            
            $user = $supplier->user;
            $user->name = $request->nama;
            $user->email = $request->email;
            $user->save();

            $fotoPath = $supplier->foto;
            if ($request->hasFile('foto')) {
                if ($fotoPath && file_exists(public_path('images/supplier/'.$fotoPath))) {
                    unlink(public_path('images/supplier/'.$fotoPath));
                }

                $fotoPath = time().'.'.$request->foto->extension();
                $request->foto->move(public_path('images/supplier'), $fotoPath);
            }

            $supplier->update([
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
                'subjek_tipe' => 'supplier',
                'subjek_id' => $supplier->id,
                'deskripsi' => 'Memperbarui data supplier: ' . $supplier->nama,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
           
            ]);
        });

        return redirect()->route('supplier.index')->with('success', 'Data supplier berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $supplier = Supplier::with('user')->findOrFail($id);

        DB::transaction(function () use ($supplier) {
            // Record activity before deletion
            AktivitasRiwayat::create([
                'user_id' => auth()->id(),
                'tipe_aktivitas' => 'delete',
                'subjek_tipe' => 'supplier',
                'subjek_id' => $supplier->id,
                'deskripsi' => 'Menghapus supplier: ' . $supplier->nama,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            if ($supplier->foto && file_exists(public_path('images/supplier/'.$supplier->foto))) {
                unlink(public_path('images/supplier/'.$supplier->foto));
            }

            if ($supplier->user) {
                $supplier->user->delete();
            }
            
            $supplier->delete();
        });

        return redirect()->route('supplier.index')->with('success', 'Supplier berhasil dihapus.');
    }

    public function deleteMultiple(Request $request)
    {
        $ids = $request->input('ids');

        if (!empty($ids)) {
            DB::transaction(function () use ($ids) {
                $suppliers = Supplier::with('user')->whereIn('id', $ids)->get();

                foreach ($suppliers as $supplier) {
                    // Record activity for each deletion
                    AktivitasRiwayat::create([
                        'user_id' => auth()->id(),
                        'tipe_aktivitas' => 'delete',
                        'subjek_tipe' => 'supplier',
                        'subjek_id' => $supplier->id,
                        'deskripsi' => 'Menghapus supplier (multiple): ' . $supplier->nama,
                        'ip_address' => request()->ip(),
                        'user_agent' => request()->userAgent(),
                    ]);

                    if ($supplier->foto && file_exists(public_path('images/supplier/'.$supplier->foto))) {
                        unlink(public_path('images/supplier/'.$supplier->foto));
                    }

                    if ($supplier->user) {
                        $supplier->user->delete();
                    }
                    
                    $supplier->delete();
                }
            });

            return redirect()->route('supplier.index')->with('success', 'Data supplier berhasil dihapus.');
        }

        return redirect()->route('supplier.index')->with('error', 'Tidak ada supplier yang dipilih.');
    }

    public function export()
    {
        $suppliers = Supplier::with('user')->get();

        $csvData = [];
        $csvData[] = ['Nama', 'Email', 'Telepon', 'Alamat', 'Catatan'];

        foreach ($suppliers as $supplier) {
            $csvData[] = [
                $supplier->nama,
                $supplier->user->email ?? '-',
                $supplier->telepon,
                $supplier->alamat,
                $supplier->catatan
            ];
        }

        $filename = 'data_supplier_' . now()->format('Ymd_His') . '.csv';
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