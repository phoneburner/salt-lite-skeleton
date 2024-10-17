<?php

declare(strict_types=1);

namespace PhoneBurner\SaltLiteFramework\Cache;

use PhoneBurner\SaltLiteFramework\App\BuildStage;
use PhoneBurner\SaltLiteFramework\App\Context;
use PhoneBurner\SaltLiteFramework\App\Environment;
use PhoneBurner\SaltLiteFramework\Cache\Marshaller\RemoteCacheMarshaller;
use PhoneBurner\SaltLiteFramework\Cache\Marshaller\Serializer;
use PhoneBurner\SaltLiteFramework\Database\Redis\RedisManager;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Adapter\ChainAdapter;
use Symfony\Component\Cache\Adapter\NullAdapter;
use Symfony\Component\Cache\Adapter\PhpArrayAdapter;
use Symfony\Component\Cache\Adapter\PhpFilesAdapter;
use Symfony\Component\Cache\Adapter\ProxyAdapter;
use Symfony\Component\Cache\Adapter\RedisAdapter;

use const PhoneBurner\SaltLiteFramework\APP_ROOT;

class CacheItemPoolFactory
{
    public const string DEFAULT_NAMESPACE = 'cache';

    public const string DEFAULT_STATIC_CACHE_FILE = APP_ROOT . '/storage/bootstrap/static.cache.php';

    public const string DEFAULT_FILE_CACHE_DIRECTORY = APP_ROOT . '/storage/';

    private CacheItemPoolInterface|null $pool = null;

    private CacheItemPoolInterface|null $memory = null;

    private CacheItemPoolInterface|null $file = null;

    private CacheItemPoolInterface|null $null = null;

    /**
     * Note: we inject the `RedisManager` here instead of an instance of `\Redis`,
     * in order to potentially delay instantiating the Redis connection until
     * it is actually needed, if needed at all.
     */
    public function __construct(
        private readonly Environment $environment,
        private readonly RedisManager $redis_manager,
        private readonly LoggerInterface $logger = new NullLogger(),
    ) {
    }

    public function make(CacheDriver $driver, string|null $namespace = null): CacheItemPoolInterface
    {
        return $namespace !== null ? new ProxyAdapter($this->make($driver), $namespace) : match ($driver) {
            CacheDriver::Remote => $this->pool ??= $this->createDefaultCacheItemPool(),
            CacheDriver::File => $this->file ??= $this->createFileCacheItemPool(),
            CacheDriver::Memory => $this->memory ??= new ArrayAdapter(storeSerialized: false),
            CacheDriver::None => $this->null ??= new NullAdapter(),
        };
    }

    public function createFileCacheItemPool(
        string|null $namespace = null,
        string $directory = self::DEFAULT_FILE_CACHE_DIRECTORY,
        bool $allow_collection = true,
    ): CacheItemPoolInterface {
        if ($this->environment->context === Context::Test) {
            return $this->make(CacheDriver::Memory);
        }

        return new PhpFilesAdapter($namespace ?: self::DEFAULT_NAMESPACE, directory: $directory, appendOnly: true);
    }

    /**
     * Note: The external most cache adapter needs to either be the instance of
     * PhpArrayAdapter or a TraceableAdapter directly wrapping the PhpArrayAdapter.
     * The PhpArrayAdapter is the only adapter that can be used to warm up the cache
     * and the values are already held in memory, so we do not want to put anything
     * in front of it in production.
     */
    private function createDefaultCacheItemPool(): CacheItemPoolInterface
    {
        if ($this->environment->context === Context::Test) {
            return $this->make(CacheDriver::Memory);
        }

        $cache = new RedisAdapter(
            redis: $this->redis_manager->connect(),
            namespace: self::DEFAULT_NAMESPACE,
            marshaller: match ($this->environment->stage) {
                BuildStage::Production, BuildStage::Integration => new RemoteCacheMarshaller(Serializer::Igbinary),
                BuildStage::Development => new RemoteCacheMarshaller(
                    serializer: Serializer::Php,
                    compress: false,
                    throw_on_serialization_failure: true,
                    logger: $this->logger,
                ),
            },
        );

        $cache = new ChainAdapter([new ArrayAdapter(storeSerialized: false), $cache]);

        return new PhpArrayAdapter(self::DEFAULT_STATIC_CACHE_FILE, $cache);
    }
}
