<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Permission;
use App\Role;
use Session;

class PermissionsController extends Controller
{

	#permissions list
	public function PermissionsList()
	{
		$roles = Role::latest()->get();
    	return view('dashboard.permission.permissions_list',compact('roles',$roles));
	}

	#add permissioms page
	public function AddPermissionsPage()
	{
		$permission = Permission::latest()->get();
		return view('dashboard.permission.permission',compact('permission',$permission));
	}

	#add permissions
	public function AddPermissions(Request $request)
	{
		$this->validate($request,[
			'role_name' =>'required|min:2|max:190',
		]);

		$role = new Role;
		$role->role = $request->role_name;
		$role->save();
		$permissions = $request->permissions;
		if(count($permissions) > 0)
		{
			foreach($permissions as $p)
			{
				$per = new Permission;
				$per->permissions = $p;
				$per->role_id = $role->id;
				$per->save();
			}
		}

		Session::flash('success','تم الحفظ');
		return back();
	}

	#edit permission page
	public function EditPermissions($id)
	{
		$role = Role::with('Permissions')->findOrFail($id);
		return view('dashboard.permission.edit_permission',compact('role',$role));
	}

	#update permissions
	public function UpdatePermission(Request $request)
	{
		$role = Role::findOrFail($request->id);
		$role->role = $request->role_name;
		$role->save();

		// if($request->id != 1)
		// {
			Permission::where('role_id',$request->id)->delete();
			if($request->permissions){
				foreach($request->permissions as $per)
				{
					$permission = new Permission;
					$permission->permissions = $per;
					$permission->role_id = $role->id;
					$permission->save();
				}
			}
		// }else
		// {
			Session::flash('success','تم حفظ التعديلات');
			return back();
		// }
	}

	#delete permission
	public function DeletePermission(Request $request)
	{	if($request->id != 1)
		{
			Role::findOrFail($request->id)->delete();
			Session::flash('success','تم الحذف بنجاح');
			return back();
		}else
		{
			Session::flash('danger','لا يمكن حذف هذه الصلاحيه ! ..... يمكنك تعديل الاسم فقط ');
			return back();
		}
	}
    
}
