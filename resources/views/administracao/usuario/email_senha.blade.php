<html>
    <body>
        <p>Olá!</p>
        <p>Você acaba de ser cadastrado no Sistema de Gestão de Identidades da empresa Gens Cidadanias</p>
        <p>Segue suas credenciais de acesso:</p>
        <p>Usuário: {{ $usuario->email }}</p>
        <p>Senha: {{ $usuario->senha }}</p>
        <p></p>
    </body>
</html>