<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\AccountRole;
use App\Models\ClinicBudgetSettings;
use App\Models\User;

final class ClinicBudgetSettingsPolicy
{
    use Concerns\HasAccountRoleChecks;

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ClinicBudgetSettings $settings): bool
    {
        return $user->can('viewNova') || $this->userBelongsToAccount($user, $settings->clinic->account);
    }

    /**
     * Determine whether the user can create the model.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ClinicBudgetSettings $settings): bool
    {
        return $user->can('viewNova') || $this->userHasRoleInAccount($user, $settings->clinic->account, [AccountRole::Administrator]);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ClinicBudgetSettings $settings): bool
    {
        return $user->can('viewNova') || $this->userHasRoleInAccount($user, $settings->clinic->account, [AccountRole::Administrator]);
    }
}
