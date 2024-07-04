<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class KasirController extends Controller
{
    public function index(Request $request)
    {
        $barangs = Barang::all();

        if ($request->ajax()) {
            $kodeBarang = $request->kode_barang;
            $barang = Barang::findOrFail($kodeBarang);
            return response()->json([
                'kode_barang' => $barang->kodeBarang,
                'stok' => $barang->Stok,
                'harga_jual' => $barang->HargaJual,
            ]);
        }

        return view('kasir')->with('barangs', $barangs);
    }

    public function getBarangDetails($kodeBarang)
    {
        $barang = Barang::findOrFail($kodeBarang);
        return response()->json([
            'kode_barang' => $barang->KodeBarang,
            'stok' => $barang->Stok,
            'harga_jual' => $barang->HargaJual
        ]);
    }

    public function submitOrder(Request $request)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'total_harga' => 'required|numeric',
                'items' => 'required|array',
                'items.*.kode_barang' => 'required|string',
                'items.*.quantity' => 'required|integer|min:1',
                'items.*.item_total_price' => 'required|numeric'
            ]);

            Log::info('Request data validated:', ['validated' => $validated]);

            $order = Order::create([
                'total_harga' => $validated['total_harga'],
                'tanggal_order' => now(),
                'user_id' => Auth::id(),
            ]);

            Log::info('Order created:', ['order_id' => $order->id]);

            foreach ($validated['items'] as $item) {
                $barang = Barang::where('Nama', $item['kode_barang'])->first();

                if ($barang->Stok < $item['quantity']) {
                    throw new Exception('Stok barang ' . $barang->nama_barang . ' tidak mencukupi.');
                }

                Log::info('Updating stock for barang:', ['kode' => $barang->kode, 'current_stok' => $barang->stok, 'quantity' => $item['quantity']]);

                $barang->Stok -= $item['quantity'];
                $barang->save();

                Log::info('Stock updated for barang:', ['kode' => $barang->kode, 'new_stok' => $barang->stok]);

                OrderDetail::create([
                    'order_id' => $order->id,
                    'KodeBarang' => $barang->KodeBarang,
                    'NamaBarang' => $item['kode_barang'],
                    'Kuantitas' => $item['quantity'],
                    'SubTotal' => $item['item_total_price'],
                    'tanggal_order' => now()
                ]);   
            }

            DB::commit();

            return response()->json(['success' => true]);

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Error processing order:', ['exception' => $e, 'request_data' => $request->all()]);
            return response()->json(['success' => false, 'message' => 'Internal Server Error', 'error' => $e->getMessage()], 500);
        }
    }

    public function showOrder()
{
    $orders = Order::with('orderDetails')->get();
    return view('orders', compact('orders'));
}
}
