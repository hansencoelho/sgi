<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>DATA_REGISTRO</th>
            <th>DATA_FATO</th>
            <th>TIPO_REGISTRO</th>
            <th>TIPO_LOCAL_REGISTRO</th>
            <th>LOCAL_REGISTRO</th>
            <th>NOME</th>
            <th>SOBRENOME</th>
            <th>NOME_PAI</th>
            <th>SOBRENOME_PAI</th>
            <th>NOME_MAE</th>
            <th>SOBRENOME_MAE</th>
            <th>LIVRO</th>
            <th>FOLHA</th>
            <th>TERMO</th>
            <th>UF</th>
            <th>CIDADE</th>
            <th>DECLARANTE</th>
            <th>DECLARANTE_TERCEIRO</th>
            <th>NACIONALIADE</th>
            <th>ESTADO_CIVIL</th>
            <th>NOME_CONJUGE</th>
            <th>SOBRENOME_CONJUGE</th>
            <th>RELIGIAO</th>
        </tr>
    </thead>
    <tbody>
    @foreach($registros as $registro)
        <tr>
            <td>{{ $registro->registro.id }} </td>             
            <td>{{ $registro->registro.data_registro }} </td>       
            <td>{{ $registro->registro.data_fato }} </td>           
            <td>{{ $registro->tr.descricao        
            <td>{{ $registro->tlr.descricao               
            <td>{{ $registro->registro.local_registro     
            <td>{{ $registro->registro.nome               
            <td>{{ $registro->registro.sobrenome          
            <td>{{ $registro->registro.nome_pai           
            <td>{{ $registro->registro.sobrenome_pai      
            <td>{{ $registro->registro.nome_mae           
            <td>{{ $registro->registro.sobrenome_mae      
            <td>{{ $registro->registro.livro              
            <td>{{ $registro->registro.folha              
            <td>{{ $registro->registro.termo              
            <td>{{ $registro->uf.sigla                    
            <td>{{ $registro->ci.descricao                
            <td>{{ $registro->de.descricao                
            <td>{{ $registro->registro.declarante_terceiro
            <td>{{ $registro->ns.descricao                
            <td>{{ $registro->cv.descricao                
            <td>{{ $registro->registro.nome_conjuge       
            <td>{{ $registro->registro.sobrenome_conjuge  
            <td>{{ $registro->rl.descricao                
        </tr>
    @endforeach
    </tbody>
</table>