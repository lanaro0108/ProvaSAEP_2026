<?php
declare(strict_types=1);

namespace App\Models;

use App\Core\ConexaoBanco;
use App\Core\Criptografia;
use PDO;

// Model para gestao de tutores com suporte a LGPD e busca.
final class Tutor {
    private static function pdo(): PDO {
        $cfg = dirname(__DIR__, 2) . '/config/database.ini';
        return ConexaoBanco::obterConexao($cfg);
    }

    public static function listar(?string $termo = null): array {
        if (!empty($termo)) {
            $sql = "SELECT id, nome, telefone, email, criado_em
                    FROM tutores
                    WHERE nome ILIKE :termo OR email ILIKE :termo OR telefone ILIKE :termo
                    ORDER BY nome ASC";
            $stmt = self::pdo()->prepare($sql);
            $stmt->execute([':termo' => '%' . trim($termo) . '%']);
            $registros = $stmt->fetchAll();
        } else {
            $sql = "SELECT id, nome, telefone, email, criado_em FROM tutores ORDER BY nome ASC";
            $registros = self::pdo()->query($sql)->fetchAll();
        }
        return $registros;
    }

    public static function buscarPorId(int $id): ?array {
        $sql = "SELECT id, nome, cpf_criptografado, telefone, email FROM tutores WHERE id = :id LIMIT 1";
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute([':id' => $id]);
        $tutor = $stmt->fetch();
        if ($tutor) {
            $tutor['cpf'] = Criptografia::decifrar($tutor['cpf_criptografado']);
        }
        return $tutor ?: null;
    }

    public static function cpfJaCadastrado(string $cpf, int $excluirId = 0): bool {
        $sql = "SELECT EXISTS (SELECT 1 FROM tutores WHERE cpf_hash = :cpf_hash AND id <> :id)";
        $stmt = self::pdo()->prepare($sql);
        $stmt->execute([
            ':cpf_hash' => Criptografia::hashCpf($cpf),
            ':id'       => $excluirId
        ]);
        return (bool)$stmt->fetchColumn();
    }

    public static function salvar(string $nome, string $cpf, string $telefone, string $email): bool {
        $sql = "INSERT INTO tutores (nome, cpf_criptografado, cpf_hash, telefone, email)
                VALUES (:nome, :cpf, :cpf_hash, :telefone, :email)";
        $stmt = self::pdo()->prepare($sql);
        return $stmt->execute([
            ':nome'     => trim($nome),
            ':cpf'      => Criptografia::cifrar(trim($cpf)),
            ':cpf_hash' => Criptografia::hashCpf($cpf),
            ':telefone' => trim($telefone),
            ':email'    => trim($email)
        ]);
    }

    public static function atualizar(int $id, string $nome, string $cpf, string $tel, string $email): bool {
        $sql = "UPDATE tutores
                SET nome = :nome, cpf_criptografado = :cpf, cpf_hash = :cpf_hash, telefone = :tel, email = :email
                WHERE id = :id";
        $stmt = self::pdo()->prepare($sql);
        return $stmt->execute([
            ':nome'  => trim($nome),
            ':cpf'   => Criptografia::cifrar(trim($cpf)),
            ':cpf_hash' => Criptografia::hashCpf($cpf),
            ':tel'   => trim($tel),
            ':email' => trim($email),
            ':id'    => $id
        ]);
    }

}