<?php
namespace Ihtisham467\LaravelPermissionEditor\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::withCount('permissions')->get();

        return view('permission-editor::roles.index', compact('roles'));
    }

    public function create() {
        $permissions = Permission::pluck('name', 'id');

        return view('permission-editor::roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'unique:roles'],
            'permissions' => ['array'],
        ]);

        $role = Role::create(['name' => $request->input('name')]);

        if(is_array($request->input('permissions')) && count($request->input('permissions')) > 0) {
            $permissions = Permission::whereIn('id', $request->input('permissions'))->get();
            $role->givePermissionTo($permissions);
        }

        return redirect()->route('permission-editor.roles.index');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::pluck('name', 'id');

        return view('permission-editor::roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => ['required', 'string', 'unique:roles,name,' . $role->id],
            'permissions' => ['array'],
        ]);

        $role->update(['name' => $request->input('name')]);

        if(is_array($request->input('permissions')) && count($request->input('permissions')) > 0) {
            $permissions = Permission::whereIn('id', $request->input('permissions'))->get();
            $role->givePermissionTo($permissions);
        }

        return redirect()->route('permission-editor.roles.index');
    }

    public function destroy(Role $role)
    {
        $role->delete();

        return redirect()->route('permission-editor.roles.index');
    }
}
