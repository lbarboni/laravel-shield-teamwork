<?php

namespace Shield\Teamwork;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Shield\Shield\Contracts\Service;

/**
 * Class Teamwork
 *
 * @package \Shield\Teamwork
 */
class Teamwork implements Service
{
    public function verify(Request $request, Collection $config): bool
    {
        $generatedHash = hash_hmac('sha256', $request->getContent(), $config->get('token'));
        return hash_equals($generatedHash, $request->header('X-Projects-Signature'));
    }
    public function headers(): array
    {
        return ['X-Projects-Signature'];
    }
}
