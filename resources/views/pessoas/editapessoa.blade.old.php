<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PK Siga Edita Pessoa</title>

    <style>
        @font-face {
            font-family: 'vila madalena';
            src: url("{{ asset('/fonts/vila madalena.otf') }}");
        }

        body { font-family: Inter, sans-serif; }
        .sombra { filter: drop-shadow(0 4px 3px rgba(0,0,0,0.07)); }
        .spinner-inline { display:inline-flex; align-items:center; gap:8px; }
        .spinner-inline::before {
            content:"";
            width:14px;height:14px;border:3px solid #666;border-top-color:transparent;border-radius:50%;
            animation:spin .6s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* overlay spinner quando .carregando.show estiver presente */
        .carregando { display:none; position:fixed; inset:0; align-items:center; justify-content:center; z-index:60; }
        .carregando.show { display:flex; background: rgba(0,0,0,0.25); }
        .carregando .box { background:#fff;padding:20px;border-radius:8px; display:flex; gap:12px; align-items:center; }

    </style>

    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">

<nav class="bg-gray-800 py-4 shadow-md">
    <div class="container mx-auto px-4 text-white flex items-center gap-2">
        <img width="50px" src="{{ asset('imgs/sistema/pk_siga_branco.png') }}" />
        <span>PrettusKlan Computatrum.Ius - siga</span>
    </div>
</nav>

<main class="container mx-auto p-6">
    <div class="bg-white p-6 rounded shadow">
        <h2 class="divTitulo text-2xl font-bold mb-4">Editar Pessoa -&NonBreakingSpace;{{ $pessoas->id }}</h2>
        <a href="javascript:history.back()">
        <button id="fechar" 
            class="absolute bg-green-500 hover:bg-red-500 text-white font-bold py-2 px-4 
                    rounded-md transition duration-300 ease-in-out text-sm sm:text-base md:text-lg" 
            style="right:10px; top: 7px;"> &times;
        </button>
        </a>
        <!-- OVERLAY SPINNER -->
        <div class="carregando" id="overlay-loading">
            <div class="box">
                <div class="spinner-inline"></div>
                <div>Carregando...</div>
            </div>
        </div>

        <form method="POST" action="{{ route('pessoas.atualizar', $pessoas->id) }}" enctype="multipart/form-data" id="form-pessoa">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold">Nome</label>
                    <input type="text" name="nome" value="{{ old('nome', $pessoas->nome) }}" class="w-full border rounded px-3 py-2">
                </div>

                <div>
                    <label class="block font-semibold">E-mail</label>
                    <input type="email" name="email" value="{{ old('email', $pessoas->email) }}" class="w-full border rounded px-3 py-2">
                </div>

                <div>
                    <label class="block font-semibold">Avatar</label>
                    <div class="flex items-center gap-4">
                        <img id="avatar-preview" src="{{ asset('avatars/'.$pessoas->avatar) }}" alt="avatar" class="h-20 w-20 object-cover rounded sombra">
                        <input type="file" name="avatar" id="avatar" accept="image/*">
                    </div>
                </div>

                <div>
                    <label class="block font-semibold">Data Nasc.</label>
                    <input type="text" name="dt_nascimento" value="{{ dateFormatDatabaseScreen($pessoas->dt_nascimento,'screen') }}" class="w-full border rounded px-3 py-2">
                </div>

                <div>
                    <label class="block font-semibold">CPF / CNPJ</label>
                    <input type="text" name="cpf_cnpj" value="{{ old('cpf_cnpj', $pessoas->cpf_cnpj) }}" class="w-full border rounded px-3 py-2">
                </div>

                <div>
                    <label class="block font-semibold">RG / IE</label>
                    <input type="text" name="rg_ie" value="{{ old('rg_ie', $pessoas->rg_ie) }}" class="w-full border rounded px-3 py-2">
                </div>
            </div>

            <hr class="my-4">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold">Estado</label>
                    <!-- name deve ser esta_ibge -->
                    <select id="estado" name="esta_ibge" class="w-full border rounded px-3 py-2">
                        <option value="">Selecione um Estado</option>
                        @foreach($estados as $estado)
                            <option value="{{ $estado->esta_ibge }}" {{ $pessoas->esta_ibge == $estado->esta_ibge ? 'selected' : '' }}>
                                {{ $estado->esta_uf }} - {{ $estado->esta_nm ?? '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-semibold">Cidade</label>
                    <!-- name deve ser muni_ibge -->
                    <select id="municipio" name="muni_ibge" class="w-full border rounded px-3 py-2" @if(empty($pessoas->muni_ibge)) disabled @endif>
                        <option value="">Selecione uma Cidade</option>
                        @foreach($municipios as $municipio)
                            <option value="{{ $municipio->muni_ibge }}" {{ $pessoas->muni_ibge == $municipio->muni_ibge ? 'selected' : '' }}>
                                {{ $municipio->muni_nm }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-4">
                    <div class="w-1/4">
                        <label class="block font-semibold">Logradouro</label>
                        <input type="text" id="logradouro" name="logradouro" value="{{ old('logradouro', $pessoas->logradouro) }}" class="w-full border rounded px-3 py-2">
                    </div>

                    <div class="w-1/2">
                        <label class="block font-semibold">Endereço</label>
                        <input type="text" id="endereco" name="endereco" value="{{ old('endereco', $pessoas->endereco) }}" class="w-full border rounded px-3 py-2">
                    </div>

                    <div class="w-1/4">
                        <label class="block font-semibold">Número</label>
                        <input type="text" name="nr" value="{{ old('nr', $pessoas->nr) }}" class="w-full border rounded px-3 py-2">
                    </div>
                </div>
                    
                <div class="flex gap-4">
                    <div class="w-1/4">
                        <label class="block font-semibold">complemento</label>
                        <input type="text" name="complemento" value="{{ old('complemento', $pessoas->complemento) }}" class="w-full border rounded px-3 py-2">
                    </div>

                    <div class="w-1/4">
                        <label class="block font-semibold">cep</label>
                        <input type="text" id="cep" onblur="buscarCep()" name="cep" value="{{ old('cep', $pessoas->cep) }}" class="w-full border rounded px-3 py-2">
                        
                    </div>

                    <div class="w-1/2">
                        <label class="block font-semibold">Bairro</label>
                        <input type="text" id="bairro" name="bairro" value="{{ old('bairro', $pessoas->bairro) }}" class="w-full border rounded px-3 py-2">
                    </div>
                </div>
            </div>

            <div class="gap-4 w-1/4"">
                <label class="block font-semibold">Associado</label>
                <select name="associado" class="w-full border rounded px-3 py-2">
                    <option value="0" {{ $pessoas->associado === 0 ? 'selected' : '' }}>CLIENTE</option>
                    <option value="1" {{ $pessoas->associado === 1 ? 'selected' : '' }}>ADVOGADO</option>
                    <option value="2" {{ $pessoas->associado === 2 ? 'selected' : '' }}>CONVIDADO</option>
                </select>
            </div>

            <div class="grid grid-cols-1 gap-4 mt-4">
                <div>
                    <label class="block font-semibold">Observação</label>
                    <textarea name="obs" rows="4" class="w-full border rounded px-3 py-2">{{ old('obs', $pessoas->obs) }}</textarea>
                </div>
            </div>

            <div class="mt-4 text-right">
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Salvar</button>
            </div>
        </form>
    </div>
</main>

<script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>

<script type="module">

    const caminhoPublic = '<?php echo env('CAMINHO_PUBLIC'); ?>';
    const elemento = document.getElementById('listaRequerentes');

    const rotaEstados       = @json(route('carrega.estados'));
    const rotaMunicipiosTpl = @json(route('estados.municipios', ['esta_ibge' => 'UF_IBGE']));
    const preEstado         = '{{ $pessoas->esta_ibge ?? "" }}';
    const preMunicipio      = '{{ $pessoas->muni_ibge ?? "" }}';
    const rotaCep           = "{{ url('api/cep') }}";

    // ===============================
    // MAPA UF → IBGE
    // ===============================
    const UF_TO_IBGE = {
        "AC": 12, "AL": 27, "AP": 16, "AM": 13, "BA": 29,
        "CE": 23, "DF": 53, "ES": 32, "GO": 52, "MA": 21,
        "MT": 51, "MS": 50, "MG": 31, "PA": 15, "PB": 25,
        "PR": 41, "PE": 26, "PI": 22, "RJ": 33, "RN": 24,
        "RS": 43, "RO": 11, "RR": 14, "SC": 42, "SP": 35,
        "SE": 28, "TO": 17
    };

    // =================================================================
    // INICIALIZAÇÃO DO DATA TABLE
    // =================================================================
    $(document).ready(function() {

        $(window).on('beforeunload', function(){
            window.addEventListener("message", function(event) {
                if (event.data.id && event.data.requerente) {
                    $('#id').val(event.data.id);
                    $('#requerente').val(event.data.requerente);
                }
            });
        });

        $('#listagem').DataTable({
            stripeClasses: ['odd', 'even'],
            paging: true,
            pageLength: 8,
            responsive: true,
            pagingType: 'full_numbers',
            language: {
                url: "../lang/pt-br.json",
                search: "Busca Pessoa:",
                paginate: {
                    first: '&#x25C0;Inicio',
                    previous: '&#x25C0;',
                    next: '&#x25B6;',
                    last: 'Final&#x25B6;',
                },
            },
            columns: [
                { data: 'avatar' },
                { data: 'nome' },
                { data: 'email' },
                { data: 'rg_ie' },
                { data: 'cpf_cnpj' }
            ],
            initComplete: function() {
                $('div.dataTables_filter label').css({ 'margin-left': '20px' });
            }
        });
    });

    // ========================================================================
    // FUNÇÃO: BUSCAR CEP NA API /api/cep/{cep}
    // ========================================================================
    async function buscarCep() {

        let cep = document.getElementById('cep').value.replace(/\D/g, '');
        if (cep.length !== 8) return;

        try {
            const resposta = await fetch(`${rotaCep}/${cep}`);

            if (!resposta.ok) throw new Error('Erro ao consultar CEP');

            const dados = await resposta.json();

            if (dados.erro) {
                alert(dados.erro);
                return;
            }

            // Preenche endereço
            document.getElementById('logradouro').value = dados.logradouro ?? "";
            document.getElementById('endereco').value   = dados.logradouro ?? "";
            document.getElementById('bairro').value     = dados.bairro ?? "";

            // Converte UF para IBGE
            const ibge = UF_TO_IBGE[dados.estado];

            if (!ibge) {
                console.error("UF não existe no mapa IBGE:", dados.estado);
                return;
            }

            document.getElementById('estado').value = ibge;

            // Carrega municípios
            await loadMunicipios(ibge);

            // Seleciona município correspondente
            $('#municipio option').filter(function() {
                return $(this).text().trim() === dados.cidade.trim();
            }).prop('selected', true);

        } catch (e) {
            console.error(e);
            alert("Erro ao buscar CEP.");
        }
    }

    // ========================================================================
    // FUNÇÃO: CARREGAR MUNICÍPIOS VIA ROTA /estados/municipios/{IBGE}
    // ========================================================================
    async function loadMunicipios(estadoIbge) {

        try {
            const url = rotaMunicipiosTpl.replace('UF_IBGE', estadoIbge);
            const response = await fetch(url);

            if (!response.ok) throw new Error("Erro ao carregar municípios");

            const municipios = await response.json();

            let select = document.getElementById('municipio');
            select.innerHTML = '<option value="">Selecione...</option>';

            municipios.forEach((m) => {
                const opt = document.createElement('option');
                opt.value = m.id;
                opt.textContent = m.nome;
                select.appendChild(opt);
            });

        } catch (e) {
            console.error("Erro carregando municípios:", e);
        }
    }

    // EVENTO NO CAMPO DE CEP
    document.getElementById('cep').addEventListener('blur', buscarCep);

</script>

</body>
</html>
