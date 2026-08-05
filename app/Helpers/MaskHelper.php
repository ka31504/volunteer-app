<?php

namespace App\Helpers;

class MaskHelper
{
    /**
     * Ẩn từng từ trong chuỗi: giữ 2 ký tự đầu, phần còn lại thay bằng *.
     * VD: "Nguyễn Văn A" -> "Ng** Vă* A"
     * Hỗ trợ UTF-8 (tiếng Việt có dấu) qua mb_* functions.
     */
    public static function maskWords(?string $text): ?string
    {
        if (blank($text)) {
            return $text;
        }

        $words = explode(' ', $text);

        $masked = array_map(function (string $word) {
            $length = mb_strlen($word);

            // Từ ngắn (<=2 ký tự) không đủ để ẩn, giữ nguyên
            if ($length <= 2) {
                return $word;
            }

            $visible = mb_substr($word, 0, 2);
            $hiddenLength = $length - 2;

            return $visible . str_repeat('*', $hiddenLength);
        }, $words);

        return implode(' ', $masked);
    }
}