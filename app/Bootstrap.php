<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

	protected function _initViewHelpers() {
		$this->bootstrap('layout');
		$layout = $this->getResource('layout');
		$view = $layout->getView();
		$view->doctype('XHTML1_STRICT');
		$view->headMeta()->appendHttpEquiv('Content-Type', 'text/html;charset=utf-8');

		$env = getenv('APPLICATION_ENV');
		if ($env === 'production') {
			$view->headLink()->prependStylesheet('/external_library/bootstrap/css/bootstrap.min.css')
				 ->headLink()->appendStylesheet('/external_library/jquery-ui/jquery.ui.datepicker.css')
				 ->headLink()->appendStylesheet('/css/app-min.css');

		}else if ($env === 'development'){
			$view   ->headLink()->prependStylesheet('/external_library/bootstrap/css/bootstrap.min.css')
					->headLink()->appendStylesheet('/external_library/jquery-ui/jquery.ui.datepicker.css')
					->headLink()->appendStylesheet('/css/app-min.css');
		}

		$view->headScript()->appendFile('/external_library/bootstrap/js/bootstrap.js')
			->headScript()->appendFile('/external_library/bootstrap/js/jquery.functions.js')
			->headScript()->appendFile('/external_library/jquery-ui/jquery.ui.datepicker.js')
			->headScript()->appendFile('/external_library/jquery-ui/jquery.ui.core.js')
			->headScript()->prependFile('/js/jquery-1.11.0.js');

		$view->headTitle()->setSeparator(' - ');
		$view->headTitle('PROYECTO');
		Zend_Session::start();
		Zend_Layout::startMvc(APPLICATION_PATH . '/layouts/scripts');
		$view = Zend_Layout::getMvcInstance()->getView();
		$viewRenderer = Zend_controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
		$viewRenderer->setView($view);
		$moneda = new Zend_Locale('es_PE');
		Zend_Registry::set('Zend_Locale', $moneda);
		return;
	}

	protected function _initPlaceholders(){
		$this->bootstrap('layout');
		$layout = $this->getResource('layout');
		$view = $view = $layout->getView();
		$view->placeholder('Textnav')
				->setPrefix("<p class=\"main_title_paragraph\">\n")
				->setPostfix("</p>");
		$view->placeholder('Btnnav')
				->setPrefix("<div class=\"navigationButtonsMainLayout\">\n")
				->setPostfix("</div>");

		$view->placeholder('BtnSVCRight')
				->setPrefix("<div class=\"navigationButtonsSVCLayoutRight\">\n")
				->setPostfix("</div>");

		$view->placeholder('BtnSVCLeft')
				->setPrefix("<div class=\"navigationButtonsSVCLayoutLeft\">\n")
				->setPostfix("</div>");

	}
}

