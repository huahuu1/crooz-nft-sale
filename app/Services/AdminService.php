<?php

namespace App\Services;

use App\Models\Admin;

class AdminService
{
    /**
     * Get balances of a user follow token id
     *
     * @param walletAddress
     * @return user
     */
    public function getAdminById($id)
    {
        return Admin::findOrFail($id);
    }
}
