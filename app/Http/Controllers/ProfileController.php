<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Profile\UpdateAddressRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use App\Services\RegionService;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $this->authorize('view', $user);
        $section = $request->get('section', 'profile');

        return view('profile.index', compact('user', 'section'));
    }

    public function address(Request $request)
    {
        $user = Auth::user();
        $this->authorize('view', $user);
        $section = $request->get('section', 'saved');
        return view('profile.index', compact('user', 'section'));
    }

    public function updateAddress(UpdateAddressRequest $request)
    {
        $this->authorize('update', Auth::user());
        $data = $request->validated();

        Auth::user()->update($data);
        return redirect()->route('profile.index')->with('success', 'Alamat berhasil disimpan.');
    }

    public function regions(RegionService $regionService, string $level, ?string $parent = null)
    {
        return Response::json($regionService->get($level, $parent));
    }
}
