<?php

namespace App\Helpers\Ui;

use Illuminate\Support\Facades\Storage;

class GetUrlSafeFiles
{
   public static function alignAvatar($USER, $fillWithDummy = false)
   {
      // $userAvatar = null;
      $userAvatar = $fillWithDummy ? asset('site_assets/dummies/dummy_man.webp') : null;

      if ($USER?->avatar) {
         $userAvatar = self::makeAbsolute(Storage::url($USER->avatar));
      }

      return $userAvatar;
   }

   public static function alignFile($file, $fillWithDummy = false)
   {
      $getFile = $fillWithDummy ? asset('site_assets/dummies/dummy_no_file.jpg') : null;

      if ($file) {
         $getFile = self::makeAbsolute(Storage::url($file));
      }
      return $getFile;
   }

   private static function makeAbsolute(string $url): string
   {
      return str_starts_with($url, 'http://') || str_starts_with($url, 'https://') ? $url : url($url);
   }
}

/*
USE CASE:
   $userAvatar = \App\Helpers\Ui\GetUrlSafeFiles::alignAvatar($user, false);
   $file = \App\Helpers\Ui\GetUrlSafeFiles::alignFile($project->file, true);
*/