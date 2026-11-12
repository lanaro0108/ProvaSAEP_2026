<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\Controller;
use App\Model\Pet;
use App\Model\Tutor;

// Controller para cadastro e exibicao dos pets vinculados a seus tutores.
final class PetController extends Controller {

    // Listar todos os pets
    public function listar(): void {
        $pets = Pet::listarTodos();
        $this->renderizar('pets/listar', [
            'titulo' => 'Gerenciamento de Pets',
            'pets'   => $pets
        ]);
    }

    // Renderizar o formulario de cadastro de pet
    public function formulario(): void {
        $tutores = Tutor::listar();
        $this->renderizar('pets/criar', [
            'titulo'    => 'Cadastrar Novo Pet',
            'tutores'   => $tutores,
            'csrfToken' => $this->obterCsrfToken()
        ]);
    }

    // Salva um pet novo
    public function salvar(): void {
        if (!$this->validarCsrfToken($_POST['csrf_token'] ?? null)) {
            $this->renderizar('pets/criar', [
                'titulo'    => 'Cadastrar Novo Pet',
                'erro'      => 'Não foi possível validar o formulário. Recarregue a página e tente novamente.',
                'tutores'   => Tutor::listar(),
                'csrfToken' => $this->obterCsrfToken()
            ]);
            return;
        }
        $tutorId = (int)($_POST['tutor_id'] ?? 0);
        $nome    = trim($_POST['nome'] ?? '');
        $especie = trim($_POST['especie'] ?? '');
        $raca    = trim($_POST['raca'] ?? '');
        $dtNasc  = trim($_POST['data_nascimento'] ?? '');

        $dataValida = \DateTimeImmutable::createFromFormat('!Y-m-d', $dtNasc);
        if ($tutorId > 0 && Tutor::buscarPorId($tutorId) && $nome !== '' && $especie !== '' && $raca !== ''
            && $dataValida && $dataValida->format('Y-m-d') === $dtNasc) {
            Pet::salvar($tutorId, $nome, $especie, $raca, $dtNasc);
            $this->redirecionar('/pets');
        }
        $this->renderizar('pets/criar', [
            'titulo'    => 'Cadastrar Novo Pet',
            'erro'      => 'Selecione um tutor válido e preencha todos os dados do animal.',
            'tutores'   => Tutor::listar(),
            'dados'     => $_POST,
            'csrfToken' => $this->obterCsrfToken()
        ]);
    }
}
