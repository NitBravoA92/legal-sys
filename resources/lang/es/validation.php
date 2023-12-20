<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => 'El :attribute debe ser aceptado.',
    'accepted_if' => 'El :attribute debe ser aceptado cuando :other es :value.',
    'active_url' => 'El :attribute no es una URL valida.',
    'after' => 'El :attribute debe ser una fecha posterior al :date.',
    'after_or_equal' => 'El :attribute debe ser una fecha posterior o igual al :date.',
    'alpha' => 'El :attribute solo debe contener letras.',
    'alpha_dash' => 'El :attribute solo debe contener letras, numeros, dashes y underscores.', //this must be updated later
    'alpha_num' => 'El :attribute solo debe contener letras y numeros.',
    'array' => 'El :attribute debe ser un array.',
    'before' => 'El :attribute debe ser una fecha anterior al :date.',
    'before_or_equal' => 'El :attribute debe ser una fecha anterior o igual al :date.',
    'between' => [
        'numeric' => 'El :attribute debe estar entre :min y :max.',
        'file' => 'El :attribute debe pesar entre :min y :max kilobytes.',
        'string' => 'El :attribute debe tener entre :min y :max caracteres.',
        'array' => 'El :attribute debe tener entre :min y :max elementos.',
    ],
    'boolean' => 'El campo :attribute debe ser verdadero o falso.',
    'confirmed' => 'La confirmación de :attribute no coincide.',
    'current_password' => 'La contraseña es incorrecta.',
    'date' => ':attribute no es una fecha valida.',
    'date_equals' => ':attribute debe ser una fecha igual a :date.',
    'date_format' => ':attribute no coincide con el formato :format.',
    'declined' => 'El :attribute debe ser declinado.',
    'declined_if' => 'El :attribute debe ser declinado cuando :other es :value.',
    'different' => ':attribute y :other deben ser diferentes.',
    'digits' => ':attribute debe tener :digits digitos.',
    'digits_between' => ':attribute debe tener entre :min y :max digitos.',
    'dimensions' => ':attribute tiene unas dimensiones de imagen invalidas.',
    'distinct' => 'El campo :attribute tiene un valor duplicado.',
    'email' => ':attribute debe ser un correo electronico valido.',
    'ends_with' => ':attribute debe terminar con uno de los siguientes valores: :values.',
    'exists' => 'El :attribute seleccionado es invalido.',
    'file' => ':attribute debe ser un archivo.',
    'filled' => 'El campo :attribute no debe estar vacio.',
    'gt' => [
        'numeric' => ':attribute debe ser mayor que :value.',
        'file' => ':attribute debe ser mayor a :value kilobytes.',
        'string' => ':attribute debe ser mayor a :value caracteres.',
        'array' => ':attribute debe tener más de :value elementos.',
    ],
    'gte' => [
        'numeric' => ':attribute debe ser mayor o igual que :value.',
        'file' => ':attribute debe ser mayor o igual a :value kilobytes.',
        'string' => ':attribute debe ser mayor o igual a :value caracteres.',
        'array' => ':attribute debe tener :value o más elementos.',
    ],
    'image' => ':attribute debe ser una imagen.',
    'in' => 'El :attribute seleccionado es invalido.',
    'in_array' => 'El campo :attribute no debe existir en :other.',
    'integer' => ':attribute debe ser un numero entero.',
    'ip' => ':attribute debe ser una dirección IP valida.',
    'ipv4' => ':attribute debe ser una dirección IPv4 valida.',
    'ipv6' => ':attribute debe ser una dirección IPv6 valida.',
    'json' => ':attribute debe ser una cadena JSON valida.',
    'lt' => [
        'numeric' => ':attribute debe ser menor que :value.',
        'file' => ':attribute debe ser menor a :value kilobytes.',
        'string' => ':attribute debe ser menor a :value caracteres.',
        'array' => ':attribute debe tener menos de :value elementos.',
    ],
    'lte' => [
        'numeric' => ':attribute debe ser menor o igual que :value.',
        'file' => ':attribute debe ser menor o igual a :value kilobytes.',
        'string' => ':attribute debe ser menor o igual a :value caracteres.',
        'array' => ':attribute debe tener :value o menos elementos.',
    ],
    'max' => [
        'numeric' => ':attribute no debe ser mayor que :max.',
        'file' => ':attribute no debe ser mayor a :max kilobytes.',
        'string' => ':attribute no debe tener más de :max caracteres.',
        'array' => ':attribute no debe tener más de :max elementos.',
    ],
    'mimes' => ':attribute debe ser un archivo de tipo: :values.',
    'mimetypes' => ':attribute debe ser un archivo de tipo: :values.',
    'min' => [
        'numeric' => ':attribute debe ser por lo menos :min.',
        'file' => ':attribute debe ser de al menos :min kilobytes.',
        'string' => ':attribute debe tener por lo menos :min caracteres.',
        'array' => ':attribute debe tener por lo menos :min elementos.',
    ],
    'multiple_of' => ':attribute debe ser un multiplo de :value.',
    'not_in' => 'El elemento :attribute seleccionado es invalido.',
    'not_regex' => 'El formato de :attribute es invalido.',
    'numeric' => ':attribute debe ser un numero.',
    'password' => 'La contraseña es Incorrecta.',
    'present' => 'El campo :attribute debe estar presente.',
    'regex' => 'El formato de :attribute es invalido.',
    'required' => 'El campo :attribute debe ser llenado obligatoriamente.',
    'required_if' => 'El campo :attribute es obligatorio cuando :other es :value.',
    'required_unless' => 'El campo :attribute es obligatorio hasta que :other esta en :values.',
    'required_with' => 'El campo :attribute es obligatorio cuando :values esta presente',
    'required_with_all' => 'El campo :attribute es obligatorio cuando :values estan presentes.',
    'required_without' => 'El campo :attribute es obligatorio cuando :values no esta presente.',
    'required_without_all' => 'El campo :attribute es obligatorio cuando ninguno de los valores :values estan presentes.',
    'prohibited' => 'El campo :attribute esta prohibido.',
    'prohibited_if' => 'El campo :attribute esta prohibido cuando :other es :value.',
    'prohibited_unless' => 'El campo :attribute esta prohibido unless :other esta en :values.',
    'prohibits' => 'El campo :attribute prohibe a :other de estar presente.',
    'same' => ':attribute y :other deben coincidir.',
    'size' => [
        'numeric' => ':attribute debe ser :size.',
        'file' => ':attribute debe ser :size kilobytes.',
        'string' => ':attribute debe ser :size caracteres.',
        'array' => ':attribute debe contener :size elementos.',
    ],
    'starts_with' => ':attribute debe comenzar con uno de los siguientes valores: :values.',
    'string' => ':attribute debe ser una cadena de caracteres.',
    'timezone' => ':attribute debe ser una zona horaria valida.',
    'unique' => ':attribute ya ha sido usado.',
    'uploaded' => 'El :attribute falló al subirse.',
    'url' => ':attribute debe ser una URL valida.',
    'uuid' => ':attribute debe ser un UUID valido.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];