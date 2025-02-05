<?php

use PHPUnit\Framework\TestCase;
use App\Controllers\BilhetesController;
use App\Models\Bilhete;
use App\Models\BilheteDezena;
use App\Models\Tripulante;

class BilhetesControllerTest extends TestCase
{
    //** REMOVER BILHETES **//
    public function testRemoverBilhetesSuccess()
    {
        //** MOCK BILHETE **//
        $bilheteMock = $this->createMock(Bilhete::class);
        $bilheteMock->method('truncate')->willReturn([
            "status" => 200,
            "message" => "Bilhetes removidos com sucesso"
        ]);

        //** MOCK BILHETE DEZENA **//
        $bilheteDezenaMock = $this->createMock(BilheteDezena::class);

        //** MOCK TRIPULANTE **//
        $tripulanteMock = $this->createMock(Tripulante::class);
        $tripulanteMock->method('show')
            ->willReturn(['status' => 200]);

        //** MOCK BILHETECONTROLLER **//
        $controllerMock = $this->getMockBuilder(BilhetesController::class)
            ->setConstructorArgs([$bilheteMock, $bilheteDezenaMock, $tripulanteMock])
            ->onlyMethods(['validacao', 'gerarNumerosBilhete'])
            ->getMock();

        //** EXECULTA TESTE **//
        $response = $controllerMock->removerBilhetes();
        $this->assertEquals(["success" => "Bilhetes removidos com sucesso"], $response);
    }

    public function testRemoverBilhetesError()
    {
        //** MOCK BILHETE **//
        $bilheteMock = $this->createMock(Bilhete::class);
        $bilheteMock->method('truncate')->willReturn([
            "status" => 400,
            "message" => "Nenhum bilhete encontrado para remoção"
        ]);

        //** MOCK BILHETE DEZENA **//
        $bilheteDezenaMock = $this->createMock(BilheteDezena::class);

        //** MOCK TRIPULANTE **//
        $tripulanteMock = $this->createMock(Tripulante::class);
        $tripulanteMock->method('show')
            ->willReturn(['status' => 200]);

        //** MOCK BILHETECONTROLLER **//
        $controllerMock = $this->getMockBuilder(BilhetesController::class)
            ->setConstructorArgs([$bilheteMock, $bilheteDezenaMock, $tripulanteMock])
            ->onlyMethods(['validacao', 'gerarNumerosBilhete'])
            ->getMock();


        //** EXECULTA TESTE **//
        $response = $controllerMock->removerBilhetes();
        $this->assertEquals(["error" => "Nenhum bilhete encontrado para remoção"], $response);
    }

    public function testRemoverBilhetesException()
    {
        //** MOCK BILHETE **//
        $bilheteMock = $this->createMock(Bilhete::class);
        $bilheteMock->method('truncate')->willThrowException(new \PDOException("Erro ao truncar"));

        //** MOCK BILHETE DEZENA **//
        $bilheteDezenaMock = $this->createMock(BilheteDezena::class);

        //** MOCK TRIPULANTE **//
        $tripulanteMock = $this->createMock(Tripulante::class);
        $tripulanteMock->method('show')
            ->willReturn(['status' => 200]);

        //** MOCK BILHETECONTROLLER **//
        $controllerMock = $this->getMockBuilder(BilhetesController::class)
            ->setConstructorArgs([$bilheteMock, $bilheteDezenaMock, $tripulanteMock])
            ->onlyMethods(['validacao', 'gerarNumerosBilhete'])
            ->getMock();

        //** EXECULTA TESTE **//
        $response = $controllerMock->removerBilhetes();
        $this->assertEquals(["error" => "Falha ao remover bilhetes"], $response);
    }

    //** GERAR BILHETES **//
    public function testGerarBilhetesSuccess()
    {
        //** MOCK BILHETE **//
        $bilheteMock = $this->createMock(Bilhete::class);
        $bilheteMock->method('create')->willReturn([
            'data' => ['id_bilhete' => 1]
        ]);

        //** MOCK BILHETE DEZENAS **//
        $bilheteDezenaMock = $this->createMock(BilheteDezena::class);
        $bilheteDezenaMock->method('create')->willReturn(true);

        //** MOCK TRIPULANTE **//
        $tripulanteMock = $this->createMock(Tripulante::class);
        $tripulanteMock->method('show')
            ->willReturn(['status' => 200]);

        //** MOCK BILHETECONTROLLER **//
        $controllerMock = $this->getMockBuilder(BilhetesController::class)
            ->setConstructorArgs([$bilheteMock, $bilheteDezenaMock, $tripulanteMock])
            ->onlyMethods(['validacao', 'gerarNumerosBilhete'])
            ->getMock();

        //** MOCK PARA PASSAR NA VALIDACAO **//
        $controllerMock->method('validacao')->willReturn(["error" => []]);

        //** MOCK PARA NUMEROS GERADOS **//
        $controllerMock->method('gerarNumerosBilhete')->willReturn([10, 20, 30, 40, 50, 60]);

        //** EXECULTA TESTE **//
        $response = $controllerMock->gerarBilhetes(1, 6, 1);
        $this->assertEquals(["success" => "Bilhetes cadastrados com suceso"], $response);
    }

    public function testGerarBilhetesError()
    {
        //** MOCK BILHETE **//
        $bilheteMock = $this->createMock(Bilhete::class);
        $bilheteMock->method('create')->willReturn([
            'data' => ['id_bilhete' => 1]
        ]);

        //** MOCK BILHETE DEZENA **//
        $bilheteDezenaMock = $this->createMock(BilheteDezena::class);

        //** MOCK TRIPULANTE **//
        $tripulanteMock = $this->createMock(Tripulante::class);
        $tripulanteMock->method('show')
            ->willReturn(['status' => 200]);

        //** MOCK BILHETECONTROLLER **//
        $controllerMock = $this->getMockBuilder(BilhetesController::class)
            ->setConstructorArgs([$bilheteMock, $bilheteDezenaMock, $tripulanteMock])
            ->onlyMethods(['validacao', 'gerarNumerosBilhete'])
            ->getMock();

        //** MOCK PARA NÃO PASSAR NA VALIDACAO **//
        $controllerMock->method('validacao')->willReturn([
            "error" => ["Quantidade inválida"]
        ]);

        //** EXECULTA TESTE **//
        $response = $controllerMock->gerarBilhetes(0, 6, 1);
        $this->assertEquals(["error" => "Quantidade inválida"], $response);
    }

    public function testGerarBilhetesException()
    {
        //** MOCK BILHETE **//
        $bilheteMock = $this->createMock(Bilhete::class);
        $bilheteMock->method('create')->willThrowException(new \PDOException());

        //** MOCK BILHETE DEZENA **//
        $bilheteDezenaMock = $this->createMock(BilheteDezena::class);

        //** MOCK TRIPULANTE **//
        $tripulanteMock = $this->createMock(Tripulante::class);
        $tripulanteMock->method('show')
            ->willReturn(['status' => 200]);

        //** MOCK BILHETECONTROLLER **//
        $controllerMock = $this->getMockBuilder(BilhetesController::class)
            ->setConstructorArgs([$bilheteMock, $bilheteDezenaMock, $tripulanteMock])
            ->onlyMethods(['validacao', 'gerarNumerosBilhete'])
            ->getMock();

        //** MOCK PARA PASSAR NA VALIDACAO **//
        $controllerMock->method('validacao')->willReturn(["error" => []]);

        //** MOCK PARA NUMEROS GERADOS **//
        $controllerMock->method('gerarNumerosBilhete')->willReturn([10, 20, 30, 40, 50, 60]);

        //** EXECULTA TESTE **//
        $response = $controllerMock->gerarBilhetes(2, 6, 1);
        $this->assertEquals(["error" => "Falha ao gerar bilhetes"], $response);
    }

    // ** VALIDACAO **//
    public function testValidacaoQuantidadeMenorQueMinimo()
    {
        //** MOCK BILHETECONTROLLER **//
        $controllerMock = new BilhetesController();

        ///** EXECULTA TESTE **//
        $response = $controllerMock->validacao(0, 6, 1);
        $this->assertContains("Quantidade de bilhetes deve estar entre 1 e 50;", $response['error']);
    }

    public function testValidacaoQuantidadeMaiorQueMaximo()
    {
        //** MOCK BILHETECONTROLLER **//
        $controllerMock = new BilhetesController();

        ///** EXECULTA TESTE **//
        $response = $controllerMock->validacao(51, 6, 1);
        $this->assertContains("Quantidade de bilhetes deve estar entre 1 e 50;", $response['error']);
    }

    public function testValidacaoDezenasMenorQueMinimo()
    {
        //** MOCK BILHETECONTROLLER **//
        $controllerMock = new BilhetesController();

        ///** EXECULTA TESTE **//
        $response = $controllerMock->validacao(1, 5, 1);
        $this->assertContains("Quantidade de dezenas deve estar entre 6 e 10;", $response['error']);
    }

    public function testValidacaoDezenasMaiorQueMaximo()
    {
        //** MOCK BILHETECONTROLLER **//
        $controllerMock = new BilhetesController();

        ///** EXECULTA TESTE **//
        $response = $controllerMock->validacao(1, 11, 1);
        $this->assertContains("Quantidade de dezenas deve estar entre 6 e 10;", $response['error']);
    }

    public function testValidacaoTripulanteNaoEncontrado()
    {
        //** MOCK BILHETE **//
        $bilheteMock = $this->createMock(Bilhete::class);
        $bilheteMock->method('create')->willReturn([
            'data' => ['id_bilhete' => 1]
        ]);

        //** MOCK BILHETE DEZENA **//
        $bilheteDezenaMock = $this->createMock(BilheteDezena::class);

        //** MOCK TRIPULANTE **//
        $tripulanteMock = $this->createMock(Tripulante::class);
        $tripulanteMock->method('show')->willReturn([
            'status' => 404
        ]);

        //** MOCK BILHETECONTROLLER **//
        $controllerMock = $this->getMockBuilder(BilhetesController::class)
            ->setConstructorArgs([$bilheteMock, $bilheteDezenaMock, $tripulanteMock])
            ->onlyMethods([])
            ->getMock();

        //** EXECULTA TESTE **//
        $response = $controllerMock->validacao(1, 6, 1);
        $this->assertContains("Tripulante não encontrado;", (array) $response['error']);
    }

    public function testValidacaoTripulanteExcedeuBilhetesTotalmente()
    {
        //** MOCK BILHETE **//
        $bilheteMock = $this->createMock(Bilhete::class);
        $bilheteMock->expects($this->once())
            ->method('getQtdBilhetesByTripulante')
            ->willReturn(['qtd' => 50]);

        //** MOCK BILHETE DEZENA **//
        $bilheteDezenaMock = $this->createMock(BilheteDezena::class);

        //** MOCK TRIPULANTE **//
        $tripulanteMock = $this->createMock(Tripulante::class);
        $tripulanteMock->method('show')
            ->willReturn(['status' => 200]);  // Tripulante existe

        //** MOCK BILHETECONTROLLER **//
        $controllerMock = $this->getMockBuilder(BilhetesController::class)
            ->setConstructorArgs([$bilheteMock, $bilheteDezenaMock, $tripulanteMock])
            ->onlyMethods([])
            ->getMock();

        //** EXECULTA TESTE **//
        $response = $controllerMock->validacao(1, 6, 1);
        $this->assertContains("Tripulante não pode gerar mais bilhetes", $response['error']);
    }

    public function testValidacaoTripulanteExcedeuBilhetesParcialmente()
    {
        //** MOCK BILHETE **//
        $bilheteMock = $this->createMock(Bilhete::class);
        $bilheteMock->expects($this->once())
            ->method('getQtdBilhetesByTripulante')
            ->willReturn(['qtd' => 45]);

        //** MOCK BILHETE DEZENA **//
        $bilheteDezenaMock = $this->createMock(BilheteDezena::class);

        //** MOCK TRIPULANTE **//
        $tripulanteMock = $this->createMock(Tripulante::class);
        $tripulanteMock->method('show')
            ->willReturn(['status' => 200]);

        //** MOCK BILHETECONTROLLER **//
        $controllerMock = $this->getMockBuilder(BilhetesController::class)
            ->setConstructorArgs([$bilheteMock, $bilheteDezenaMock, $tripulanteMock])
            ->onlyMethods([])
            ->getMock();

        //** EXECULTA TESTE **//
        $response = $controllerMock->validacao(6, 6, 1);
        $this->assertContains("Tripulante só pode gerar mais 5 bilhetes;", $response['error']);
    }

    public function testValidacaoSucesso()
    {
        //** MOCK BILHETE **//
        $bilheteMock = $this->createMock(Bilhete::class);
        $bilheteMock->method('getQtdBilhetesByTripulante')
            ->willReturn(['qtd' => 0]);

        //** MOCK BILHETE DEZENA **//
        $bilheteDezenaMock = $this->createMock(BilheteDezena::class);

        //** MOCK TRIPULANTE **//
        $tripulanteMock = $this->createMock(Tripulante::class);
        $tripulanteMock->method('show')
            ->willReturn(['status' => 200]);

        //** MOCK BILHETECONTROLLER **//
        $controllerMock = $this->getMockBuilder(BilhetesController::class)
            ->setConstructorArgs([$bilheteMock, $bilheteDezenaMock, $tripulanteMock])
            ->onlyMethods([])
            ->getMock();

        //** EXECULTA TESTE **//
        $response = $controllerMock->validacao(6, 6, 1);
        $this->assertTrue($response['success']);
    }

    // ** GERAR NUMEROS BILHETE **//
    public function testGerarNumerosBilheteUnicos()
    {
        //** MOCK BILHETE **//
        $bilheteMock = $this->createMock(Bilhete::class);

        //** MOCK BILHETE DEZENA **//
        $bilheteDezenaMock = $this->createMock(BilheteDezena::class);

        //** MOCK TRIPULANTE **//
        $tripulanteMock = $this->createMock(Tripulante::class);

        //** MOCK BILHETECONTROLLER **//
        $controllerMock = $this->getMockBuilder(BilhetesController::class)
            ->setConstructorArgs([$bilheteMock, $bilheteDezenaMock, $tripulanteMock])
            ->onlyMethods([])
            ->getMock();

        //** EXECULTA TESTE **//
        $bilhete = $controllerMock->gerarNumerosBilhete(10);
        $this->assertCount(count(array_unique($bilhete)), $bilhete);
    }

    public function testGerarNumerosBilheteOrdenados()
    {
        //** MOCK BILHETE **//
        $bilheteMock = $this->createMock(Bilhete::class);

        //** MOCK BILHETE DEZENA **//
        $bilheteDezenaMock = $this->createMock(BilheteDezena::class);

        //** MOCK TRIPULANTE **//
        $tripulanteMock = $this->createMock(Tripulante::class);

        //** MOCK BILHETECONTROLLER **//
        $controllerMock = $this->getMockBuilder(BilhetesController::class)
            ->setConstructorArgs([$bilheteMock, $bilheteDezenaMock, $tripulanteMock])
            ->onlyMethods([])
            ->getMock();

        //** EXECULTA TESTE **//
        $bilhete = $controllerMock->gerarNumerosBilhete(10);
        $ordenado = $bilhete;
        sort($ordenado);
        $this->assertEquals($ordenado, $bilhete);
    }
}
