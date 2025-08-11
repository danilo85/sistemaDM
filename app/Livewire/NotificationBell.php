<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Notifications\DatabaseNotificationCollection;

class NotificationBell extends Component
{
    // Ouve o evento disparado quando uma nova notificação é criada
    protected $listeners = ['new-alert-sent' => '$refresh'];

    public function getUnreadNotificationsProperty(): DatabaseNotificationCollection
    {
        return Auth::user()->unreadNotifications;
    }

    public function getNotificationsProperty(): Collection
    {
        return Auth::user()->notifications()->latest()->take(10)->get();
    }

    public function markAllAsRead(): void
    {
        Auth::user()->unreadNotifications->markAsRead();
    }

    public function deleteNotification($notificationId): void
    {
        Auth::user()->notifications()->findOrFail($notificationId)->delete();
    }

    public function render(): View
    {
        return view('livewire.notification-bell');
    }
}
