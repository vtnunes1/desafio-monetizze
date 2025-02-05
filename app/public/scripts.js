/**
 * Função que carrega dados em várias tabelas e no combobox.
 * 
 * @async
 * @function
 * @returns {Promise<void>} Não retorna valor, apenas executa as funções para carregar os dados.
 */
async function carregarDadosPagina() {
    await carregarTripulantesTable();
    await carregarBilhetesTable();
    await carregarBilhetesPremiadosTable();
    await carregarTripulantesCombobox();
}

/**
 * Função que carrega e exibe a lista de tripulantes em uma tabela.
 * 
 * @async
 * @function
 * @returns {Promise<void>} Atualiza a tabela na página sem retornar valor.
 */
async function carregarTripulantesTable() {

    try {
        //** FAZ REQUISICAO AO ENDPOINT **//
        const response = await fetch('/api/v1/tripulantes');

        if (!response.ok) {
            throw new Error('Erro ao carregar os tripulantes');
        }

        //** CONVERTE RESPOSTA EM JSON **//
        const dados = await response.json();

        if (![200, 404].includes(dados.status)) {
            throw new Error(dados.message || 'Erro');
        }

        if(dados.status === 200){

            //** LIMPA TABELA **//
            const tabelaBody = document.querySelector('#tripulantes-table tbody');
            tabelaBody.innerHTML = '';

            //** MONTA TABELA **//
            dados.data.forEach(tripulante => {

                const tr = document.createElement('tr');
                
                const tdId = document.createElement('td');
                tdId.textContent = tripulante.id_tripulante;

                const tdNome = document.createElement('td');
                tdNome.textContent = tripulante.nome;

                const tdEmail = document.createElement('td');
                tdEmail.textContent = tripulante.email;

                const tdCriadoEm = document.createElement('td');
                tdCriadoEm.textContent = formatarData(tripulante.criado_em);

                tr.appendChild(tdId);
                tr.appendChild(tdNome);
                tr.appendChild(tdEmail);
                tr.appendChild(tdCriadoEm);
                
                tabelaBody.appendChild(tr);
            });
        }
    } catch (error) {

        console.error('Erro ao carregar os tripulantes:', error);
        alert('Erro ao carregar os tripulantes, tente novamente mais tarde.');
    }
}

/**
 * Função que carrega e exibe a lista de bilhetes em uma tabela.
 * 
 * @async
 * @function
 * @returns {Promise<void>} Atualiza a tabela na página sem retornar valor.
 */
async function carregarBilhetesTable() {

    try {
        //** FAZ REQUISICAO AO ENDPOINT **//
        const response = await fetch('/api/v1/bilhetes-dezenas');

        if (!response.ok) {
            throw new Error('Erro ao carregar os bilhetes');
        }

        //** CONVERTE RESPOSTA EM JSON **//
        const dados = await response.json();

        if (![200, 404].includes(dados.status)) {
            throw new Error(dados.message || 'Erro');
        }

        if (dados.status === 200) {
            //** LIMPA TABELA **//
            const tabelaBody = document.querySelector('#bilhetes-table tbody');
            tabelaBody.innerHTML = '';

            //** CARREGA NUMEROS **//
            const bilhetesPremiadosResponse = await fetch('/api/v1/bilhetes-premiados-dezenas');
            const bilhetesPremiadosDados = await bilhetesPremiadosResponse.json();
            const numerosSorteados = bilhetesPremiadosDados.status === 200 
                ? bilhetesPremiadosDados.data.flatMap(bilhete => bilhete.dezenas)
                : [];

            //** MONTA TABELA **//
            dados.data.forEach(bilhete => {
                const tr = document.createElement('tr');

                const tdId = document.createElement('td');
                tdId.textContent = bilhete.bilhete.id_bilhete;

                const tdDezenas = document.createElement('td');
                tdDezenas.innerHTML = highlightNumeros(bilhete.dezenas, numerosSorteados);

                const tdTripulante = document.createElement('td');
                tdTripulante.textContent = bilhete.bilhete.tripulante.nome;

                const tdCriadoEm = document.createElement('td');
                tdCriadoEm.textContent = formatarData(bilhete.bilhete.criado_em);

                tr.appendChild(tdId);
                tr.appendChild(tdDezenas);
                tr.appendChild(tdTripulante)
                tr.appendChild(tdCriadoEm);

                tabelaBody.appendChild(tr);
            });
        }
    } catch (error) {
        console.error('Erro ao carregar os bilhetes:', error);
        alert('Erro ao carregar os bilhetes, tente novamente mais tarde.');
    }
}

/**
 * Função que carrega e exibe a lista de bilhetes premiados em uma tabela.
 * 
 * @async
 * @function
 * @returns {Promise<void>} Atualiza a tabela na página sem retornar valor.
 */
async function carregarBilhetesPremiadosTable() {

    try {
        //** FAZ REQUISICAO AO ENDPOINT **//
        const response = await fetch('/api/v1/bilhetes-premiados-dezenas');

        if (!response.ok) {
            throw new Error('Erro ao carregar os bilhete premiado');
        }

        //** CONVERTE RESPOSTA EM JSON **//
        const dados = await response.json();

        if (![200, 404].includes(dados.status)) {
            throw new Error(dados.message || 'Erro');
        }

        if(dados.status === 200){

            //** LIMPA TABELA **//
            const tabelaBody = document.querySelector('#bilhetes-premiados-table tbody');
            tabelaBody.innerHTML = '';

            //** MONTA TABELA **//
            dados.data.forEach(bilhete => {

                const tr = document.createElement('tr');
                
                const tdId = document.createElement('td');
                tdId.textContent = bilhete.bilhete.id_bilhete_premiado;
                
                const tdDezenas = document.createElement('td');
                tdDezenas.innerHTML = highlightNumeros(bilhete.dezenas, bilhete.dezenas);

                const tdCriadoEm = document.createElement('td');
                tdCriadoEm.textContent = formatarData(bilhete.bilhete.criado_em);
                
                tr.appendChild(tdId);
                tr.appendChild(tdDezenas);
                tr.appendChild(tdCriadoEm);
                
                tabelaBody.appendChild(tr);
            });
        }
    } catch (error) {

        console.error('Erro ao carregar o bilhete premiado:', error);
        alert('Erro ao carregar os bilhete premiado, tente novamente mais tarde.');
    }
}

/**
 * Função que carrega a lista de tripulantes em um combobox <select>.
 * 
 * @async
 * @function
 * @returns {Promise<void>} Atualiza o combobox na página sem retornar valor.
 */
async function carregarTripulantesCombobox() {

    //** FAZ REQUISICAO AO ENDPOINT **//
    const response = await fetch('/api/v1/tripulantes');

    //** CONVERTE RESPOSTA EM JSON **//
    const dados = await response.json();

    //** MONTA COMBOBOX **//
    if(dados.status === 200){
        const select = document.getElementById('idTripulante');

        dados.data.forEach(tripulante => {
            const option = document.createElement('option');
            option.value = tripulante.id_tripulante;
            option.textContent = `${tripulante.nome}`;
            select.appendChild(option);
        });
    }
}

/**
 * Função que destaca os números sorteados em uma lista de dezenas.
 * 
 * @param {number[]} dezenas
 * @param {number[]} numerosSorteados 
 * @returns {string} Uma string com os números, com as classes CSS aplicadas.
 */
function highlightNumeros(dezenas, numerosSorteados) {

    return dezenas.map(numero => {
        if (numerosSorteados.includes(numero)) {
            return `<span class="sorteado">${numero}</span>`;
        } else {
            return `<span class="nao-sorteado">${numero}</span>`;
        }
    }).join(' ');
}

/**
 * Função que abre o modal na página.
 * 
 * @returns {void} Não retorna nenhum valor.
 */
function openModal(){
    document.getElementById("myModal").style.display = "block";
}

/**
 * Função que fecha o modal na página.
 * 
 * @returns {void} Não retorna nenhum valor.
 */
function closeModal(){
    document.getElementById("myModal").style.display = "none";
}

/**
 * Função que formata uma data no formato 'YYYY-MM-DD HH:mm:ss' para 'DD/MM/YYYY HH:mm:ss'.
 * 
 * @param {string} data
 * @returns {string} A data formatada no formato 'DD/MM/YYYY HH:mm:ss'.
 */
function formatarData(data) {

    const [dataParte, horaParte] = data.split(' ');
    const [ano, mes, dia] = dataParte.split('-');
  
    //** RETURN **//
    return `${dia}/${mes}/${ano} ${horaParte}`;
}