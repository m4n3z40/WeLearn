<div id="certificado-exibir-content">
    <div>
        <?php echo $htmlCertificado ?>
    </div>
    <div>
        <h4>Outras Informações:</h4>
        <ul>
            <li><strong>Data de Ingresso:</strong> <?php echo date('d/m/Y', $dataInscricao) ?></li>
            <li><strong>Último Acesso:</strong> <?php echo date('d/m/Y à\s H:i:s', $dataUltimoAcesso) ?></li>
            <li><strong>Tempo de Curso:</strong> <?php echo round($frequenciaTotal, 1) ?> h</li>
            <li><strong>CR do Curso:</strong> <?php echo number_format($crFinal, 1, ',', '.') ?></li>
        </ul>
    </div>
</div>