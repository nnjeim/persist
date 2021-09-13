<?php

namespace Nnjeim\Persist;

use Illuminate\Contracts\Cache\Repository as CacheRepository;

class PersistHelper {

    public string $cacheTag = 'default';
    public string $cacheKey;
    public string $separator = '_';

    private CacheRepository $cacheRepository;

    public function __construct(CacheRepository $cacheRepository) {

        $this->cacheRepository = $cacheRepository;
    }

    /**
     * @param string $separator
     * @return $this
     */
    public function setSeparator(string $separator = '_') {

        $this->separator = $separator;

        return $this;
    }

    /**
     * @param string $cacheTag
     * @param string | null $suffix
     * @return $this
     */
    public function setCacheTag(string $cacheTag, ?string $suffix = null) {

        $this->cacheTag = $this->postfix($cacheTag, $suffix);

        return $this;
    }

    /**
     * @param string $string
     * @param string | null $suffix
     * @return string
     */
    private function postfix(string $string, ?string $suffix = null) {

        if (!$suffix) {
            return $string;
        }

        return $string . $this->separator . $suffix;
    }

    /**
     * @param $args
     * @return $this
     */
    public function formCacheKey($args) {

        $args = is_array($args) ? $args : func_get_args();

        $this->cacheKey = implode($this->separator, $args);

        return $this;
    }

    /**
     * @return bool
     */
    public function hasCacheKey() {

        return $this->cacheRepository->tags($this->cacheTag)->has($this->cacheKey);
    }

    /**
     * @param $value
     * @param $ttl
     * @return array|mixed
     */
    public function rememberCache($value, $ttl) {

        $this->cacheRepository->tags($this->cacheTag)->put($this->cacheKey, $value, $ttl);

        return $this->getCacheKey();
    }

    /**
     * @return array|mixed
     */
    public function getCacheKey() {

        return $this->cacheRepository->tags($this->cacheTag)->get($this->cacheKey);
    }

    /**
     * @param $cacheKey
     * @return $this
     */
    public function setCacheKey(string $cacheKey) {

        $this->cacheKey = $cacheKey;

        return $this;
    }

    /**
     * @param $value
     * @return mixed
     */
    public function rememberCacheForever($value) {

        $this->cacheRepository->tags($this->cacheTag)->forever($this->cacheKey, $value);

        return $this->getCacheKey();
    }

    /**
     * @return mixed
     */
    public function forgetCacheKey() {

        return $this->cacheRepository->tags($this->cacheTag)->forget($this->cacheKey);
    }

    /**
     * @return mixed
     */
    public function flushCacheTag() {

        return $this->cacheRepository->tags($this->cacheTag)->flush();
    }

    /**
     * @param int $amount
     * @return mixed
     */
    public function incrementCacheKey($amount = 1) {

        return $this->cacheRepository->tags($this->cacheTag)->increment($this->cacheKey, $amount);
    }

    /**
     * @param int $amount
     * @return mixed
     */
    public function decrementCacheKey($amount = 1) {

        return $this->cacheRepository->tags($this->cacheTag)->decrement($this->cacheKey, $amount);
    }

    /**
     * @param string $cacheTag
     * @param string $cacheKey
     * @param string|null $suffix
     * @return bool
     */
    public function forgetForeignCacheKey(string $cacheTag, string $cacheKey, ?string $suffix = null) {

        return $this->cacheRepository->tags($this->postfix($cacheTag, $suffix))->forget($cacheKey);
    }

    /**
     * @param string $cacheTag
     * @param array $cacheKeys
     * @param string|null $suffix
     */
    public function forgetForeignCacheKeys(string $cacheTag, array $cacheKeys, ?string $suffix = null) {

        foreach ($cacheKeys as $cacheKey) {

            $this->cacheRepository->tags($this->postfix($cacheTag, $suffix))->forget($cacheKey);
        }
    }

    /**
     * @param string $cacheTag
     * @param string $cacheKey
     * @param $response
     * @param string|null $suffix
     * @return array|mixed
     */
    public function fetchForeignKey(string $cacheTag, string $cacheKey, $response, ?string $suffix = null) {

        if ($this->hasForeignCacheKey($cacheTag, $cacheKey, $suffix)) {

            return $this->getForeignCachekey($cacheTag, $cacheKey, $suffix);
        }

        return $this->rememberForeignCacheForever($cacheTag, $cacheKey, $response, $suffix);
    }

    /**
     * @param string $cacheTag
     * @param string $cacheKey
     * @param string|null $suffix
     * @return bool
     */
    public function hasForeignCacheKey(string $cacheTag, string $cacheKey, ?string $suffix = null) {

        return $this->cacheRepository->tags($this->postfix($cacheTag, $suffix))->has($cacheKey);
    }

    /**
     * @param string $cacheTag
     * @param string $cacheKey
     * @param string|null $suffix
     * @return array|mixed
     */
    public function getForeignCacheKey(string $cacheTag, string $cacheKey, ?string $suffix = null) {

        return $this->cacheRepository->tags($this->postfix($cacheTag, $suffix))->get($cacheKey);
    }

    /**
     * @param string $cacheTag
     * @param string $cacheKey
     * @param $value
     * @param string|null $suffix
     * @return array|mixed
     */
    public function rememberForeignCacheForever(string $cacheTag, string $cacheKey, $value, ?string $suffix = null) {

        $this->cacheRepository->tags($this->postfix($cacheTag, $suffix))->forever($cacheKey, $value);

        return $this->getForeignCachekey($cacheTag, $cacheKey, $suffix);
    }

    /**
     * @param string $cacheTag
     * @param string|null $suffix
     * @return bool
     */
    public function flushForeignCacheTag(string $cacheTag, ?string $suffix = null) {

        return $this->cacheRepository->tags($this->postfix($cacheTag, $suffix))->flush();
    }

    /**
     * @param array $cacheTags
     * @param string|null $suffix
     * @return bool
     */
    public function flushForeignCacheTags(array $cacheTags, ?string $suffix = null) {

        $count = 0;

        foreach ($cacheTags as $cacheTag) {

            if ($this->cacheRepository->tags($this->postfix($cacheTag, $suffix))->flush()) {
                $count++;
            }
        }

        return $count == count($cacheTags);
    }
}
