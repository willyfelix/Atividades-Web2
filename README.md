# Atividades Laravel

Este repositório irá conter diversas atividades desenvolvidos com o framework Laravel. Aqui você encontra o passo a passo para rodar qualquer um dos projetos Laravel localmente.

## Requisitos

Antes de rodar os projetos, certifique-se de ter os seguintes requisitos instalados:

- **PHP** (versão 8.0 ou superior)
- **Composer** (gerenciador de dependências PHP)
- **MySQL** ou **MariaDB** (para banco de dados)
- **Git** (para clonar o repositório)

## Configuração do Ambiente

1. **Clone o repositório**:

   Clone este repositório para o seu ambiente local:

   ```bash
   git clone https://github.com/seu-usuario/Atividades-Laravel.git
   cd Atividades-Laravel
   ```

2. **Instale as dependências do Laravel**:

   Execute o Composer para instalar as dependências do Laravel:

   ```bash
   composer install
   ```

3. **Crie o arquivo `.env`**:

   O Laravel usa um arquivo `.env` para armazenar configurações do ambiente, como credenciais de banco de dados. Para criar um novo arquivo `.env`, copie o arquivo `.env.example`:

   ```bash
   cp .env.example .env
   ```

4. **Configure o banco de dados**:

   No arquivo `.env`, altere as configurações de banco de dados conforme seu ambiente local. Exemplo:

   ```ini
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=nome_do_banco
   DB_USERNAME=root
   DB_PASSWORD=senha_do_banco
   ```

5. **Execute as migrações do banco de dados**:

   Se o projeto incluir migrações, você pode rodá-las para criar as tabelas do banco de dados:

   ```bash
   php artisan migrate
   ```

6. **Instale as dependências do front-end** (se necessário):

   Se o projeto incluir front-end com o Laravel Mix (baseado em Node.js), instale as dependências do Node.js:

   ```bash
   npm install
   ```

7. **Compile os arquivos front-end** (se necessário):

   Para compilar os arquivos front-end com Laravel Mix, execute:

   ```bash
   npm run dev
   ```

8. **Inicie o servidor**:

   Para rodar o servidor de desenvolvimento, execute o comando:

   ```bash
   php artisan serve
   ```

   O servidor estará rodando em `http://127.0.0.1:8000`.
