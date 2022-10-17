<?php

namespace App\Http\Controllers\Admin;

use App\Models\Plan;
use App\Models\Role;
use App\Models\User;
use App\Models\Table;
use App\Models\Tenant;
use App\Models\Product;
use App\Models\Profile;
use App\Models\Category;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function home()
    {
        $tenant = auth()->user()->tenant;

        $totalUsers = User::where('tenant_id', $tenant->id)->count();
        $totalTables = Table::count();
        $totalCategories = Category::count();
        $totalProducts = Product::count();
        $totalTenants = Tenant::count();
        $totalPlans = Plan::count();
        $totalRoles = Role::count();
        $totalProfiles = Profile::count();
        $totalPermissions = Permission::count();

        return view(
            'admin.pages.home.home', compact(
                'totalUsers',
                'totalTables',
                'totalCategories',
                'totalProducts',
                'totalTenants',
                'totalPlans',
                'totalRoles',
                'totalProfiles',
                'totalPermissions'
            )
        );
    }
}
