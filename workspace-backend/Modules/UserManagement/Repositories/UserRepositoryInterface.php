<?php

namespace Modules\UserManagement\Repositories;

use Illuminate\Http\Request;

interface UserRepositoryInterface
{
    public function getAllUsers();

    public function authenticate(array $credentials);

    public function prelogin();

    public function logout();

    public function changePassword(Request $request);

    public function resetPassword(Request $request);

    public function verifyAndChangePassword(Request $request);

    public function getUserFromEmail($email);

    public function getUserProfile();

    public function editUserProfile(Request $request);

    public function deleteProfileImg();

}