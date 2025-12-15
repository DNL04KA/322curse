<?php

namespace App\Helpers;

class PhoneHelper
{
    /**
     * Форматирует номер телефона в стандартный вид: +375(29) 123-45-67
     *
     * @param string $countryCode Код страны (например, '+375')
     * @param string $phone Номер телефона (может содержать форматирование)
     * @return string Отформатированный номер телефона
     */
    public static function format(string $countryCode, string $phone): string
    {
        // Убираем все символы кроме цифр
        $digits = preg_replace('/\D/', '', $phone);
        
        // Если номер пустой, возвращаем как есть
        if (empty($digits)) {
            return $countryCode . ' ' . $phone;
        }
        
        // Для Беларуси (+375)
        if ($countryCode === '+375' && strlen($digits) === 9) {
            // Форматируем как +375(29) 123-45-67
            $operator = substr($digits, 0, 2);
            $part1 = substr($digits, 2, 3);
            $part2 = substr($digits, 5, 2);
            $part3 = substr($digits, 7, 2);
            
            return "{$countryCode}({$operator}) {$part1}-{$part2}-{$part3}";
        }
        
        // Для других стран или нестандартных форматов
        // Просто объединяем код страны и номер
        return $countryCode . ' ' . $digits;
    }
    
    /**
     * Извлекает чистые цифры из номера телефона
     *
     * @param string $phone Номер телефона с форматированием
     * @return string Только цифры
     */
    public static function parse(string $phone): string
    {
        return preg_replace('/\D/', '', $phone);
    }
}

