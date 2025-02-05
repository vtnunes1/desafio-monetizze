# Monetizze - Ambiente Docker

Este projeto utiliza Docker para configurar e executar um ambiente de desenvolvimento com Apache, PHP e MySQL.

## Requisitos

Antes de iniciar, certifique-se de ter instalado os seguintes softwares:

- [Docker](https://docs.docker.com/get-docker/)
- [Docker Compose](https://docs.docker.com/compose/install/)

## Estrutura do Projeto

O projeto segue a seguinte estrutura:

```
├── app/                   # Código-fonte da aplicação
├── container-data/        # Dados persistentes dos containers
│   ├── mysql-data/        # Dados do MySQL
├── docker/                # Configuração dos containers
│   ├── apache/            # Dockerfile do Apache
│   ├── php/               # Dockerfile do PHP
│   ├── mysql/             # Dockerfile do MySQL e script de inicialização
├── docker-compose.yml     # Arquivo de configuração do Docker Compose
├── .env                   # Arquivo de variáveis de ambiente
├── README.md              # Documentação do projeto
```

## Subindo os Containers

Para iniciar os containers, execute o seguinte comando na raiz do projeto:

```sh
docker compose up -d --build
```

### Explicação dos Serviços

- **web**: Container do Apache configurado para servir a aplicação.
- **php**: Container com o PHP configurado para rodar o código do projeto.
- **mysql**: Container do MySQL responsável pelo banco de dados.

### Considerações sobre Arquitetura

Este projeto foi desenvolvido em um ambiente com tecnologia Apple (ARM64). Caso seja executado em um ambiente com tecnologias diferentes, podem ocorrer incompatibilidades.

Se enfrentar problemas ao iniciar a aplicação com `docker compose up`, consulte a [documentação oficial do Docker](https://docs.docker.com/) para possíveis soluções.

#### Configuração para outros ambientes

A seguir, apresento um exemplo de configuração recomendada para execução em ambientes Windows (AMD64) do arquivo `docker\mysql\Dockerfile`:

```
FROM mysql:8.0
LABEL maintainer="Vitor Nunes <vt.nunes1@gmail.com>"
COPY ./default.conf /etc/mysql/conf.d/
COPY ./init.sql /docker-entrypoint-initdb.d/
EXPOSE 3306
CMD ["mysqld"]
```

### Acessando os Serviços

- **Apache (Web Server)**: http://localhost:8001/
- **MySQL**: MySQL disponibilizado na porta `3306`, usuário e senha definidos no `.env`.

# Monetizze - Aplicação

## Subindo Aplicação
Após a criação bem sucedida dos containers, acesse o container do PHP e execulte o comando: `composer install` para a instalação das dependencias do projeto.

## Estrutura da Aplicação

Esta aplicação possui tanto uma interface HTML para interação do usuário quanto rotas para API que processam requisições específicas. Abaixo está a descrição das rotas e suas respectivas funcionalidades.

## Rotas de Interface HTML

Essas rotas servem para carregar a página principal e fornecer feedback ao usuário através de mensagens de erro ou sucesso armazenadas na sessão.

### `GET /`

**Descrição:** Exibe a página principal da aplicação aonde o usuário pode realizar a criação de bilhetes.

---

## Rotas de API

### TRIPULANTES

#### `GET /api/v1/tripulantes`

- **Descrição:** Retorna todos os tripulantes.
- **Controller:** `TripulantesController@index()`

#### `GET /api/v1/tripulantes/{id_tripulante}`

- **Descrição:** Retorna um tripulante específico pelo ID.
- **Controller:** `TripulantesController@show($id_tripulante)`

#### `POST /api/v1/tripulantes`

- **Descrição:** Cria um novo tripulante.
- **Controller:** `TripulantesController@store($data)`

#### `PUT /api/v1/tripulantes/{id_tripulante}`

- **Descrição:** Atualiza os dados de um tripulante existente.
- **Controller:** `TripulantesController@update($data, $id_tripulante)`

#### `DELETE /api/v1/tripulantes/{id_tripulante}`

- **Descrição:** Exclui um tripulante pelo ID.
- **Controller:** `TripulantesController@delete($id_tripulante)`

---

### BILHETES

#### `GET /api/v1/bilhetes`

- **Descrição:** Retorna todos os bilhetes.
- **Controller:** `BilhetesController@index()`

#### `GET /api/v1/bilhetes/{id_bilhete}`

- **Descrição:** Retorna um bilhete específico pelo ID.
- **Controller:** `BilhetesController@show($id_bilhete)`

#### `POST /api/v1/bilhetes`

- **Descrição:** Cria um novo bilhete.
- **Controller:** `BilhetesController@store($data)`

#### `PUT /api/v1/bilhetes/{id_bilhete}`

- **Descrição:** Atualiza os dados de um bilhete existente.
- **Controller:** `BilhetesController@update($data, $id_bilhete)`

#### `DELETE /api/v1/bilhetes/{id_bilhete}`

- **Descrição:** Exclui um bilhete pelo ID.
- **Controller:** `BilhetesController@delete($id_bilhete)`

---

### BILHETES DEZENAS

#### `GET /api/v1/bilhetes-dezenas`

- **Descrição:** Retorna todas as dezenas de bilhetes.
- **Controller:** `BilhetesDezenasController@index()`

#### `GET /api/v1/bilhetes-dezenas/{id_bilhete_dezena}`

- **Descrição:** Retorna uma dezena específica de um bilhete pelo ID.
- **Controller:** `BilhetesDezenasController@show($id_bilhete)`

#### `POST /api/v1/bilhetes-dezenas`

- **Descrição:** Cria uma nova dezena para um bilhete.
- **Controller:** `BilhetesDezenasController@store($data)`

#### `DELETE /api/v1/bilhetes-dezenas/{id_bilhete_dezena}/{dezena}`

- **Descrição:** Exclui uma dezena de um bilhete pelo ID e número da dezena.
- **Controller:** `BilhetesDezenasController@delete($id_bilhete_dezena, $dezena)`

---

### BILHETES PREMIADOS

#### `GET /api/v1/bilhetes-premiados`

- **Descrição:** Retorna todos os bilhetes premiados.
- **Controller:** `BilhetesPremiadosController@index()`

#### `POST /api/v1/bilhetes-premiados`

- **Descrição:** Cria um novo bilhete premiado.
- **Controller:** `BilhetesPremiadosController@store($data)`

#### `DELETE /api/v1/bilhetes-premiados/{id_bilhete_premiado}`

- **Descrição:** Exclui um bilhete premiado pelo ID.
- **Controller:** `BilhetesPremiadosController@delete($id_bilhete_premiado)`

---

### BILHETES PREMIADOS DEZENAS

#### `GET /api/v1/bilhetes-premiados-dezenas`

- **Descrição:** Retorna todas as dezenas de bilhetes premiados.
- **Controller:** `BilhetesPremiadosDezenasController@index()`

#### `POST /api/v1/bilhetes-premiados-dezenas`

- **Descrição:** Cria uma nova dezena para um bilhete premiado.
- **Controller:** `BilhetesPremiadosDezenasController@store($data)`

#### `DELETE /api/v1/bilhetes-premiados-dezenas/{id_bilhete_premiado}/{dezena}`

- **Descrição:** Exclui uma dezena de um bilhete premiado pelo ID e número da dezena.
- **Controller:** `BilhetesPremiadosDezenasController@delete($id_bilhete_premiado, $dezena)`

---

## Considerações

- Após cada ação na interface HTML, o usuário é redirecionado de volta à página anterior (`HTTP_REFERER`), garantindo melhor experiência na interface.
- O arquivo `index.php` renderiza a interface, enquanto as demais rotas manipulam dados e processam requisições.
