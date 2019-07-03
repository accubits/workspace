<?php

namespace Modules\UserManagement\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\UserManagement\Entities\User;
use Modules\UserManagement\Entities\UserProfile;
use Modules\UserManagement\Http\Requests\EditProfileRequest;
use Modules\UserManagement\Http\Requests\ForgotPasswordRequest;
use Modules\UserManagement\Repositories\UserRepositoryInterface;

class UserController extends Controller
{
    private $user;

    public function __construct(UserRepositoryInterface $user)
    {
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $collection = $this->user->getAllUsers();
        return response()->json($collection, 200);
    }


    /**
     * @param Request $request
     * @return mixed
     */
    public function login(Request $request)
    {
        $content = array();
        $credentials = [
            'email'    => $request->email,
            'password' => $request->password,
        ];
        $request->validate([
            'email'    => 'required|email|max:100',
            'password' => 'required'
        ]);

        $auth = $this->user->authenticate($credentials);
        return response()->json($auth, $auth['code']);
    }

    public function prelogin(Request $request)
    {
        $response = $this->user->prelogin();
        return response()->json($response, $response['code']);
    }

    /**
     * @return mixed
     */
    public function logout()
    {
        $authResponse = $this->user->logout();
        return response()->json($authResponse, $authResponse['code']);
    }

    /**
     * change password
     * @param Request $request
     * @return mixed
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'current_password'    => 'required|min:8',
            'new_password'        => 'required|confirmed|min:8',
        ]);

        $response = $this->user->changePassword($request);
        return response()->json($response, $response['code']);
    }

    public function verifyUser($token)
    {
        $user = User::where('remember_token', $token)->first();
        if (!$user) {
            $errorPage = env('FRONT_END_BASEURL'). 'token-expire';
            return redirect($errorPage);
        } else {
            $newToken = User::generateVerificationCode();
            $user->remember_token   = $newToken;
            $user->verified         = TRUE;
            $user->save();

            $errorPage = env('FRONT_END_BASEURL'). 'auth/invite/'. $newToken;
            return redirect($errorPage);
        }
    }

    public function verifyAndChangePassword(Request $request)
    {
        $request->validate([
            'name'                => 'required',
            'password'    => 'required|min:8',
            'password_confirmation'        => 'required|same:password',
        ]);

        $response = $this->user->verifyAndChangePassword($request);
        return response()->json($response, $response['code']);
    }

    /**
     * reset user password
     * @param ForgotPasswordRequest $request
     * @return mixed
     */
    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $email    = $request->email;
        $response = $this->user->getUserFromEmail($email);
        return response()->json($response, $response['code']);
    }

    /**
     * reset password link from email
     * @param Request $request
     * @param $token
     * @return mixed
     */
    public function resetPasswordLink(Request $request)
    {
        $request->validate([
            'new_password'        => 'required|confirmed|min:8',
        ]);

        $response = $this->user->resetPassword($request);
        return response()->json($response, $response['code']);

    }

    public function getProfile()
    {
        $response = $this->user->getUserProfile();
        return response()->json($response, $response['code']);
    }

    /**
     * api for update profile details
     * @param Request $request
     * @param $slug
     */
    public function editProfile(EditProfileRequest $request)
    {
        
        $response = $this->user->editUserProfile($request);
        return response()->json($response, $response['code']);
    }

    public function deleteProfileImg()
    {
        $response = $this->user->deleteProfileImg();
        return response()->json($response, $response['code']);

    }
}
