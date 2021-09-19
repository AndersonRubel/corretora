<p align="center">
  <a href="https://iluminareweb.com.br">
    <img src="public/assets/img/logo.png" alt="Iluminare Web" />
  </a>
</p>

<h1 align="center">Porta Jóias</h1>
<h4 align="center">Software de gerenciamento de Vendas</h4>

<div align="center">

[![ILUMINARE](https://img.shields.io/static/v1?label=Feito%20com%20%E2%99%A1%20por&message=ILUMINARE%20WEB&color=0E2D39&style=for-the-badge)](https://iluminareweb.com.br)
[![INICIO](https://img.shields.io/static/v1?label=Iniciado%20em&message=Julho%20de%202021&color=41B095&style=for-the-badge)](#)
[![PHP](https://img.shields.io/static/v1?label=PHP&message=8.0&color=73c1fe&style=for-the-badge)](#)
[![CODEIGNITER](https://img.shields.io/static/v1?label=CODEIGNITER&message=4&color=ffbf69&style=for-the-badge)](#)

</div>

<h3 align="center"> 🚧  Fase do projeto: Em construção...  🚧</h3>

## Visão Geral

O Porta Jóias é um software de gerenciamento de vendas para empresas que possui vendedores externos e precisam controlar o estoque de seus vendedores.

### Pré-requisitos

Antes de começar, você vai precisar ter instalado em sua máquina as seguintes ferramentas:
[Git](https://git-scm.com), [PHP](https://www.apachefriends.org/pt_br), [Composer](https://getcomposer.org/download).
Além disto é bom ter um editor para trabalhar com o código como [VSCode](https://code.visualstudio.com/)

### Rodando o Projeto

```bash
# Tenha primeiramente em seu disco o diretorio
$ C:\repositorio\iluminare-web (Windows) ou /var/www/html/iluminare-web (Linux/MAC)

# Clone este repositório
$ git clone <https://github.com/Iluminare-Web/portajoias.git>

# Acesse a pasta do projeto no terminal/cmd
$ cd portajoias

# Instale as dependências
$ composer install

# Rode as Migrations
$ php spark migrate

# Rode os Seeders
$ php spark db:seed DatabaseSeeder

# Execute a aplicação
$ php spark serve

# O servidor inciará na porta:8080 - acesse <http://localhost:8080>
```

### Recursos

-   [Dashboard de Indicadores](#dashboard)
-   [Gestão de Cadastros](#cadastros)
-   [Gestão de Empresas](#empresa)
-   [Gestão de Estoque](#estoque)
-   [Gestão de Usuários](#usuario)
-   [Gestão de Clientes](#cliente)
-   [Gestão de Fornecedor](#fornecedor)
-   [Gestão de Vendedor](#vendedor)
-   [Gestão](#gestao)
    -   [Centro de Custo](#centrodecusto)
-   [Gestão Financeira](#financeiro)
    -   [Contas a Pagar](#pagar)
    -   [Contas a Receber](#receber)
    -   [Fluxo de Caixa](#fluxocaixa)
-   [Gestão de Vendas](#vendas)
    -   [Fechamento](#fechamento)
-   [Relatórios](#relatorio)

## Licença

MIT © [Iluminare Web](https://github.com/Iluminare-Web)
