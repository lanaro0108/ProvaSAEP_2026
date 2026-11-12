<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\Controller;
use App\Model\Tutor;

// Controller para gerenciamento, cadastro, busca e edicao de tutores.
final class TutorController extends Controller {
    // Listar todos os tutores
    public function listar(): void {
        $termo = trim((string)($_GET['q'] ?? ''));
        $tutores = Tutor::listar($termo !== '' ? $termo : null);
        $this->renderizar('tutores/listar', [
            'titulo'  => 'Gerenciamento de Tutores',
            'tutores' => $tutores,
            'busca'   => $termo
        ]);
    }
    // Renderizar o formulario de cadastro
    public function formulario(): void {
        $this->renderizar('tutores/criar', [
            'titulo'    => 'Novo Tutor',
            'csrfToken' => $this->obterCsrfToken()
        ]);
    }
    //salvar o tutor no banco
    public function salvar(): void {
        if (!$this->validarCsrfToken($_POST['csrf_token'] ?? null)) {
            $this->renderizar('tutores/criar', [
                'titulo'    => 'Novo Tutor',
                'erro'      => 'Não foi possível validar o formulário. Recarregue a página e tente novamente.',
                'dados'     => $_POST,
                'csrfToken' => $this->obterCsrfToken()
            ]);
            return;
        }
        $nome = trim($_POST['nome'] ?? '');
        $cpf  = trim($_POST['cpf'] ?? '');
        $tel  = trim($_POST['telefone'] ?? '');
        $mail = trim($_POST['email'] ?? '');
        $cpfDigitos = preg_replace('/\D+/', '', $cpf);
        if ($nome !== '' && strlen((string)$cpfDigitos) === 11 && $tel !== ''
            && filter_var($mail, FILTER_VALIDATE_EMAIL) && !Tutor::cpfJaCadastrado($cpf)) {
            Tutor::salvar($nome, $cpf, $tel, $mail);
            $this->redirecionar('/tutores');
        }
        $this->renderizar('tutores/criar', [
            'titulo'    => 'Novo Tutor',
            'erro'      => Tutor::cpfJaCadastrado($cpf)
                ? 'Já existe um tutor cadastrado com este CPF.'
                : 'Preencha todos os campos e informe um CPF com 11 dígitos e um e-mail válido.',
            'dados'     => $_POST,
            'csrfToken' => $this->obterCsrfToken()
        ]);
    }

    // Renderizar a informações de um tutor selecionado
    public function formularioEdicao(): void {
        $id = (int)($_GET['id'] ?? 0);
        $tutor = Tutor::buscarPorId($id);
        if (!$tutor) {
            $this->redirecionar('/tutores');
        }
        $this->renderizar('tutores/editar', [
            'titulo'    => 'Editar Dados do Tutor',
            'tutor'     => $tutor,
            'csrfToken' => $this->obterCsrfToken()
        ]);
    }

    // Atualizar as informações de um tutor
    public function atualizar(): void {
        if (!$this->validarCsrfToken($_POST['csrf_token'] ?? null)) {
            $this->redirecionar('/tutores');
        }
        $id   = (int)($_POST['id'] ?? 0);
        $nome = trim($_POST['nome'] ?? '');
        $cpf  = trim($_POST['cpf'] ?? '');
        $tel  = trim($_POST['telefone'] ?? '');
        $mail = trim($_POST['email'] ?? '');
        $cpfDigitos = preg_replace('/\D+/', '', $cpf);
        $cpfDuplicado = Tutor::cpfJaCadastrado($cpf, $id);
        if ($id > 0 && $nome !== '' && strlen((string)$cpfDigitos) === 11 && $tel !== ''
            && filter_var($mail, FILTER_VALIDATE_EMAIL) && !$cpfDuplicado) {
            Tutor::atualizar($id, $nome, $cpf, $tel, $mail);
            $this->redirecionar('/tutores');
        }
        $tutor = Tutor::buscarPorId($id);
        if (!$tutor) {
            $this->redirecionar('/tutores');
        }
        $tutor = array_merge($tutor, ['nome' => $nome, 'cpf' => $cpf, 'telefone' => $tel, 'email' => $mail]);
        $this->renderizar('tutores/editar', [
            'titulo'    => 'Editar Dados do Tutor',
            'tutor'     => $tutor,
            'erro'      => $cpfDuplicado
                ? 'Já existe outro tutor cadastrado com este CPF.'
                : 'Preencha todos os campos e informe um CPF com 11 dígitos e um e-mail válido.',
            'csrfToken' => $this->obterCsrfToken()
        ]);
    }
}
