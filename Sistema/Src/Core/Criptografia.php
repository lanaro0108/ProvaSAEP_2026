<?php
declare(strict_types=1);

namespace App\Core;

use RuntimeException;

final class Criptografia {
    // Classe para criptografia do CPF
     private static function obterChave(): string {
        $cfg = parse_ini_file(dirname(__DIR__, 2) . '/Config/database.ini', true);
        $chaveConfigurada = trim((string)($cfg['security']['app_key'] ?? ''));
        if ($chaveConfigurada === '') {
            throw new RuntimeException('Configure security.app_key antes de processar CPFs.');
        }
        return hash('sha256', $chaveConfigurada);
    }

    // Cifra uma string gerando IV aleatorio embutido no resultado Base64.
    public static function cifrar(string $dado): string {
        $iv = random_bytes(16);
        $cifrado = openssl_encrypt($dado, 'aes-256-cbc', self::obterChave(), 0, $iv);
        if ($cifrado === false) {
            throw new RuntimeException('Não foi possível criptografar o CPF.');
        }
        return base64_encode($iv . $cifrado);
    }

    public static function hashCpf(string $cpf): string {
        $cpfNormalizado = preg_replace('/\D+/', '', $cpf);
        return hash_hmac('sha256', (string)$cpfNormalizado, self::obterChave());
    }

    // Decifra uma string codificada em Base64 extraindo seu IV correspondente.
    public static function decifrar(string $dadoCifrado): string {
        $dadosBrutos = base64_decode($dadoCifrado, true);
        if ($dadosBrutos === false || strlen($dadosBrutos) <= 16) {
            throw new RuntimeException('O CPF armazenado não está em um formato válido.');
        }
        $iv = substr($dadosBrutos, 0, 16);
        $texto = substr($dadosBrutos, 16);
        $resultado = openssl_decrypt($texto, 'aes-256-cbc', self::obterChave(), 0, $iv);
        if ($resultado === false) {
            throw new RuntimeException('Não foi possível descriptografar o CPF. Confira a chave de segurança configurada.');
        }
        return $resultado;
    }
}