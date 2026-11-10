<?php

namespace App\Domains\Billings\Shared\Enums;

enum CurrencyEnums: string
{
   case AUD = 'aud';
   case USD = 'usd';
   case NZD = 'nzd';
   case GBP = 'gbp';
   case EUR = 'eur';
   case CAD = 'cad';
   case CHF = 'chf';
   case SEK = 'sek';
   case NOK = 'nok';
   case DKK = 'dkk';

   public function code(): string
   {
      return strtoupper($this->value);
   }

   public function label(): string
   {
      return match ($this) {
         self::AUD => 'AUD - Australian Dollar',
         self::USD => 'USD - United States Dollar',
         self::NZD => 'NZD - New Zealand Dollar',
         self::GBP => 'GBP - Great Britain Pound',
         self::EUR => 'EUR - Euro',
         self::CAD => 'CAD - Canadian Dollar',
         self::CHF => 'CHF - Swiss Franc',
         self::SEK => 'SEK - Swedish Krona',
         self::NOK => 'NOK - Norwegian Krone',
         self::DKK => 'DKK - Danish Krone',
      };
   }

   public static function values(): array
   {
      return array_map(fn($case) => $case->value, self::cases());
   }

   public static function options(): array
   {
      return array_map(fn($case) => ['value' => $case->value, 'label' => $case->label()], self::cases());
   }

   public static function default(self $currency = self::USD): string
   {
      return $currency->value;
   }
}
/*
   USE CASE-1: value:
      $table->string('currency', 3)->default(CurrencyEnums::USD->value);

   USE CASE-2: dropdown:
      $currencies = CurrencyEnums::options();

   USE CASE-3: code only:
      CurrencyEnums::USD->code()
*/