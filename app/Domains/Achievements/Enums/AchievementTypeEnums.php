<?php

namespace App\Domains\Achievements\Enums;

enum AchievementTypeEnums: string
{
   case COMMUNITY_ENGAGEMENT = 'community_engagement';
   case AUTH_ACTIVITY = 'auth_activity';
   case E_STORE_PRODUCT = 'e_store_product';
   case E_STORE_PURCHASE = 'e_store_purchase';

   public function code(): string
   {
      return strtoupper($this->value);
   }

   public function label(): string
   {
      return match ($this) {
         self::COMMUNITY_ENGAGEMENT => 'Community Engagement',
         self::AUTH_ACTIVITY => 'Auth Activity, e.g., daily login, app start',
         self::E_STORE_PRODUCT => 'E-Store Product',
         self::E_STORE_PURCHASE => 'E-Store Purchase',
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

   public static function default(self $type = self::COMMUNITY_ENGAGEMENT): string
   {
      return $type->value;
   }
}
/*
   $table->string('type')->default(AchievementTypeEnums::COMMUNITY_ENGAGEMENT->value);
*/