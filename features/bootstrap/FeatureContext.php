<?php

use AppBundle\Entity\User;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\MinkExtension\Context\RawMinkContext;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\Session;

require_once __DIR__ . '/../../vendor/phpunit/phpunit/src/Framework/Assert/Functions.php';

/**
 * Defines application features from the specific context.
 */
class FeatureContext extends RawMinkContext implements Context
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct()
    {
        $kernel = new AppKernel('test', true);
        $kernel->boot();
        $this->em = $kernel->getContainer()->get('doctrine')->getManager();
    }

    /**
     * @BeforeScenario
     */
    public function clearData()
    {
        $purger = new ORMPurger($this->em);
        $purger->purge();
    }


    /**
     * @Then /^I wait for result$/
     */
    public function iWaitForResult()
    {
        $this->getSession()->wait(3000);
    }

    /**
     * @Given /^there is and admin user "([^"]*)" with password "([^"]*)"$/
     */
    public function thereIsAndAdminUserWithPassword($arg1, $arg2)
    {
        $user = new User();
        $user->setEmail($arg1);
        $user->setPlainPassword($arg2);
        $user->setName('Mario');
        $user->setSurname('Brothers');
        $user->setMale(true);


        $this->em->persist($user);
        $this->em->flush();

    }

    /**
     * Pauses the scenario until the user presses a key. Useful when debugging a scenario.
     *
     * @Then I break
     */
    public function iPutABreakpoint()
    {
        fwrite(STDOUT, "\033[s    \033[93m[Breakpoint] Press \033[1;93m[RETURN]\033[0;93m to continue...\033[0m");
        while (fgets(STDIN, 1024) == '') {
        }
        fwrite(STDOUT, "\033[u");
        return;
    }
}
