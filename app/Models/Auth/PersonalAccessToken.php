<?php

namespace App\Models\Auth;

use Laravel\Sanctum\PersonalAccessToken as SanctumPersonalAccessToken;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class PersonalAccessToken extends SanctumPersonalAccessToken
{
    use HasUuids;
}
