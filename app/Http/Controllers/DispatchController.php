<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Redirect;

class DispatchController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $ownedOrders = Order::where("user_id", $user->id)->get();
        return view('index', [
            "ownedOrders" => $ownedOrders
        ]);
    }

    public function create()
    {
        return view('orders.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_name'        => 'required|string|max:255',
            'tracking_code'       => 'required|string|unique:orders,tracking_code',
            'origin_address'      => 'required|string|max:500',
            'destination_address' => 'required|string|max:500',
        ]);

        $originResponse = Http::withHeaders(['User-Agent' => 'OrderTracker/1.0'])
            ->get('https://nominatim.openstreetmap.org/search', [
                'q' => $validated['origin_address'],
                'format' => 'json',
                'limit' => 1
            ]);

        $originData = $originResponse->json();
        $originLat = $originData[0]['lat'] ?? null;
        $originLng = $originData[0]['lon'] ?? null;

        // Busca Destino
        $destinationResponse = Http::withHeaders(['User-Agent' => 'OrderTracker/1.0'])
            ->get('https://nominatim.openstreetmap.org/search', [
                'q' => $validated['destination_address'],
                'format' => 'json',
                'limit' => 1
            ]);

        $destinationData = $destinationResponse->json();
        $destinationLat = $destinationData[0]['lat'] ?? null;
        $destinationLng = $destinationData[0]['lon'] ?? null;

        if (!$originLat || !$originLng) {
            $originLat = '-23.5505';
            $originLng = '-46.6333';
        }
        if (!$destinationLat || !$destinationLng) {
            $destinationLat = '-22.9068';
            $destinationLng = '-43.1729';
        }

        Auth::user()->orders()->create(array_merge($validated, [
            'latitude_origem'    => $originLat,
            'longitude_origem'   => $originLng,
            'latitude_destino'   => $destinationLat,
            'longitude_destino'  => $destinationLng,
        ]));

        return redirect('/dashboard')->with('msg', 'Pedido enviado com sucesso!');
    }
}
