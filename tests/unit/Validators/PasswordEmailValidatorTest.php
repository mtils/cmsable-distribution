<?php 

use Mockery as m;
use Ems\App\Validators\PasswordEmailValidator;

class PasswordEmailValidatorTest extends PHPUnit\Framework\TestCase
{

    public function test()
    {
        $this->assertInstanceOf(
            'Cmsable\Resource\Contracts\Validator',
            $this->newValidator()
        );
    }

    protected function newValidator()
    {
        return new PasswordEmailValidator;
    }

    public function tearDown()
    {
        m::close();
    }

}