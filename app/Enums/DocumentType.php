<?php

namespace App\Enums;

enum DocumentType: string
{
    case IDENTITY_CARD = 'IDENTITY CARD';
    case PASSPORT = 'PASSPORT';
    case RECEIPT = 'RECEIPT';
    case SIGNED_DOCUMENT = 'SIGNED DOCUMENT';
}
