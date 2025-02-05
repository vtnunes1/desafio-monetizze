<?php

use PHPUnit\Framework\TestCase;
use App\Controllers\BilhetesPremiadosController;
use App\Models\BilhetePremiado;
use App\Models\BilhetePremiadoDezena;

class BilhetesPremiadosControllerTest extends TestCase
{
    // ** GERAR NUMEROS BILHETE **//
    public function testGerarNumerosBilhetePremiadoUnicos()
    {
        //** MOCK BILHETE PREMIADO **//
        $bilhetePremiadoMock = $this->createMock(BilhetePremiado::class);

        //** MOCK BILHETE PREMIADO DEZENA **//
        $bilhetePremiadoDezenaMock = $this->createMock(BilhetePremiadoDezena::class);

        //** MOCK BILHETECONTROLLER **//
        $controllerMock = $this->getMockBuilder(BilhetesPremiadosController::class)
            ->setConstructorArgs([$bilhetePremiadoMock, $bilhetePremiadoDezenaMock])
            ->onlyMethods([])
            ->getMock();

        //** EXECULTA TESTE **//
        $bilhete = $controllerMock->gerarNumerosBilhete(6);
        $this->assertCount(count(array_unique($bilhete)), $bilhete);
    }

    public function testGerarNumerosBilhetePremiadoOrdenados()
    {
        //** MOCK BILHETE PREMIADO **//
        $bilhetePremiadoMock = $this->createMock(BilhetePremiado::class);

        //** MOCK BILHETE PREMIADO DEZENA **//
        $bilhetePremiadoDezenaMock = $this->createMock(BilhetePremiadoDezena::class);

        //** MOCK BILHETECONTROLLER **//
        $controllerMock = $this->getMockBuilder(BilhetesPremiadosController::class)
            ->setConstructorArgs([$bilhetePremiadoMock, $bilhetePremiadoDezenaMock])
            ->onlyMethods([])
            ->getMock();

        //** EXECULTA TESTE **//
        $bilhetePremiado = $controllerMock->gerarNumerosBilhete(6);
        $ordenado = $bilhetePremiado;
        sort($ordenado);
        $this->assertEquals($ordenado, $bilhetePremiado);
    }

    //** GERAR BILHETE PREMIADO **//
    public function testGerarBilhetePremiadoSuccess()
    {
        //** MOCK BILHETE PREMIADO **//
        $bilhetePremiadoMock = $this->createMock(BilhetePremiado::class);
        $bilhetePremiadoMock->method('delete')->willReturn(true);
        $bilhetePremiadoMock->method('create')->willReturn([
            'data' => ['id_bilhete_premiado' => 1]
        ]);

        //** MOCK BILHETE PREMIADO DEZENAS **//
        $bilhetePremiadoDezenaMock = $this->createMock(BilhetePremiadoDezena::class);
        $bilhetePremiadoDezenaMock->method('create')->willReturn(true);

        //** MOCK BILHETESCONTROLLER **//
        $controllerMock = $this->getMockBuilder(BilhetesPremiadosController::class)
            ->setConstructorArgs([$bilhetePremiadoMock, $bilhetePremiadoDezenaMock])
            ->onlyMethods(['gerarNumerosBilhete'])
            ->getMock();

        //** MOCK PARA NUMEROS GERADOS **//
        $controllerMock->method('gerarNumerosBilhete')->willReturn([10, 20, 30, 40, 50, 60]);

        //** EXECUTA TESTE **//
        $response = $controllerMock->gerarBilhetePremiado();
        $this->assertEquals(["success" => "Bilhete premiado gerado com suceso"], $response);
    }

    public function testGerarBilhetePremiadoException()
    {
        //** MOCK BILHETE PREMIADO **//
        $bilhetePremiadoMock = $this->createMock(BilhetePremiado::class);
        $bilhetePremiadoMock->method('delete')->willThrowException(new \PDOException("Erro ao deletar bilhete premiado"));

        //** MOCK BILHETE PREMIADO DEZENAS **//
        $bilhetePremiadoDezenaMock = $this->createMock(BilhetePremiadoDezena::class);

        //** MOCK BILHETESCONTROLLER **//
        $controllerMock = $this->getMockBuilder(BilhetesPremiadosController::class)
            ->setConstructorArgs([$bilhetePremiadoMock, $bilhetePremiadoDezenaMock])
            ->onlyMethods(['gerarNumerosBilhete'])
            ->getMock();

        //** EXECUTA TESTE **//
        $response = $controllerMock->gerarBilhetePremiado();
        $this->assertEquals(["error" => "Falha ao gerar bilhete premiado"], $response);
    }
}
