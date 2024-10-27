<?php include('layouts/header.php'); ?>

<?php
function converterDataParaNumerico($data) {
    list($dia, $mes) = explode('/', $data);
    return intval($mes . str_pad($dia, 2, '0', STR_PAD_LEFT));
}

function verificarSigno($data_nascimento, $data_inicio, $data_fim) {
    $dia_mes_nascimento = date('d/m', strtotime($data_nascimento));
    
    $data_nascimento_num = converterDataParaNumerico($dia_mes_nascimento);
    $data_inicio_num = converterDataParaNumerico($data_inicio);
    $data_fim_num = converterDataParaNumerico($data_fim);
    
    if ($data_inicio_num > $data_fim_num) {
        return $data_nascimento_num >= $data_inicio_num || $data_nascimento_num <= $data_fim_num;
    } else {
        return $data_nascimento_num >= $data_inicio_num && $data_nascimento_num <= $data_fim_num;
    }
}

$data_nascimento = $_POST['data_nascimento'];

$signos = simplexml_load_file("signos.xml");

$signo_encontrado = null;

foreach ($signos->signo as $signo) {
    if (verificarSigno($data_nascimento, (string)$signo->dataInicio, (string)$signo->dataFim)) {
        $signo_encontrado = $signo;
        break;
    }
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <?php if ($signo_encontrado): ?>
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h2 class="text-center mb-0"><?php echo $signo_encontrado->signoNome; ?></h2>
                    </div>
                    <div class="card-body">
                        <p class="text-center mb-4">
                            <strong>Período:</strong> 
                            <?php echo $signo_encontrado->dataInicio; ?> - <?php echo $signo_encontrado->dataFim; ?>
                        </p>
                        <p class="mb-4"><?php echo $signo_encontrado->descricao; ?></p>
                    </div>
                    <div class="card-footer text-center">
                        <a href="index.php" class="btn btn-primary">Fazer nova consulta</a>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-danger">
                    Não foi possível encontrar seu signo. Por favor, tente novamente.
                </div>
                <div class="text-center">
                    <a href="index.php" class="btn btn-primary">Voltar</a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>