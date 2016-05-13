<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\User;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;

class UserController extends Controller
{

    public function index()
    {
        $users = User::all();
        return view('admin.user.index')->withUsers($users);
    }

    protected $fields = [
        'name' => '',
        'email' => '',
        'password' => ''
    ];

    public function create()
    {
        $data = [];
        foreach ($this->fields as $field => $default) {
            $data[$field] = old($field, $default);
        }
        
        return view('admin.user.create', $data);
    }
    
    // 修改 store() 方法代码如下
    /**
     * Store the newly created tag in the database.
     *
     * @param TagCreateRequest $request            
     * @return Response
     */
    public function store(UserCreateRequest $request)
    {
        $user = new User();
        foreach (array_keys($this->fields) as $field) {
            $user->$field = $request->get($field);
        }
        $user->password = bcrypt($user->password);
        $user->save();
        
        return redirect('/admin/user')->withSuccess("The user '$user->name' was created.");
    }

    /**
     * Show the form for editing a tag
     *
     * @param int $id            
     * @return Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $user->password = "";
        $data = [
            'id' => $id
        ];
        foreach (array_keys($this->fields) as $field) {
            $data[$field] = old($field, $user->$field);
        }
        
        return view('admin.user.edit', $data);
    }

    /**
     * Update the tag in storage
     *
     * @param TagUpdateRequest $request            
     * @param int $id            
     * @return Response
     */
    public function update(UserUpdateRequest $request, $id)
    {
        $user = User::findOrFail($id);
        
        foreach (array_keys(array_except($this->fields, [
            'user'
        ])) as $field) {
            $user->$field = $request->get($field);
        }
        $user->password = bcrypt($user->password);
        $user->save();
        
        return redirect("/admin/user/$id/edit")->withSuccess("Changes saved.");
    }

    /**
     * Delete the tag
     *
     * @param int $id            
     * @return Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        
        return redirect('/admin/user')->withSuccess("The user '$user->name' has been deleted.");
    }
}
