<?php
namespace App\ValidationRules;

use Psr\SimpleCache\CacheInterface;
use Rakit\Validation\Rule;

class EmailHostValidationRule extends Rule
{
    const CACHED_RESULT_TTL_SECONDS = 3600;

    /**
     * @var CacheInterface
     */
    protected $cacheClient;

    /**
     * EmailHostValidationRule constructor.
     *
     * @param CacheInterface $cacheClient
     */
    public function __construct(CacheInterface $cacheClient)
    {
        $this->cacheClient = $cacheClient;
    }

    /**
     * @inheritDoc
     */
    public function check($value): bool
    {
        $emailParts = explode('@', $value);
        $host = array_pop($emailParts);

        if ($host === null) {
            return false;
        }

        $cacheKey = 'validatedEmailHosts-' . $host;
        $cachedValue = $this->cacheClient->get($cacheKey);

        if ($cachedValue === true) {
            return true;
        }

        $records = [];
        dns_get_mx($host, $records);
        $hostExists = count($records) > 0;

        if ($hostExists) {
            $this->cacheClient->set($cacheKey, $hostExists, self::CACHED_RESULT_TTL_SECONDS);
        }

        return $hostExists;
    }
}
