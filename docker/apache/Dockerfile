# Usa a imagem oficial do Apache HTTP Server como base.
FROM httpd:2.4
LABEL maintainer="Vitor Nunes <vt.nunes1@gmail.com>"

# Atualiza os pacotes do sistema operacional base da imagem.
RUN apt-get update -y

# Copia o arquivo de configuração.
COPY ./default.conf /usr/local/apache2/conf/default.conf

# Adiciona uma linha no arquivo principal de configuração do Apache para incluir o arquivo de configuração personalizado.
RUN echo "Include /usr/local/apache2/conf/default.conf" >> /usr/local/apache2/conf/httpd.conf

