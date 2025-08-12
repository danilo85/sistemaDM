<?php

namespace App\Services;

use App\Models\WidgetCache;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CacheService
{
    /**
     * TTL padrão em segundos
     */
    const DEFAULT_TTL = 1800; // 30 minutos
    
    /**
     * TTL por tipo de widget (em segundos)
     */
    const WIDGET_TTL = [
        'financial-summary' => 300,    // 5 minutos
        'budgets' => 600,             // 10 minutos
        'upcoming-deadlines' => 180,   // 3 minutos
        'pipeline' => 300,            // 5 minutos
        'notifications' => 60,        // 1 minuto
        'top-clients' => 900,         // 15 minutos
        'shortcuts' => 3600,          // 1 hora
        'recent-activity' => 120,     // 2 minutos
        'portfolio-metrics' => 600    // 10 minutos
    ];
    
    /**
     * Prefixos de cache
     */
    const CACHE_PREFIXES = [
        'widget_data' => 'widget_data',
        'user_config' => 'user_config',
        'dashboard_layout' => 'dashboard_layout',
        'widget_metadata' => 'widget_metadata',
        'system_stats' => 'system_stats'
    ];
    
    /**
     * Obter dados do cache
     */
    public function get($key, $default = null)
    {
        try {
            return Cache::get($key, $default);
        } catch (\Exception $e) {
            Log::warning('Cache get failed', [
                'key' => $key,
                'error' => $e->getMessage()
            ]);
            return $default;
        }
    }
    
    /**
     * Armazenar dados no cache
     */
    public function put($key, $value, $ttl = null)
    {
        $ttl = $ttl ?? self::DEFAULT_TTL;
        
        try {
            return Cache::put($key, $value, $ttl);
        } catch (\Exception $e) {
            Log::warning('Cache put failed', [
                'key' => $key,
                'ttl' => $ttl,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
    
    /**
     * Armazenar dados no cache com remember
     */
    public function remember($key, $ttl, $callback)
    {
        try {
            return Cache::remember($key, $ttl, $callback);
        } catch (\Exception $e) {
            Log::warning('Cache remember failed', [
                'key' => $key,
                'ttl' => $ttl,
                'error' => $e->getMessage()
            ]);
            return $callback();
        }
    }
    
    /**
     * Remover item do cache
     */
    public function forget($key)
    {
        try {
            return Cache::forget($key);
        } catch (\Exception $e) {
            Log::warning('Cache forget failed', [
                'key' => $key,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
    
    /**
     * Limpar cache por padrão
     */
    public function forgetByPattern($pattern)
    {
        try {
            if (Cache::getStore() instanceof \Illuminate\Cache\RedisStore) {
                $keys = Redis::keys($pattern);
                if (!empty($keys)) {
                    Redis::del($keys);
                }
                return true;
            } else {
                // Para outros drivers, não há suporte nativo a padrões
                Log::info('Pattern-based cache clearing not supported for current cache driver');
                return false;
            }
        } catch (\Exception $e) {
            Log::warning('Cache pattern forget failed', [
                'pattern' => $pattern,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
    
    /**
     * Obter dados de widget com cache
     */
    public function getWidgetData($userId, $widgetType, $callback)
    {
        $key = $this->getWidgetCacheKey($userId, $widgetType);
        $ttl = self::WIDGET_TTL[$widgetType] ?? self::DEFAULT_TTL;
        
        return $this->remember($key, $ttl, function () use ($userId, $widgetType, $callback) {
            $data = $callback();
            
            // Armazenar também no banco para backup
            $this->storeWidgetCacheInDatabase($userId, $widgetType, $data);
            
            return $data;
        });
    }
    
    /**
     * Invalidar cache de widget
     */
    public function invalidateWidgetCache($userId, $widgetType)
    {
        $key = $this->getWidgetCacheKey($userId, $widgetType);
        $this->forget($key);
        
        // Remover também do banco
        WidgetCache::where('user_id', $userId)
            ->where('widget_type', $widgetType)
            ->delete();
    }
    
    /**
     * Invalidar todo cache do usuário
     */
    public function invalidateUserCache($userId)
    {
        // Invalidar cache de widgets
        foreach (array_keys(self::WIDGET_TTL) as $widgetType) {
            $this->invalidateWidgetCache($userId, $widgetType);
        }
        
        // Invalidar cache de configuração
        $configKey = $this->getUserConfigCacheKey($userId);
        $this->forget($configKey);
        
        // Invalidar cache de layout
        $layoutKey = $this->getUserLayoutCacheKey($userId);
        $this->forget($layoutKey);
    }
    
    /**
     * Pré-carregar cache de widgets
     */
    public function preloadWidgetCache($userId, $widgetTypes, $dataCallbacks)
    {
        foreach ($widgetTypes as $widgetType) {
            if (isset($dataCallbacks[$widgetType])) {
                $key = $this->getWidgetCacheKey($userId, $widgetType);
                
                // Verificar se já está em cache
                if (!$this->get($key)) {
                    $ttl = self::WIDGET_TTL[$widgetType] ?? self::DEFAULT_TTL;
                    $data = $dataCallbacks[$widgetType]();
                    
                    $this->put($key, $data, $ttl);
                    $this->storeWidgetCacheInDatabase($userId, $widgetType, $data);
                }
            }
        }
    }
    
    /**
     * Obter estatísticas do cache
     */
    public function getCacheStats()
    {
        try {
            $stats = [
                'total_keys' => 0,
                'widget_cache_count' => 0,
                'config_cache_count' => 0,
                'memory_usage' => 0,
                'hit_rate' => 0
            ];
            
            if (Cache::getStore() instanceof \Illuminate\Cache\RedisStore) {
                $info = Redis::info('memory');
                $stats['memory_usage'] = $info['used_memory_human'] ?? 'N/A';
                
                // Contar chaves por tipo
                $widgetKeys = Redis::keys(self::CACHE_PREFIXES['widget_data'] . '*');
                $configKeys = Redis::keys(self::CACHE_PREFIXES['user_config'] . '*');
                
                $stats['widget_cache_count'] = count($widgetKeys);
                $stats['config_cache_count'] = count($configKeys);
                $stats['total_keys'] = $stats['widget_cache_count'] + $stats['config_cache_count'];
            }
            
            return $stats;
        } catch (\Exception $e) {
            Log::warning('Failed to get cache stats', ['error' => $e->getMessage()]);
            return [];
        }
    }
    
    /**
     * Limpar cache expirado
     */
    public function clearExpiredCache()
    {
        try {
            // Limpar cache expirado do banco
            WidgetCache::where('expires_at', '<', Carbon::now())->delete();
            
            Log::info('Expired cache cleared successfully');
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to clear expired cache', ['error' => $e->getMessage()]);
            return false;
        }
    }
    
    /**
     * Otimizar cache
     */
    public function optimizeCache()
    {
        try {
            // Limpar cache expirado
            $this->clearExpiredCache();
            
            // Compactar cache do Redis se disponível
            if (Cache::getStore() instanceof \Illuminate\Cache\RedisStore) {
                Redis::bgrewriteaof();
            }
            
            Log::info('Cache optimization completed');
            return true;
        } catch (\Exception $e) {
            Log::error('Cache optimization failed', ['error' => $e->getMessage()]);
            return false;
        }
    }
    
    /**
     * Backup do cache para o banco
     */
    public function backupCacheToDatabase()
    {
        try {
            if (Cache::getStore() instanceof \Illuminate\Cache\RedisStore) {
                $widgetKeys = Redis::keys(self::CACHE_PREFIXES['widget_data'] . '*');
                
                foreach ($widgetKeys as $key) {
                    $data = Redis::get($key);
                    if ($data) {
                        // Extrair userId e widgetType da chave
                        if (preg_match('/widget_data_(\d+)_(.+)/', $key, $matches)) {
                            $userId = $matches[1];
                            $widgetType = $matches[2];
                            
                            $this->storeWidgetCacheInDatabase($userId, $widgetType, json_decode($data, true));
                        }
                    }
                }
            }
            
            Log::info('Cache backup to database completed');
            return true;
        } catch (\Exception $e) {
            Log::error('Cache backup failed', ['error' => $e->getMessage()]);
            return false;
        }
    }
    
    /**
     * Restaurar cache do banco
     */
    public function restoreCacheFromDatabase($userId = null)
    {
        try {
            $query = WidgetCache::where('expires_at', '>', Carbon::now());
            
            if ($userId) {
                $query->where('user_id', $userId);
            }
            
            $cacheEntries = $query->get();
            
            foreach ($cacheEntries as $entry) {
                $key = $this->getWidgetCacheKey($entry->user_id, $entry->widget_type);
                $ttl = $entry->expires_at->diffInSeconds(Carbon::now());
                
                if ($ttl > 0) {
                    $this->put($key, $entry->data, $ttl);
                }
            }
            
            Log::info('Cache restored from database', ['entries' => $cacheEntries->count()]);
            return true;
        } catch (\Exception $e) {
            Log::error('Cache restore failed', ['error' => $e->getMessage()]);
            return false;
        }
    }
    
    /**
     * Gerar chave de cache para widget
     */
    protected function getWidgetCacheKey($userId, $widgetType)
    {
        return self::CACHE_PREFIXES['widget_data'] . "_{$userId}_{$widgetType}";
    }
    
    /**
     * Gerar chave de cache para configuração do usuário
     */
    protected function getUserConfigCacheKey($userId)
    {
        return self::CACHE_PREFIXES['user_config'] . "_{$userId}";
    }
    
    /**
     * Gerar chave de cache para layout do usuário
     */
    protected function getUserLayoutCacheKey($userId)
    {
        return self::CACHE_PREFIXES['dashboard_layout'] . "_{$userId}";
    }
    
    /**
     * Armazenar cache de widget no banco
     */
    protected function storeWidgetCacheInDatabase($userId, $widgetType, $data)
    {
        try {
            $ttl = self::WIDGET_TTL[$widgetType] ?? self::DEFAULT_TTL;
            $expiresAt = Carbon::now()->addSeconds($ttl);
            
            WidgetCache::updateOrCreate(
                [
                    'user_id' => $userId,
                    'widget_type' => $widgetType
                ],
                [
                    'data' => $data,
                    'expires_at' => $expiresAt
                ]
            );
        } catch (\Exception $e) {
            Log::warning('Failed to store widget cache in database', [
                'user_id' => $userId,
                'widget_type' => $widgetType,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Verificar saúde do cache
     */
    public function healthCheck()
    {
        try {
            $testKey = 'cache_health_check_' . time();
            $testValue = 'test_value';
            
            // Testar write
            $writeSuccess = $this->put($testKey, $testValue, 60);
            
            // Testar read
            $readValue = $this->get($testKey);
            $readSuccess = $readValue === $testValue;
            
            // Testar delete
            $deleteSuccess = $this->forget($testKey);
            
            return [
                'status' => $writeSuccess && $readSuccess && $deleteSuccess ? 'healthy' : 'unhealthy',
                'write' => $writeSuccess,
                'read' => $readSuccess,
                'delete' => $deleteSuccess,
                'timestamp' => Carbon::now()->toISOString()
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'unhealthy',
                'error' => $e->getMessage(),
                'timestamp' => Carbon::now()->toISOString()
            ];
        }
    }
}