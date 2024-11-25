# Selecao-Back-End
Você deverá forkar este repositório para fazer o seu exercício. Para entregar envie o link do seu repositório por e-mail.

O exercício deve ser feito apenas pelo candidato e tem como objetivo medir o seu nível de conhecimento para melhor alocação dentro da Betalabs. Existem as seguintes exigências técnicas:
- Linguagem do lado servidor: PHP 8.0;
- Linguagem cliente: JSON;
- Banco de dados: MySQL.

Para instalar o PHP/Laravel no local é recomendado usar o [Homestead](https://laravel.com/docs/8.x/homestead) pela facilidade na instalação porém qualquer instalação é válida. Entretanto a avaliação do exercício será feito usando o Homestead mais atualizado.

O exercício deve ser feito necessariamente utilizando a framework Laravel 8.0. A quantidade e qualidade da implementação dos requisitos são usadas para a avaliação do candidato.
Na seção de requisitos do sistema os requisitos são divididos em dois grupos:
- Obrigatório: o requisito deve ser implementado;
- Desejável: é interessante se o requisito for implementado, porém não é obrigatório.

## Cenário
A empresa solicitou o desenvolvimento de um sistema de comentários para um novo produto que estão lançando. Como trata-se de um sistema que será utilizado por outros agentes, então deve ser feito obrigatoriamente via API com entradas e saídas no formato JSON. Esse sistema deve manter os dados dos usuários que comentarem.

### Requisitos do sistema
#### Obrigatórios:
- O sistema deverá gerenciar os usuários, permitindo-os se cadastrar e editar seu cadastro;
- O sistema poderá autenticar o usuário através do e-mail e senha do usuário e, nas outras requisições, utilizar apenas um token de identificação;
- O sistema deverá retornar comentários a todos que o acessarem, porém deverá permitir inserir comentários apenas a usuários autenticados;
- O sistema deverá retornar qual é o autor do comentário e dia e horário da postagem;
- O Readme.md do projeto deverá conter de forma curta e objetiva uma breve explicação de onde e como cada um dos critérios (obrigatórios e desejaveis) busca ser atendido.
#### Desejáveis:
- O sistema deverá permitir o usuário editar os próprios comentários (exibindo a data de criação do comentário e data da última edição);
- O sistema deverá possuir histórico de edições do comentário;
- O sistema deverá permitir o usuário excluir os próprios comentários;
- O sistema deverá possuir um usuário administrador que pode excluir todos os comentários;
- O sistema deverá criptografar a senha do usuário;
- Implementação de testes automatizados utilizando phpunit

# Solução

#### Obrigatórios:
- O sistema deverá gerenciar os usuários, permitindo-os se cadastrar e editar seu cadastro:<br />
Isso foi implementado nos endpoints [POST] /api/manage/register e [PUT] /api/manage/me
  
- O sistema poderá autenticar o usuário através do e-mail e senha do usuário e, nas outras requisições, utilizar apenas um token de identificação:<br />
O login foi implementado no endpoint [POST] /api/manage/login, ao inves de usar a autenticação JWT, optei por usar o Laravel Sanctum
  
- O sistema deverá retornar comentários a todos que o acessarem, porém deverá permitir inserir comentários apenas a usuários autenticados:<br />
Para obter todos os comentários sem precisar estar logado, basta acessar esse endpoint: [GET] /api, para inserir novos comentários use esse endpoint usando seu token de acesso: [POST] /api/manage/comments
  
- O sistema deverá retornar qual é o autor do comentário e dia e horário da postagem:<br />
No endpoint [GET] /api está sendo apresentado essas informações

#### Desejáveis:
- O sistema deverá permitir o usuário editar os próprios comentários (exibindo a data de criação do comentário e data da última edição):<br />
O comentário pode ser editado pelo endpoint [PUT] /api/manage/comments/{id}

- O sistema deverá possuir histórico de edições do comentário:<br />

- O sistema deverá permitir o usuário excluir os próprios comentários:<br />
O comentário pode ser deletado pelo endpoint [DELETE] /api/manage/comments/{id}

- O sistema deverá possuir um usuário administrador que pode excluir todos os comentários:<br />
Adicionei um atributo do tipo boolean chamado is_admin na tabela de usuários, com esse atributo consigo ter o controle de nível de acesso de cada usuário, em conjunto com esse atributo utilizo das policies do Laravel para fazer esse controle.
Basta verificar nos arquivos: database/migrations/2014_10_12_000000_create_users_table.php, routes/api.php, app/Policies/* e app/Providers/AuthServiceProvider.php

- O sistema deverá criptografar a senha do usuário:<br />
Estou usando a classe Illuminate\Support\Facades\Hash para realizar a criptografia da senha do usuário no repositório app/Repositories/EloquentORM/UserRepository.php

- Implementação de testes automatizados utilizando phpunit:<br />


































