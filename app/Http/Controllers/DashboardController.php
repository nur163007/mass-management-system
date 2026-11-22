<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\User\UserController;

class DashboardController extends Controller
{
    protected $adminController;
    protected $userController;

    public function __construct(AdminController $adminController, UserController $userController)
    {
        $this->adminController = $adminController;
        $this->userController = $userController;
    }

    /**
     * Unified Dashboard:
     * Serves role-specific content at /dashboard without /admin or /user prefix.
     */
    public function index()
    {
        if (!session()->has('member_id') || !session()->has('role')) {
            return redirect('/');
        }

        if (session('user_status') != 1) {
            session()->flush();
            return redirect('/')->with('error', 'Your account is not active, Please contact with manager.');
        }

        $role = (int) session('role');

        // Super Admin (1) and Manager (2) receive the Admin Dashboard
        if ($role === 1 || $role === 2) {
            return $this->adminController->dashboard();
        }

        // Regular User (3) receives the User Dashboard
        return $this->userController->dashboard();
    }
}
