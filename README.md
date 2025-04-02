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

**Resolução passo a passo**  

1. Preparação Inicial  

    Instale a biblioteca League\CSV:

       composer require league/csv

2. Baixar e Preparar o Arquivo CSV  

    Acesse o link no navegador:

    [https://raw.githubusercontent.com/mwaskom/seaborn-data/master/exercise.csv](https://raw.githubusercontent.com/mwaskom/seaborn-data/master/exercise.csv)

    Clique com o botão direito e selecione "Salvar como"  

    Salve o arquivo como exercise.csv na pasta storage/app do seu projeto  

3. Criar Model e Migration  

    Crie o model com migration:

        php artisan make:model Exercise -m

   Edite o model Exercise.php:

       protected $fillable = [
        'csv_id',
        'diet', 
        'pulse',
        'time',
        'kind'
       ];

   Edite a migration (database/migrations/xxxx_create_exercises_table.php):
  
       public function up()
       {
           Schema::create('exercises', function (Blueprint $table) {
               $table->id(); // 1. Chave primária auto-incremento
               $table->integer('csv_id')->nullable(); // 2. ID original do CSV
               $table->string('diet'); // 3. Tipo de dieta (ex: 'low fat')
               $table->integer('pulse'); // 4. Valor numérico do pulso
               $table->string('time'); // 5. Tempo do exercício (ex: '1 min')
               $table->string('kind'); // 6. Tipo de exercício (rest/walking/running)
               $table->timestamps(); // 7. Created_at e updated_at automáticos
           });
       }

    Execute a migration:
  
       php artisan migrate

5. Criar o Controller  

    Crie o controller:

       php artisan make:controller ExerciseController

    Edite o controller (app/Http/Controllers/ExerciseController.php):
    
       <?php

       namespace App\Http\Controllers;
        
       use Illuminate\Http\Request;
       use App\Models\Exercise;
       use League\Csv\Reader;
       use Illuminate\Support\Facades\Storage;
        
       class ExerciseController extends Controller
       {
           // Método para importar dados do CSV
           public function importCsv()
           {
               // 1. Define o caminho do arquivo no storage
               $filePath = storage_path('app/exercise.csv');
               
               // 2. Cria o leitor CSV e define a primeira linha como cabeçalho
               $csv = Reader::createFromPath($filePath, 'r');
               $csv->setHeaderOffset(0);
               
               // 3. Limpa a tabela antes da importação (evita duplicados)
               Exercise::truncate();
               
               // 4. Processa cada linha do CSV
               foreach ($csv as $record) {
                   Exercise::create([
                       'csv_id' => $record['id'], // 5. ID original do CSV
                       'diet' => $record['diet'], // 6. Tipo de dieta
                       'pulse' => $record['pulse'], // 7. Valor do pulso
                       'time' => $record['time'], // 8. Duração do exercício
                       'kind' => $record['kind'], // 9. Tipo de atividade
                   ]);
               }
               
               // 10. Redireciona com mensagem de sucesso
               return redirect('/exercise/stats')->with('success', 'Dados importados com sucesso!');
           }
           
            
           // Método para calcular e exibir estatísticas
           public function stats()
           {
               // 1. Busca todos os registros de cada tipo de exercício
               $restStats = Exercise::where('kind', 'rest')->get(); // 2. Apenas repouso
               $walkingStats = Exercise::where('kind', 'walking')->get(); // 3. Apenas caminhada
               $runningStats = Exercise::where('kind', 'running')->get(); // 4. Apenas corrida
       
               // 5. Organiza os dados calculados em um array
               $data = [
                   'rest' => [
                       'count' => $restStats->count(), // 6. Total de registros de repouso
                       'avg_pulse' => $restStats->avg('pulse') // 7. Média de pulso para repouso
                   ],
                   'walking' => [
                       'count' => $walkingStats->count(), // 8. Total de registros de caminhada
                       'avg_pulse' => $walkingStats->avg('pulse') // 9. Média de pulso para caminhada
                   ],
                   'running' => [
                       'count' => $runningStats->count(), // 10. Total de registros de corrida
                       'avg_pulse' => $runningStats->avg('pulse') // 11. Média de pulso para corrida
                   ]
               ];
        
               // 12. Passa os dados para a view
               return view('exercises.stats', compact('data'));
           }
       }
   
   **Pontos-chave do método importCsv():**  
  
    ´->get()´ - Executa a query e retorna uma Collection (lista de resultados)  
  
    ´->count()´ - Método da Collection que conta quantos itens existem  
  
    ´->avg('coluna')´ - Calcula a média dos valores na coluna especificada  
  
    ´compact('data')´ - Equivalente a ['data' => $data]

    **Pontos-chave do método stats:**  
  
     ´->get()´ - Executa a query e retorna uma Collection (lista de resultados filtrados)  
  
     ´->count()´ - Método da Collection que conta quantos registros existem  
  
     ´->avg('pulse')´ - Calcula a média dos valores na coluna 'pulse' para cada grupo  
  
     ´view()´ - Retorna a view especificada com os dados processados  
  
    ´compact('data')´ - Passa a variável $data para a view de forma simplificada  

7. Configurar as Rotas  

Edite routes/web.php:

      use Illuminate\Support\Facades\Route;
      use App\Http\Controllers\ExerciseController;
        
      // 1. Rota para importação do CSV
      Route::get('/exercise/import', [ExerciseController::class, 'importCsv']);
        
      // 2. Rota para exibir estatísticas
      Route::get('/exercise/stats', [ExerciseController::class, 'stats']);


6. Criar a View de Estatísticas  

    Crie a pasta para as views:

       mkdir -p resources/views/exercises

    Crie o arquivo resources/views/exercises/stats.blade.php:
    
       <!DOCTYPE html>
       <html lang="pt-br">
       <head>
           <meta charset="UTF-8">
           <meta name="viewport" content="width=device-width, initial-scale=1.0">
           <title>Estatísticas de Exercícios</title>
           <style>
               /* 1. Estilo básico para a tabela */
               table {
                   border-collapse: collapse; /* 2. Faz as bordas se fundirem */
                   width: 100%; /* 3. Largura total */
               }
               /* 4. Estilo para células e cabeçalhos */
               th, td {
                   border: 1px solid #000; /* 5. Bordas pretas de 1px */
                   padding: 8px; /* 6. Espaçamento interno */
                   text-align: center; /* 7. Alinhamento centralizado */
               }
               /* 8. Estilo adicional para cabeçalhos */
               th {
                   font-weight: bold; /* 9. Texto em negrito */
               }
           </style>
       </head>
       <body>
           <h1>Estatísticas de Exercícios</h1>
            
           <!-- 1. Exibe mensagem de sucesso se existir -->
           @if(session('success'))
               <div style="color: green; margin-bottom: 20px;">
                   {{ session('success') }}
               </div>
           @endif
            
           <!-- 2. Tabela de estatísticas -->
           <table>
               <!-- Cabeçalho da tabela -->
               <thead>
                   <tr>
                       <th>exercise.csv</th> <!-- 3. Coluna de identificação -->
                       <th>rest</th> <!-- 4. Dados de repouso -->
                       <th>walking</th> <!-- 5. Dados de caminhada -->
                       <th>running</th> <!-- 6. Dados de corrida -->
                   </tr>
               </thead>
                
               <!-- Corpo da tabela -->
               <tbody>
                   <!-- 7. Primeira linha: Contagem de registros -->
                   <tr>
                       <td>Qtde linhas</td> <!-- 8. Label -->
                       <td>{{ $data['rest']['count'] }}</td> <!-- 9. Total repouso -->
                       <td>{{ $data['walking']['count'] }}</td> <!-- 10. Total caminhada -->
                       <td>{{ $data['running']['count'] }}</td> <!-- 11. Total corrida -->
                   </tr>
                   
                   <!-- 12. Segunda linha: Médias de pulso -->
                   <tr>
                       <td>Média Pulse</td> <!-- 13. Label -->
                       <!-- 14. Médias arredondadas para 2 casas decimais -->
                       <td>{{ round($data['rest']['avg_pulse'], 2) }}</td>
                       <td>{{ round($data['walking']['avg_pulse'], 2) }}</td>
                       <td>{{ round($data['running']['avg_pulse'], 2) }}</td>
                   </tr>
               </tbody>
           </table>
            
           <!-- 15. Link para nova importação -->
           <div style="margin-top: 20px;">
               <a href="/exercise/import">Importar Dados Novamente</a>
           </div>
       </body>
       </html>

**Quem calcula o quê:**  

| Cálculo | Onde é feito | O que faz|
|---------|--------------|----------|
| Contagem de linhas | Controller (->count()) |	Conta quantos registros existem de cada tipo |
| Média do pulse | Controller (->avg('pulse')) | Calcula a média dos valores na coluna 'pulse' |
| Arredondamento	| View (round(..., 2)) | Formata o número para 2 casas decimais |

8. Testar a Aplicação

    Inicie o servidor de desenvolvimento:

       php artisan serve

    Acesse no navegador:

    Para importar os dados:        

        http://localhost:8000/exercises/import

    Para ver as estatísticas:

        http://localhost:8000/exercises/stats

