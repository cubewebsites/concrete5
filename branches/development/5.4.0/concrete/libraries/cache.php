<?

class CacheLocal {

	public $cache = array();
	public $enabled = true;
	
	public static function get() {
		static $instance;
		if (!isset($instance)) {
			$v = __CLASS__;
			$instance = new $v;
		}
		return $instance;
	}
}

class Cache {
	
	public function key($type, $id) {
		return md5($type . $id);
	}
	
	public function getLibrary() {
		static $cache;
		if (!isset($cache)) {
			Loader::library('3rdparty/Zend/Cache');
			$frontendOptions = array(
				'lifetime' => 7200,
				'automatic_serialization' => true			
			);
			$backendOptions = array(
				'cache_dir' => DIR_BASE . '/files/cache'			
			);
			if (!defined('CACHE_LIBRARY') || (defined("CACHE_LIBRARY") && CACHE_LIBRARY == "default")) {
				define('CACHE_LIBRARY', 'File');
			}
			$cache = Zend_Cache::factory('Core', CACHE_LIBRARY, $frontendOptions, $backendOptions);
		}
		return $cache;
	}
	
	public function startup() {
		$cache = Cache::getLibrary();
	}
	
	public function disableCache() {
		Cache::getLibrary()->setOption('caching', false);
	}
	public function enableCache() {
		if (defined('ENABLE_CACHE') && ENABLE_CACHE == TRUE) {
			Cache::getLibrary()->setOption('caching', true);
		}
	}
	
	public function disableLocalCache() {
		CacheLocal::get()->enabled = false;
	}
	public function enableLocalCache() {
		CacheLocal::get()->enabled = true;
	}
	
	/** 
	 * Inserts or updates an item to the cache
	 * If $forceSet is true, we sidestep ENABLE_CACHE. This is for certain operations that
	 * the cache must always be enabled for (getting remote data, etc..)
	 */	
	public function set($type, $id, $obj, $expire = false) {
		$loc = CacheLocal::get();
		if ($loc->enabled) {
			$loc->cache[Cache::key($type, $id)] = $obj;
		}
		$cache = Cache::getLibrary();
		$cache->save($obj, Cache::key($type, $id), array($type), $expire);
	}
	
	/** 
	 * Retrieves an item from the cache
	 * If $forceGet is true, we sidestep ENABLE_CACHE. This is for certain operations that
	 * the cache must always be enabled for (getting remote data, etc..)
	 */	
	public function get($type, $id, $mustBeNewerThan = false, $forceGet = false) {
		$loc = CacheLocal::get();
		if ($loc->enabled && isset($loc->cache[Cache::key($type, $id)])) {
			return $loc->cache[Cache::key($type, $id)];
		}
			
		$cache = Cache::getLibrary();
		return $cache->load(Cache::key($type, $id));
	}
	
	/** 
	 * Removes an item from the cache
	 */	
	public function delete($type, $id){
		Cache::getLibrary()->remove(Cache::key($type, $id));
		$loc = CacheLocal::get();
		if ($loc->enabled && isset($loc->cache[Cache::key($type, $id)])) {
			unset($loc->cache[Cache::key($type, $id)]);
		}
	}
	
	/** 
	 * Completely flushes the cache
	 */	
	public function flush() {
		$cache = Cache::getLibrary();
		$cache->clean(Zend_Cache::CLEANING_MODE_ALL);
		return true;
	}
		
}