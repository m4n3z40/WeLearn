<?php

function create_paginacao_mysql(array &$dataset, $inicio_pagina_atual = 0, $por_pagina = 10) {
    $paginacao = array (
        'proxima_pagina' => false,
        'inicio_proxima_pagina' => null
    );

    if (count($dataset) == $por_pagina + 1) {
        $paginacao['proxima_pagina'] = true;
        $paginacao['inicio_proxima_pagina'] = $inicio_pagina_atual + $por_pagina;

        unset($dataset[$por_pagina]);
    }

    return $paginacao;
}
 
