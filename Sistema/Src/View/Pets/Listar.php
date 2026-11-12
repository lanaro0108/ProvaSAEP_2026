<div class="card">
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <h2><?= htmlspecialchars($titulo ?? 'Gerenciamento de Pets') ?></h2>
        <a href="/pets/novo" class="btn btn-success">+ Cadastrar Pet</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Pet</th>
                <th>Espécie</th>
                <th>Raça</th>
                <th>Data de Nascimento</th>
                <th>Tutor Responsável</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($pets)): ?>
                <tr>
                    <td colspan="5" style="text-align:center; color:#666;">Nenhum pet cadastrado.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($pets as $pet): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($pet['nome']) ?></strong></td>
                        <td><?= htmlspecialchars($pet['especie']) ?></td>
                        <td><?= htmlspecialchars($pet['raca']) ?></td>
                        <td><?= htmlspecialchars(date('d/m/Y', strtotime($pet['data_nascimento']))) ?></td>
                        <td><?= htmlspecialchars($pet['tutor_nome']) ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>