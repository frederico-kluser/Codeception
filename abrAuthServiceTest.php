<?php

define('_ABRAUTH_INDEX_BOOTSTRAP_', TRUE);

class AbrAuthServiceTest extends PHPUnit\Framework\TestCase
{
	public static $emailValidoNovo;

  public static function setUpBeforeClass()
	{
	  self::$emailValidoNovo = 'vector-testeautomatizado-abrid-'.strtotime("now").'@guerrillamail.com';
	}

  protected function setUp()
  {
    $this->abrAuthService = new AbrAuthService();
    $this->AuthUser = new AuthUser();
    $this->emailValido = 'aamaral@vectoritcgroup.com';
    $this->emailInvalido = 'invalid'.strtotime(date('Y-m-d h:i:sa')).'@email';
    $this->emailInexistente = 'inexistent'.strtotime(date('Y-m-d h:i:sa')).'@email.com.br';
  }

  protected function tearDown()
  {
    $this->abrAuthService = NULL;
  }

  public static function tearDownAfterClass()
  {
		echo "\n". "Email utlizado para criar usuário: " . self::$emailValidoNovo . "\n";
  }


  public function testEsqueciSenhaComEmailInvalido(){
    $esperado  = [
      'status' => 'error',
      'msg'    => 'E-mail inválido.'
    ];
    $email     = $this->emailInvalido;
    $arrReturn = $this->abrAuthService->forgotPassword($email);
    $this->assertEquals($esperado, $arrReturn);
  }

  public function testEsqueciSenhaComEmailNaoExistente(){
    $esperado  = [
      'status' => 'error',
      'msg'    => 'email-inexistente',
      'field' => 'email'
    ];
    $email     = $this->emailInexistente;
    $arrReturn = $this->abrAuthService->forgotPassword($email);
    $this->assertEquals($esperado, $arrReturn);
  }

  public function testEsqueciSenhaComEmailVazio(){
    $esperado  = [
      'status' => 'error',
      'msg'    => 'E-mail não pode ser vazio.'
    ];
    $email     = '';
    $arrReturn = $this->abrAuthService->forgotPassword($email);
    $this->assertEquals($esperado, $arrReturn);
  }

  public function testEsqueciSenhaComEmailValido(){
    $esperado  = [
      'status' => 'success',
      'msg'    => 'email-enviado',
      'rf'     => 'rf10'
    ];
    $email     = $this->emailValido;
    $arrReturn = $this->abrAuthService->forgotPassword($email);
    $this->assertEquals($esperado, $arrReturn);
  }

  public function testConfirmaEsqueciSenhaComCodigoVazio(){
    $esperado  = [
      'status' => 'error',
      'msg'    => 'Código não pode ser vazio.'
    ];
    $email       = $this->emailValido;
    $confirmCode = '';
    $newPassword = 'pass@@1234';
    $arrReturn   = $this->abrAuthService->confirmForgotPassword($email, $confirmCode, $newPassword, $newPassword);
    $this->assertEquals($esperado, $arrReturn);
  }

  public function testConfirmaEsqueciSenhaComCodigoInvalido(){
    $esperado  = [
      'status' => 'error',
      'msg'    => 'codigo-invalido',
      'field' => 'confirmCode'
    ];
    $email       = $this->emailValido;
    $confirmCode = '12345';
    $newPassword = 'Pass@@1234';
    $arrReturn   = $this->abrAuthService->confirmForgotPassword($email, $confirmCode, $newPassword, $newPassword);
    $this->assertEquals($esperado, $arrReturn);
  }

  public function testConfirmaEsqueciSenhaComEmailVazio(){
    $esperado  = [
      'status' => 'error',
      'msg'    => 'E-mail não pode ser vazio.'
    ];
    $email       = '';
    $confirmCode = '123456';
    $newPassword = 'Pass@@1234';
    $arrReturn   = $this->abrAuthService->confirmForgotPassword($email, $confirmCode, $newPassword, $newPassword);
    $this->assertEquals($esperado, $arrReturn);
  }

  public function testConfirmaEsqueciSenhaComEmailInvalido(){
    $esperado  = [
      'status' => 'error',
      'msg'    => 'E-mail inválido.'
    ];
    $email       = $this->emailInvalido;
    $confirmCode = '123456';
    $newPassword = 'pass@@1234';
    $arrReturn   = $this->abrAuthService->confirmForgotPassword($email, $confirmCode, $newPassword, $newPassword);
    $this->assertEquals($esperado, $arrReturn);
  }

  public function testConfirmaEsqueciSenhaComSenhaVazia(){
    $esperado  = [
      'status' => 'error',
      'msg'    => 'Senha não pode ser vazio.'
    ];
    $email       = $this->emailValido;
    $confirmCode = '12345';
    $newPassword = '';
    $arrReturn   = $this->abrAuthService->confirmForgotPassword($email, $confirmCode, $newPassword, $newPassword);
    $this->assertEquals($esperado, $arrReturn);
  }

  public function testConfirmaEsqueciSenhaComSenhaCurta(){
    $esperado  = [
      'status' => 'error',
      'msg'    => 'A senha deve ter ao menos 8 caracteres, com letras maiúsculas, minúsculas, números e símbolos.'
    ];
    $email       = $this->emailValido;
    $confirmCode = '12345';
    $newPassword = '1234567';
    $arrReturn   = $this->abrAuthService->confirmForgotPassword($email, $confirmCode, $newPassword, $newPassword);
    $this->assertEquals($esperado, $arrReturn);
  }

  public function testConfirmaEsqueciSenhaComSenhaFraca(){
    $esperado  = [
      'status' => 'error',
      'msg'    => 'A senha não atingiu os critérios necessários de complexidade.'
    ];
    $email       = $this->emailValido;
    $confirmCode = '12345';
    $newPassword = '12345678';
    $arrReturn   = $this->abrAuthService->confirmForgotPassword($email, $confirmCode, $newPassword, $newPassword);
    $this->assertEquals($esperado, $arrReturn);
  }

  public function testConfirmaEsqueciSenhaComSenhasDiferentes(){
    $esperado  = [
      'status' => 'error',
      'msg'    => 'senha'
    ];
    $email       = $this->emailValido;
    $confirmCode = '12345';
    $newPassword = 'Pass@@1234';
    $confirmNewPassword = 'Pass@@12345';
    $arrReturn   = $this->abrAuthService->confirmForgotPassword($email, $confirmCode, $newPassword, $confirmNewPassword);
    $this->assertEquals($esperado, $arrReturn);
  }

  public function testConfirmaEsqueciSenhaComSuccesso(){
    $esperado  = [
      'status' => 'success',
      'msg'    => 'Senha alterada com sucesso.'
    ];

    $stub = $this->getMockBuilder('AbrAuthService')->getMock();
    $stub->method('confirmForgotPassword')->willReturn(['status' => 'success', 'msg' => 'Senha alterada com sucesso.']);

    $email       = $this->emailValido;
    $confirmCode = '123456';
    $newPassword = 'pass@@1234';
    $arrReturn   = $stub->confirmForgotPassword($email, $confirmCode, $newPassword, $newPassword);
    $this->assertEquals($esperado, $arrReturn);
  }

  public function testLoginComEmailVazio(){
    $esperado  = [
      'status' => 'error',
      'msg'    => 'credenciais-invalidas',
      'rf'     => 'rf01',
    ];
    $email     = '';
    $password  = 'Pass@@1234';
    $arrReturn = $this->abrAuthService->login($email, $password);
    $this->assertEquals($esperado, $arrReturn);
  }

  public function testLoginComEmailInvalido(){
    $esperado  = [
      'status' => 'error',
      'msg'    => 'credenciais-invalidas',
      'rf'     => 'rf01',
    ];
    $email     =  $this->emailInvalido;
    $password  = 'Pass@@1234';
    $arrReturn = $this->abrAuthService->login($email, $password);
    $this->assertEquals($esperado, $arrReturn);
  }

  public function testLoginComSenhaVazia(){
    $esperado  = [
      'status' => 'error',
      'msg'    => 'credenciais-invalidas',
      'rf'     => 'rf01',
    ];
    $email     =  $this->emailInvalido;
    $password  = '';
    $arrReturn = $this->abrAuthService->login($email, $password);
    $this->assertEquals($esperado, $arrReturn);
  }

  public function testLoginComSenhaCurta(){
    $esperado  = [
      'status' => 'error',
      'msg'    => 'credenciais-invalidas',
      'rf'     => 'rf01',
    ];
    $email     =  $this->emailInvalido;
    $password  = '1234567';
    $arrReturn = $this->abrAuthService->login($email, $password);
    $this->assertEquals($esperado, $arrReturn);
  }

  public function testLoginComSenhaFraca(){
    $esperado  = [
      'status' => 'error',
      'msg'    => 'credenciais-invalidas',
      'rf'     => 'rf01',
    ];
    $email     =  $this->emailInvalido;
    $password  = '12345678';
    $arrReturn = $this->abrAuthService->login($email, $password);
    $this->assertEquals($esperado, $arrReturn);
  }

  public function testLoginComSuccesso(){
    $esperado  = [
      'status' => 'success',
      'msg'    => 'Conta autenticada.'
    ];
	    $email     = $this->emailValido;
    $password  = 'Loh@ny001';
    $arrReturn = $this->abrAuthService->login($email, $password);
    $this->assertEquals($esperado, $arrReturn);
  }

  public function testCriarContaComPrimeiroNomeVazio(){
    $esperado  = [
        'status' => 'error',
        'msg'    => 'primeiro-nome-vazio'
    ];
    $email = "";
    $password = "";
    $firstName = "";
    $lastName = "";
    $arrReturn     = $this->AuthUser->validateUserData($email, $password, $firstName, $lastName) ;
    $this->assertEquals($esperado, $arrReturn);
  }

  public function testCriarContaComUltimoNomeVazio(){
    $esperado  = [
        'status' => 'error',
        'msg'    => 'ultimo-nome-vazio'
    ];
    $email = "";
    $password = "";
    $firstName = "Marcus";
    $lastName = "";
    $arrReturn     = $this->AuthUser->validateUserData($email, $password, $firstName, $lastName) ;
    $this->assertEquals($esperado, $arrReturn);
  }

  public function testCriarContaComPrimeiroNomeCaracterEspecial(){
    $esperado  = [
        'status' => 'error',
        'msg'    => 'Nome inválido.'
    ];
    $email = "marcruz@abril.com.br";
    $password = "C1@23a456";
    $confirmPassword = "C1@23a456";
    $firstName = "#$!";
    $lastName = "Cruz";
    $arrReturn     = $this->AuthUser->validateUserData($email, $password, $firstName, $lastName, false, $confirmPassword) ;
    $this->assertEquals($esperado, $arrReturn);
  }

  public function testCriarContaComUltimoNomeCaracterEspecial(){
    $esperado  = [
        'status' => 'error',
        'msg'    => 'ultimo-nome-invalido'
    ];
    $email = "marcruz@abril.com.br";
    $password = "C1@23a456";
    $confirmPassword = "C1@23a456";
    $firstName = "Marcus";
    $lastName = "#$!";
    $arrReturn     = $this->AuthUser->validateUserData($email, $password, $firstName, $lastName, false, $confirmPassword) ;
    $this->assertEquals($esperado, $arrReturn);
  }

  public function testCriarContaComEmailVazio(){
    $esperado  = [
        'status' => 'error',
        'msg'    => 'E-mail não pode ser vazio.'
    ];
    $email = "";
    $password = "";
    $firstName = "Marcus";
    $lastName = "Cruz";
    $arrReturn     = $this->AuthUser->validateUserData($email, $password, $firstName, $lastName) ;
    $this->assertEquals($esperado, $arrReturn);
  }

  public function testCriarContaComEmailInvalido(){
    $esperado  = [
        'status' => 'error',
        'msg'    => 'E-mail inválido.'
    ];
    $email = $this->emailInvalido;
    $password = "";
    $firstName = "Marcus";
    $lastName = "Cruz";
    $arrReturn     = $this->AuthUser->validateUserData($email, $password, $firstName, $lastName) ;
    $this->assertEquals($esperado, $arrReturn);
  }

  public function testCriarContaComSenhaVazio(){
    $esperado  = [
        'status' => 'error',
        'msg'    => 'obrigatorio-senha'
    ];
    $email = $this->emailValido;;
    $password = "";
    $firstName = "Marcus";
    $lastName = "Cruz";
    $arrReturn     = $this->AuthUser->validateUserData($email, $password, $firstName, $lastName) ;
    $this->assertEquals($esperado, $arrReturn);
  }

  public function testCriarContaComSenhaMenosDe8Caracteres(){
    $esperado  = [
        'status' => 'error',
        'msg'    => 'A senha deve ter ao menos 8 caracteres, com letras maiúsculas, minúsculas, números e símbolos.'
    ];
    $email = "marcruz@abril.com.br";
    $password = "1wed";
    $firstName = "Marcus";
    $lastName = "Cruz";
    $arrReturn     = $this->AuthUser->validateUserData($email, $password, $firstName, $lastName) ;
    $this->assertEquals($esperado, $arrReturn);
  }

  public function testCriarContaComSenhaNaoAtendeCriterios(){
    $esperado  = [
        'status' => 'error',
        'msg'    => 'A senha não atingiu os critérios necessários de complexidade.'
    ];
    $email = "marcruz@abril.com.br";
    $password = "123456789";
    $firstName = "Marcus";
    $lastName = "Cruz";
    $arrReturn     = $this->AuthUser->validateUserData($email, $password, $firstName, $lastName) ;
    $this->assertEquals($esperado, $arrReturn);
  }

  public function testCriarContaComSenhasDiferentes(){
    $esperado  = [
        'status' => 'error',
        'msg'    => 'senha-nao-confere',
	      'rf'     => 'rf06',
    ];
    $email = $this->emailValido;;
    $password = "Abc@1719";
    $confirmPassword = "Abc@1719999999999";
    $firstName = "Marcus";
    $lastName = "Cruz";
    $user =  new AuthUser($email, $password, $firstName, $lastName, false, $confirmPassword);
    $arrReturn = $this->abrAuthService->register($user);
    $this->assertEquals($esperado, $arrReturn);
  }

  public function testCriarContaComEmailExistente(){
    $esperado  = [
        'status' => 'error',
        'msg'    => 'usuario-cadastrar',
	      'rf'     => 'rf06',
    ];
    $email = $this->emailValido;
    $password = "Abc@1719";
    $firstName = "Marcus";
    $lastName = "Cruz";
    $user =  new AuthUser($email, $password, $firstName, $lastName, false, $password);
    $arrReturn = $this->abrAuthService->register($user);
    $this->assertEquals($esperado, $arrReturn);
  }

  public function testCriarContaComSucesso(){
    $esperado  = [
        'status' => 'success',
        'msg'    => 'O cadastro foi feito com sucesso. Aguardando ativação de conta.'
    ];
    $email = self::$emailValidoNovo;
    $password = "Abc@1719";
    $firstName = "Mailinator";
    $lastName = "Cruz";

    $user =  new AuthUser($email, $password, $firstName, $lastName, false, $password);
    $arrReturn = $this->abrAuthService->register($user);

    $this->assertEquals($esperado, $arrReturn);
  }

  /**
     * @depends testCriarContaComSucesso
  */
  public function testAtivarContaComCodigoIncorreto(){
    $esperado  = [
        'status' => 'error',
        'msg'    => 'codigo-invalido',
        'field' => 'confirmCode'
    ];
    $email = self::$emailValidoNovo;
    $confirmCode = "11111";

    $arrReturn = $this->abrAuthService->confirmationRegister($email, $confirmCode);
    $this->assertEquals($esperado, $arrReturn);
  }

  public function testAtivarContaComCodigoVazio(){
    $esperado  = [
        'status' => 'error',
        'msg'    => 'Código não pode ser vazio.'
    ];
    $email = self::$emailValidoNovo;
    $confirmCode = "";

    $arrReturn = $this->abrAuthService->confirmationRegister($email, $confirmCode);
    $this->assertEquals($esperado, $arrReturn);
  }

  public function testAtivarContaComEmailVazio(){
    $esperado  = [
        'status' => 'error',
        'msg'    => 'E-mail não pode ser vazio.'
    ];
    $email = "";
    $confirmCode = "1111111";

    $arrReturn = $this->abrAuthService->confirmationRegister($email, $confirmCode);
    $this->assertEquals($esperado, $arrReturn);
  }

  public function testAtivarContaComEmailInvalido(){
    $esperado  = [
        'status' => 'error',
        'msg'    => 'E-mail inválido.'
    ];
    $email = $this->emailInvalido;;
    $confirmCode = "1111111111";

    $arrReturn = $this->abrAuthService->confirmationRegister($email, $confirmCode);
    $this->assertEquals($esperado, $arrReturn);
  }

  public function testAtivarContaComEmailSucesso(){
    $esperado  = [
        'status' => 'success',
        'msg'    => 'Conta ativada com sucesso.'
    ];
    $email = self::$emailValidoNovo;
    $confirmCode = "385694";

    $stub = $this->getMockBuilder('AbrAuthService')->getMock();
    $stub->method('confirmationRegister')->willReturn(['status' => 'success', 'msg' => 'Conta ativada com sucesso.']);

    $arrReturn   = $stub->confirmationRegister($email, $confirmCode);
    $this->assertEquals($esperado, $arrReturn);
  }

  public function testReenviaCodigoComEmailInvalido(){
    $esperado  = [
      'status' => 'error',
      'msg'    => 'E-mail inválido.'
    ];
    $email     = $this->emailInvalido;
    $arrReturn = $this->abrAuthService->resendCode($email);
    $this->assertEquals($esperado, $arrReturn);
  }

  public function testReenviaCodigoComEmailNaoExistente(){
    $esperado  = [
      'status' => 'error',
      'msg'    => 'email-invalido'
    ];
    $email     = $this->emailInexistente;
    $arrReturn = $this->abrAuthService->resendCode($email);
    $this->assertEquals($esperado, $arrReturn);
  }

  public function testReenviaCodigoComEmailVazio(){
    $esperado  = [
      'status' => 'error',
      'msg'    => 'E-mail não pode ser vazio.'
    ];
    $email     = '';
    $arrReturn = $this->abrAuthService->resendCode($email);
    $this->assertEquals($esperado, $arrReturn);
  }

  public function testReenviaCodigoComEmailJaAtivado(){
    $esperado  = [
      'status' => 'error',
      'msg'    => 'conta-ja-ativa'
    ];
    $email     = $this->emailValido;
    $arrReturn = $this->abrAuthService->resendCode($email);
    $this->assertEquals($esperado, $arrReturn);
  }

  public function testReenviaCodigoComEmailValido(){
    $esperado  = [
      'status' => 'success',
      'msg'    => 'E-mail reenviado com sucesso.'
    ];
    $email = self::$emailValidoNovo;
    $arrReturn = $this->abrAuthService->resendCode($email);
    $this->assertEquals($esperado, $arrReturn);
  }

  public function testCadastroComSuccessoGoogle(){
    $esperado  = [
        'status' => 'success',
        'msg' => 'Usuário cadastrado com sucesso.'
    ];

    $identityProvider = "Google";
    $accessToken = "EAAQ3T4814JMBAO3yeTtkx3hxubuGKG6SBzQ2nmDb7JhWvZBYMBZB61baYJMgoCEtN5M62FsZA5NAtRe8BzyRERp5cKGb3kAVsIYwKegmkoA5mp0KQfkoLvwRwZCbWj8dZBZAr1jI2zR9RIfLvd9AqL0t0mabTf8l5Vr3pzsIXZAZARpdsJOjQ9jjxJZBsnrnANjzWbc4DB6s4FAZDZD";

    $stub = $this->getMockBuilder('AbrAuthService')->getMock();
    $stub->method('socialSignIn')->willReturn($esperado);

    $arrReturn   = $stub->socialSignIn($accessToken, $identityProvider);
    $this->assertEquals($esperado, $arrReturn);
  }

  public function testCadastroComSuccessoFacebook(){
    $esperado  = [
        'status' => 'success',
        'msg' => 'Usuário cadastrado com sucesso.'
    ];

    $identityProvider = "Facebook";
    $accessToken = "EAAQ3T4814JMBAO3yeTtkx3hxubuGKG6SBzQ2nmDb7JhWvZBYMBZB61baYJMgoCEtN5M62FsZA5NAtRe8BzyRERp5cKGb3kAVsIYwKegmkoA5mp0KQfkoLvwRwZCbWj8dZBZAr1jI2zR9RIfLvd9AqL0t0mabTf8l5Vr3pzsIXZAZARpdsJOjQ9jjxJZBsnrnANjzWbc4DB6s4FAZDZD";

    $stub = $this->getMockBuilder('AbrAuthService')->getMock();
    $stub->method('socialSignIn')->willReturn($esperado);

    $arrReturn   = $stub->socialSignIn($accessToken, $identityProvider);
    $this->assertEquals($esperado, $arrReturn);
  }

  public function testLoginComSuccessoFacebook(){
    $esperado  = [
        'status' => 'success',
        'email' => 'carlos@carlossoler.net',
        'username' => '8ba3701a-1eb8-4391-880c-958338a768c7',
        'password' => '28ef52c1978528961e0733c837cc9186'
    ];

    $identityProvider = "Facebook";
    $accessToken = "EAAQ3T4814JMBAO3yeTtkx3hxubuGKG6SBzQ2nmDb7JhWvZBYMBZB61baYJMgoCEtN5M62FsZA5NAtRe8BzyRERp5cKGb3kAVsIYwKegmkoA5mp0KQfkoLvwRwZCbWj8dZBZAr1jI2zR9RIfLvd9AqL0t0mabTf8l5Vr3pzsIXZAZARpdsJOjQ9jjxJZBsnrnANjzWbc4DB6s4FAZDZD";

    $stub = $this->getMockBuilder('AbrAuthService')->getMock();
    $stub->method('socialLogin')->willReturn($esperado);

    $arrReturn   = $stub->socialLogin($accessToken, $identityProvider);
    $this->assertEquals($esperado, $arrReturn);
  }

  public function testLoginComSuccessoGoogle(){
    $esperado  = [
        'status' => 'success',
        'email' => 'carlos@carlossoler.net',
        'username' => '8ba3701a-1eb8-4391-880c-958338a768c7',
        'password' => '28ef52c1978528961e0733c837cc9186'
    ];

    $identityProvider = "Google";
    $accessToken = "EAAQ3T4814JMBAO3yeTtkx3hxubuGKG6SBzQ2nmDb7JhWvZBYMBZB61baYJMgoCEtN5M62FsZA5NAtRe8BzyRERp5cKGb3kAVsIYwKegmkoA5mp0KQfkoLvwRwZCbWj8dZBZAr1jI2zR9RIfLvd9AqL0t0mabTf8l5Vr3pzsIXZAZARpdsJOjQ9jjxJZBsnrnANjzWbc4DB6s4FAZDZD";

    $stub = $this->getMockBuilder('AbrAuthService')->getMock();
    $stub->method('socialLogin')->willReturn($esperado);

    $arrReturn   = $stub->socialLogin($accessToken, $identityProvider);
    $this->assertEquals($esperado, $arrReturn);
  }

}
