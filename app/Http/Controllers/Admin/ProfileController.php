<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        return view('admin.profile.edit', [
            'title' => __('Profile'),
        ]);
    }

    public function update(UpdateProfileRequest $request): RedirectResponse
    {
        $user = $request->user();
        $data = $request->validated();

        if (! empty($data['password'])) {
            $user->password = $data['password'];
        }

        unset($data['password'], $data['password_confirmation']);

        $user->fill($data);
        $user->save();

        return redirect()
            ->route('admin.profile.edit')
            ->with('status', __('flash.profile.updated'));
    }
}
