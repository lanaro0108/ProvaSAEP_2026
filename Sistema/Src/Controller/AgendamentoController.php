<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\Controller;
use App\Model\Agendamento;
use App\Model\Tutor;
use App\Model\Pet;

// Controller para agendamentos ordenados por ano e atualizacao de datas.
final class AgendamentoController extends Controller {
    public function listar(): void {
        $agendamentos = Agendamento::listarOrdenadoPorAno();
        $this->renderizar('agendamentos/listar', [
            'titulo'       => 'Agendamentos da Clínica',
            'agendamentos' => $agendamentos
        ]);
    }

    public function formulario(): void {
        $tutores = Tutor::listar();
        $pets = Pet::listarTodos();
        $this->renderizar('agendamentos/criar', [
            'titulo'    => 'Novo Agendamento',
            'tutores'   => $tutores,
            'pets'      => $pets,
            'csrfToken' => $this->obterCsrfToken()
        ]);
    }

    public function salvar(): void {
        if (!$this->validarCsrfToken($_POST['csrf_token'] ?? null)) {
            $this->renderizarFormulario('Não foi possível validar o formulário. Recarregue a página e tente novamente.');
            return;
        }
        $tutId = (int)($_POST['tutor_id'] ?? 0);
        $petId = (int)($_POST['pet_id'] ?? 0);
        $dt    = trim((string)($_POST['data_agendamento'] ?? ''));
        $hr    = trim((string)($_POST['hora_agendamento'] ?? ''));
        $serv  = trim((string)($_POST['servico'] ?? ''));
        $usrId = (int)($_SESSION['usuario_id'] ?? 0);
        $pet   = Pet::buscarPorId($petId);

        if ($tutId > 0 && $pet && (int)$pet['tutor_id'] === $tutId && $usrId > 0
            && $this->validarData($dt) && $this->validarHora($hr) && $serv !== '') {
            Agendamento::salvar($tutId, $petId, $usrId, $dt, $hr, $serv);
            $this->redirecionar('/agendamentos');
        }
        $this->renderizarFormulario('Confira os dados, a data e o horário, e selecione o tutor responsável pelo pet.');
    }

    public function formularioEditarData(): void {
        $id = (int)($_GET['id'] ?? 0);
        $agendamento = Agendamento::buscarPorId($id);
        if (!$agendamento) {
            $this->redirecionar('/agendamentos');
        }
        $this->renderizar('agendamentos/editar-data', [
            'titulo'      => 'Atualizar Data do Agendamento #' . $id,
            'agendamento' => $agendamento,
            'csrfToken'   => $this->obterCsrfToken()
        ]);
    }

    public function atualizarData(): void {
        if (!$this->validarCsrfToken($_POST['csrf_token'] ?? null)) {
            $this->redirecionar('/agendamentos');
        }
        $id       = (int)($_POST['id'] ?? 0);
        $novaData = trim((string)($_POST['data_agendamento'] ?? ''));
        $novaHora = trim((string)($_POST['hora_agendamento'] ?? ''));

        if ($id > 0 && $this->validarData($novaData) && ($novaHora === '' || $this->validarHora($novaHora))) {
            Agendamento::atualizarData($id, $novaData, $novaHora !== '' ? $novaHora : null);
            $this->redirecionar('/agendamentos');
        }
        $agendamento = Agendamento::buscarPorId($id);
        if (!$agendamento) {
            $this->redirecionar('/agendamentos');
        }
        $agendamento['data_agendamento'] = $novaData;
        $agendamento['hora_agendamento'] = $novaHora;
        $this->renderizar('agendamentos/editar-data', [
            'titulo'      => 'Atualizar Data do Agendamento #' . $id,
            'agendamento' => $agendamento,
            'erro'        => 'Informe uma data e um horário válidos.',
            'csrfToken'   => $this->obterCsrfToken()
        ]);
    }

    private function validarData(string $valor): bool {
        $valor = trim($valor);
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $valor)) {
            return false;
        }
        $data = \DateTimeImmutable::createFromFormat('!Y-m-d', $valor);
        return $data !== false && $data->format('Y-m-d') === $valor;
    }

    private function validarHora(string $valor): bool {
        $valor = trim($valor);
        if (!preg_match('/^(?:[01]?\d|2[0-3]):[0-5]\d$/', $valor)) {
            return false;
        }
        $hora = \DateTimeImmutable::createFromFormat('!H:i', $valor);
        return $hora !== false;
    }

    private function renderizarFormulario(string $erro): void {
        $this->renderizar('agendamentos/criar', [
            'titulo'    => 'Novo Agendamento',
            'erro'      => $erro,
            'tutores'   => Tutor::listar(),
            'pets'      => Pet::listarTodos(),
            'dados'     => $_POST,
            'csrfToken' => $this->obterCsrfToken()
        ]);
    }
}
