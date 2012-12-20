<?php
namespace Login\Form;


use Zend\Form\Form;
use Zend\Form\Element\Captcha;
use Zend\Captcha\Image as CaptchaImage;
use Login\Form\RegisterFilter;


class RegisterForm extends Form
{
    public function __construct()
    {
        // we want to ignore the name passed
        parent::__construct('register');

        $this->setAttribute('method', 'post');
        $this->setInputFilter(new RegisterFilter);
        
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));

        $this->add(array(
            'name' => 'email',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Email',
            ),
        ));
        
        $dirdata = './data';

                                         //pass captcha image options
        $captchaImage = new CaptchaImage(  array(
                'font' => $dirdata . '/fonts/Arial_Italic.ttf',
                'width' => 250,
                'height' => 100,
                'dotNoiseLevel' => 40,
                'lineNoiseLevel' => 3)
        );
        $captchaImage->setImgDir($dirdata.'/captcha');
        $captchaImage->setImgUrl('/register/generate/');

        $captcha = new Captcha('captcha');
        $captcha->setCaptcha($captchaImage);
        $captcha->setLabel('Please verify you are human');

        //add captcha element...
        $this->add($captcha);
        
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        ));

    }
}
