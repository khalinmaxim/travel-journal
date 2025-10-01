<?php
namespace App\Core;

final class Config {
    private static array $cfg = [];

    public static function init(array $cfg): void {
        self::$cfg = $cfg;
    }
    public static function get(string $key, $default=null) {
        $segments = explode('.', $key);
        $val = self::$cfg;
        foreach ($segments as $s) {
            if (!is_array($val) || !array_key_exists($s, $val)) return $default;
            $val = $val[$s];
        }
        return $val;
    }
}
