<?php
namespace App\Http\Controllers;

use App\Models\Sampah;
use Illuminate\Http\Request;

class SampahController extends Controller
{
    public function index()
    {
        $data = [
            'tong_organik' => Sampah::where('kategori', 'organik')->orderBy('created_at')->get(),
            'tong_non_organik' => Sampah::where('kategori', 'anorganik')->orderBy('created_at')->get(),
            'tong_logam' => Sampah::where('kategori', 'logam')->orderBy('created_at')->get(),
        ];

        // Mengambil timestamps untuk sumbu x grafik
        $timestamps = $data['tong_organik']->pluck('created_at')->map(function($item) {
            return $item->format('Y-m-d H:i'); // Format waktu yang diinginkan
        });

        return view('dashboard', compact('data', 'timestamps'));
    }

    public function store(Request $request)
    {
        Sampah::create($request->all());
        return response()->json(['message' => 'Data berhasil disimpan'], 201);
    }

    public function history()
    {
        $data = Sampah::orderBy('created_at', 'desc')->paginate(10);
        return view('history', compact('data'));
    }

    public function fetchLatestData()
    {
        $data = [
            'organik' => Sampah::where('kategori', 'organik')->orderBy('created_at', 'desc')->take(10)->pluck('jarak'),
            'anorganik' => Sampah::where('kategori', 'anorganik')->orderBy('created_at', 'desc')->take(10)->pluck('jarak'),
            'logam' => Sampah::where('kategori', 'logam')->orderBy('created_at', 'desc')->take(10)->pluck('jarak'),
        ];

        // Ambil 10 data terbaru dengan timestamp
        $timestamps = Sampah::orderBy('created_at', 'desc')->take(10)->pluck('created_at')->map(function ($item) {
            return $item->setTimezone('Asia/Jakarta')->format('Y-m-d H:i'); // Format waktu WIB
        });

        return response()->json([
            'timestamps' => $timestamps,
            'organik' => $data['organik'],
            'anorganik' => $data['anorganik'],
            'logam' => $data['logam']
        ]);
    }
}
