<?php
namespace App\Repositories;

use App\Interfaces\NotificationRepositoryInterface;
use App\Models\Notification;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Client\RequestException;

class NotificationRepository
{
    public function getNotificationByUser($UserId)
    {
        return Notification::JOIN('users', 'users.id', '=', 'notifications.user_id')
                            ->where('notifications.user_id', '=', $UserId)
                            ->where('notifications.status', false)
                            ->orderBy('notifications.id', 'DESC')
                            ->get();
    }

    public function createNotification(array $NotificationDetails)
    {
        return Notification::create($NotificationDetails);
    }

    public function updateNotification($UserId, array $NotificationDetails)
    {
        return Notification::whereId($UserId)->update($NotificationDetails);
    }

    public function toggleNotification($UserId, $toggleId)
    {
        return Notification::whereId($UserId)->update($toggleId);
    }
    public function deleteNotificationRecord($NotificationRef)
    {
        Notification::destroy($NotificationRef);
    }

}