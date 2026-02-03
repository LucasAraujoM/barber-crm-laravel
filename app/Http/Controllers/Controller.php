<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Staff;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Log;

class Controller
{
    public function index()
    {
        $appointments = Appointment::where('date', '>=', now()->toDateString())->get();
        return view('home', compact('appointments'));
    }

    public function staff()
    {
        $staff = Staff::all();
        return view('pages.staff', compact('staff'));
    }

    public function store(Request $request)
    {
        $staff = new Staff();
        $staff->name = $request->name;
        $staff->email = $request->email;
        $staff->phone = $request->phone;

        if ($request->has('role')) {
            $staff->role = $request->role;
        }

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $staff->avatar = $path;
            Log::info('Avatar path: ' . $path);
        }

        $staff->notes = $request->notes;
        $staff->save();
        return redirect()->route('staff');
    }

    public function update(Request $request, $id)
    {
        $staff = Staff::find($id);
        $staff->name = $request->name;
        $staff->email = $request->email;
        $staff->phone = $request->phone;

        if ($request->has('role')) {
            $staff->role = $request->role;
        }

        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($staff->avatar) {
                Storage::disk('public')->delete($staff->avatar);
            }
            $path = $request->file('avatar')->store('avatars', 'public');
            $staff->avatar = $path;
        }

        $staff->notes = $request->notes;
        $staff->save();
        return redirect()->route('staff');
    }

    public function destroy($id)
    {
        $staff = Staff::find($id);
        if ($staff->avatar) {
            Storage::disk('public')->delete($staff->avatar);
        }
        $staff->delete();
        return redirect()->route('staff');
    }
}
