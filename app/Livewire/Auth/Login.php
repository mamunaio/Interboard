<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;

#[Layout('components.layouts.guest')]
class Login extends Component
{
    #[Validate('required|email')]
    public string $email = '';

    #[Validate('required')]
    public string $password = '';

    public bool $remember = false;

    /**
     * Handle the admin authentication attempt.
     */
    public function authenticate()
    {
        $this->validate();

        // Attempt authentication using the provided email and password
        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            // Prevent session fixation attacks by regenerating the active session ID
            session()->regenerate();

            return redirect()->intended('/dashboard');
        }

        // Add verification mismatch feedback directly to the validation stack
        $this->addError('email', 'The provided credentials do not match our records.');
    }

    /**
     * Render the login blade view.
     */
    public function render()
    {
        return view('livewire.auth.login');
    }
}
