<?php

namespace App\Livewire;

use App\Models\Notification;
use App\Models\NotificationAction;
use App\Models\NotificationSetting;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;

class NotificationBell extends Component
{
    use WithPagination;
    
    public $unreadCount = 0;
    public $totalNotifications = 0;
    public $showDropdown = false;
    public $settings = [];
    public $selectedPriority = 'all';
    public $selectedType = 'all';
    public $showSettings = false;
    
    protected $paginationTheme = 'tailwind';

    protected $listeners = [
        'notificationCreated' => 'refreshNotifications',
        'notificationUpdated' => 'refreshNotifications',
    ];

    public function mount()
    {
        $this->loadNotifications();
        $this->loadSettings();
    }
    
    public function updatedSelectedPriority()
    {
        $this->resetPage();
        $this->loadNotifications();
    }
    
    public function updatedSelectedType()
    {
        $this->resetPage();
        $this->loadNotifications();
    }

    public function loadNotifications()
    {
        if (!Auth::check()) {
            return;
        }

        // Calcular total de notificações e não lidas
        $totalQuery = Notification::forUser(Auth::id())->notSnoozed();
        if ($this->selectedPriority !== 'all') {
            $totalQuery->where('priority', $this->selectedPriority);
        }
        if ($this->selectedType !== 'all') {
            $totalQuery->where('type', $this->selectedType);
        }
        $this->totalNotifications = $totalQuery->count();
        
        $this->unreadCount = Notification::forUser(Auth::id())
            ->unread()
            ->notSnoozed()
            ->count();
    }
    
    public function getNotificationsProperty()
    {
        if (!Auth::check()) {
            return collect();
        }

        $query = Notification::forUser(Auth::id())
            ->notSnoozed()
            ->with(['budget', 'actions'])
            ->orderByPriority()
            ->latest();

        // Aplicar filtros
        if ($this->selectedPriority !== 'all') {
            $query->where('priority', $this->selectedPriority);
        }

        if ($this->selectedType !== 'all') {
            $query->where('type', $this->selectedType);
        }

        return $query->paginate(10);
    }

    public function loadSettings()
    {
        if (!Auth::check()) {
            return;
        }

        $this->settings = NotificationSetting::getForUser(Auth::id())->toArray();
    }

    public function toggleDropdown()
    {
        $this->showDropdown = !$this->showDropdown;
        
        if ($this->showDropdown) {
            $this->loadNotifications();
        }
    }

    public function markAsRead($notificationId)
    {
        $notification = Notification::find($notificationId);
        
        if ($notification && $notification->user_id === Auth::id()) {
            $notification->markAsRead();
            NotificationAction::logMarkRead($notificationId);
            $this->loadNotifications();
            
            $this->dispatch('notificationRead', $notificationId);
        }
    }

    public function markAllAsRead()
    {
        $notifications = Notification::forUser(Auth::id())
            ->unread()
            ->notSnoozed()
            ->get();

        foreach ($notifications as $notification) {
            $notification->markAsRead();
            NotificationAction::logMarkRead($notification->id);
        }

        $this->loadNotifications();
        $this->dispatch('allNotificationsRead');
    }

    public function snoozeNotification($notificationId, $duration = '1 hour')
    {
        $notification = Notification::find($notificationId);
        
        if ($notification && $notification->user_id === Auth::id()) {
            $snoozeUntil = match($duration) {
                '15 minutes' => now()->addMinutes(15),
                '1 hour' => now()->addHour(),
                '4 hours' => now()->addHours(4),
                '1 day' => now()->addDay(),
                '1 week' => now()->addWeek(),
                default => now()->addHour()
            };
            
            $notification->snooze($snoozeUntil);
            NotificationAction::logSnooze($notificationId, $duration, $snoozeUntil);
            $this->loadNotifications();
            
            $this->dispatch('notificationSnoozed', $notificationId, $duration);
        }
    }

    public function openBudget($notificationId, $budgetId)
    {
        $notification = Notification::find($notificationId);
        
        if ($notification && $notification->user_id === Auth::id()) {
            // Marcar como lida se não estiver
            if (!$notification->is_read) {
                $notification->markAsRead();
                NotificationAction::logMarkRead($notificationId);
            }
            
            // Registrar ação de abertura
            NotificationAction::logOpenBudget($notificationId, $budgetId);
            
            $this->loadNotifications();
            
            // Redirecionar para o orçamento
            return redirect()->route('orcamentos.show', $budgetId);
        }
    }

    public function deleteNotification($notificationId)
    {
        $notification = Notification::find($notificationId);
        
        if ($notification && $notification->user_id === Auth::id()) {
            $notification->delete();
            $this->loadNotifications();
            
            $this->dispatch('notificationDeleted', $notificationId);
        }
    }

    public function updateFilters()
    {
        $this->resetPage();
        $this->loadNotifications();
    }

    public function toggleSettings()
    {
        $this->showSettings = !$this->showSettings;
        
        if ($this->showSettings) {
            $this->loadSettings();
        }
    }

    public function updateSettings($field, $value)
    {
        if (!Auth::check()) {
            return;
        }

        $setting = NotificationSetting::getForUser(Auth::id());
        $setting->update([$field => $value]);
        
        $this->loadSettings();
        $this->dispatch('settingsUpdated', $field, $value);
    }

    public function getNotificationIcon($type)
    {
        return match($type) {
            'budget_approved' => 'check-circle',
            'budget_rejected' => 'x-circle',
            'deadline_approaching' => 'clock',
            'viewed_no_action' => 'eye',
            default => 'bell'
        };
    }

    public function getNotificationColor($priority)
    {
        return match($priority) {
            'high' => 'text-red-500',
            'medium' => 'text-yellow-500',
            'low' => 'text-blue-500',
            default => 'text-gray-500'
        };
    }

    public function getPriorityBadgeClass($priority)
    {
        return match($priority) {
            'high' => 'bg-red-100 text-red-800',
            'medium' => 'bg-yellow-100 text-yellow-800',
            'low' => 'bg-blue-100 text-blue-800',
            default => 'bg-gray-100 text-gray-800'
        };
    }

    public function getTimeAgo($createdAt)
    {
        return \Carbon\Carbon::parse($createdAt)->diffForHumans();
    }

    #[On('notificationCreated')]
    public function refreshNotifications()
    {
        $this->loadNotifications();
    }

    public function render()
    {
        return view('livewire.notification-bell');
    }
}