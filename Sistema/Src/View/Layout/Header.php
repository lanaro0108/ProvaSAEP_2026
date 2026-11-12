<?php
$usuarioLogado = $_SESSION['usuario_nome'] ?? null;
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo ?? 'VetCare - Clínica Veterinária') ?></title>
    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #fff;
            margin: 0;
            color: #222;
        }

        header {
            background: #fff;
            padding: 16px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            border-bottom: 1px solid #ddd;
        }

        header h1 {
            font-size: 1.1rem;
            color: #222;
            margin: 0;
        }

        nav {
            display: flex;
            align-items: center;
            gap: 16px;
            flex-wrap: wrap;
        }

        nav a {
            color: #444;
            text-decoration: none;
            font-size: 0.9rem;
        }

        nav a:hover {
            text-decoration: underline;
        }

        .user-tag {
            color: #555;
            font-size: 0.85rem;
        }

        .container {
            max-width: 1100px;
            margin: 24px auto;
            padding: 0 16px;
        }

        .card {
            background: #fff;
            border-radius: 4px;
            padding: 20px;
            border: 1px solid #ddd;
        }

        h2, h3 {
            color: #222;
        }

        p {
            line-height: 1.5;
        }

        .grid-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 12px;
            margin-top: 16px;
        }

        .stat-box {
            background: #fff;
            padding: 16px;
            border: 1px solid #ddd;
        }

        .stat-box span {
            font-size: 0.8rem;
            color: #555;
        }

        .stat-box strong {
            display: block;
            font-size: 1.8rem;
            color: #222;
            margin-top: 6px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
            font-size: 0.9rem;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            color: #333;
            font-weight: 600;
            background: #f5f5f5;
        }

        .btn {
            display: inline-block;
            background: #333;
            color: white;
            padding: 8px 12px;
            border-radius: 3px;
            text-decoration: none;
            font-size: 0.875rem;
            border: none;
            cursor: pointer;
        }

        .btn:hover {
            background: #111;
        }

        .btn-success {
            background: #333;
        }

        .btn-success:hover {
            background: #111;
        }

        .btn-danger {
            background: #666;
        }

        .btn-danger:hover {
            background: #444;
        }

        .form-group {
            margin-bottom: 16px;
        }

        label {
            display: block;
            font-size: 0.85rem;
            margin-bottom: 6px;
            color: #333;
            font-weight: 500;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 9px;
            border-radius: 3px;
            border: 1px solid #bbb;
            background: #fff;
            color: #222;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: 2px solid #999;
            outline-offset: 1px;
        }

        .alerta-erro {
            background: #fff4f4;
            border: 1px solid #c33;
            color: #922;
            padding: 10px;
            border-radius: 3px;
            margin-bottom: 15px;
            font-size: 0.85rem;
        }

        footer {
            text-align: center;
            padding: 20px 16px;
            color: #666;
            font-size: 0.8rem;
            border-top: 1px solid #ddd;
            margin-top: 32px;
        }

        @media (max-width: 700px) {
            header {
                align-items: flex-start;
                flex-direction: column;
                padding: 14px 16px;
            }

            nav {
                gap: 12px;
            }

            .card {
                padding: 14px;
                overflow-x: auto;
            }
        }
    </style>
</head>

<body>
    <header>
        <h1>Gestão Veterinária</h1>
        <nav>
            <?php if ($usuarioLogado): ?>
                <a href="/">Painel</a>
                <a href="/tutores">Tutores</a>
                <a href="/pets">Pets</a>
                <a href="/agendamentos">Agendamentos</a>
                <span class="user-tag"><?= htmlspecialchars($usuarioLogado) ?></span>
                <form action="/logout" method="POST" style="display:inline;margin:0;">
                    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken ?? '') ?>">
                    <button type="submit" class="btn btn-danger" style="padding:5px 10px;font-size:0.8rem;">Sair</button>
                </form>
            <?php else: ?>
                <a href="/login" class="btn">Entrar</a>
            <?php endif; ?>
        </nav>
    </header>
    <div class="container">