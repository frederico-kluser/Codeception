<?php 

class LoginCest
{
    public function _before(AcceptanceTester $I)
    {
    }

    // tests
    public function tryToTest(AcceptanceTester $I)
    {
        $I->amOnPage('/');
        $I->wait(1);
        $I->waitForElement('#login', 10);
        $I->see('ENTRAR', '#login');
        $I->click('#login');
        $I->waitForElement('#piano_offer iframe', 10);
        $I->switchToIFrame("#piano_offer iframe");
        $I->waitForElement('#asi_screen', 10);
        $I->switchToIFrame("#asi_screen");
        $I->see('acesse com seus dados');
        $I->fillField('email', 'thiago.silva@abril.com.br');
        $I->fillField('senha', '325119998622');
        $I->click('#login');
        $I->wait(5);
    }
}

/*
$0.getAttribute('onclick');
"logoutPaywall()"
*/

// java -jar selenium-server-standalone-3.141.59.jar
// ./chromedriver --url-base=/wd/hub    
// php vendor/bin/codecept run --steps  

/*
$I->amOnPage('PATH')
metodo para troca de pagina a partir da página default de abertura
$I->amOnUrl('PATH')
metodo para trocar de pagina por link absoluto e definir esse novo como default
$I->click('css selector')
cria uma click com base de um seletor css
$I->dontSee('Text');
verifica se não consegue ver o texto escrito, isso ignora tags e não case sensitive
$I->dontSeeCookie('CookieName')
verifica se o cookie expecífico não existe
$I->dontSeeInSource('<tag>text</tag>')
verifica se o conteudo não existe no código bruto

*/