<?php

/**
 * Este arquivo foi criado para padronização dos erros
 */

return [
    'banco' => [
        'validaInsercao' => 'Não foi possível inserir ao banco de dados.',
        'validaUpdate' => 'Não foi possível atualizar no banco o dados.',
        'validaDelete' => 'Não foi possível deletar no banco o dados.',
    ],
    'email' => [
        'enviaEmail' => 'Não foi possivel enviar email',
        'validaEmail' => 'As credencias informadas são inválidas.',
    ],
    'empresa' => [
        'buscaEmpresa' => 'Empresa não encontrada.',
        'diaPagamentoInvalido' => 'O dia de Pagamento informado não é válido',
    ],
    'estoque' => [
        'estoqueQuantidadeInvalida' => 'Estoque atual insuficiente para o produto.',
        'transferencia' => 'Não foi possível realizar a transferência.',
        'transferenciaSemProdutos' => 'Não foi informado produtos na transferência.',
        'devolucao' => 'Não foi possível realizar a devolução.',
        'devolucaoSemProdutos' => 'Não foi informado produtos na devolucao.',
    ],
    'fluxo' => [
        'semValorAbater' => 'O Cliente não possui valores em aberto.',
    ],
    'faturamento' => [
        'semDados' => 'Não foram encontrados dados suficientes.',
        'comissao' => 'Parâmetro de comissão não encontrada.',
    ],
    'geral' => [
        'validaUuid' => 'Parâmetro de uuid incorreto.',
        'validaEmail' => 'E-mail já cadastrado no sistema.',
        'registroNaoEncontrado' => 'Registro não encontrado.',
        'acessoNaoPermitido' => 'Acesso não permitido.',
        'telefoneInvalido' => 'Formato de telefone inválido, utilize o formato (XX) XXXXX-XXXX.',
        'cepInvalido' => 'Formato de CEP inválido, utilize o formato XXXXX-XXX.',
        'cpfInvalido' => 'Formato de CPF inválido, utilize o formato XXX.XXX.XXX-XX.',
        'validaData' => 'A data final, não pode ser retroativa a data início.',
        'erroUpload' => 'Não foi possível salvar o arquivo.',
        'acessoNaoPermitido' => 'Não foi possível salvar o arquivo.',
        'operacao' => 'Ocorreu um erro ao realizar a operação.',
    ],
    'http' => [
        '401' => 'Não autorizado.',
        '404' => 'Não encontrado.',
    ],
    'login' => [
        'usuarioSenhaIncorreto' => 'Usuário ou senha incorreto.',
        'acessoNaoPermitido' => 'Você não possui acesso à esse sistema.',
        'naoPossuiEmpresa' => 'Nenhuma empresa vinculada a esse usuário encontrada.',
        'necessarioTrocarSenha' => 'É necessário efetuar a troca da sua senha. Por favor confira seu email;.',
    ],
    'usuario' => [
        'buscaUsuario' => 'Usuario não encontrado.',
        'senhaRegex' => 'Utilize no mínimo uma letra e um número na senha.',
        'confirmaSenha' => 'O campo confirmar senha deve ser igual a senha digitada.',
        'grupoObrigatorio' => 'É obrigatório escolher um grupo de permissões.',
        'emailNaoEncontrado' => 'Email não encontrado.',
        'tokenSenhaInvalido' => 'Link expirado, por favor solicite e-mail de recuperação novamente.',
        'empresaPadrao' => 'Empresa não encontrada.',
        'senhaAntigaNaoBate' => 'Senha antiga incorreta.',
    ],
    'pdv' => [
        'carrinhoVazio' => 'O carrinho está vazio.',
        'naoRealizada' => 'Não foi possível realizar a venda.',
        'estoqueInsuficiente' => 'Estoque atual insuficiente para o produto {0}.',
    ],
    'produto' => [
        'imagemInvalida' => 'A imagem é inválida.',
        'codigoEmUso' => 'O Código Interno/Barras informado já está em uso.',
        'codigoNaogerado' => 'Não foi possível gerar um código.',
    ],
    'venda' => [
        'estorno' => 'Não foi possível estornar a venda.',
        'vendedor' => 'Esta venda não é deste vendedor.',
        'produto' => 'Não foram encontrados produtos na venda.',
        'vendaFaturamento' => 'Esta venda não pode ser estornada pois está dentro de um faturamento.',
    ]
];
