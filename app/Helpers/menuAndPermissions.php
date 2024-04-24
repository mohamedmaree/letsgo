<?php
use Illuminate\Support\Facades\Route;

use App\User;
use App\Role;
use App\Contact;
use App\SmsEmailNotification;
use App\Permission;





function menu()
{
	$routes = Route::getRoutes();
	foreach ($routes as $value)
	{
		if($value->getName() !== null)
		{
			if(isset($value->getAction()['title']) && !isset($value->getAction()['front']) && isset($value->getAction()['icon']))
			{
				if(isset($value->getAction()['child']) && isset($value->getAction()['subTitle']) && isset($value->getAction()['subIcon']))
				{
                    $arr = [];
                    $permission = Permission::where('role_id',Auth::user()->role)->select('permissions')->get();
                    foreach($permission as $key=>$per)
                    {
                        $arr[$key] = $per->permissions;
                    }

                    if(in_array($value->getName(),$arr))
                    {
                        echo '<li>';
                        echo '<a class="drop-down-btn">'.$value->getAction()['icon'].$value->getAction()['title']. '</a>';
                            echo '<ul class="drop-down-menu">';
                            echo '<li><a class="drop-down-btn" href="'.route($value->getName()).'">'.$value->getAction()['subTitle']. $value->getAction()['subIcon'].'</a></li>';
                                foreach ($value->getAction()['child'] as $child)
                                {
                                    #foreach for sub links
                                    $routes = Route::getRoutes();
                                    foreach ($routes as $value)
                                    {
                                        if($value->getName() !== null && isset($value->getAction()['icon']))
                                        {
                                            if($value->getName() == $child)
                                            {
                                                if(in_array($value->getName(),$arr))
                                                {
                                                    echo '<li><a href="'.route($value->getName()).'">'.$value->getAction()['title'].$value->getAction()['icon'].'</a></li>';
                                                }
                                            }
                                        }
                                    }
                                }
                            echo '</ul>';
                        echo '</li>';
                    }

				}else if(isset($value->getAction()['child']) && isset($value->getAction()['icon']))
				{
                    if(in_array($value->getName(),$arr))
                    {
                        echo '<li><a href="'.route($value->getName()).'">'.$value->getAction()['title'].$value->getAction()['icon'].'</a></li>';
                    }
                }else if(!isset($value->getAction()['child']) && isset($value->getAction()['icon']) && !isset($value->getAction()['hasFather']))
                {
                    $arr = [];
                    $permission = Permission::where('role_id',Auth::user()->role)->select('permissions')->get();
                    foreach($permission as $key=>$per)
                    {
                        $arr[$key] = $per->permissions;
                    }
                    if(in_array($value->getName(),$arr))
                    {
                        echo '<li><a href="'.route($value->getName()).'">'.$value->getAction()['title'].$value->getAction()['icon'].'</a></li>';
                    } 
                }
			}
		} 
	}
}

function Permissions()
{
	$routes = Route::getRoutes();
	foreach ($routes as $value)
	{
		if($value->getName() !== null)
		{
			if(isset($value->getAction()['title']) && !isset($value->getAction()['front']) && isset($value->getAction()['child']))
			{
                echo '<div class="col-sm-2" style="border: 1px solid #000;margin-right:10px;margin-bottom:5px;padding:0">';                                    
                foreach ($value->getAction()['child'] as $child)
                {
                   
                    
                    if(isset($value->getAction()['title']))
                    {
                        echo '<label class="checkbox" style="display:block;background:#37474f;margin-top:0;color:#fff">';
                        echo '<input type="checkbox" name="permissions[]"  value="'.$value->getName().'"> ';    
                        echo '<i class="icon-checkbox"></i>';                    
                        echo '<label class="checkbox" style="padding-right:8px">'.$value->getAction()['title'].'</label>';
                        echo '</label>';    
                    }
                    
                    #????
                    if(isset($value->getAction()['subTitle']))
                    {
                        echo '<label class="checkbox" style="display:block;background:#37474f;margin-top:0;color:#fff">';
                        echo '<input type="checkbox" name="permissions[]"  value="'.$value->getName().'"> ';
                        echo '<i class="icon-checkbox"></i>';
                        echo '<label class="checkbox" style="padding-right:8px">'.$value->getAction()['subTitle'].'</label><br>';
                        echo '</label>';    
                    }

                    #foreach for sub links
                    $routes = Route::getRoutes();
                    foreach ($routes as $value)
                    {
                        if($value->getName() !== null && !isset($value->getAction()['icon']) || isset($value->getAction()['hasFather']))
                        {
                            
                            if($value->getName() == $child)
                            {
                                echo '<label class="checkbox" style="display:block">';
                                echo '<input type="checkbox" name="permissions[]"  value="'.$value->getName().'"> ';
                                echo '<i class="icon-checkbox"></i>';
                                echo '<label style="padding-right:8px">'.$value->getAction()['title'].'</label><br>';
                                echo '</label>';    
                            }
                        }
                    }
                   
                }
                echo '</div>';
            }
            if(!isset($value->getAction()['child']) && isset($value->getAction()['icon']) && !isset($value->getAction()['front']) && isset($value->getAction()['title']) && !isset($value->getAction()['hasFather']))
            {
                echo '<div class="col-sm-2" style="border: 1px solid #000;margin-right:10px;margin-bottom:5px;padding:0">';                                    
                echo '<label class="checkbox" style="display:block;background:#37474f;margin-top:0;margin-bottom:0;color:#fff">';
                echo '<input type="checkbox" name="permissions[]" value="'.$value->getName().'"> ';
                echo '<i class="icon-checkbox"></i>';
                echo '<label style="padding-right:8px ;margin-top:6px">'.$value->getAction()['title'].'</label><br>';
                echo '</label>';  
                echo '</div>';  
            }
        }
        
	}	
}

function EditPermissions($id)
{
	$routes = Route::getRoutes();
	foreach ($routes as $value)
	{
		if($value->getName() !== null)
		{
			if(isset($value->getAction()['title']) && !isset($value->getAction()['front']) && isset($value->getAction()['child']))
			{

                $arr = [];
                $permission = Permission::where('role_id',$id)->select('permissions')->get();
                foreach($permission as $key=>$per)
                {
                    $arr[$key] = $per->permissions;
                }


                echo '<div class="col-sm-2" style="border: 1px solid #000;margin-right:7px;margin-bottom:5px;padding:0">';
                foreach ($value->getAction()['child'] as $child)
                {
                   
                    if(isset($value->getAction()['title']))
                    {
                        if(in_array($value->getName(),$arr))
                        {
                            echo '<label class="checkbox" style="display:block;background:#37474f;margin-top:0;color:#fff">';
                            echo '<input type="checkbox" name="permissions[]" checked value="'.$value->getName().'"> ';
                            echo '<i class="icon-checkbox"></i>';
                            echo '<label class="checkbox" style="padding-right:8px">'.$value->getAction()['title'].'</label>';
                            echo '</label>';    
                        }else
                        {
                            echo '<label class="checkbox" style="display:block;background:#37474f;margin-top:0;color:#fff">';
                            echo '<input type="checkbox" name="permissions[]" value="'.$value->getName().'"> ';    
                            echo '<i class="icon-checkbox"></i>';
                           echo '<label class="checkbox" style="padding-right:8px">'.$value->getAction()['title'].'</label>';
                            echo '</label>'; 
                        }
                    }
                    
                    #????
                    if(isset($value->getAction()['subTitle']))
                    {
                        if(in_array($value->getName(),$arr))
                        {
                            echo '<label class="checkbox" style="display:block;background:#37474f;margin-top:0;color:#fff">';
                            echo '<input type="checkbox" name="permissions[]" checked value="'.$value->getName().'"> ';
                            echo '<i class="icon-checkbox"></i>';
                            echo '<label style="padding-right:8px">'.$value->getAction()['subTitle'].'</label><br>';
                            echo '</label>';
                        }else
                        {
                            echo '<label class="checkbox" style="display:block;background:#37474f;margin-top:0;color:#fff">';
                            echo '<input type="checkbox" name="permissions[]" value="'.$value->getName().'"> ';
                            echo '<i class="icon-checkbox"></i>';
                            echo '<label style="padding-right:8px">'.$value->getAction()['subTitle'].'</label><br>';
                            echo '</label>';
                        }
                    }

                    #foreach for sub links
                    $routes = Route::getRoutes();
                    foreach ($routes as $value)
                    {
                        if($value->getName() !== null && !isset($value->getAction()['icon']) || isset($value->getAction()['hasFather']))
                        
                        {
                            if($value->getName() == $child)
                            {
                                if(in_array($value->getName(),$arr))
                                {
                                    echo '<label class="checkbox" style="display:block">';
                                    echo '<input type="checkbox" name="permissions[]" checked  value="'.$value->getName().'"> ';
                                    echo '<i class="icon-checkbox"></i>';
                                    echo '<label style="padding-right:8px">'.$value->getAction()['title'].'</label><br>';
                                    echo '</label>';
                                }else
                                {
                                    echo '<label class="checkbox" style="display:block">';
                                    echo '<input type="checkbox" name="permissions[]"  value="'.$value->getName().'"> ';
                                    echo '<i class="icon-checkbox"></i>';
                                    echo '<label style="padding-right:8px">'.$value->getAction()['title'].'</label><br>';
                                    echo '</label>';
                                }
                            }
                        }
                    }
                   
                }
                echo '</div>';
            }
            if(!isset($value->getAction()['child']) && isset($value->getAction()['icon']) && !isset($value->getAction()['front']) && isset($value->getAction()['title']) && !isset($value->getAction()['hasFather']))
            {
                $arr = [];
                $permission = Permission::where('role_id',$id)->select('permissions')->get();
                foreach($permission as $key=>$per)
                {
                    $arr[$key] = $per->permissions;
                }

                echo '<div class="col-sm-2" style="border: 1px solid #000;margin-right:10px;margin-bottom:5px;padding:0;background:#eee">';
                if(in_array($value->getName(),$arr))
                {
                    echo '<label class="checkbox" style="display:block;background:#37474f;margin-top:0;margin-bottom:0;color:#fff">';
                    echo '<input type="checkbox" name="permissions[]" checked  value="'.$value->getName().'"> ';
                    echo '<i class="icon-checkbox"></i>';
                    echo '<label style="padding-right:8px ;margin-top:6px">'.$value->getAction()['title'].'</label><br>';
                    echo '</label>';
                }else
                {
                    echo '<label class="checkbox" style="display:block;background:#37474f;margin-top:0;margin-bottom:0;color:#fff">';
                    echo '<input type="checkbox" name="permissions[]"  value="'.$value->getName().'"> ';
                    echo '<i class="icon-checkbox"></i>';
                     echo '<label style="padding-right:8px ;margin-top:6px">'.$value->getAction()['title'].'</label><br>';
                     echo '</label>';
                }
                echo '</div>';
            }
		} 
	}	
}

