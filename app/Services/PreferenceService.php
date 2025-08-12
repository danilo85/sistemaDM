<?php

namespace App\Services;

use App\Models\DashboardConfig;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class PreferenceService
{
    /**
     * Cache TTL em minutos
     */
    const CACHE_TTL = 60;
    
    /**
     * Configurações padrão do dashboard
     */
    const DEFAULT_CONFIG = [
        'layout' => [
            'columns' => 4,
            'gap' => 16,
            'padding' => 24,
            'responsive' => [
                'desktop' => 4,
                'tablet' => 2,
                'mobile' => 1
            ]
        ],
        'theme' => [
            'mode' => 'auto', // auto, light, dark
            'primary_color' => '#3b82f6',
            'accent_color' => '#10b981'
        ],
        'animations' => [
            'enabled' => true,
            'duration' => 300,
            'easing' => 'ease-in-out',
            'respect_reduced_motion' => true
        ],
        'auto_save' => [
            'enabled' => true,
            'interval' => 30 // segundos
        ],
        'notifications' => [
            'toast_position' => 'top-right',
            'toast_duration' => 5000,
            'sound_enabled' => false
        ],
        'widgets' => [
            'default_size' => 'M',
            'auto_refresh' => true,
            'refresh_interval' => 300, // segundos
            'show_loading_skeletons' => true
        ],
        'accessibility' => [
            'high_contrast' => false,
            'large_text' => false,
            'keyboard_navigation' => true,
            'screen_reader_support' => true
        ],
        'performance' => [
            'lazy_loading' => true,
            'cache_enabled' => true,
            'preload_widgets' => false
        ]
    ];
    
    /**
     * Obter configuração do usuário
     */
    public function getUserConfig($userId = null)
    {
        $userId = $userId ?? Auth::id();
        $cacheKey = "user_dashboard_config_{$userId}";
        
        return Cache::remember($cacheKey, self::CACHE_TTL * 60, function () use ($userId) {
            $config = DashboardConfig::where('user_id', $userId)->first();
            
            if (!$config) {
                return self::DEFAULT_CONFIG;
            }
            
            // Mesclar com configurações padrão para garantir que todas as chaves existam
            return array_merge_recursive(self::DEFAULT_CONFIG, $config->config ?? []);
        });
    }
    
    /**
     * Salvar configuração do usuário
     */
    public function saveUserConfig($configData, $userId = null)
    {
        $userId = $userId ?? Auth::id();
        
        // Validar configuração
        $validatedConfig = $this->validateConfig($configData);
        
        // Buscar ou criar configuração
        $config = DashboardConfig::firstOrNew(['user_id' => $userId]);
        
        // Mesclar com configuração existente
        $currentConfig = $config->config ?? self::DEFAULT_CONFIG;
        $newConfig = array_merge_recursive($currentConfig, $validatedConfig);
        
        $config->config = $newConfig;
        $config->save();
        
        // Limpar cache
        $this->clearUserConfigCache($userId);
        
        return $newConfig;
    }
    
    /**
     * Resetar configuração para padrão
     */
    public function resetUserConfig($userId = null)
    {
        $userId = $userId ?? Auth::id();
        
        $config = DashboardConfig::where('user_id', $userId)->first();
        
        if ($config) {
            $config->config = self::DEFAULT_CONFIG;
            $config->save();
        } else {
            DashboardConfig::create([
                'user_id' => $userId,
                'config' => self::DEFAULT_CONFIG
            ]);
        }
        
        // Limpar cache
        $this->clearUserConfigCache($userId);
        
        return self::DEFAULT_CONFIG;
    }
    
    /**
     * Obter preferência específica
     */
    public function getPreference($key, $userId = null, $default = null)
    {
        $config = $this->getUserConfig($userId);
        
        return data_get($config, $key, $default);
    }
    
    /**
     * Definir preferência específica
     */
    public function setPreference($key, $value, $userId = null)
    {
        $userId = $userId ?? Auth::id();
        
        $config = $this->getUserConfig($userId);
        data_set($config, $key, $value);
        
        return $this->saveUserConfig($config, $userId);
    }
    
    /**
     * Obter configurações de layout
     */
    public function getLayoutConfig($userId = null)
    {
        return $this->getPreference('layout', $userId, self::DEFAULT_CONFIG['layout']);
    }
    
    /**
     * Salvar configurações de layout
     */
    public function saveLayoutConfig($layoutConfig, $userId = null)
    {
        return $this->setPreference('layout', $layoutConfig, $userId);
    }
    
    /**
     * Obter configurações de tema
     */
    public function getThemeConfig($userId = null)
    {
        return $this->getPreference('theme', $userId, self::DEFAULT_CONFIG['theme']);
    }
    
    /**
     * Salvar configurações de tema
     */
    public function saveThemeConfig($themeConfig, $userId = null)
    {
        return $this->setPreference('theme', $themeConfig, $userId);
    }
    
    /**
     * Obter configurações de animação
     */
    public function getAnimationConfig($userId = null)
    {
        return $this->getPreference('animations', $userId, self::DEFAULT_CONFIG['animations']);
    }
    
    /**
     * Verificar se animações estão habilitadas
     */
    public function areAnimationsEnabled($userId = null)
    {
        $animationConfig = $this->getAnimationConfig($userId);
        return $animationConfig['enabled'] ?? true;
    }
    
    /**
     * Obter configurações de acessibilidade
     */
    public function getAccessibilityConfig($userId = null)
    {
        return $this->getPreference('accessibility', $userId, self::DEFAULT_CONFIG['accessibility']);
    }
    
    /**
     * Salvar configurações de acessibilidade
     */
    public function saveAccessibilityConfig($accessibilityConfig, $userId = null)
    {
        return $this->setPreference('accessibility', $accessibilityConfig, $userId);
    }
    
    /**
     * Obter configurações de performance
     */
    public function getPerformanceConfig($userId = null)
    {
        return $this->getPreference('performance', $userId, self::DEFAULT_CONFIG['performance']);
    }
    
    /**
     * Verificar se cache está habilitado
     */
    public function isCacheEnabled($userId = null)
    {
        $performanceConfig = $this->getPerformanceConfig($userId);
        return $performanceConfig['cache_enabled'] ?? true;
    }
    
    /**
     * Obter configurações de widgets
     */
    public function getWidgetConfig($userId = null)
    {
        return $this->getPreference('widgets', $userId, self::DEFAULT_CONFIG['widgets']);
    }
    
    /**
     * Obter intervalo de atualização automática
     */
    public function getAutoRefreshInterval($userId = null)
    {
        $widgetConfig = $this->getWidgetConfig($userId);
        return $widgetConfig['refresh_interval'] ?? 300;
    }
    
    /**
     * Verificar se auto-refresh está habilitado
     */
    public function isAutoRefreshEnabled($userId = null)
    {
        $widgetConfig = $this->getWidgetConfig($userId);
        return $widgetConfig['auto_refresh'] ?? true;
    }
    
    /**
     * Obter configurações de notificação
     */
    public function getNotificationConfig($userId = null)
    {
        return $this->getPreference('notifications', $userId, self::DEFAULT_CONFIG['notifications']);
    }
    
    /**
     * Exportar configurações do usuário
     */
    public function exportUserConfig($userId = null)
    {
        $userId = $userId ?? Auth::id();
        $config = $this->getUserConfig($userId);
        
        return [
            'user_id' => $userId,
            'exported_at' => Carbon::now()->toISOString(),
            'version' => '1.0',
            'config' => $config
        ];
    }
    
    /**
     * Importar configurações do usuário
     */
    public function importUserConfig($configData, $userId = null)
    {
        $userId = $userId ?? Auth::id();
        
        // Validar estrutura do arquivo de importação
        if (!isset($configData['config']) || !is_array($configData['config'])) {
            throw new \InvalidArgumentException('Arquivo de configuração inválido');
        }
        
        // Validar e salvar configuração
        return $this->saveUserConfig($configData['config'], $userId);
    }
    
    /**
     * Validar configuração
     */
    protected function validateConfig($config)
    {
        $validated = [];
        
        // Validar layout
        if (isset($config['layout'])) {
            $layout = $config['layout'];
            $validated['layout'] = [
                'columns' => $this->validateRange($layout['columns'] ?? 4, 1, 6),
                'gap' => $this->validateRange($layout['gap'] ?? 16, 0, 32),
                'padding' => $this->validateRange($layout['padding'] ?? 24, 0, 48)
            ];
            
            if (isset($layout['responsive'])) {
                $validated['layout']['responsive'] = [
                    'desktop' => $this->validateRange($layout['responsive']['desktop'] ?? 4, 1, 6),
                    'tablet' => $this->validateRange($layout['responsive']['tablet'] ?? 2, 1, 4),
                    'mobile' => $this->validateRange($layout['responsive']['mobile'] ?? 1, 1, 2)
                ];
            }
        }
        
        // Validar tema
        if (isset($config['theme'])) {
            $theme = $config['theme'];
            $validated['theme'] = [
                'mode' => in_array($theme['mode'] ?? 'auto', ['auto', 'light', 'dark']) ? $theme['mode'] : 'auto',
                'primary_color' => $this->validateColor($theme['primary_color'] ?? '#3b82f6'),
                'accent_color' => $this->validateColor($theme['accent_color'] ?? '#10b981')
            ];
        }
        
        // Validar animações
        if (isset($config['animations'])) {
            $animations = $config['animations'];
            $validated['animations'] = [
                'enabled' => (bool) ($animations['enabled'] ?? true),
                'duration' => $this->validateRange($animations['duration'] ?? 300, 100, 1000),
                'easing' => in_array($animations['easing'] ?? 'ease-in-out', ['ease', 'ease-in', 'ease-out', 'ease-in-out']) ? $animations['easing'] : 'ease-in-out',
                'respect_reduced_motion' => (bool) ($animations['respect_reduced_motion'] ?? true)
            ];
        }
        
        // Validar auto-save
        if (isset($config['auto_save'])) {
            $autoSave = $config['auto_save'];
            $validated['auto_save'] = [
                'enabled' => (bool) ($autoSave['enabled'] ?? true),
                'interval' => $this->validateRange($autoSave['interval'] ?? 30, 10, 300)
            ];
        }
        
        // Validar notificações
        if (isset($config['notifications'])) {
            $notifications = $config['notifications'];
            $validated['notifications'] = [
                'toast_position' => in_array($notifications['toast_position'] ?? 'top-right', ['top-left', 'top-right', 'bottom-left', 'bottom-right']) ? $notifications['toast_position'] : 'top-right',
                'toast_duration' => $this->validateRange($notifications['toast_duration'] ?? 5000, 1000, 10000),
                'sound_enabled' => (bool) ($notifications['sound_enabled'] ?? false)
            ];
        }
        
        // Validar widgets
        if (isset($config['widgets'])) {
            $widgets = $config['widgets'];
            $validated['widgets'] = [
                'default_size' => in_array($widgets['default_size'] ?? 'M', ['S', 'M', 'L']) ? $widgets['default_size'] : 'M',
                'auto_refresh' => (bool) ($widgets['auto_refresh'] ?? true),
                'refresh_interval' => $this->validateRange($widgets['refresh_interval'] ?? 300, 60, 3600),
                'show_loading_skeletons' => (bool) ($widgets['show_loading_skeletons'] ?? true)
            ];
        }
        
        // Validar acessibilidade
        if (isset($config['accessibility'])) {
            $accessibility = $config['accessibility'];
            $validated['accessibility'] = [
                'high_contrast' => (bool) ($accessibility['high_contrast'] ?? false),
                'large_text' => (bool) ($accessibility['large_text'] ?? false),
                'keyboard_navigation' => (bool) ($accessibility['keyboard_navigation'] ?? true),
                'screen_reader_support' => (bool) ($accessibility['screen_reader_support'] ?? true)
            ];
        }
        
        // Validar performance
        if (isset($config['performance'])) {
            $performance = $config['performance'];
            $validated['performance'] = [
                'lazy_loading' => (bool) ($performance['lazy_loading'] ?? true),
                'cache_enabled' => (bool) ($performance['cache_enabled'] ?? true),
                'preload_widgets' => (bool) ($performance['preload_widgets'] ?? false)
            ];
        }
        
        return $validated;
    }
    
    /**
     * Validar valor dentro de um range
     */
    protected function validateRange($value, $min, $max)
    {
        return max($min, min($max, (int) $value));
    }
    
    /**
     * Validar cor hexadecimal
     */
    protected function validateColor($color)
    {
        if (preg_match('/^#[a-f0-9]{6}$/i', $color)) {
            return $color;
        }
        return '#3b82f6'; // Cor padrão
    }
    
    /**
     * Limpar cache de configuração do usuário
     */
    protected function clearUserConfigCache($userId)
    {
        $cacheKey = "user_dashboard_config_{$userId}";
        Cache::forget($cacheKey);
    }
}