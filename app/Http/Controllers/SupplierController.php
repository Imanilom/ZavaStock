<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SupplierController extends Controller
{
    public function index()
    {
        $suppliers = Supplier::with('user')->get();
        return view('supplier.index', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'email_supplier' => 'nullable|email',
            'catatan' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = time().'.'.$request->foto->extension();
            $request->foto->move(public_path('images/supplier'), $fotoPath);
        }

        Supplier::create([
            'user_id' => $user->id,
            'nama' => $request->name,
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
            'email' => $request->email_supplier,
            'catatan' => $request->catatan,
            'foto' => $fotoPath,
        ]);

        return redirect()->route('supplier.index')->with('success', 'Supplier berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $supplier = Supplier::with('user')->findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:100',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'email_supplier' => 'nullable|email',
            'catatan' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = $supplier->user;
        $user->name = $request->name;
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
            'nama' => $request->name,
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
            'email' => $request->email_supplier,
            'catatan' => $request->catatan,
            'foto' => $fotoPath,
        ]);

        return redirect()->route('supplier.index')->with('success', 'Data supplier berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $supplier = Supplier::with('user')->findOrFail($id);

        if ($supplier->foto && file_exists(public_path('images/supplier/'.$supplier->foto))) {
            unlink(public_path('images/supplier/'.$supplier->foto));
        }

        if ($supplier->user) {
            $supplier->user->delete();
        } else {
            $supplier->delete();
        }

        return redirect()->route('supplier.index')->with('success', 'Supplier berhasil dihapus.');
    }

    public function deleteMultiple(Request $request)
    {
        $ids = $request->input('ids');

        if (!empty($ids)) {
            $suppliers = Supplier::with('user')->whereIn('id', $ids)->get();

            foreach ($suppliers as $supplier) {
                if ($supplier->foto && file_exists(public_path('images/supplier/'.$supplier->foto))) {
                    unlink(public_path('images/supplier/'.$supplier->foto));
                }

                if ($supplier->user) {
                    $supplier->user->delete();
                } else {
                    $supplier->delete();
                }
            }

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
