<div class="card">
    <div style="display:flex; justify-content:space-between; align-items:center;">
        <div>
            <h2><?= htmlspecialchars($titulo ?? 'Agendamentos') ?></h2>
            <p style="color:#555; font-size:0.85rem; margin-top:4px;">
                Consultas em ordem cronológica com os dados do paciente e do tutor responsável.
            </p>
        </div>
        <a href="/agendamentos/novo" class="btn btn-success">+ Novo Agendamento</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Data / Hora</th>
                <th>Paciente (Pet)</th>
                <th>Tutor Responsável</th>
                <th>Contato</th>
                <th>Serviço</th>
                <th>Status</th>
                <th style="text-align:right;">Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($agendamentos)): ?>
                <tr><td colspan="7" style="text-align:center; color:#666;">Nenhum agendamento cadastrado.</td></tr>
            <?php else: ?>
                <?php foreach ($agendamentos as $a): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars(date('d/m/Y', strtotime($a['data_agendamento']))) ?></strong> às <?= htmlspecialchars(substr($a['hora_agendamento'], 0, 5)) ?></td>
                        <td>
                            <strong><?= htmlspecialchars($a['pet_nome']) ?></strong><br>
                            <span style="color:#666;"><?= htmlspecialchars($a['pet_especie']) ?> · <?= htmlspecialchars($a['pet_raca']) ?></span><br>
                            <span style="color:#666; font-size:0.8rem;">Nasc.: <?= htmlspecialchars(date('d/m/Y', strtotime($a['pet_data_nascimento']))) ?></span>
                        </td>
                        <td><?= htmlspecialchars($a['tutor_nome']) ?></td>
                        <td><?= htmlspecialchars($a['tutor_telefone']) ?><br><span style="color:#666;"><?= htmlspecialchars($a['tutor_email']) ?></span></td>
                        <td><?= htmlspecialchars($a['servico']) ?></td>
                        <td><?= htmlspecialchars($a['status']) ?></td>
                        <td style="text-align:right;">
                            <a href="/agendamentos/editar-data?id=<?= (int)$a['id'] ?>" class="btn" style="padding:4px 8px; font-size:0.75rem;">
                                Alterar Data
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>