<p align="center">
  <a href="/corretora">
    <img src="public/assets/img/logo.png" alt="Corretora" />
  </a>
</p>

<h1 align="center">Corretora</h1>
<h4 align="center">Software de gerenciamento de Imóveis</h4>

<div align="center">

[![INICIO](https://img.shields.io/static/v1?label=Iniciado%20em&message=Julho%20de%202021&color=41B095&style=for-the-badge)](#)
[![PHP](https://img.shields.io/static/v1?label=PHP&message=8.0&color=73c1fe&style=for-the-badge)](#)
[![CODEIGNITER](https://img.shields.io/static/v1?label=CODEIGNITER&message=4&color=ffbf69&style=for-the-badge)](#)

</div>

<h3 align="center"> 🚧  Fase do projeto: Em construção...  🚧</h3>

## Visão Geral

O Corretora é um software de gerenciamento de imóveis.

### Pré-requisitos

Antes de começar, você vai precisar ter instalado em sua máquina as seguintes ferramentas:
[Git](https://git-scm.com), [PHP8](https://www.apachefriends.org/pt_br), [Composer](https://getcomposer.org/download), [Postgres13](https://www.postgresql.org/download).
Além disto é bom ter um editor para trabalhar com o código como [VSCode](https://code.visualstudio.com/)

### Extensões PHP Necessárias 
Na pasta do php, abra o arquivo php.ini que contem as configurações nele descomente as seguintes extensões:
extension=bz2,curl,fileinfo,gd,gettext,gmp,intl,imap,ldap,mbstring,exif,mysqli,openssl,pdo_mysql,pdo_pgsql,pdo_sqlite,pgsql.
### Criando banco
Após instalar o Postgres, com uma ferramenta de gestão como o pg admin
Crie uma nova base de dados com o nome "corretora"

## Crie o arquivo .env dentro da pasta raiz com as configuração do seu banco 
### Rodando o Projeto

```bash
# Tenha primeiramente em seu disco o diretorio
$ C:\repositorio\corretora (Windows) ou /var/www/html/corretora (Linux/MAC)

# Clone este repositório
$ git clone <https://github.com/AndersonRubel/corretora.git>

# Acesse a pasta do projeto no terminal/cmd
$ cd corretora

# Instale as dependências
$ composer install

# Rode o comando abaixo ele executa todas migrations e seeders
$ php spark importa:initbase

# Execute a aplicação
$ php spark serve

# O servidor inciará na porta:8080 - acesse <http://localhost:8080>
```

### Recursos

-   [Dashboard de Indicadores](#dashboard)
-   [Gestão de Cadastros](#cadastros)
-   [Gestão de Empresas](#empresa)
-   [Gestão de Imóveis](#imovel)
-   [Gestão de Usuários](#usuario)
-   [Gestão de Clientes](#cliente)
-   [Gestão de Proprietários](#proprietario)
-   [Gestão de Reseravas](#reserva)



## Licença

MIT © [AndersonRubel](https://github.com/AndersonRubel)
