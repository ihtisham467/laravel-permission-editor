<?php

namespace Ihtisham467\LaravelPermissionEditor\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permissions = Permission::with('roles:name')->get();

        return view('permission-editor::permissions.index', compact('permissions'));
    }

    public function create() {
        $roles = Role::pluck('name', 'id');

        return view('permission-editor::permissions.create', compact('roles'));
    }

    public function store(Request $request) {
        $data = $request->validate([
            'name' => ['required', 'string', 'unique:permissions'],
            'roles' => ['array'],
        ]);

        $permission = Permission::create($data);

        if(is_array($request->input('roles')) && count($request->input('roles')) > 0) {
            $roles = Role::whereIn('id', $request->input('roles'))->get();
            $permission->syncRoles($roles);
        }

        return redirect()->route('permission-editor.permissions.index');
    }

    public function edit(Permission $permission) {
        $roles = Role::pluck('name', 'id');

        return view('permission-editor::permissions.edit', compact('permission', 'roles'));
    }

    public function update(Request $request, Permission $permission)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'unique:permissions,id,' . $permission->id],
            'roles' => ['array'],
        ]);

        $permission->update($data);

        if(is_array($request->input('roles')) && count($request->input('roles')) > 0) {
            $roles = Role::whereIn('id', $request->input('roles'))->get();
            $permission->syncRoles($roles);
        }

        return redirect()->route('permission-editor.permissions.index');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();

        return redirect()->route('permission-editor.permissions.index');
    }
}
