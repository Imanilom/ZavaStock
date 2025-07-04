<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::with('user')->get();
        return view('customer.index', compact('customers'));
    }

    public function create()
    {
        return view('customer.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        Customer::create([
            'user_id' => $user->id,
            'nama' => $request->name,
            'telepon' => $request->telepon,
            'email' => $request->email,
            'alamat' => $request->alamat,
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('customer.index')->with('success', 'Customer berhasil ditambahkan.');
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
            'name' => 'required|string|max:100',
            'telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        $customer->update([
            'nama' => $request->name,
            'telepon' => $request->telepon,
            'alamat' => $request->alamat,
            'catatan' => $request->catatan,
        ]);

        if ($customer->user) {
            $customer->user->name = $request->name;
            $customer->user->save();
        }

        return redirect()->route('customer.index')->with('success', 'Customer berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $customer = Customer::with('user')->findOrFail($id);

        if ($customer->user) {
            $customer->user->delete();
        } else {
            $customer->delete();
        }

        return redirect()->route('customer.index')->with('success', 'Customer berhasil dihapus.');
    }

    public function deleteMultiple(Request $request)
    {
        $ids = $request->input('ids');

        if (!empty($ids)) {
            $customers = Customer::with('user')->whereIn('id', $ids)->get();

            foreach ($customers as $customer) {
                if ($customer->user) {
                    $customer->user->delete();
                } else {
                    $customer->delete();
                }
            }

            return redirect()->route('customer.index')->with('success', 'Beberapa customer berhasil dihapus.');
        }

        return redirect()->route('customer.index')->with('error', 'Tidak ada customer yang dipilih.');
    }
}
