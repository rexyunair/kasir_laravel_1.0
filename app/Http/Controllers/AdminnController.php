<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Order;
use App\Models\OrderDetail;

class AdminnController extends Controller
{
    public function index()
    {
        $totalProduk = Barang::count();
        $totalOrder = Order::count();
        $barangs = Barang::all();
        $orders = Order::all();
        $orderDetails = OrderDetail::all(); // Fetch order details

        // Prepare data for the charts
        $stokData = $barangs->pluck('Stok');
        $hargaData = $barangs->pluck('HargaJual');
        $totalHargaData = $orders->pluck('total_harga');

        return view('admin', compact('totalProduk', 'totalOrder', 'barangs', 'orders', 'orderDetails', 'stokData', 'hargaData', 'totalHargaData'));
    }

    public function getChartData()
    {
        $barangs = Barang::all();
        $orders = Order::all();

        // Prepare data for the charts
        $stokData = $barangs->pluck('Stok');
        $hargaData = $barangs->pluck('HargaJual');
        $totalHargaData = $orders->pluck('total_harga');

        return response()->json([
            'stokData' => $stokData,
            'hargaData' => $hargaData,
            'totalHargaData' => $totalHargaData,
            'barangLabels' => $barangs->pluck('Nama'),
            'orderLabels' => $orders->pluck('id'),
            'stokVsHargaData' => $barangs->map(function($item) {
                return ['x' => $item->Stok, 'y' => $item->HargaJual];
            })
        ]);
    }

    public function showOrderDetails()
    {
        $orderDetails = OrderDetail::all();
        return view('admin', compact('orderDetails'));
    }
}
