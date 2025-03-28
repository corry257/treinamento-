# Instalação

Antes de instalar o Laravel no Debian, é necessário garantir que todas as dependências estejam instaladas. O Laravel depende do PHP e de algumas extensões, além de um banco de dados como MariaDB ou Sqlite. Aqui estão os principais pacotes que devem ser instalados no Debian:  
  
    sudo apt-get install php php-common php-cli php-gd php-curl php-xml php-mbstring php-zip php-sybase php-mysql php-sqlite3
    sudo apt-get install mariadb-server sqlite3 git

O Composer é um gerenciador de dependências para PHP. Ele permite instalar, atualizar e gerenciar bibliotecas e pacotes de forma simples, garantindo que um projeto tenha todas as dependências necessárias. No Laravel, o Composer é usado para instalar o framework e suas bibliotecas.

    curl -s https://getcomposer.org/installer | php
    sudo mv composer.phar /usr/local/bin/composer

Além disso, é importante configurar o banco de dados, pois ele será usado para instalar o Laravel. Vamos inicialmente criar um usuário admin com senha admin e criar um banco de dados chamado treinamento:

    sudo mariadb
    GRANT ALL PRIVILEGES ON *.* TO 'admin'@'%'  IDENTIFIED BY 'admin' WITH GRANT OPTION;
    create database treinamento;
    quit

O comando a seguir cria um novo projeto Laravel na pasta treinamento, baixando a estrutura básica do framework e instalando todas as dependências necessárias via Composer, garantindo que o ambiente esteja pronto para o desenvolvimento:

    composer create-project laravel/laravel treinamento
    cd treinamento
    php artisan serve

# MVC

Uma rota é a forma como o framework define e gerencia URLs para acessar diferentes partes da aplicação. As rotas são configuradas no arquivo routes/web.php (para páginas web) ou routes/api.php (para APIs) e determinam qual código será executado quando um usuário acessa uma URL específica. Exemplo:

    Route::get('/exemplo-de-rota', function () {
    echo "Uma rota sem controller, not good!";
    });

O controller é uma classe responsável por organizar a lógica da aplicação, separando as regras de negócio das rotas. Em vez de definir toda a lógica diretamente nas rotas, os controllers agrupam funcionalidades relacionadas, tornando o código mais limpo e modular. A convenção de nomenclatura para controllers segue o padrão PascalCase, onde o nome deve ser descritivo, no singular e sempre terminar com “Controller”, como ProdutoController ou UsuarioController. Vamos criar o EstagiarioController com o seguinte comando que gera automaticamente o arquivo correspondente dentro de app/Http/Controllers:

    php artisan make:controller EstagiarioController

A seguir criamos a rota estagiarios e a apontamos para o controller EstagiarioController, importando anteriormente o namespace App\Http\Controllers\EstagiarioController. O namespace é uma forma de organizar classes, funções e constantes para evitar conflitos de nomes em projetos grandes. Ele permite agrupar elementos relacionados dentro de um mesmo escopo, facilitando a reutilização e manutenção do código.

    use App\Http\Controllers\EstagiarioController;
    Route::get('/estagiarios', [EstagiarioController::class,'index']);

A camada View é responsável por exibir a interface da aplicação, separando a lógica de apresentação da lógica de negócio (controller). Ela utiliza o Blade, uma linguagem de templates que permite criar páginas dinâmicas de forma eficiente. As views ficam armazenadas na pasta resources/views e podem ser retornadas a partir de um controller usando return view('nome_da_view').

    mkdir resources/views/estagiarios
    touch resources/views/estagiarios/index.blade.php

No controller:

    public function index()
    {
        return view('estagiarios.index');
    }

Conteúdo mínimo de index.blade.php:

    <!DOCTYPE html>
    <html>
        <head>
            <title>Estagiários</title>
    </head>
        <body>
            João<br>
            Maria
        </body>
    </html>

O Model é uma representação de uma tabela no banco de dados e é responsável pela interação com os dados dessa tabela. Ele encapsula a lógica de acesso e manipulação dos dados, permitindo realizar operações como inserção, atualização, exclusão e leitura de registros de forma simples e intuitiva. O Laravel usa o Eloquent ORM (Object-Relational Mapping) para mapear os dados do banco de dados para objetos PHP, o que permite que você trabalhe com as tabelas como se fosse uma classe de objetos.

Criando o model chamado Estagiario:

    php artisan make:model Estagiario -m

As migrations são uma forma de versionar e gerenciar o esquema do banco de dados, permitindo criar, alterar e remover tabelas de forma controlada e rastreável. Elas funcionam como um histórico de mudanças no banco de dados, ajudando a manter o controle de versões entre diferentes ambientes de desenvolvimento e produção.

Cada migration é uma classe PHP que define as operações a serem realizadas no banco de dados. As migrations são armazenadas na pasta database/migrations. As migrations tornam o processo de gerenciamento do banco de dados mais organizado e flexível, principalmente em projetos com múltiplos desenvolvedores. Vamos colocar três colunas para o model Estagiario: nome, idade e email.

    $table->string('nome');
    $table->string('email');
    $table->integer('idade');

# Desafio

   Crie uma rota chamada estagiarios/create apontando para o método create em EstagiarioController, que também deve ser criado.  

No método create do EstagiarioController, insira os estagiários:

    public function create(){
        $estagiario1 = new \App\Models\Estagiario;
        $estagiario1->nome = "João";
        $estagiario1->email = "joao@usp.br";
        $estagiario1->idade = 26;
        $estagiario1->save();

        $estagiario2 = new \App\Models\Estagiario;
        $estagiario2->nome = "Maria";
        $estagiario2->email = "maria@usp.br";
        $estagiario2->idade = 27;
        $estagiario2->save();
        return redirect("/estagiarios");
    }

**Dica**  

Toda vez que a rota estagiarios/create for acessada os cadastros serão realizados, pode-se deletar tudo antes das inserções com a função: App\Models\Estagiario::truncate()  

Por fim, na view da index podemos buscar os estagiários cadastrados e passar como uma variável para o template:

    public function index(){
        return view('estagiarios.index', [
            'estagiarios' => App\Models\Estagiario::all()
        ]);
    }

No blade, listamos os estagiários:


    <ul>
        @foreach($estagiarios as $estagiario)
            <li>{{ $estagiario->nome }} - {{ $estagiario->email }} - {{ $estagiario->idade }} anos</li>
        @endforeach
    </ul>

# Exercício 1 - Importação de Dados e Estatísticas com Laravel

Objetivo: Criar um sistema básico em Laravel para importar dados de um arquivo CSV e exibir estatísticas desses dados em uma view.  

https://raw.githubusercontent.com/mwaskom/seaborn-data/master/exercise.csv  

1) Criar o Model e a Migration:

    Crie um model chamado Exercise com uma migration correspondente.
    Na migration, defina os campos necessários com base nas colunas do arquivo exercise.csv
    Execute a migration para criar a tabela no banco de dados.

2) Criar o Controller e a Rota para Importação

    Crie um controller chamado ExerciseController com o método importCsv.
    Defina uma rota exercises/importcsv que aponte para o método importCsv do controller.
    No método importCsv, implemente a lógica para ler o arquivo exercise.csv e salvar os dados no banco de dados usando o model Exercise.

Dica: Você pode usar a classe League\Csv\Reader (disponível via Composer) para facilitar a leitura do CSV.  

3) Criar a Rota e Método para Estatísticas

    No mesmo ExerciseController, crie um método chamado stats.
    Defina uma rota exercises/stats que aponte para o método stats.
    No método stats, calcule as média da coluna pulse para os casos rests, walking e running, conforme tabela abaixo.
    Passe esses dados para uma view chamada resources/views/exercises/stats.blade.php e monte finalmente a tabela com html.

Exemplo de saída:  

|exercise.csv	|rest	|walking|running|
|---------------|-------|-------|-------|
|Qtde linhas	| XX	|   XX  |  XXX  |
|Média Pulse	| XX	| XX	|  XXX  |
