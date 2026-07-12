<?php

namespace App\Http\Controllers;

use App\Models\DonorDirectory;
use Illuminate\Http\Request;

class DonorController extends Controller
{
    public function index(Request $request)
    {
        $query = DonorDirectory::with('user:id,name,city');
        if ($request->has('province')) $query->where('province', $request->province);
        if ($request->has('golongan_darah')) $query->where('golongan_darah', $request->golongan_darah);
        if ($request->has('rhesus')) $query->where('rhesus', $request->rhesus);
        if ($request->boolean('available_only', true)) $query->where('is_available', true);

        return response()->json($query->get());
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'golongan_darah' => 'required|string',
            'rhesus' => 'required|string',
            'province' => 'required|string',
            'city' => 'required|string',
        ]);

        $donor = DonorDirectory::updateOrCreate(
            ['user_id' => 1],
            array_merge($validated, ['is_available' => true])
        );

        return response()->json(['donor' => $donor, 'message' => 'Pendaftaran donor berhasil']);
    }
}
