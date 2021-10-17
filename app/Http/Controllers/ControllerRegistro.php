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
use Illuminate\Support\Facades\Storage;
use File;
use Auth;
use Response;

class ControllerRegistro extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        
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

        $contagem = $registro->count();
        $titulo = 'Registro';
        

        return view('registro.view', compact(
            'registro',
            'titulo',
            'contagem',
            
        ));

        }
    }

    public function find(Request $request)
    {
        if( Gate::denies('usuario-view')) {

            abort(403, 'Não autorizado');
    
            } else {

            $pesquisa_opcao = $request->pesquisa_opcao;
            $texto          = $request->pesquisa_texto;

            switch ($pesquisa_opcao) {
            case 1:
                $pesquisa_opcao = 'users.name';
                break;
            case 2:
                $pesquisa_opcao = 'email'; 
                break;
            case 3:
                $pesquisa_opcao = 'username'; 
                break;

            default:
                $pesquisa_opcao = 'name'; 
                break;
        
            }
        
            $usuario = User::select(
        
                'id                 AS ID_USUARIO',
                'name               AS NOME',
                'email              AS EMAIL',
                'username           AS USERNAME',
                'departamento       AS AREA',
                'gu.DESCRICAO       AS GRUPO_USUARIO',
                'FK_GRUPO_USUARIO   AS ID_GRUPO_USUARIO',    
                )
                ->leftjoin('acl_grupo_usuario AS gu', 'users.FK_GRUPO_USUARIO', '=', 'gu.ID_ACL_GRUPO_USUARIO')

                ->where($pesquisa_opcao, 'LIKE','%'.$texto.'%')
                ->orderby('name', 'ASC')
                ->get();

            $contagem = $usuario->count();
            $titulo = 'Usuário';

            $pesquisa_retorno = [
                'pesquisa_opcao' => $request->pesquisa_opcao,
                'pesquisa_texto' => $request->pesquisa_texto,
            ];        

            return view('admin.usuario.listagem', compact(
                'usuario',
                'contagem',
                'titulo',
                'pesquisa_retorno',
                
            ));

        }
    }

    public function exportar(Request $request)
    {
        if( Gate::denies('usuario-view')) {

            abort(403, 'Não autorizado');
    
            } else {

            $pesquisa_opcao = $request->pesquisa_opcao;
            $texto          = $request->pesquisa_texto;

            switch ($pesquisa_opcao) {
            case 1:
                $pesquisa_opcao = 'users.name';
                break;
            case 2:
                $pesquisa_opcao = 'email'; 
                break;
            case 3:
                $pesquisa_opcao = 'username'; 
                break;

            default:
                $pesquisa_opcao = 'name'; 
                break;
        
            }
        
            $usuario = User::select(
        
                'id                 AS ID_USUARIO',
                'name               AS NOME',
                'email              AS EMAIL',
                'username           AS USERNAME',
                'departamento       AS AREA',
                'gu.DESCRICAO       AS GRUPO_USUARIO',
                'FK_GRUPO_USUARIO   AS ID_GRUPO_USUARIO',    
                )
                ->leftjoin('acl_grupo_usuario AS gu', 'users.FK_GRUPO_USUARIO', '=', 'gu.ID_ACL_GRUPO_USUARIO')

                ->where($pesquisa_opcao, 'LIKE','%'.$texto.'%')
                ->orderby('name', 'ASC')
                ->get();

            $arquivo = 'Usuários.xls';

                // Criar uma tabela HTML com o formato da planilha
                $html = '<meta charset="UTF-8">';
                $html .= '<table class="table table-sm table-hover" border="1">';
                    $html .= '<div>';
                        $html .= '<thead>';
                            $html .= '<tr>';
                                $html .= '<th scope="col">ID</th>';
                                $html .= '<th scope="col">NOME</th>';
                                $html .= '<th scope="col">EMAIL</th>';
                                $html .= '<th scope="col">USUÁRIO</th>';
                                $html .= '<th scope="col">ÁREA</th>';
                                $html .= '<th scope="col">GRUPO DO USUÁRIO</th>';
                            $html .= '</tr>';
                        $html .= '</thead>';

                        $html .= '<tbody>';

                        foreach($usuario as $tb_usuario){

                            $html .= '<tr>';
                                $html .= '<td>'.$tb_usuario->ID_USUARIO.'</td>';
                                $html .= '<td>'.$tb_usuario->NOME.'</td>';
                                $html .= '<td>'.$tb_usuario->EMAIL.'</td>';
                                $html .= '<td>'.$tb_usuario->USERNAME.'</td>';
                                $html .= '<td>'.$tb_usuario->AREA.'</td>';
                                $html .= '<td>'.$tb_usuario->GRUPO_USUARIO.'</td>';
                            $html .= '</tr>';
                        }

                        $html .= '</tbody>';
                    $html .= '</table>';
        
                    # Configurações header para download
                    header ("Expires: 0");
                    header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
                    header ("Cache-Control: no-cache, must-revalidate");
                    header ("Pragma: no-cache");
                    header ("Content-type: application/x-msexcel");
                    header ("Content-Disposition: attachment; filename=\"{$arquivo}\"" );
                    header ("Content-Description: PHP Generated Data" );

                    echo $html;

                    exit;

        }
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
        $dados->tipo_registro = TipoRegistro::all();
        $dados->estado_civil = EstadoCivil::all();
        $dados->nacionalidade_sobrenome = NacionalidadeSobrenome::all();

        $dados->titulo = "Novo - Registro";

        return response()->json($dados);

        }

    }

    public function store(Request $request)
    {
        // dd ($request->multiplos_arquivos);
        if( Gate::denies('registro-create')) {

            return 0;
            abort(403, 'Não autorizado. Você não tem permissão de editar.');
    
        } else {

            if ($request->tipo_registro != 1 and 2 and 3) {

                return 0;

            }

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
            $registro->fk_tipo_registro                = $request->tipo_registro;
            $registro->fk_tipo_local_registro          = $request->tipo_local_registro;
            $registro->local_registro                  = $request->local_registro;
            $registro->fk_nacionalidade_sobrenome      = $request->nacionalidade_sobrenome;
            $registro->fk_religiao                     = $request->id_religiao;

            switch ($request->tipo_registro) {

                case 1:
                    $registro->fk_declarante                   = $request->declarante;
                    $registro->declarante_terceiro             = $request->declarante_terceiro;
                    break;

                case 2:
                    $registro->fk_estado_civil                 = $request->estado_civil;
                    $registro->nome_conjuge                    = $request->nome_conjuge;
                    $registro->sobrenome_conjunge              = $request->sobrenome_conjunge;
                    break;

                case 3:
                    $registro->fk_declarante                   = $request->declarante;
                    $registro->declarante_terceiro             = $request->declarante_terceiro;
                    $registro->fk_estado_civil                 = $request->estado_civil;
                    $registro->nome_conjuge                    = $request->nome_conjuge;
                    $registro->sobrenome_conjunge              = $request->sobrenome_conjunge;
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

                    // dd($arquivo->getClientOriginalName());

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



    public function show($id_usuario)
    {

        if( Gate::denies('registro-view')) {

            abort(403, 'Não autorizado');
    
        } else {

        $dados = array();  
        $dados = (object) $dados;
        
        // $dados->registro = 'show';
        // $dados['teste'] = 123;
        // $dados->show->registros =  [ 0 => 'karen', 1 => 'rodrigo'];

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
            ->where('registro.id' , '=' , $id_usuario)
            // ->orderBy('name', 'ASC')
            ->get();

        $dados->tipo_registro = TipoRegistro::all();
        $dados->tipo_local_registro = TipoLocalRegistro::all();
        $dados->declarante = Declarante::all();
        $dados->tipo_registro = TipoRegistro::all();
        $dados->estado_civil = EstadoCivil::all();
        // $dados->religiao = Religiao::all();
        // $dados->uf = Uf::all();
        // $dados->cidade = Cidade::all();
        $dados->nacionalidade_sobrenome = NacionalidadeSobrenome::all();

        // dd($dados);

        $dados->titulo = "Visualizar - Registro";
  
        

        // return view('admin.usuario.formulario', compact(
        //     'titulo',
        //     'grupo_usuario',
        //     'usuario',
        // ));

        return response()->json($dados);

        }

    }

    public function update(Request $request)
    {

        if( Gate::denies('usuario-edit')) {

            abort(403, 'Não autorizado. Você não tem permissão de editar.');
    
        } else {

            $update = User::where('id', '=', $request->ID_USUARIO)->update([

            'FK_GRUPO_USUARIO'          => $request->ID_GRUPO_USUARIO,

            ]);

            $usuario = User::select(
        
                'id                 AS ID_USUARIO',
                'name               AS NOME',
                'email              AS EMAIL',
                'username           AS USERNAME',
                'departamento       AS AREA',
                'gu.DESCRICAO       AS GRUPO_USUARIO',
                'FK_GRUPO_USUARIO   AS ID_GRUPO_USUARIO',
                'users.created_at   AS INTEGRADO',
                'users.updated_at   AS ATUALIZADO',
        
        
                )
                ->leftjoin('acl_grupo_usuario AS gu', 'users.FK_GRUPO_USUARIO', '=', 'gu.ID_ACL_GRUPO_USUARIO')
                ->where('id' , '=' , $request->ID_USUARIO)
                ->orderBy('name', 'ASC')
                ->get();
    
            $grupo_usuario = AclGrupoUsuario::all();
    
            $titulo = "Usuário";
            $mensagem = "Usuário alterado com sucesso.";
    
            return view('admin.usuario.formulario', compact(
                'titulo',
                'mensagem',
                'usuario',
            ));

                    
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
