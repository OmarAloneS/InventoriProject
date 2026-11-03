<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidarPasswordEstricto implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Longitud mínima de 8 caracteres
        if (strlen($value) < 8) {
            $fail('La contraseña debe tener al menos 8 caracteres.');
            return;
        }

        // Mínimo una mayúscula y una minúscula
        if (!preg_match('/[a-z]/', $value) || !preg_match('/[A-Z]/', $value)) {
            $fail('La contraseña debe contener al menos una letra mayúscula y una minúscula.');
            return;
        }

        // Mínimo un carácter especial
        if (!preg_match('/[^a-zA-Z0-9]/', $value)) {
            $fail('La contraseña debe contener al menos un carácter especial (ej. @, $, !, %, *, ?, &).');
            return;
        }


        // Convertimos a minúsculas para evaluar consecutividad de letras sin importar el "case"
        $lowerValue = strtolower($value);
        $longitud = strlen($value);

        for ($i = 0; $i < $longitud - 1; $i++) {
            $charActual = $value[$i];
            $charSiguiente = $value[$i + 1];

            $asciiActual = ord($charActual);
            $asciiSiguiente = ord($charSiguiente);

            // No permitir NÚMEROS consecutivos
            if (is_numeric($charActual) && is_numeric($charSiguiente)) {
                if (abs($asciiActual - $asciiSiguiente) === 1) {
                    $fail('No se permiten números consecutivos (ascendentes o descendentes).');
                    return;
                }
            }

            // No permitir LETRAS consecutivas en el abecedario 
            if (ctype_alpha($charActual) && ctype_alpha($charSiguiente)) {
                $lowerActual = ord($lowerValue[$i]);
                $lowerSiguiente = ord($lowerValue[$i + 1]);

                if (abs($lowerActual - $lowerSiguiente) === 1) {
                    $fail('No se permiten letras consecutivas en el abecedario (ascendentes o descendentes).');
                    return;
                }
            }
        }
    }
}