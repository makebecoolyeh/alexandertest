<?php


namespace App\Services;

use DateTime;
use DateTimeZone;

class ExchangeRateService
{
    public static function getCCYNumber($array, $key) {
        /* ISO 4217 number-3
            USD = 840
            EUR = 978
            UAH = 980 */
        $ccy = $array[$key];
        if($ccy != null) {
            switch ($ccy) {
                case 'USD': return 840;
                case 'EUR': return 978;
                case 'UAH': return 980;
                default: return false;
            }
        }
        return false;
    }

    public static function getRemoteExchangeRates() {
        $url = 'https://api.privatbank.ua/p24api/pubinfo?json&exchange&coursid=5';
        $data = file_get_contents($url);
        $decodedData = json_decode($data, true);
        $exchangeRates = [];
        $dateTimeNow = new DateTime('now', new DateTimeZone('GMT+03:00'));
        foreach ($decodedData as $member) {
            if(self::getCCYNumber($member, 'ccy') === false) {
                continue;
            }
            else {
                $exchangeRates[] = [
                    'ccy' => self::getCCYNumber($member, 'ccy'),
                    'base_ccy' => self::getCCYNumber($member, 'base_ccy'),
                    'buy' => $member['buy'],
                    'sale' => $member['sale'],
                    'created_at' => $dateTimeNow->format('Y-m-d H:i:s')
                ];
            }
        }
        return $exchangeRates;
    }
}