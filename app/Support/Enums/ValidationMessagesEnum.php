<?php

namespace App\Support\Enums;

enum ValidationMessagesEnum: string
{
    case REQUIRED = 'O campo :attribute é obrigatório.';
    case MAX_LENGTH = 'O campo :attribute não pode ter mais que 255 caracteres.';
    case EMAIL = 'O campo :attribute deve ser um endereço de e-mail válido.';
    case UNIQUE = 'O campo :attribute já está sendo utilizado.';
    case SAME = 'Os campos :attribute e :other devem corresponder.';
}
