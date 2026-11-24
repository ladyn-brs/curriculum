@extends('../layouts.siga')

<style type="text/css">
    @font-face {
        font-family: 'vila madalena';
        src        : url("{{ asset('/fonts/vila madalena.otf') }}");
    }

    table.dataTable tbody tr.odd  { background: #98a6bdff; }
    table.dataTable tbody tr.even { background: #ffffff; }
    table.dataTable tbody tr:hover { background: #eef6ff; }

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

@vite(['resources/css/app.css', 'resources/js/app.js'])

@section('content')
    <center>
    <section class="borda1" style="width:85%">
            <nav style="padding-right:20px;" class="py-2 ">
                <a href="{{ route('dashboard') }}">
                    <button id="fechar"
                        class="retorna bg-green-500 hover:bg-red-500
                        text-white font-bold py-4 px-4 rounded fechar"
                    >
                        &times;
                    </button>
                </a>
            </nav>

            <a href='' data-twe-toggle="modal" data-twe-target="#cadPessoa"
                data-twe-ripple-init  data-twe-ripple-color="light" class="divTitulo">
                Lista Pessoas
            </a>

            <table id='listagem' class="display responsive nowrap" style="width:98%;">
                <thead>
                    <tr>
                        <th data-priority="0" class="bck01" style="width:5%; text-align:center;"> Pessoa </th>
                        <th data-priority="1" class="bck01" style="text-align:center;"> Nome    </th>
                        <th data-priority="2" class="bck01" style="width:25%; text-align:center;"> e-mail  </th>
                        <th data-priority="3" class="bck01 hide-on-small" style="text-align:center;"> RG / Insc. E. </th>
                        <th data-priority="4" class="bck01 hide-on-small" style="text-align:cener;"> CPF / CNPJ  </th>
                    </tr>
                </thead>

                @foreach($pessoas as $pessoa)
                    <tr>
                        
                        <td id="nome" name="nome" class="registro nome" style="text-align:left;">
                            <a href="{{ route('pessoas.mostrar', $pessoa->id) }}">{{ substr($pessoa->nome, 0, 30) }}</a>
                        </td>

                        <td id="processo" name="processo" class="registro" style="width:35%; text-align:center;">
                            <a href="{{ route('pessoas.mostrar', $pessoa->id) }}">{{ substr($pessoa->email, 0, 25) }}</a>
                        </td>

                        <td id="movimento" name="movimento" class = "hide-on-small" style="text-align:left;">
                            <a href="#"> {{ $pessoa->rg_ie }} </a>
                        </td>

                        <td id="status" name="status" class = "hide-on-small" style="text-align:center;">
                            <a href="#">  {{ $pessoa->cpf_cnpj }} </a>
                        </td>
                    </tr>
                @endforeach
            </table>

            <button id="novo"
                data-twe-toggle="modal"
                data-twe-target="#cadPessoa"
                data-twe-ripple-init
                data-twe-ripple-color="light"
                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                &nbsp;&nbsp;Novo&nbsp;&nbsp;
            </button>
    </div>

    <!----------------------------- Modal cadPessoa ------------------------------->
    <div id="cadPessoa"
        data-twe-modal-init
        data-twe-backdrop="static"
        data-twe-keyboard="false"
        draggable="true"
        tabindex="-1"
        aria-labelledby="sigaBackdropLabel"
        class="fixed inset-0 z-[1055] hidden overflow-y-auto overflow-x-hidden p-4">

        <div data-twe-modal-dialog-ref class="mx-auto w-full max-w-4xl">
            <div class="relative w-full rounded-xl bg-white shadow-xl ring-1 ring-black/5">

                <!-- HEADER -->
                <div class="flex items-center justify-between bg-gray-100 rounded-t-xl border-b px-6 py-4">
                    <h5 id="tituloModal" class="text-xl font-bold text-gray-800">
                        Cadastrar Pessoa
                    </h5>

                    <button type="button"
                        class="inline-flex h-9 w-9 items-center justify-center rounded-md text-gray-600 hover:bg-gray-200 transition"
                        data-twe-modal-dismiss aria-label="Close">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- BODY -->
                <form action="{{ route('pessoas.adicionar') }}" method="get" enctype="multipart/form-data"

                    @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                <div>
                    <label class="font-semibold">Nome</label>
                    <input type="text" id="nome" name="nome"
                         class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500"
                        
                    >
                </div>

                <div>
                    <label class="block font-semibold">E-mail</label>
                    <input type="email" name="email" 
                    class="w-full border rounded px-3 py-2">
                </div>

                <div>
                    <label class="block font-semibold">Avatar</label>
                    <div class="flex items-center gap-4">
                        @php
                            use Illuminate\Support\Str;

                            $avatar = $pessoas->avatar ?? null;

                            // padrão
                            $avatarUrl = asset('avatars/imgs/nobody.png');

                            if ($avatar) {

                                // URL completa (http/https)
                                if (Str::startsWith($avatar, ['http://', 'https://'])) {
                                    $avatarUrl = $avatar;

                                } else {

                                    // remove barras iniciais
                                    $avatarClean = ltrim($avatar, '/');

                                    // remove "public/" e "imgs/" se vierem do banco
                                    $avatarClean = Str::replaceFirst('public/', '', $avatarClean);
                                    $avatarClean = Str::replaceFirst('imgs/', '', $avatarClean);

                                    // remove "avatars/imgs/" se vier duplicado
                                    $avatarClean = Str::replaceFirst('avatars/imgs/', '', $avatarClean);

                                    // agora sempre monta o caminho correto
                                    $avatarUrl = asset('avatars/imgs/' . $avatarClean);
                                }
                            }
                        @endphp

                        <img id="avatar-preview"
                            src="{{ $avatarUrl }}"
                            class="w-16 h-16 rounded border"
                        />
                        <input type="file" name="avatar" id="avatar" accept="image/*">
                    </div>
                </div>

                <div>
                    <label class="block font-semibold">Data Nasc.</label>
                    <input type="text" name="dt_nascimento" 
                    class="w-full border rounded px-3 py-2">
                </div>

                <div>
                    <label class="block font-semibold">CPF / CNPJ</label>
                    <input type="text" name="cpf_cnpj" 
                    class="w-full border rounded px-3 py-2">
                </div>

                <div>
                    <label class="block font-semibold">RG / IE</label>
                    <input type="text" name="rg_ie" 
                    class="w-full border rounded px-3 py-2">
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
                        <input type="text" id="logradouro" name="logradouro" 
                        class="w-full border rounded px-3 py-2">
                    </div>

                    <div class="w-1/2">
                        <label class="block font-semibold">Endereço</label>
                        <input type="text" id="endereco" name="endereco" 
                        class="w-full border rounded px-3 py-2">
                    </div>

                    <div class="w-1/4">
                        <label class="block font-semibold">Número</label>
                        <input type="text" name="nr" 
                        class="w-full border rounded px-3 py-2">
                    </div>
                </div>
                    
                <div class="flex gap-4">
                    <div class="w-1/4">
                        <label class="block font-semibold">complemento</label>
                        <input type="text" name="complemento" 
                        class="w-full border rounded px-3 py-2">
                    </div>

                    <div class="w-1/4">
                        <label class="block font-semibold">cep</label>
                        <input type="text" id="cep" onblur="buscarCep()" name="cep" 
                        class="w-full border rounded px-3 py-2">
                        
                    </div>

                    <div class="w-1/2">
                        <label class="block font-semibold">Bairro</label>
                        <input type="text" id="bairro" name="bairro" 
                        class="w-full border rounded px-3 py-2">
                    </div>
                </div>
            </div>

            <div class="flex gap-4">
                <div class="w-1/2">
                    <label class="block font-semibold">Associado</label>
                    <select name="associado" class="w-full border rounded px-3 py-2">
                        <option value="0" {{ $pessoas->associado === 0 ? 'selected' : '' }}>CLIENTE</option>
                        <option value="1" {{ $pessoas->associado === 1 ? 'selected' : '' }}>ADVOGADO</option>
                        <option value="2" {{ $pessoas->associado === 2 ? 'selected' : '' }}>CONVIDADO</option>
                    </select>
                </div>    
           
                <div class="w-1/3">
                    <label class="block font-semibold mb-1">Tipo Pessoa</label>

                    <select name="pessoa_tp" class="form-select w-full border rounded p-2">
                        <option value="">-- Selecione --</option>
                        <option value="F" {{ old('pessoa_tp', $pessoas->pessoa_tp) == 'F' ? 'selected' : '' }}>Física</option>
                        <option value="J" {{ old('pessoa_tp', $pessoas->pessoa_tp) == 'J' ? 'selected' : '' }}>Jurídica</option>
                    </select>
                </div>           
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
        </div>
    </div>

    </center>
@endsection

<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/dataTables.responsive.min.js') }}"></script>

<script type="module">
    const caminhoPublic = '<?php echo env('CAMINHO_PUBLIC'); ?>';
    const elemento = document.getElementById('listaRequerentes');
    const rotaEstados       = @json(route('carrega.estados'));
    const rotaMunicipiosTpl = @json(route('estados.municipios', ['esta_ibge' => 'UF_IBGE']));
    const preEstado    = '{{ $pessoas[0]->esta_ibge ?? "" }}';
    const preMunicipio = '{{ $pessoas[0]->muni_ibge ?? "" }}';
    const rotaCep  = "{{ url('api/cep') }}";

    $(document).ready(function(){
        $(window).on('beforeunload', function(){
                console.log('Carrega Pagina Lista Pessoas');
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
                scrollCollapse: true,
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
                table: '#listagem',
                columns: [
                            { data: 'avatar'    }
                            , { data: 'nome' }
                            , { data: 'email' }
                            , { data: 'rg_ie'}
                            , { data: 'cpf_cnpj'   }
                ],
                columnDefs: [
                                { targets: [0], orderData: [0, 1] }
                                , { targets: [1], orderData: [1, 0] }
                                , { targets: [2], orderData: [2, 0] }
                                , { targets: [3], orderData: [3, 0] }
                                , { targets: [4], orderData: [4, 0] }
                ],
                initComplete: function () {
                    // Define margem esquerda de 10px no campo de busca
                    $('div.dataTables_filter label').css({
                        'margin-left': '20px'
                    });
                },
                    dom: 'Bfrtip',
                    select: true,
                    buttons: ['colvis']
        });

        // Preview do avatar
        $('#avatar').on('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;
            const url = URL.createObjectURL(file);
            $('#avatar-preview').attr('src', url);
        });

    });

    // CEP
    // http://localhost/desenv/pk.com/web/public/api/cep/01001000
    // {"cep":"01001-000","logradouro":"Pra\u00e7a da S\u00e9","bairro":"S\u00e9","cidade":"S\u00e3o Paulo","estado":"SP"}
    const UF_TO_IBGE = {
        "AC": 12, "AL": 27, "AP": 16, "AM": 13, "BA": 29,
        "CE": 23, "DF": 53, "ES": 32, "GO": 52, "MA": 21,
        "MT": 51, "MS": 50, "MG": 31, "PA": 15, "PB": 25,
        "PR": 41, "PE": 26, "PI": 22, "RJ": 33, "RN": 24,
        "RS": 43, "RO": 11, "RR": 14, "SC": 42, "SP": 35,
        "SE": 28, "TO": 17
    };

    // ===============================
    // FUNÇÃO PARA BUSCAR CEP
    // ===============================
    async function buscarCep() {
        let cep = document.getElementById('cep').value.replace(/\D/g, '');
        if (cep.length !== 8) return;

        try {
            let resposta = await fetch(`${rotaCep}/${cep}`);
            if (!resposta.ok) throw new Error('Erro ao consultar CEP');

            let dados = await resposta.json();

            if (dados.erro) {
                alert(dados.erro);
                return;
            }

            // Preenchendo campos de endereço
            document.getElementById('logradouro').value = dados.logradouro || "";
            document.getElementById('endereco').value   = dados.logradouro || "";
            document.getElementById('bairro').value     = dados.bairro || "";

            // ===============================
            // CONVERTE UF → IBGE
            // ===============================
            let ibge = UF_TO_IBGE[dados.estado];

            if (!ibge) {
                console.error("UF não encontrada no mapa IBGE:", dados.estado);
                return;
            }

            document.getElementById('estado').value = ibge;

            // ===============================
            // CARREGA MUNICÍPIOS DO ESTADO
            // ===============================
            await loadMunicipios(ibge);

            // Seleciona o município pelo nome vindo da API
            $('#municipio option').filter(function() {
                return $(this).text().trim() === dados.cidade.trim();
            }).prop('selected', true);

        } catch (error) {
            console.error(error);
            alert(error.message);
        }
    }

    // ===============================
    // CARREGAR MUNICÍPIOS (IBGE)
    // ===============================
    async function loadMunicipios(estadoIbge) {
        try {
            let response = await fetch(`${rotaMunicipios}/${estadoIbge}`);
            if (!response.ok) throw new Error("Erro ao carregar municípios");

            let municipios = await response.json();
            let select = document.getElementById('municipio');

            select.innerHTML = '<option value="">Selecione...</option>';

            municipios.forEach(m => {
                let opt = document.createElement('option');
                opt.value = m.id;
                opt.textContent = m.nome;
                select.appendChild(opt);
            });

        } catch (e) {
            console.error("Erro ao carregar municípios:", e);
        }
    }

    // ===============================
    // EVENTO NO CAMPO DE CEP
    // ===============================
    document.getElementById('cep').addEventListener('blur', buscarCep);

</script>

