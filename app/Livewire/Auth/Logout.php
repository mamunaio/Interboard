<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Logout extends Component
{
    /**
     * Terminate the operator session and redirect to login page.
     */
    public function logout()
    {
        Auth::logout();

        // Invalidate the session to clear authentication data
        session()->invalidate();

        // Regenerate the CSRF token to prevent cross-site request forgery replay attacks
        session()->regenerateToken();

        return redirect('/login');
    }

    /**
     * Render the logout component view.
     */
    public function render()
    {
        return view('livewire.auth.logout');
    }
}
