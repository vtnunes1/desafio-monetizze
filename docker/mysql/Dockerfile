# Usa a imagem oficial do php-alpine como base.
FROM arm64v8/mysql:8.0
LABEL maintainer="Vitor Nunes <vt.nunes1@gmail.com>"

# Copia o instalador de extensões do PHP
COPY ./default.conf /etc/mysql/conf.d/

# Copia o script de inicialização para o diretório de scripts do MySQL
COPY ./init.sql /docker-entrypoint-initdb.d/

# Expõe a porta padrão do MySQL
EXPOSE 3306

# Comando padrão para rodar o MySQL
CMD ["mysqld"]
