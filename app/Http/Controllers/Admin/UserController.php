<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        return view('admin.users.index', [
            'title' => __('Users'),
            'users' => User::query()->orderByDesc('id')->paginate(20)->withQueryString(),
        ]);
    }

    public function create(): View
    {
        return view('admin.users.create', [
            'title' => __('New user'),
        ]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        User::query()->create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'password' => $request->validated('password'),
            'is_admin' => $request->boolean('is_admin'),
            'can_access_panel' => true,
            'status' => $request->validated('status'),
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('status', __('flash.user.created'));
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', [
            'title' => __('Edit user'),
            'editUser' => $user,
        ]);
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();

        if (! empty($data['password'])) {
            $user->password = $data['password'];
        }

        unset($data['password'], $data['password_confirmation']);

        $user->fill(collect($data)->except(['is_admin'])->all());
        $user->is_admin = $request->boolean('is_admin');
        $user->can_access_panel = true;
        $user->save();

        return redirect()
            ->route('admin.users.index')
            ->with('status', __('flash.user.updated'));
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return redirect()
                ->route('admin.users.index')
                ->withErrors(['delete' => __('validation.cannot_delete_self')]);
        }

        if ($user->is_admin && $user->isActive() && User::administratorsCount() <= 1) {
            return redirect()
                ->route('admin.users.index')
                ->withErrors(['delete' => __('validation.cannot_delete_last_admin')]);
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('status', __('flash.user.deleted'));
    }
}
