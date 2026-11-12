<?php

declare(strict_types=1);

require dirname(__DIR__, 2) . '/vendor/autoload.php';

session_start();

$roteador = new \App\Core\Roteador();

$roteador->registrar('GET', '/', [\App\Controller\HomeController::class, 'index'], [['App\Core\AuthGuard', 'proteger']]);
$roteador->registrar('GET', '/login', [\App\Controller\AuthController::class, 'formulario']);
$roteador->registrar('POST', '/login', [\App\Controller\AuthController::class, 'login']);
$roteador->registrar('POST', '/logout', [\App\Controller\AuthController::class, 'logout']);

$roteador->registrar('GET', '/tutores', [\App\Controller\TutorController::class, 'listar'], [['App\Core\AuthGuard', 'proteger']]);
$roteador->registrar('GET', '/tutores/novo', [\App\Controller\TutorController::class, 'formulario'], [['App\Core\AuthGuard', 'proteger']]);
$roteador->registrar('POST', '/tutores/salvar', [\App\Controller\TutorController::class, 'salvar'], [['App\Core\AuthGuard', 'proteger']]);
$roteador->registrar('GET', '/tutores/editar', [\App\Controller\TutorController::class, 'formularioEdicao'], [['App\Core\AuthGuard', 'proteger']]);
$roteador->registrar('POST', '/tutores/atualizar', [\App\Controller\TutorController::class, 'atualizar'], [['App\Core\AuthGuard', 'proteger']]);

$roteador->registrar('GET', '/pets', [\App\Controller\PetController::class, 'listar'], [['App\Core\AuthGuard', 'proteger']]);
$roteador->registrar('GET', '/pets/novo', [\App\Controller\PetController::class, 'formulario'], [['App\Core\AuthGuard', 'proteger']]);
$roteador->registrar('POST', '/pets/salvar', [\App\Controller\PetController::class, 'salvar'], [['App\Core\AuthGuard', 'proteger']]);

$roteador->registrar('GET', '/agendamentos', [\App\Controller\AgendamentoController::class, 'listar'], [['App\Core\AuthGuard', 'proteger']]);
$roteador->registrar('GET', '/agendamentos/novo', [\App\Controller\AgendamentoController::class, 'formulario'], [['App\Core\AuthGuard', 'proteger']]);
$roteador->registrar('POST', '/agendamentos/salvar', [\App\Controller\AgendamentoController::class, 'salvar'], [['App\Core\AuthGuard', 'proteger']]);
$roteador->registrar('GET', '/agendamentos/editar-data', [\App\Controller\AgendamentoController::class, 'formularioEditarData'], [['App\Core\AuthGuard', 'proteger']]);
$roteador->registrar('POST', '/agendamentos/atualizar-data', [\App\Controller\AgendamentoController::class, 'atualizarData'], [['App\Core\AuthGuard', 'proteger']]);

$roteador->despachar($_SERVER['REQUEST_METHOD'] ?? 'GET', $_SERVER['REQUEST_URI'] ?? '/');
