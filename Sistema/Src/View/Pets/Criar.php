<div class="card" style="max-width: 600px; margin: 0 auto;">
    <h2><?= htmlspecialchars($titulo ?? 'Cadastrar Novo Pet') ?></h2>
    <?php if (!empty($erro)): ?>
        <div class="alerta-erro"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <?php if (empty($tutores)): ?>
        <p style="color:#555;">Cadastre um tutor antes de registrar um pet.</p>
        <a href="/tutores/novo" class="btn btn-success">Cadastrar Tutor</a>
    <?php else: ?>
        <form action="/pets/salvar" method="POST">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken ?? '') ?>">
            <div class="form-group">
                <label for="tutor_id">Tutor Responsável</label>
                <select id="tutor_id" name="tutor_id" required>
                    <option value="">-- Selecione o Tutor --</option>
                    <?php foreach ($tutores as $t): ?>
                        <option value="<?= (int)$t['id'] ?>" <?= (int)($dados['tutor_id'] ?? 0) === (int)$t['id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($t['nome']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="nome">Nome do Pet</label>
                <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($dados['nome'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="especie">Espécie</label>
                <input type="text" id="especie" name="especie" value="<?= htmlspecialchars($dados['especie'] ?? '') ?>" required placeholder="Ex.: Canina, Felina">
            </div>
            <div class="form-group">
                <label for="raca">Raça</label>
                <input type="text" id="raca" name="raca" value="<?= htmlspecialchars($dados['raca'] ?? '') ?>" required>
            </div>
            <div class="form-group">
                <label for="data_nascimento">Data de Nascimento</label>
                <input type="date" id="data_nascimento" name="data_nascimento" value="<?= htmlspecialchars($dados['data_nascimento'] ?? '') ?>" required>
            </div>
            <div style="display:flex; justify-content:space-between; margin-top:20px;">
                <a href="/pets" style="color:#555; text-decoration:none;">← Voltar</a>
                <button type="submit" class="btn btn-success">Salvar Pet</button>
            </div>
        </form>
    <?php endif; ?>
</div>