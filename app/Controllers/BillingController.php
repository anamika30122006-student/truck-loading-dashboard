<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\BillingModel;
use App\Models\NotificationModel;
use App\Models\ActivityModel;

class BillingController extends Controller {
    private $billingModel;
    private $notificationModel;
    private $activityModel;

    public function __construct() {
        $this->billingModel = new BillingModel();
        $this->notificationModel = new NotificationModel();
        $this->activityModel = new ActivityModel();
    }

    public function index() {
        $invoices = $this->billingModel->getAll();
        $stats = $this->billingModel->getStats();

        $this->render('billing/index', [
            'title' => 'Billing & Invoicing - Logistics Pro',
            'activePage' => 'billing',
            'invoices' => $invoices,
            'stats' => $stats
        ]);
    }

    public function store() {
        $custName = trim($_POST['customer_name'] ?? '');
        $custEmail = trim($_POST['customer_email'] ?? '');
        $custGst = trim($_POST['customer_gst'] ?? '');
        $invDate = $_POST['invoice_date'] ?? date('Y-m-d');
        $dueDate = $_POST['due_date'] ?? date('Y-m-d', strtotime('+15 days'));
        $paymentMode = $_POST['payment_mode'] ?? 'NEFT / RTGS';

        $descriptions = $_POST['item_desc'] ?? [];
        $quantities = $_POST['item_qty'] ?? [];
        $rates = $_POST['item_rate'] ?? [];

        $items = [];
        $subtotal = 0;

        foreach ($descriptions as $idx => $desc) {
            $desc = trim($desc);
            if (!empty($desc)) {
                $qty = (float)($quantities[$idx] ?? 1);
                $rate = (float)($rates[$idx] ?? 0);
                $amount = $qty * $rate;
                $subtotal += $amount;
                $items[] = [
                    'description' => $desc,
                    'qty' => $qty,
                    'rate' => $rate,
                    'amount' => $amount
                ];
            }
        }

        if (empty($items)) {
            return $this->redirect('/billing', 'error', 'Please add at least one line item.');
        }

        $taxRate = (float)($_POST['tax_rate_pct'] ?? 18);
        $taxAmount = ($subtotal * $taxRate) / 100;
        $discount = (float)($_POST['discount'] ?? 0);
        $totalAmount = ($subtotal + $taxAmount) - $discount;

        $invoiceNo = 'INV-' . rand(100, 999);

        $newInvoice = $this->billingModel->create([
            'invoice_no' => $invoiceNo,
            'customer_name' => $custName,
            'customer_email' => $custEmail,
            'customer_gst' => $custGst,
            'invoice_date' => $invDate,
            'due_date' => $dueDate,
            'items' => $items,
            'subtotal' => $subtotal,
            'tax_rate_pct' => $taxRate,
            'tax_amount' => $taxAmount,
            'discount' => $discount,
            'total_amount' => $totalAmount,
            'status' => 'Pending',
            'payment_mode' => $paymentMode
        ]);

        // Add payment reminder notification
        $this->notificationModel->create([
            'title' => "₹" . number_format($totalAmount, 2) . " payment pending from {$custName}",
            'subtitle' => "Invoice {$invoiceNo}",
            'type' => 'payment_pending',
            'status' => 'Pending',
            'amount' => $totalAmount,
            'time_str' => 'Just now',
            'is_read' => false
        ]);

        $this->activityModel->log('invoice', "Invoice {$invoiceNo} generated", "Amount: ₹" . number_format($totalAmount, 2), 'ph-file-text', 'color-green');

        return $this->redirect('/billing', 'success', "Invoice {$invoiceNo} created successfully!");
    }

    public function markPaid() {
        $id = (int)($_POST['id'] ?? 0);
        $invoice = $this->billingModel->getById($id);
        if (!$invoice) {
            return $this->redirect('/billing', 'error', 'Invoice not found.');
        }

        $this->billingModel->update($id, [
            'status' => 'Paid',
            'paid_at' => date('Y-m-d H:i:s')
        ]);

        // Notify payment received
        $this->notificationModel->create([
            'title' => "₹" . number_format($invoice['total_amount'], 2) . " received from {$invoice['customer_name']}",
            'subtitle' => "Invoice {$invoice['invoice_no']}",
            'type' => 'payment_received',
            'status' => 'Received',
            'amount' => $invoice['total_amount'],
            'time_str' => 'Just now',
            'is_read' => false
        ]);

        $this->activityModel->log('payment', "Payment received from {$invoice['customer_name']}", "Amount: ₹" . number_format($invoice['total_amount'], 2), 'ph-check-circle', 'color-green');

        return $this->redirect('/billing', 'success', "Invoice {$invoice['invoice_no']} marked as Paid!");
    }

    public function viewInvoice($id) {
        $invoice = $this->billingModel->getById($id);
        if (!$invoice) {
            return $this->redirect('/billing', 'error', 'Invoice not found.');
        }

        return $this->json($invoice);
    }
}
