<?php

class Default_Form_Login extends Zend_Form
{
	public function init(){
		/* Form Elements & Other Definitions Here ... */
		$this->setName("frmLogin");
		$this->setAttrib("class","form-control");
		
		$usuario  = new Zend_Form_Element_Text('usuario');
		$usuario->setRequired(true)->addErrorMessage('Este campo es requerido');
		$usuario->setAttrib("title","Usuario");
		$usuario->removeDecorator('Label');
		$usuario->setAttrib("class","form-control user-code input__login");
		$usuario->setAttrib("maxlength","10");
		$usuario->removeDecorator('HtmlTag');        
		$usuario->setAttrib("rel","tooltip");
		$usuario->setAttrib("placeholder","Usuario");
		

		$clave = new Zend_Form_Element_Password("clave");
		$clave->setRequired(true)->addErrorMessage('Este campo es requerido');;
		$clave->setAttrib("title","Ingrese su contraseña");
		$clave->setAttrib("class","form-control input__login");
		$clave->setAttrib("rel","tooltip");
		$clave->setAttrib("placeholder","Contraseña");
		
		$clave->removeDecorator('HtmlTag');
		$clave->removeDecorator('Label')->addFilters(array('StringTrim', 'StripTags'));
		
		$submit = new Zend_Form_Element_Submit('enviar');
		$submit->setAttrib('class', 'button button--alter-blue button__submit')->setLabel("Ingresar");
		$submit->setAttrib('id', 'enviarf');
		$submit->removeDecorator('HtmlTag');

		$this->addElements(array($usuario,$clave,$submit));        
	}

}



