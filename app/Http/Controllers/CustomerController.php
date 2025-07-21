<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use App\Models\AktivitasRiwayat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = Customer::with('user');

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

        $customers = $query->orderBy('nama')->paginate(10);
        return view('customer.index', compact('customers'));
    }

    public function create()
    {
        return view('customer.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->nama,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'user',
            ]);

            $customer = Customer::create([
                'user_id' => $user->id,
                'nama' => $request->nama,
                'telepon' => $request->telepon,
                'email' => $request->email,
                'alamat' => $request->alamat,
                'catatan' => $request->catatan,
            ]);

            // Record activity
            AktivitasRiwayat::create([
                'user_id' => auth()->id(),
                'tipe_aktivitas' => 'create',
                'subjek_tipe' => 'customer',
                'subjek_id' => $customer->id,
                'deskripsi' => 'Menambahkan customer baru: ' . $customer->nama,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        });

        return redirect()->route('customer.index')->with('success', 'Customer berhasil ditambahkan.');
    }

    public function show($id)
    {
        $customer = Customer::with(['user', 'aktivitas'])->findOrFail($id);
        return view('customer.show', compact('customer'));
    }

    public function edit($id)
    {
        $customer = Customer::with('user')->findOrFail($id);
        return view('customer.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::with('user')->findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:100',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        DB::transaction(function () use ($request, $customer) {
            $oldData = $customer->toArray();
            
            $customer->update([
                'nama' => $request->nama,
                'telepon' => $request->telepon,
                'email' => $request->email,
                'alamat' => $request->alamat,
                'catatan' => $request->catatan,
            ]);

            if ($customer->user) {
                $customer->user->name = $request->nama;
                $customer->user->email = $request->email;
                $customer->user->save();
            }

            // Record activity
            AktivitasRiwayat::create([
                'user_id' => auth()->id(),
                'tipe_aktivitas' => 'update',
                'subjek_tipe' => 'customer',
                'subjek_id' => $customer->id,
                'deskripsi' => 'Memperbarui data customer: ' . $customer->nama,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);
        });

        return redirect()->route('customer.index')->with('success', 'Customer berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $customer = Customer::with('user')->findOrFail($id);

        DB::transaction(function () use ($customer) {
            // Record activity before deletion
            AktivitasRiwayat::create([
                'user_id' => auth()->id(),
                'tipe_aktivitas' => 'delete',
                'subjek_tipe' => 'customer',
                'subjek_id' => $customer->id,
                'deskripsi' => 'Menghapus customer: ' . $customer->nama,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            if ($customer->user) {
                $customer->user->delete();
            }
            
            $customer->delete();
        });

        return redirect()->route('customer.index')->with('success', 'Customer berhasil dihapus.');
    }

    public function deleteMultiple(Request $request)
    {
        $ids = $request->input('ids');

        if (!empty($ids)) {
            DB::transaction(function () use ($ids) {
                $customers = Customer::with('user')->whereIn('id', $ids)->get();

                foreach ($customers as $customer) {
                    // Record activity for each deletion
                    AktivitasRiwayat::create([
                        'user_id' => auth()->id(),
                        'tipe_aktivitas' => 'delete',
                        'subjek_tipe' => 'customer',
                        'subjek_id' => $customer->id,
                        'deskripsi' => 'Menghapus customer (multiple): ' . $customer->nama,
                        'ip_address' => request()->ip(),
                        'user_agent' => request()->userAgent(),
                    ]);

                    if ($customer->user) {
                        $customer->user->delete();
                    }
                    
                    $customer->delete();
                }
            });

            return redirect()->route('customer.index')->with('success', 'Beberapa customer berhasil dihapus.');
        }

        return redirect()->route('customer.index')->with('error', 'Tidak ada customer yang dipilih.');
    }
}