<html>
    <body>
        <p>Olá!</p>
        <p>Sua senha do Sistema de Gestão de Identidades da empresa Gens Cidadanias foi alterada.</p>
        <p>Segue suas credenciais de acesso:</p>
        <p>Usuário: {{ $usuario->email }}</p>
        <p>Senha: {{ $usuario->senha }}</p>
        <p></p>
    </body>
</html>