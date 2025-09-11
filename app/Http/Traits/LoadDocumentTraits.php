<?php
namespace App\Http\Traits;
use App\Http\Model\Document;
use App\Http\Model\AccSystems;

trait LoadDocumentTraits
{
      public function getId ($document_code)
    {
         $sys = AccSystems::get_systems($document_code);
         $doc = Document::get_code($sys->value);
         return $doc->id;
    }

}
