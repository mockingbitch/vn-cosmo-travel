<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Http\Requests\Admin\UpdateUserStatusRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $q = trim((string) request()->query('q', ''));
        $status = trim((string) request()->query('status', ''));
        $role = trim((string) request()->query('role', ''));

        $users = User::query()
            ->when($q !== '', function ($query) use ($q): void {
                $query->where(function ($inner) use ($q): void {
                    $inner
                        ->where('name', 'like', '%'.$q.'%')
                        ->orWhere('email', 'like', '%'.$q.'%');
                });
            })
            ->when($status !== '', fn ($query) => $query->where('status', $status))
            ->when($role !== '', function ($query) use ($role): void {
                if ($role === 'admin') {
                    $query->where('is_admin', true);
                } elseif ($role === 'editor') {
                    $query->where('is_admin', false);
                }
            })
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('admin.users.index', [
            'title' => __('users'),
            'users' => $users,
            'filters' => [
                'q' => $q,
                'status' => $status,
                'role' => $role,
            ],
        ]);
    }

    public function create(): View
    {
        return view('admin.users.create', [
            'title' => __('ui.new_user'),
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
            'title' => __('ui.edit_user'),
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

    public function updateStatus(UpdateUserStatusRequest $request, User $user): RedirectResponse
    {
        $user->status = $request->validated('status');
        $user->save();

        return redirect()
            ->route('admin.users.index', $this->userListQueryFromRequest($request))
            ->with('status', __('flash.user.updated'));
    }

    /**
     * @return array<string, int|string>
     */
    private function userListQueryFromRequest(Request $request): array
    {
        $query = [];

        if ($request->filled('page')) {
            $page = (int) $request->input('page');
            if ($page > 0) {
                $query['page'] = $page;
            }
        }

        if ($request->filled('q')) {
            $query['q'] = trim((string) $request->input('q'));
        }

        if ($request->filled('status')) {
            $query['status'] = (string) $request->input('status');
        }

        if ($request->filled('role')) {
            $query['role'] = (string) $request->input('role');
        }

        return $query;
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
