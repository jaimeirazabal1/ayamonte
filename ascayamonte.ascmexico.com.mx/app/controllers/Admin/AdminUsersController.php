<?php
/**
 * Created by Apptitud.mx.
 * User: diegogonzalez
 * Date: 7/26/15
 * Time: 4:38 PM
 */



/**
 * Class AdminUsersController
 * @package Admin
 */
class AdminUsersController extends \AdminController {


    /**
     *
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of users
     *
     * @return Response
     */
    public function index()
    {
        $users = User::paginate(25);

        return View::make('admin.modules.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user
     *
     * @return Response
     */
    public function create()
    {
        $profiles     = $this->profileOptions();
        $profile_user = null;

        return View::make('admin.modules.users.create', compact('profiles', 'profile_user'));
    }

    /**
     * Store a newly created user in storage.
     *
     * @return Response
     */
    public function store()
    {
        $validator = Validator::make($data = Input::all(), [
            'first_name'            => 'required',
            'last_name'             => 'required',
            'email'                 => 'required|email|unique:users',
            'password'              => 'required|confirmed|min:8',
            'password_confirmation' => 'required|min:8',
            'group_id'              => 'required|integer'
        ]);

        if ($validator->fails())
        {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $data['activated'] = empty($data['activated']) ? false : true;

        $user = Sentry::createUser(array(
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'],
            'email'      => $data['email'],
            'password'   => $data['password'],
            'activated'  => $data['activated'],
        ));

        $group = Sentry::findGroupById($data['group_id']);

        // Assign the group to the user
        $user->addGroup($group);


        Flash::success(trans('messages.flash.created'));

        return Redirect::route('admin.users.index');
    }



    /**
     * Show the form for editing the specified user.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $user         = User::find($id);
        $profiles     = $this->profileOptions();
        $profile_user = $user->getGroups()->first();
        $profile_user = empty($profile_user->id) ? null : $profile_user->id;

        return View::make('admin.modules.users.edit', compact('user', 'profiles', 'profile_user'));
    }

    /**
     * Update the specified user in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $user = Sentry::findUserById($id);

        $validator = Validator::make($data = Input::all(), [
            'first_name'            => 'required',
            'last_name'             => 'required',
            'email'                 => 'required|email|unique:users,email,' . $user->id,
            'password'              => 'confirmed|min:8',
            'password_confirmation' => 'min:8',
            'group_id'              => 'required|integer'
        ]);

        if ($validator->fails())
        {
            return Redirect::back()->withErrors($validator)->withInput();
        }

        $data['activated'] = empty($data['activated']) ? false : true;


        $old_user_group = $user->getGroups()->first();

        // El rol del usuario cambió
        if ($old_user_group->id != $data['group_id'])
        {
            // Elimina el grupo del usuario
            $old_group = Sentry::findGroupById($old_user_group->id);
            $user->removeGroup($old_group);


            $new_group = Sentry::findGroupById($data['group_id']);
            $user->addGroup($new_group);

        }

        $user->first_name = $data['first_name'];
        $user->last_name  = $data['last_name'];
        $user->email      = $data['email'];

        if ( ! empty($data['password']))
        {
            $user->password   = $data['password'];
        }

        $user->activated  = $data['activated'];
        $user->save();

        Flash::success(trans('messages.flash.updated'));

        return Redirect::route('admin.users.index');
    }


    private function profileOptions()
    {
        $options = [
            '' => 'Perfil de usuario'
        ];

        $groups = Sentry::findAllGroups();

        foreach($groups as $group)
        {
           $options[$group->id] = $group->name;
        }

        return $options;
    }


}