
<p><img src="https://eu.ui-avatars.com/api/?name=Najm+Njeim?size=100" width="100"/></p>

## nnjeim Persist Helper

A Laravel cache helper methods.

## Installation

You can install the package via composer:
```
composer require nnjeim/persist
```

## Usage

##### Persist Facade
``` 
use Nnjeim\Fetch\Fetch;
use Nnjeim\Respond\Respond;
use Nnjeim\Persist\Persist;

class Country { 

	public function index() {

		if (Persist::setCacheTag('countries')->setCacheKey('index')->hasCacheKey()) {
		
			return Respond::toJson()
				->setMessage('countries')
				->setData(
					Persist::setCacheTag('countries')
						->setCacheKey('index')
						->getCacheKey()
				)
				->withSuccess();
		}
		
		['response' => $response, 'status' => $status] = Fetch::setBaseUri('https://someapi.com')->get('countries');
		
			if ($status === 200 && $response->success) {
				
				$data = Persist::setCacheTag('countries')
					->setCacheKey('index')
					->rememberCacheForever($response->data);H
		
				return Respond::toJson()
					->setMessage('countries')
					->setData($data)
					->withSuccess();
			}
		
			return Respond::withErrors();
	}
}
```
##### PersistHelper Instantiation

```
use Nnjeim\Persist\PersistHelper;
use Nnjeim\Fetch\FetchHelper;
use Nnjeim\Respond\RespondHelper;

class Country { 

	private PersistHelper $persist;
	private FetchHelper $fetch;
	private RespondHelper $respond;

	public function __construct(
		PersistHelper $persist, 
		FetchHelper $fetch, 
		RespondHelper $respond
		) {
	
		$this->persist = $persist;
		$this->fetch = $fetch;
		$this->respond = $respond;
	
		$this->persist->setCacheTag = 'countries';
		$this->fetch->setBaseUri = 'https://someapi.com';
	}
	
	public function index() {
	
		$this->persist->setCacheKey = 'index';
	
		if ($this->persist->hasCacheKey()) {
			
			return $this->respond
				->toJson()
				->setData($this->persist->getCacheKey())
				->withSuccess();    
		}
	
		['response' => $response, 'status' => $status] = $this->fetch->get('countries');
	
		if ($status === 200 && $response->success) {
			
			$data = $this->persist->rememberCacheForever($response->data);
	
			return $this->respond
                ->toJson()
                ->setData($data)
                ->withSuccess();    
		}
	
		return $this->respond->withErrors();
	}
}
```

## Methods

##### Set the cache tag
```
Sets the cache tag string | array

@return $this       setCacheTag(string | array $cacheTag, $suffix = null)
```

##### Set the cache key
```
Sets the cache key string 

@return $this       setCacheKey(string $cacheKey)
```

##### Form a composite cache key
```
sets the cache key with the result of the concatenatenation of multiple strings with an '_' seprator.

@return $this       formCacheKey(array $strings) or formCacheKey($string1, $string2, ...)
```

##### Cache Key exist
```
Asserts if a cache key exists

@return bool       hasCacheKey(string $cacheKey)
```

##### Get a cache key
```
Returns the peristed cache key.

@return mixed       getCacheKey(string $cacheKey)
```

##### Remember cache
```
returns the cached value for a given number of seconds

@return mixed       rememberCache($value, $ttl)
```

##### Remember cache forever
```
returns the cached value

@return mixed       rememberCacheForever($value)
```

##### Forget a cache key
```
Clears the cache of the given cache key.   

@return void       forgetCacheKey(string $cacheKey)
```

##### Flush the cache by Tag
```
Clears all the cache keys related to a given cache tag

@return void       flushCacheTag()
```

##### Increment a cache key
```
Increments a cache key by a given value. default = 1.

@return void       incrementCacheKey($amount = 1)
```

##### Decrement a cache key
```
Decrements a cache key by a given value. default = 1.

@return void       decrementCacheKey($amount = 1)
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.
