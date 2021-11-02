<?php

namespace App\Exports;

use DB;
use App\Models\Registro;
use Maatwebsite\Excel\Excel;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
// use Maatwebsite\Excel\Concerns\FromView;

class RegistroExport implements FromCollection, WithHeadings
{

    use Exportable;

    /**
    * @return \Illuminate\Support\Collection
    */

    protected $pesquisa;

    function __construct($pesquisa) {
        $this->pesquisa = $pesquisa;
    }

     /**
    * It's required to define the fileName within
    * the export class when making use of Responsable.
    */
    private $fileName = 'registros.xlsx';
    
    /**
    * Optional Writer Type
    */
    private $writerType = Excel::XLSX;
    
    /**
    * Optional headers
    */
    // private $headers = [
    //     'Content-Type' => 'text/csv',
    // ];

    public function collection()
    {
        
        switch ($this->pesquisa->pesquisa_opcao) {
            case 1:
                $this->pesquisa->pesquisa_opcao = 'registro.nome';
            break;

            case 2:
                $this->pesquisa->pesquisa_opcao = 'registro.sobrenome'; 
            break;

            case 3:
                $this->pesquisa->pesquisa_opcao = 'registro.local_registro'; 
            break;

            default:
                $this->pesquisa->pesquisa_opcao = 'registro.nome'; 
            break;
        
        }

        $dados = array();  
        $dados = (object) $dados;

        $dados = Registro::select(DB::raw("

        registro.id,
        DATE_FORMAT(registro.data_registro,'%d/%m/%Y') as DATA_REGISTRO,
        DATE_FORMAT(registro.data_fato,'%d/%m/%Y') as DATA_FATO,
        tr.descricao AS TIPO_REGISTRO,
        tlr.descricao AS TIPO_LOCAL_REGISTRO,
        registro.local_registro AS LOCAL_REGISTRO,
        registro.nome,
        registro.sobrenome,
        registro.nome_pai AS NOME_PAI,
        registro.sobrenome_pai AS SOBRENOME_PAI,
        registro.nome_mae AS NOME_MAE,
        registro.sobrenome_mae AS SOBRENOME_MAE,
        registro.livro AS LIVRO,
        registro.folha AS FOLHA,
        registro.termo AS TERMO,
        uf.sigla AS UF,
        ci.descricao AS CIDADE,
        de.descricao AS DECLARANTE,
        registro.declarante_terceiro,
        ns.descricao AS NACIONALIDADE_SOBRENOME,
        cv.descricao AS ESTADO_CIVIL,
        registro.nome_conjuge AS NOME_CONJUGE,
        registro.sobrenome_conjuge AS SOBRENOME_CONJUGE,
        rl.descricao AS RELIGIAO,
        CASE
            WHEN registro.avos_registrados = 0 THEN 'Não'
            WHEN registro.avos_registrados = 1 THEN 'Sim'
        END AS AVOS_REGISTRADOS,
        registro.nome_avo_paterno AS NOME_AVO_PATERNO,
        registro.sobrenome_avo_paterno AS SOBRENOME_AVO_PATERNO,
        registro.nome_avo_paterna AS NOME_AVO_PATERNA,
        registro.sobrenome_avo_paterna AS SOBRENOME_AVO_PATERNA,
        registro.nome_avo_materno AS NOME_AVO_MATERNO,
        registro.sobrenome_avo_materno AS SOBRENOME_AVO_MATERNO,
        registro.nome_avo_materna AS NOME_AVO_MATERNA,
        registro.sobrenome_avo_materna AS SOBRENOME_AVO_MATERNA

    
           "))
            ->leftjoin('tipo_registro AS tr', 'registro.fk_tipo_registro', '=', 'tr.id')
            ->leftjoin('tipo_local_registro AS tlr', 'registro.fk_tipo_local_registro', '=', 'tlr.id')
            ->leftjoin('cidade AS ci', 'registro.fk_cidade', '=', 'ci.id')
            ->leftjoin('uf', 'ci.fk_uf', '=', 'uf.id')
            ->leftjoin('declarante AS de', 'registro.fk_declarante', '=', 'de.id')
            ->leftjoin('nacionalidade_sobrenome AS ns', 'registro.fk_nacionalidade_sobrenome', '=', 'ns.id')
            ->leftjoin('estado_civil AS cv', 'registro.fk_estado_civil', '=', 'cv.id')
            ->leftjoin('religiao AS rl', 'registro.fk_religiao', '=', 'rl.id')
            ->where( $this->pesquisa->pesquisa_opcao ,'LIKE','%'.$this->pesquisa->pesquisa_texto.'%')
            ->where('registro.fk_tipo_registro', 'LIKE', $this->pesquisa->pesquisa_tipo_registro)
            ->where('registro.fk_tipo_local_registro', 'LIKE', $this->pesquisa->pesquisa_tipo_local_registro)
            ->get();

        return $dados;

    }

   

     /**
     * Write code on Method
     *
     * @return response()
     */
    public function headings() :array
    {
        return [
            "ID",
            "DATA_REGISTRO",
            "DATA_FATO",
            "TIPO_REGISTRO",
            "TIPO_LOCAL_REGISTRO",
            "LOCAL_REGISTRO",
            "NOME",
            "SOBRENOME",
            "NOME_PAI",
            "SOBRENOME_PAI",
            "NOME_MAE",
            "SOBRENOME_MAE",
            "LIVRO",
            "FOLHA",
            "TERMO",
            "UF",
            "CIDADE",
            "DECLARANTE",
            "DECLARANTE_TERCEIRO",
            "NACIONALIDADE_SOBRENOME",
            "ESTADO_CIVIL",
            "NOME_CONJUGE",
            "SOBRENOME_CONJUGE",
            "RELIGIAO",
            "AVOS_REGISTRADOS",
            "NOME_AVO_PATERNO",
            "SOBRENOME_AVO_PATERNO",
            "NOME_AVO_PATERNA",
            "SOBRENOME_AVO_PATERNA",
            "NOME_AVO_MATERNO",
            "SOBRENOME_AVO_MATERNO",
            "NOME_AVO_MATERNA",
            "SOBRENOME_AVO_MATERNA",
            ];
    }

}
