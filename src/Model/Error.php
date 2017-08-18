<?php

declare(strict_types=1);

namespace oneofftech\KRegistryClient\Model;


final class Error extends Model
{
   public function getStatusCode(): string
   {
      return $this->data['status_code'] ?? '';
   }

   public function getMessage(): string
   {
       return $this->data['message'] ?? '';
   }

   protected static function getFields()
   {
       return ['status_code', 'message'];
   }

}