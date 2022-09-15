<?php

namespace App\Services;

class TokenUnlockRuleService
{
    /**
     * Get information of Token Unlock Rule.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUnlockRule($tokenUnlockRule)
    {
        return collect($tokenUnlockRule);
    }
}
