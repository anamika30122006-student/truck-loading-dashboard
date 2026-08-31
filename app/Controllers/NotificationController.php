<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\NotificationModel;

class NotificationController extends Controller {
    private $notificationModel;

    public function __construct() {
        $this->notificationModel = new NotificationModel();
    }

    public function index() {
        $notifications = $this->notificationModel->getAll();
        $unreadCount = $this->notificationModel->getUnreadCount();

        $this->render('notifications/index', [
            'title' => 'Payment Notifications & Reminders - Logistics Pro',
            'activePage' => 'notifications',
            'notifications' => $notifications,
            'unreadCount' => $unreadCount
        ]);
    }

    public function markAsRead() {
        $id = (int)($_POST['id'] ?? 0);
        $this->notificationModel->markAsRead($id);
        return $this->redirect('/notifications', 'success', 'Notification marked as read.');
    }

    public function markAllAsRead() {
        $this->notificationModel->markAllAsRead();
        return $this->redirect('/notifications', 'success', 'All notifications marked as read.');
    }

    public function sendReminder() {
        $title = trim($_POST['title'] ?? 'Payment reminder');
        $amount = (float)($_POST['amount'] ?? 0);
        $target = trim($_POST['target'] ?? 'Client');

        $this->notificationModel->create([
            'title' => "Payment reminder sent to {$target}",
            'subtitle' => $title,
            'type' => 'payment_reminder',
            'status' => 'Pending',
            'amount' => $amount,
            'time_str' => 'Just now',
            'is_read' => false
        ]);

        return $this->redirect('/notifications', 'success', "Reminder sent successfully to {$target}!");
    }
}
