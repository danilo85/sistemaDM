<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\NotificationAction;
use App\Models\NotificationSetting;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class NotificationController extends Controller
{
    /**
     * Get notifications for the authenticated user.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json(['error' => 'Usuário não autenticado'], 401);
            }

            $query = Notification::forUser($user->id)
                ->notSnoozed()
                ->with(['budget', 'actions'])
                ->orderByPriority()
                ->latest();

            // Aplicar filtros se fornecidos
            if ($request->has('priority') && $request->priority !== 'all') {
                $query->where('priority', $request->priority);
            }

            if ($request->has('type') && $request->type !== 'all') {
                $query->where('type', $request->type);
            }

            if ($request->has('is_read')) {
                $query->where('is_read', $request->boolean('is_read'));
            }

            // Paginação
            $perPage = min($request->get('per_page', 20), 50); // Máximo 50 por página
            $notifications = $query->paginate($perPage);

            // Contar não lidas
            $unreadCount = Notification::forUser($user->id)
                ->notSnoozed()
                ->unread()
                ->count();

            return response()->json([
                'notifications' => $notifications->items(),
                'pagination' => [
                    'current_page' => $notifications->currentPage(),
                    'last_page' => $notifications->lastPage(),
                    'per_page' => $notifications->perPage(),
                    'total' => $notifications->total(),
                    'has_more_pages' => $notifications->hasMorePages()
                ],
                'unread_count' => $unreadCount,
                'filters' => [
                    'priority' => $request->get('priority', 'all'),
                    'type' => $request->get('type', 'all'),
                    'is_read' => $request->get('is_read')
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('NotificationController@index: Erro ao buscar notificações', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json(['error' => 'Erro interno do servidor'], 500);
        }
    }

    /**
     * Mark a notification as read.
     */
    public function markAsRead(Request $request, $id): JsonResponse
    {
        try {
            $notification = Notification::find($id);

            if (!$notification) {
                return response()->json(['error' => 'Notificação não encontrada'], 404);
            }

            if ($notification->user_id !== Auth::id()) {
                return response()->json(['error' => 'Acesso negado'], 403);
            }

            if (!$notification->is_read) {
                $notification->markAsRead();
                NotificationAction::logMarkRead($id);

                Log::info('Notificação marcada como lida via API', [
                    'notification_id' => $id,
                    'user_id' => Auth::id()
                ]);
            }

            return response()->json([
                'message' => 'Notificação marcada como lida',
                'notification' => $notification->fresh()
            ]);
        } catch (\Exception $e) {
            Log::error('NotificationController@markAsRead: Erro ao marcar como lida', [
                'notification_id' => $id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json(['error' => 'Erro interno do servidor'], 500);
        }
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            
            $updatedCount = Notification::forUser($user->id)
                ->unread()
                ->update(['is_read' => true, 'read_at' => now()]);

            // Log das ações
            if ($updatedCount > 0) {
                Log::info('Todas as notificações marcadas como lidas via API', [
                    'user_id' => $user->id,
                    'count' => $updatedCount
                ]);
            }

            return response()->json([
                'message' => "$updatedCount notificações marcadas como lidas",
                'count' => $updatedCount
            ]);
        } catch (\Exception $e) {
            Log::error('NotificationController@markAllAsRead: Erro ao marcar todas como lidas', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json(['error' => 'Erro interno do servidor'], 500);
        }
    }

    /**
     * Snooze a notification.
     */
    public function snooze(Request $request, $id): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'minutes' => 'required|integer|min:5|max:10080' // 5 minutos a 7 dias
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $notification = Notification::find($id);

            if (!$notification) {
                return response()->json(['error' => 'Notificação não encontrada'], 404);
            }

            if ($notification->user_id !== Auth::id()) {
                return response()->json(['error' => 'Acesso negado'], 403);
            }

            $snoozeUntil = now()->addMinutes($request->minutes);
            $notification->update(['snooze_until' => $snoozeUntil]);

            NotificationAction::logSnooze($id, $snoozeUntil);

            Log::info('Notificação adiada via API', [
                'notification_id' => $id,
                'user_id' => Auth::id(),
                'snooze_until' => $snoozeUntil->toISOString(),
                'minutes' => $request->minutes
            ]);

            return response()->json([
                'message' => 'Notificação adiada com sucesso',
                'notification' => $notification->fresh(),
                'snooze_until' => $snoozeUntil->toISOString()
            ]);
        } catch (\Exception $e) {
            Log::error('NotificationController@snooze: Erro ao adiar notificação', [
                'notification_id' => $id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json(['error' => 'Erro interno do servidor'], 500);
        }
    }

    /**
     * Delete a notification.
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        try {
            $notification = Notification::find($id);

            if (!$notification) {
                return response()->json(['error' => 'Notificação não encontrada'], 404);
            }

            if ($notification->user_id !== Auth::id()) {
                return response()->json(['error' => 'Acesso negado'], 403);
            }

            // Log antes de deletar
            Log::info('Notificação excluída via API', [
                'notification_id' => $id,
                'user_id' => Auth::id(),
                'type' => $notification->type,
                'budget_id' => $notification->budget_id
            ]);

            $notification->delete();

            return response()->json([
                'message' => 'Notificação excluída com sucesso'
            ]);
        } catch (\Exception $e) {
            Log::error('NotificationController@destroy: Erro ao excluir notificação', [
                'notification_id' => $id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json(['error' => 'Erro interno do servidor'], 500);
        }
    }

    /**
     * Get notification settings for the authenticated user.
     */
    public function getSettings(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            $settings = NotificationSetting::getForUser($user->id);

            return response()->json([
                'settings' => $settings
            ]);
        } catch (\Exception $e) {
            Log::error('NotificationController@getSettings: Erro ao buscar configurações', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json(['error' => 'Erro interno do servidor'], 500);
        }
    }

    /**
     * Update notification settings for the authenticated user.
     */
    public function updateSettings(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'enabled_types' => 'required|array',
                'enabled_types.*' => 'string|in:budget_approved,budget_rejected,deadline_approaching,viewed_no_action',
                'email_notifications' => 'boolean',
                'push_notifications' => 'boolean',
                'deadline_days_before' => 'integer|min:1|max:30',
                'days_after_view' => 'integer|min:1|max:30'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $user = Auth::user();
            $settings = NotificationSetting::updateForUser($user->id, $request->all());

            Log::info('Configurações de notificação atualizadas via API', [
                'user_id' => $user->id,
                'settings' => $request->all()
            ]);

            return response()->json([
                'message' => 'Configurações atualizadas com sucesso',
                'settings' => $settings
            ]);
        } catch (\Exception $e) {
            Log::error('NotificationController@updateSettings: Erro ao atualizar configurações', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json(['error' => 'Erro interno do servidor'], 500);
        }
    }

    /**
     * Get notification statistics.
     */
    public function getStats(Request $request): JsonResponse
    {
        try {
            $user = Auth::user();
            
            $stats = [
                'total' => Notification::forUser($user->id)->count(),
            'unread' => Notification::forUser($user->id)->unread()->count(),
            'by_priority' => Notification::forUser($user->id)
                    ->selectRaw('priority, COUNT(*) as count')
                    ->groupBy('priority')
                    ->pluck('count', 'priority')
                    ->toArray(),
                'by_type' => Notification::forUser($user->id)
                    ->selectRaw('type, COUNT(*) as count')
                    ->groupBy('type')
                    ->pluck('count', 'type')
                    ->toArray(),
                'recent' => Notification::forUser($user->id)
                    ->where('created_at', '>=', now()->subDays(7))
                    ->count(),
                'snoozed' => Notification::forUser($user->id)
                    ->whereNotNull('snooze_until')
                    ->where('snooze_until', '>', now())
                    ->count()
            ];

            return response()->json(['stats' => $stats]);
        } catch (\Exception $e) {
            Log::error('NotificationController@getStats: Erro ao buscar estatísticas', [
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json(['error' => 'Erro interno do servidor'], 500);
        }
    }

    /**
     * Get notification actions history.
     */
    public function getActions(Request $request, $id): JsonResponse
    {
        try {
            $notification = Notification::find($id);

            if (!$notification) {
                return response()->json(['error' => 'Notificação não encontrada'], 404);
            }

            if ($notification->user_id !== Auth::id()) {
                return response()->json(['error' => 'Acesso negado'], 403);
            }

            $actions = NotificationAction::where('notification_id', $id)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'actions' => $actions
            ]);
        } catch (\Exception $e) {
            Log::error('NotificationController@getActions: Erro ao buscar ações', [
                'notification_id' => $id,
                'user_id' => Auth::id(),
                'error' => $e->getMessage()
            ]);

            return response()->json(['error' => 'Erro interno do servidor'], 500);
        }
    }
}