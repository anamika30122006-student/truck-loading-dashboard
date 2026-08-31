<?php
namespace App\Core;

class Helper {
    public static function formatCurrency($amount, $preferCrore = true) {
        $amount = (float)$amount;
        
        // Format in Crore (Cr) if >= 1 Crore (1,00,00,000) or if preferCrore and >= 10 Lakh (10,00,000)
        if ($amount >= 10000000) {
            $cr = $amount / 10000000;
            return '₹' . number_format($cr, 2) . ' Cr';
        } elseif ($amount >= 100000 && $preferCrore) {
            $cr = $amount / 10000000;
            if ($cr >= 0.01) {
                return '₹' . number_format($cr, 2) . ' Cr';
            }
        }

        $formatted = number_format($amount, 2, '.', ',');
        $parts = explode('.', $formatted);
        $intPart = $parts[0];
        $decPart = isset($parts[1]) && $parts[1] !== '00' ? '.' . $parts[1] : '';
        
        $intPart = str_replace(',', '', $intPart);
        $len = strlen($intPart);
        if ($len > 3) {
            $lastThree = substr($intPart, $len - 3);
            $restUnits = substr($intPart, 0, $len - 3);
            $restUnits = preg_replace("/\B(?=(\d{2})+(?!\d))/", ",", $restUnits);
            return '₹' . $restUnits . ',' . $lastThree . $decPart;
        }
        return '₹' . $intPart . $decPart;
    }

    public static function formatExactCurrency($amount) {
        return self::formatCurrency($amount, false);
    }

    public static function formatDate($dateStr, $format = 'd M Y') {
        if (empty($dateStr)) return '-';
        $time = strtotime($dateStr);
        return $time ? date($format, $time) : $dateStr;
    }

    public static function timeAgo($datetime) {
        $time = strtotime($datetime);
        if (!$time) return $datetime;
        $diff = time() - $time;
        if ($diff < 60) return 'Just now';
        if ($diff < 3600) return round($diff / 60) . ' mins ago';
        if ($diff < 86400) return round($diff / 3600) . ' hours ago';
        if ($diff < 172800) return 'Yesterday';
        return date('d M Y', $time);
    }

    public static function getStatusBadge($status) {
        $statusLower = strtolower(trim($status));
        $classes = [
            'approved' => 'badge-success',
            'paid' => 'badge-success',
            'received' => 'badge-success',
            'loaded' => 'badge-success',
            'delivered' => 'badge-success',
            'in stock' => 'badge-success',
            'present' => 'badge-success',
            'active' => 'badge-success',
            
            'pending' => 'badge-warning',
            'loading' => 'badge-warning',
            'in-transit' => 'badge-info',
            'dispatched' => 'badge-info',
            'half day' => 'badge-warning',
            'on leave' => 'badge-warning',
            'low stock' => 'badge-warning',
            
            'rejected' => 'badge-danger',
            'overdue' => 'badge-danger',
            'out of stock' => 'badge-danger',
            'absent' => 'badge-danger',
            'cancelled' => 'badge-danger',
        ];

        $class = $classes[$statusLower] ?? 'badge-secondary';
        $icon = '';
        if (in_array($statusLower, ['approved', 'paid', 'received', 'loaded', 'delivered', 'in stock'])) {
            $icon = '<i class="ph ph-check-circle"></i> ';
        } elseif (in_array($statusLower, ['pending', 'loading', 'in-transit', 'dispatched'])) {
            $icon = '<i class="ph ph-clock"></i> ';
        } elseif (in_array($statusLower, ['rejected', 'overdue', 'out of stock', 'absent'])) {
            $icon = '<i class="ph ph-warning-circle"></i> ';
        }

        return "<span class=\"badge {$class}\">{$icon}" . htmlspecialchars(ucwords($status)) . "</span>";
    }

    public static function sanitize($data) {
        if (is_array($data)) {
            return array_map([self::class, 'sanitize'], $data);
        }
        return htmlspecialchars(trim((string)$data), ENT_QUOTES, 'UTF-8');
    }
}
