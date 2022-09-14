<?php

namespace App\Services;

use App\Models\TokenUnlockRule;

class TokenUnlockRuleService
{
    /**
     * Get information of Token Unlock Rule.
     *
     * @return \Illuminate\Http\Response
     */
    public function getUnlockInfo($id)
    {
        $tokenUnlockRule = TokenUnlockRule::where('id', $id)->first();

        return TokenUnlockRule::where('rule_code', $tokenUnlockRule->rule_code)->get();
    }
}
