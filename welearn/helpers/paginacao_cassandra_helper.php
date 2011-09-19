<?php

function create_paginacao_cassandra(array &$dataset, $por_pagina = 10)
{
    $resultado = array (
        'proxima_pagina' => false,
        'inicio_proxima_pagina' => null
    );

    if (count($dataset) == $por_pagina + 1) {
        $resultado['proxima_pagina'] = true;
        $resultado['inicio_proxima_pagina'] = $dataset[$por_pagina];

        unset($dataset[$por_pagina]);
    }

    return $resultado;
}