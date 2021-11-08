<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Gate;
use DB;
use App\Models\Registro;
use App\Models\TipoRegistro;
use App\Models\TipoLocalRegistro;
use App\Models\Declarante;
use App\Models\EstadoCivil;
use App\Models\Uf;
use App\Models\Cidade;
use App\Models\Religiao;
use App\Models\NacionalidadeSobrenome;
use App\Models\CaminhoArquivo;
use App\Exports\RegistroExport;
use Illuminate\Support\Facades\Storage;
use File;
use Auth;
use Response;
use Filesystem;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;

class ControllerRegistro extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {

        if (Auth::User()->primeiro_login == 1) {
            return redirect()->route('alterar_senha'); 
        }
        
        if( Gate::denies('registro-view')) {

        abort(403, 'Não autorizado');

        } else {

        
        $registro = Registro::select(

            'registro.id                AS id',
            'data_registro              AS data_registro',
            'nome                       AS nome',
            'sobrenome                  AS sobrenome',
            'nome_pai                   AS nome_pai',
            'nome_mae                   AS nome_mae',
            'nome_pai                   AS nome_pai',
            'livro                      AS livro',
            'folha                      AS folha',
            'termo                      AS termo',
            'tr.descricao               AS tipo_registro',
            're.descricao               AS religicao',
            'local_registro             AS local_registro',
            'tlr.descricao              AS tipo_local_registro',


        )

        ->leftjoin('tipo_registro AS tr', 'registro.fk_tipo_registro', '=', 'tr.id')
        ->leftjoin('religiao AS re', 'registro.fk_religiao', '=', 're.id')
        ->leftjoin('tipo_local_registro AS tlr', 'registro.fk_tipo_local_registro', '=', 'tlr.id')
        ->orderBy('nome', 'ASC')
        ->get();

        $tipo_registro = TipoRegistro::all();
        $tipo_local_registro = TipoLocalRegistro::all();

        $contagem = $registro->count();
        $titulo = 'Registro';
        

        return view('registro.view', compact(
            'registro',
            'tipo_registro',
            'tipo_local_registro',
            'titulo',
            'contagem',
            
        ));

        }
    }

    public function find(Request $request)
    {
        if( Gate::denies('registro-view')) {

            abort(403, 'Não autorizado');
    
            } else {

                // dd($request);

            $tipo_registro          = $request->pesquisa_tipo_registro;
            $tipo_local_registro    = $request->pesquisa_tipo_local_registro;
            $pesquisa_opcao         = $request->pesquisa_opcao;
            $texto                  = $request->pesquisa_texto;


            switch ($pesquisa_opcao) {
            case 1:
                $pesquisa_opcao = 'registro.nome';
                break;
            case 2:
                $pesquisa_opcao = 'registro.sobrenome'; 
                break;
            case 3:
                $pesquisa_opcao = 'registro.local_registro'; 
                break;

            default:
                $pesquisa_opcao = 'registro.nome'; 
                break;
        
            }
        
            $registro = Registro::select(

                'registro.id                AS id',
                'data_registro              AS data_registro',
                'nome                       AS nome',
                'sobrenome                  AS sobrenome',
                'nome_pai                   AS nome_pai',
                'nome_mae                   AS nome_mae',
                'nome_pai                   AS nome_pai',
                'livro                      AS livro',
                'folha                      AS folha',
                'termo                      AS termo',
                'tr.descricao               AS tipo_registro',
                're.descricao               AS religicao',
                'local_registro             AS local_registro',
                'tlr.descricao              AS tipo_local_registro',
    
            )
    
            ->leftjoin('tipo_registro AS tr', 'registro.fk_tipo_registro', '=', 'tr.id')
            ->leftjoin('religiao AS re', 'registro.fk_religiao', '=', 're.id')
            ->leftjoin('tipo_local_registro AS tlr', 'registro.fk_tipo_local_registro', '=', 'tlr.id')

            ->where( $pesquisa_opcao ,'LIKE','%'.$request->pesquisa_texto.'%')
            ->where('registro.fk_tipo_registro', 'LIKE', $request->pesquisa_tipo_registro)
            ->where('registro.fk_tipo_local_registro', 'LIKE', $request->pesquisa_tipo_local_registro)

            ->orderBy('nome', 'ASC')
            ->get();

            $tipo_registro = TipoRegistro::all();
            $tipo_local_registro = TipoLocalRegistro::all();

            $contagem = $registro->count();
            $titulo = 'Registro';

            $pesquisa_retorno = [
                'pesquisa_tipo_registro' => $request->pesquisa_tipo_registro,
                'pesquisa_tipo_local_registro' => $request->pesquisa_tipo_local_registro,
                'pesquisa_opcao' => $request->pesquisa_opcao,
                'pesquisa_texto' => $request->pesquisa_texto,
            ];        

            return view('registro.view', compact(
                'registro',
                'tipo_registro',
                'tipo_local_registro',
                'contagem',
                'titulo',
                'pesquisa_retorno',
                
            ));

        }
    }

    public function exportar(Request $request)
    {

        if( Gate::denies('registro-view')) {

            abort(403, 'Não autorizado');
    
            } else {


            $pesquisa = array();  
            $pesquisa = (object) $pesquisa;

            $pesquisa->pesquisa_tipo_registro           = $request->pesquisa_tipo_registro;
            $pesquisa->pesquisa_tipo_local_registro     = $request->pesquisa_tipo_local_registro;
            $pesquisa->pesquisa_opcao                   = $request->pesquisa_opcao;
            $pesquisa->pesquisa_texto                   = $request->pesquisa_texto;

            return (new RegistroExport($pesquisa))->download('registros.xlsx', \Maatwebsite\Excel\Excel::XLSX);

        }
    }

    public function obtem_dados_exportacao($pesquisa){

        switch ($pesquisa->pesquisa_opcao) {
            case 1:
                $pesquisa->pesquisa_opcao = 'registro.nome';
                break;
            case 2:
                $pesquisa->pesquisa_opcao = 'registro.sobrenome'; 
                break;
            case 3:
                $pesquisa->pesquisa_opcao = 'registro.local_registro'; 
                break;

            default:
            $pesquisa->pesquisa_opcao = 'registro.nome'; 
                break;
        
        }

        $dados = array();  
        $dados = (object) $dados;

        $dados->registro = Registro::select(

            'registro.*',
            'tr.descricao       AS TIPO_REGISTRO',
            'tlr.descricao      AS TIPO_LOCAL_REGISTRO',
            'uf.sigla           AS UF',
            'ci.descricao       AS CIDADE',
            'de.descricao       AS DECLARANTE',
            'ns.descricao       AS NACIONALIADE',
            'cv.descricao       AS ESTADO_CIVIL',
            'rl.descricao       AS RELIGIAO',
    
            )
            ->leftjoin('tipo_registro AS tr', 'registro.fk_tipo_registro', '=', 'tr.id')
            ->leftjoin('tipo_local_registro AS tlr', 'registro.fk_tipo_local_registro', '=', 'tlr.id')
            ->leftjoin('cidade AS ci', 'registro.fk_cidade', '=', 'ci.id')
            ->leftjoin('uf', 'ci.fk_uf', '=', 'uf.id')
            ->leftjoin('declarante AS de', 'registro.fk_declarante', '=', 'de.id')
            ->leftjoin('nacionalidade_sobrenome AS ns', 'registro.fk_nacionalidade_sobrenome', '=', 'ns.id')
            ->leftjoin('estado_civil AS cv', 'registro.fk_estado_civil', '=', 'cv.id')
            ->leftjoin('religiao AS rl', 'registro.fk_religiao', '=', 'rl.id')
            ->where( $pesquisa->pesquisa_opcao ,'LIKE','%'.$pesquisa->pesquisa_texto.'%')
            ->where('registro.fk_tipo_registro', 'LIKE', $pesquisa->pesquisa_tipo_registro)
            ->where('registro.fk_tipo_local_registro', 'LIKE', $pesquisa->pesquisa_tipo_local_registro)
            ->get();

            return $dados->registro;

    }



    public function create()
    {

        if( Gate::denies('registro-view')) {

            abort(403, 'Não autorizado');
    
        } else {

        $dados = array();  
        $dados = (object) $dados;
        
        $dados->tipo_registro = TipoRegistro::all();
        $dados->tipo_local_registro = TipoLocalRegistro::all();
        $dados->declarante = Declarante::all();
        $dados->estado_civil = EstadoCivil::all();
        $dados->nacionalidade_sobrenome = NacionalidadeSobrenome::all();

        $dados->titulo = "Novo - Registro";

        return response()->json($dados);

        }

    }

    public function store(Request $request)
    {

        // dd($request);

        if( Gate::denies('registro-create')) {

            abort(403, 'Não autorizado. Você não tem permissão de editar.');
    
        } else {

            $registro = new Registro;

            $registro->data_registro                   = $request->data_fato;
            $registro->data_fato                       = $request->data_registro;
            $registro->termo                           = $request->termo;
            $registro->folha                           = $request->folha;
            $registro->livro                           = $request->livro;
            $registro->fk_cidade                       = $request->id_cidade;
            $registro->nome                            = $request->nome;
            $registro->sobrenome                       = $request->sobrenome;
            $registro->nome_pai                        = $request->nome_pai;
            $registro->sobrenome_pai                   = $request->sobrenome_pai;
            $registro->nome_mae                        = $request->nome_mae;
            $registro->sobrenome_mae                   = $request->sobrenome_mae;
            $registro->fk_tipo_local_registro          = $request->tipo_local_registro;
            $registro->local_registro                  = $request->local_registro;
            $registro->fk_nacionalidade_sobrenome      = $request->nacionalidade_sobrenome;
            $registro->fk_religiao                     = $request->id_religiao;

            switch ($request->tipo_registro) {

                case 1:
                    $registro->fk_tipo_registro                = $request->tipo_registro;
                    $registro->fk_declarante                   = $request->declarante;
                    $registro->declarante_terceiro             = $request->declarante_terceiro;
                    break;

                case 2:
                    $registro->fk_tipo_registro                = $request->tipo_registro;
                    $registro->fk_estado_civil                 = $request->estado_civil;
                    $registro->nome_conjuge                    = $request->nome_conjuge;
                    $registro->sobrenome_conjuge              = $request->sobrenome_conjuge;
                    break;

                case 3:
                    $registro->fk_tipo_registro                = $request->tipo_registro;
                    $registro->fk_declarante                   = $request->declarante;
                    $registro->declarante_terceiro             = $request->declarante_terceiro;
                    $registro->fk_estado_civil                 = $request->estado_civil;
                    $registro->nome_conjuge                    = $request->nome_conjuge;
                    $registro->sobrenome_conjuge              = $request->sobrenome_conjuge;
                    break;

                default:
                    $registro->fk_tipo_registro                = 1;
                break;
            
                }

            $registro->avos_registrados                = $request->avos_registrados;
            
            if ($request->avos_registrados == 1) {

                $registro->nome_avo_paterno                = $request->nome_avo_paterno;
                $registro->sobrenome_avo_paterno           = $request->sobrenome_avo_paterno;
                $registro->nome_avo_paterna                = $request->nome_avo_paterna;
                $registro->sobrenome_avo_paterna           = $request->sobrenome_avo_paterna;
                $registro->nome_avo_materno                = $request->nome_avo_materno;
                $registro->sobrenome_avo_materno           = $request->sobrenome_avo_materno;
                $registro->nome_avo_materna                = $request->nome_avo_materna;
                $registro->sobrenome_avo_materna           = $request->sobrenome_avo_materna;

            }

            $insert = $registro->save();

            if ($request->multiplos_arquivos != "") {

                foreach ($request->multiplos_arquivos as $key => $arquivo){

                    $key = $key + 1;
                    $extensao = $arquivo->getClientOriginalExtension();
                    $arquivo->storeAs('arquivos_registros'.'/'.$registro->id, $arquivo->getClientOriginalName());

                    $caminho_arquivo = new CaminhoArquivo;
                    $caminho_arquivo->diretorio         = $registro->id;
                    $caminho_arquivo->nome_arquivo      = $arquivo->getClientOriginalName();
                    $caminho_arquivo->fk_registro       = $registro->id;
                    $insert = $caminho_arquivo->save();

                }

            }

            $caminho_arquivo = CaminhoArquivo::select('*')
            ->where('fk_registro', '=', $registro->id)
            ->get();

            $dados = array();  
            $dados = (object) $dados;

            $dados->id_registro = $registro->id;

            if ($caminho_arquivo !== "[]") {

                $dados->arquivos = $caminho_arquivo;
    
            } else {

                $dados->arquivos = 0;

            }

            $dados->resposta_mensagem = "Registro salvo com sucesso!";
            
            return response()->json($dados);

        }

    }

    public function arquivo($id_arquivo)
    {

        $caminho_arquivo = CaminhoArquivo::select('*')
        ->where('id', '=', $id_arquivo)
        ->get();

        if ($caminho_arquivo == "[]") {

            return 0;

        } else {

            foreach ($caminho_arquivo as $arquivo) {

            $extensao = explode(".", $arquivo->nome_arquivo);

                if ($extensao[1] == "pdf") {

                    $file = Storage::get('arquivos_registros/'.$arquivo->diretorio.'/'.$arquivo->nome_arquivo);

                    return Response::make($file, 200, [
                        'Content-Type' => 'application/pdf',
                        'Content-Disposition' => 'inline; filename="'.$arquivo->nome_arquivo
                    ]);


                } else {

                    return Storage::download('arquivos_registros/'.$arquivo->diretorio.'/'.$arquivo->nome_arquivo);

                }
          
            }
        
        }

    }



    public function show($id_registro)
    {

        if( Gate::denies('registro-view')) {

            abort(403, 'Não autorizado');
    
        } else {

        $dados = array();  
        $dados = (object) $dados;

        $dados->registro = Registro::select(

            'registro.*',
            'tr.descricao       AS ds_tipo_registro',
            'tlr.descricao      AS ds_tipo_local_registro',
            'uf.id              AS id_uf',
            'uf.sigla           AS ds_uf',
            'ci.id              AS id_cidade',
            'ci.descricao       AS ds_cidade',
            'de.descricao       AS ds_declarante',
            'ns.descricao       AS ds_nacionalidade_sobrenome',
            'cv.descricao       AS ds_estado_civil',
            'rl.id              AS ds_religiao',
            'rl.descricao       AS ds_religiao',
    
            )
            ->leftjoin('tipo_registro AS tr', 'registro.fk_tipo_registro', '=', 'tr.id')
            ->leftjoin('tipo_local_registro AS tlr', 'registro.fk_tipo_local_registro', '=', 'tlr.id')
            ->leftjoin('cidade AS ci', 'registro.fk_cidade', '=', 'ci.id')
            ->leftjoin('uf', 'ci.fk_uf', '=', 'uf.id')
            ->leftjoin('declarante AS de', 'registro.fk_declarante', '=', 'de.id')
            ->leftjoin('nacionalidade_sobrenome AS ns', 'registro.fk_nacionalidade_sobrenome', '=', 'ns.id')
            ->leftjoin('estado_civil AS cv', 'registro.fk_estado_civil', '=', 'cv.id')
            ->leftjoin('religiao AS rl', 'registro.fk_religiao', '=', 'rl.id')
            ->where('registro.id' , '=' , $id_registro)
            ->get();

        $caminho_arquivo = CaminhoArquivo::select('*')
        ->where('fk_registro', '=', $id_registro)
        ->get();


        if ($caminho_arquivo !== "[]") {

            $dados->arquivos = $caminho_arquivo;

        } else {

            $dados->arquivos = 0;

        }

        $dados->tipo_registro = TipoRegistro::all();
        $dados->tipo_local_registro = TipoLocalRegistro::all();
        $dados->declarante = Declarante::all();
        $dados->tipo_registro = TipoRegistro::all();
        $dados->estado_civil = EstadoCivil::all();
        $dados->nacionalidade_sobrenome = NacionalidadeSobrenome::all();

        $dados->titulo = "Visualizar - Registro";
  
        return response()->json($dados);

        }

    }

    public function update(Request $request)
    {
        if(Gate::denies('registro-edit')) {

            abort(403, 'Não autorizado. Você não tem permissão de editar.');
    
        } else {

            $update = Registro::where('id', '=', $request->id)->update([

                'data_registro'                   => $request->data_fato,
                'data_fato'                       => $request->data_registro,
                'termo'                           => $request->termo,
                'folha'                           => $request->folha,
                'livro'                           => $request->livro,
                'fk_cidade'                       => $request->id_cidade,
                'nome'                            => $request->nome,
                'sobrenome'                       => $request->sobrenome,
                'nome_pai'                        => $request->nome_pai,
                'sobrenome_pai'                   => $request->sobrenome_pai,
                'nome_mae'                        => $request->nome_mae,
                'sobrenome_mae'                   => $request->sobrenome_mae,
                'fk_tipo_registro'                => $request->tipo_registro,
                'fk_tipo_local_registro'          => $request->tipo_local_registro,
                'local_registro'                  => $request->local_registro,
                'fk_nacionalidade_sobrenome'      => $request->nacionalidade_sobrenome,
                'fk_religiao'                     => $request->id_religiao,
                'fk_declarante'                   => $request->declarante,
                'declarante_terceiro'             => $request->declarante_terceiro,
                'fk_estado_civil'                 => $request->estado_civil,
                'nome_conjuge'                    => $request->nome_conjuge,
                'sobrenome_conjuge'               => $request->sobrenome_conjuge,
                'nome_avo_paterno'                => $request->nome_avo_paterno,
                'sobrenome_avo_paterno'           => $request->sobrenome_avo_paterno,
                'nome_avo_paterna'                => $request->nome_avo_paterna,
                'sobrenome_avo_paterna'           => $request->sobrenome_avo_paterna,
                'nome_avo_materno'                => $request->nome_avo_materno,
                'sobrenome_avo_materno'           => $request->sobrenome_avo_materno,
                'nome_avo_materna'                => $request->nome_avo_materna,
                'sobrenome_avo_materna'           => $request->sobrenome_avo_materna,

            ]);

            if ($update == 1) {

                $registro = array();  
                $registro = (object) $registro;

                $registro->id = $request->id;

                if ($request->multiplos_arquivos != "") {

                    foreach ($request->multiplos_arquivos as $key => $arquivo){

                        $key = $key + 1;
                        $extensao = $arquivo->getClientOriginalExtension();
                        $arquivo->storeAs('arquivos_registros'.'/'.$registro->id, $arquivo->getClientOriginalName());

                        $caminho_arquivo = new CaminhoArquivo;
                        $caminho_arquivo->diretorio         = $registro->id;
                        $caminho_arquivo->nome_arquivo      = $arquivo->getClientOriginalName();
                        $caminho_arquivo->fk_registro       = $registro->id;
                        $insert = $caminho_arquivo->save();

                    }

                }

                $caminho_arquivo = CaminhoArquivo::select('*')
                ->where('fk_registro', '=', $registro->id)
                ->get();

                $dados = array();  
                $dados = (object) $dados;

                $dados->id_registro = $registro->id;

                if ($caminho_arquivo !== "[]") {

                    $dados->arquivos = $caminho_arquivo;
        
                } else {

                    $dados->arquivos = 0;

                }

            $dados->resposta_mensagem = "Registro atualizado com sucesso!";
            
            return response()->json($dados);

            } else {

                return 0;

            }
                    
        }

    }

    public function delete(Request $request)
    {

        if( Gate::denies('registro-view')) {

            abort(403, 'Não autorizado. Você não tem permissão de visualizar.');
    
        } else {


            $caminho_arquivo = CaminhoArquivo::select('*')
            ->where('fk_registro', '=', $request->id_registro)
            ->get();

            if ($caminho_arquivo !== "[]") {

                foreach ($caminho_arquivo as $arquivo){

                $delete = CaminhoArquivo::where('id', $arquivo->id )->delete();

                }
    
            }

            $delete = Registro::where('id', $request->id_registro)->delete();

            $delete_diretorio = Storage::deleteDirectory('arquivos_registros/'.$request->id_registro);

            if ($delete != 0) {

                $retorno = 1; 

            } else {

                $retorno = 0;

            }

            return response()->json($retorno);

        }

    }

    public function delete_arquivo(Request $request)
    {

        if( Gate::denies('registro-view')) {

            abort(403, 'Não autorizado. Você não tem permissão de visualizar.');
    
        } else {

            $caminho_arquivo = CaminhoArquivo::select('*')
            ->where('fk_registro', '=', $request->id_registro)
            ->get();

            $delete_registro = CaminhoArquivo::where('id', $request->id_arquivo )->delete();

            $delete_arquivos = Storage::delete('arquivos_registros/'.$request->id_registro.'/'.$caminho_arquivo[0]->nome_arquivo);

            if ($delete_registro != 0) {

                $retorno = 1; 

            } else {

                $retorno = 0;

            }

            return response()->json($retorno);

        }

    }

    public function autocomplete_uf(Request $request)
    {

        if( Gate::denies('registro-view')) {

            abort(403, 'Não autorizado. Você não tem permissão de visualizar.');
    
        } else {

        $uf = Uf::select(
        'id AS id',
        'sigla AS value',
        )
        ->where('sigla' , 'like' , '%'.$request->term.'%')
        ->get();

        return response()->json($uf);

        }

    }

    public function autocomplete_cidade(Request $request)
    {

        if( Gate::denies('registro-view')) {

            abort(403, 'Não autorizado. Você não tem permissão de visualizar.');
    
        } else {

        $cidade = Cidade::select(
        'cidade.id AS id',
        'cidade.descricao AS value',
        'cidade.fk_uf AS fk_uf',
        )
        ->leftjoin('uf', 'cidade.fk_uf', '=', 'uf.id')
        ->where('cidade.descricao' , 'like' , '%'.$request->term.'%')
        ->where('uf.id' , '=' , $request->id_uf)
        ->get();

        return response()->json($cidade);

        }

    }

    public function autocomplete_religiao(Request $request)
    {

        if( Gate::denies('registro-view')) {

            abort(403, 'Não autorizado. Você não tem permissão de visualizar.');
    
        } else {

        $religiao = Religiao::select(
        'id AS id',
        'descricao AS value',
        )
        ->where('descricao' , 'like' , '%'.$request->term.'%')
        ->get();

        return response()->json($religiao);

        }

    }


    
   


}
