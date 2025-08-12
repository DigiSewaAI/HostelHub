<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    // सबै रोलहरू देखाउन
    public function index()
    {
        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }

    // नयाँ रोल थप्ने फारम
    public function create()
    {
        return view('roles.create');
    }

    // डाटाबेसमा रोल सेभ गर्न
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:roles,name',
        ]);

        Role::create($request->only('name'));

        return redirect()->route('roles.index')->with('success', 'रोल सफलतापूर्वक थपियो।');
    }

    // एउटा रोल देखाउन
    public function show(Role $role)
    {
        return view('roles.show', compact('role'));
    }

    // रोल संशोधन फारम
    public function edit(Role $role)
    {
        return view('roles.edit', compact('role'));
    }

    // रोल अपडेट गर्न
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|unique:roles,name,' . $role->id,
        ]);

        $role->update($request->only('name'));

        return redirect()->route('roles.index')->with('success', 'रोल सफलतापूर्वक अपडेट गरियो।');
    }

    // रोल हटाउन
    public function destroy(Role $role)
    {
        $role->delete();
        return redirect()->route('roles.index')->with('success', 'रोल सफलतापूर्वक हटाइयो।');
    }
}
