<?php

namespace App\Helpers;

use App\Models\Order;

class MessageTemplateHelper
{
    public static function getReplacementsFromOrder(Order $order): array
    {
        $customer = $order->customer;

        return [
            '[name]'          => $customer?->name ?? '-',
            '[phone]'         => $customer?->phone ?? '-',
            '[order_number]'  => $order->order_number,
            '[services]'      => implode(', ', $order->services ?? []),
            '[deadline]'      => optional($order->deadline)->format('d M Y H:i'),
            '[status]'        => $order->status,
            '[priority]'      => $order->priority,
            '[special_notes]' => $order->special_notes ?? '-',
            '[created_at]'    => optional($order->created_at)->format('d M Y H:i'),
            '[estimate]'      => $order->estimate_time . ' jam',
        ];
    }

    public static function parseTemplate(string $content, Order $order): string
    {
        $replacements = self::getReplacementsFromOrder($order);

        return str_replace(array_keys($replacements), array_values($replacements), $content);
    }
}
