<?php

declare(strict_types=1);

namespace OneOffTech\KLinkRegistryClient\Model;


/*
 * Error is the Error Response of the API
 */
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
