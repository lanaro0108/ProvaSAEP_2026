<div class="card" style="max-width: 600px; margin: 0 auto;">
    <h2><?= htmlspecialchars($titulo ?? 'Criar Agendamento') ?></h2>
    <?php if (!empty($erro)): ?>
        <div class="alerta-erro"><?= htmlspecialchars($erro) ?></div>
    <?php endif; ?>

    <form action="/agendamentos/salvar" method="POST">
        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrfToken ?? '') ?>">
        <div class="form-group">
            <label for="tutor_id">Tutor Responsável</label>
            <select id="tutor_id" name="tutor_id" required>
                <option value="">-- Selecione o Tutor --</option>
                <?php foreach ($tutores ?? [] as $t): ?>
                    <option value="<?= (int)$t['id'] ?>" <?= (int)($dados['tutor_id'] ?? 0) === (int)$t['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($t['nome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="pet_id">Pet / Paciente</label>
            <select id="pet_id" name="pet_id" required>
                <option value="">-- Selecione o Pet --</option>
                <?php foreach ($pets ?? [] as $p): ?>
                    <option value="<?= (int)$p['id'] ?>"
                        data-tutor-id="<?= (int)$p['tutor_id'] ?>"
                        <?= (int)($dados['pet_id'] ?? 0) === (int)$p['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($p['nome']) ?> (Tutor: <?= htmlspecialchars($p['tutor_nome']) ?>)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="data_agendamento">Data da Consulta</label>
            <input type="date" id="data_agendamento" name="data_agendamento" value="<?= htmlspecialchars($dados['data_agendamento'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label for="hora_agendamento">Horário</label>
            <input type="time" id="hora_agendamento" name="hora_agendamento" value="<?= htmlspecialchars($dados['hora_agendamento'] ?? '') ?>" required>
        </div>
        <div class="form-group">
            <label for="servico">Serviço Solicitado</label>
            <input type="text" id="servico" name="servico" value="<?= htmlspecialchars($dados['servico'] ?? '') ?>" required placeholder="Ex.: Consulta de Rotina, Vacinação, Castração">
        </div>
        <div style="display:flex; justify-content:space-between; margin-top:20px;">
            <a href="/agendamentos" style="color:#555; text-decoration:none;">← Voltar</a>
            <button type="submit" class="btn btn-success">Salvar Agendamento</button>
        </div>
    </form>
</div>
<script>
    const tutorSelect = document.getElementById('tutor_id');
    const petSelect = document.getElementById('pet_id');

    function filtrarPetsDoTutor() {
        const tutorId = tutorSelect.value;
        Array.from(petSelect.options).forEach((option) => {
            if (!option.dataset.tutorId) return;
            option.hidden = tutorId !== '' && option.dataset.tutorId !== tutorId;
            if (option.hidden && option.selected) petSelect.value = '';
        });
    }

    tutorSelect.addEventListener('change', filtrarPetsDoTutor);
    filtrarPetsDoTutor();
</script>