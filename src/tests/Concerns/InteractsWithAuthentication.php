<?php

namespace Tests\Concerns;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

trait InteractsWithAuthentication
{
    /**
     * @return array<string, string>
     */
    protected function adminHeaders(): array
    {
        $user = User::factory()->admin()->create();

        return $this->authHeadersFor($user);
    }

    /**
     * @return array<string, string>
     */
    protected function userHeaders(): array
    {
        $user = User::factory()->create();

        return $this->authHeadersFor($user);
    }

    /**
     * @return array<string, string>
     */
    protected function authHeadersFor(User $user): array
    {
        $token = Auth::guard('api')->login($user);

        return [
            'Authorization' => "Bearer {$token}",
        ];
    }
}
