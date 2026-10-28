<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{User, Role};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of users with search & pagination.
     */
    public function index(Request $request)
    {
        $search = $request->query('search');

        $query = User::with('role')->latest();

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->paginate(15)->withQueryString();
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles', 'search'));
    }

    /**
     * Update user role.
     */
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role_id' => ['required', 'exists:roles,id'],
        ]);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat mengubah role akun Anda sendiri.');
        }

        $user->update(['role_id' => $request->role_id]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Role user "' . $user->name . '" berhasil diperbarui!');
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $name = $user->name;

        \Illuminate\Support\Facades\DB::transaction(function () use ($user) {
            // 1. Delete associated comments / reviews
            $user->kritiks()->delete();

            // 2. Clear any active sessions
            if (\Illuminate\Support\Facades\Schema::hasTable('sessions')) {
                \Illuminate\Support\Facades\DB::table('sessions')->where('user_id', $user->id)->delete();
            }

            // 3. Delete the user
            $user->delete();
        });

        return redirect()->route('admin.users.index')
            ->with('success', 'User "' . $name . '" berhasil dihapus!');
    }
}
