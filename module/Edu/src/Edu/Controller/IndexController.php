<?php

namespace Edu\Controller;

use Zend\View\Model\ViewModel;
use Bongo\Controller;
use Checkout\Model;
use Zend\Session\Container;

class IndexController extends Controller\BaseController {

    private $_traslator;

    /**
     * 
     * @var \Checkout\Model\CheckoutInfo
     */
    public $_checkoutInfo;
    private $_partnerData;
    private $_sessionId;

    const DAY_INI_EXPRESS = 1;
    const DAY_FIN_EXPRESS = 3;
    const DAY_INI_ECONOMY = 5;
    const DAY_FIN_ECONOMY = 6;

    function init() {
        $translator = $this->service()->get('translator');
        $config = $this->service()->get('Config');
        $this->_traslator = $translator->getLocale();

        $dataRoutes = $this->params()->fromRoute();
        $dataPost = $this->params()->fromPost();
        $dataGet = $this->params()->fromQuery();
        $sessionId = null;        
        if (!empty($dataGet['s'])) {
            $sessionId = $dataGet['s'];
        } elseif (!empty($dataRoutes['sessionid'])) {
            $sessionId = $dataRoutes['sessionid'];
        }       
       
        #Creando toda la configuraciones de envio si no tiene session alguna
        if (empty($sessionId)) {            
            $modelCheckout = new \Checkout\Model\Checkout($dataPost, $dataGet, $dataRoutes, $this->service());
            $data = array();
            $isTokenModel = false;
            if ($modelCheckout->isToken()) {
                if ($modelCheckout->validaToken($modelCheckout->getToken())) {
                    $data = $modelCheckout->getData();
                    $isTokenModel = true;
                }
            } elseif ($modelCheckout->isGet()) {
                if ($modelCheckout->validaPartner($modelCheckout->getPartnerKey())) {
                    $data = $modelCheckout->getData();
                }
            } elseif ($modelCheckout->isPost()) {
                if ($modelCheckout->validaPartner($modelCheckout->getPartnerKey())) {
                    $data = $modelCheckout->getData();
                }
            }
            if (empty($data)) {
                header('location:' . $config['bongo_server']['uri_checkout_invalit']);
                exit;
            }
            
            $partner = $modelCheckout->getDataPartner();
            if ($partner['checkout_security'] == 1 && !$isTokenModel) {
                echo 'Error 2033: Invalid Token Error - Please contact Bongo';
                exit;
            }

            $partner = $modelCheckout->getDataPartner();
            if ($partner['checkout_security_domain_status'] == \Bongo\Model\Entity\Partners::DOMAIN_STATUS_ACTIVE) {
                $partner['checkout_security_domain'] = trim($partner['checkout_security_domain']) . ',test.bongo1.com,bongoweb.dev,testshop.bongous.com';
                if ($partner['checkout_security_domain'] != '') {
                    $security = false;
                    if (isset($_SERVER['HTTP_REFERER'])) {
                        $pu = parse_url($_SERVER['HTTP_REFERER']);
                        $host = trim($pu['host']);
                        if (strpos($partner['checkout_security_domain'], $host) !== false) {
                            $security = true;
                        }
                    }
                    if (!$security) {
                        echo 'Error 2032: Domain Error - Please contact Bongo';
                        exit;
                    }
                }
            }
            $modelCheckout->formatData();
            $products = $modelCheckout->getProducts();
            $config = $modelCheckout->getConfig();
            $info = $modelCheckout->getInfo();
            $dataPartner = $modelCheckout->getDataPartner();
            #Creando la session
            $sessionId = uniqid('tzc');
            $this->saveCheckoutInfo($sessionId, $config['partnerkey'], $products, $config, $info);
            $urlRedirect = \Bongo\Util\Server::getContent() . 'pay/index.php?s=' . $sessionId;
            if (!empty($dataPartner['checkout_alias'])) {
                $urlRedirect = \Bongo\Util\Server::getContent() . 'pay/' . strtolower($dataPartner['checkout_alias']) . '/' . $sessionId;
            }
            header("Location: " . $urlRedirect);
            exit;
        } else {              
            #comprovando si los datos de la session indicada son corecto y validos
            $session = $this->service()->get('Cache')->getItem('CartBongo' . $sessionId);
            if(!$session || !isset($session['data'])){
                 echo 'Session Expired';
                 exit;
            }
            $this->_checkoutInfo = $session['data'];
            
            if (!$this->isSessionValid()) {
                header('location:' . $config['bongo_server']['uri_checkout_invalit']);
                exit;
            }

            #validando si hay productos en añadidos en la session 
            /* $products = $this->_checkoutInfo->getProducts();
              if (empty($products)) {
              header('location:' . $config['bongo_server']['uri_checkout_invalit']);
              exit;
              } */
        }
        $this->_sessionId = $sessionId;
    }

    public function saveCheckoutInfo($sessionId, $partnerKey, $products, $config, $info) {
        if ($sessionId != '') {
            $this->service()->get('Cache')->setItem('CartBongo' . $sessionId, array('data' => new \Checkout\Model\CheckoutInfo($partnerKey, $products, $config, $info)));
        }
    }

    public function indexAction() {
        $this->init();
        $this->service()->get('ZendViewRendererPhpRenderer')->headTitle($this->_partnerData['name']);
        $checkoutDbModel = $this->service()->get('Checkout\Model\CheckoutDb');
        $dataCountries = $checkoutDbModel->getCountryShippingAvaliable();
        $dataCountryStates = $checkoutDbModel->getCountryStates();
        $dataCountry = $this->countries($dataCountries, $dataCountryStates);

        $info = $this->_checkoutInfo->getInfo();
        //$info['iframe_hash'] = 'asdasdasd';
        $config = $this->_checkoutInfo->getConfig();

        if ($this->_partnerData['domestic_transit'] > $config['domesticTransit']) {
            $config['domesticTransit'] = $this->_partnerData['domestic_transit'];
        }

        $tInterExpress = $this->service()->get('translator')->translate('tInterExpress');
        $tInterEconomy = $this->service()->get('translator')->translate('tInterEconomy');

        $idiomas = $this->getIdiomas();
        $baseUrlPayAliasPartner = \Bongo\Util\Server::getContent() . 'pay/' . (empty($this->_partnerData['checkout_alias']) ? $this->_partnerData['bongopaylink'] : $this->_partnerData['checkout_alias']) . '/';
        $checkoutAlias = empty($this->_partnerData['checkout_alias']) ? $this->_partnerData['bongopaylink'] : $this->_partnerData['checkout_alias'];
        $isCustomizable = false;
        $baseUrlPayPartner = \Bongo\Util\Server::getContent() . 'static/';
        if (\Checkout\Model\Util::isCustomFront($this->_partnerData['bongopaylink'])) {
            $baseUrlPayPartner = \Bongo\Util\Server::getContent() . 'static/partners/' . $this->_partnerData['bongopaylink'] . '/';
            $isCustomizable = true;
        }

        $this->saveCheckoutInfo($this->_sessionId, $config['partnerkey'], $this->_checkoutInfo->getProducts(), $config, $info);
        $isIframe = \Checkout\Model\Util::isIframePartner($this->_partnerData);
        $this->layout()->sessionId = $this->_sessionId;        
        $this->layout()->baseUrlPayPartner = $baseUrlPayPartner;
        $this->layout()->baseUrlPayAliasPartner = $baseUrlPayAliasPartner;
        $this->layout()->checkoutAlias = $checkoutAlias;
        $this->layout()->idiomaSelect = $idiomas['indexSelect'];
        $this->layout()->languageCode = $this->_traslator;
        $this->layout()->bongoPayLink = $this->_partnerData['bongopaylink'];
        $this->layout()->isCustomizable = $isCustomizable;
        $this->layout()->countries = $dataCountry;
        $this->layout()->partnerActiveAdyen = $this->_partnerData['activeAdyen'];
        $this->layout()->ipClient = \Bongo\Util\Util::getRealIP();
        $this->layout()->fechaActual = date("Y-m-d H:i:s");
        $this->layout()->logoLink = $this->_partnerData['logo_link'];
        $this->layout()->lang = $this->_traslator;

        $view = new ViewModel(array(
            'sessionId' => $this->_sessionId,
            'locale' => $this->_traslator,
            'tInterExpress' => $tInterExpress,
            'tInterEconomy' => $tInterEconomy,
            'countries' => $dataCountry,
            'dataPartner' => $this->_partnerData,
            'goBackLink' =>  empty($this->_partnerData['checkout_back_url']) ? $config['referer'] : $this->_partnerData['checkout_back_url'],
            'baseUrlPayPartner' => $baseUrlPayPartner,
            'baseUrlPayAliasPartner' => $baseUrlPayAliasPartner,
            'checkoutAlias' => $checkoutAlias,
            'idiomas' => $idiomas['idiomas'],
            'cust' => $info['cust'],
            'ship' => $info['ship'],
            'info' => $info,
            'isIframe' => $isIframe
        ));
        if (\Checkout\Model\Util::isCustomFrontHtml($this->_partnerData['bongopaylink'])) {
            $view->setTemplate($this->_partnerData['bongopaylink'] . '/index.phtml');
        }
        return $view;
    }

    /**
     * valida si la session del Checckout Info es valida
     */
    public function isSessionValid() {
        $response = true;
        $partnerKey = $this->_checkoutInfo->getPartnerKey();
        if (empty($partnerKey)) {
            $response = false;
        }
        $checkoutDbModel = $this->service()->get('Checkout\Model\CheckoutDb');
        $partnerData = $checkoutDbModel->getPartner($this->_checkoutInfo->getPartnerKey());
        if (empty($partnerData)) {
            $response = false;
        }
        $this->_partnerData = (array) $partnerData;
        return $response;
    }

    /**
     * retorna los idiomas del config 
     */
    public function getIdiomas() {
        $config = $this->service()->get('Config');
        $response = array();
        $indexSelect = 0;
        if (!empty($config['locales']['list'])) {
            foreach ($config['locales']['list'] as $value) {
                $uriImg = \Bongo\Util\Server::getStatic() . 'static/locate/flag/flag_' . $value['code'] . '.gif';
                $response[] = array(
                    'text' => $value['name'],
                    'value' => $value['code'],
                    'imageSrc' => $uriImg,
                    'index' => $value['index']);
                if ($value['code'] == $this->_traslator) {
                    $indexSelect = $value['index'];
                }
            }
        }
        return array(
            'idiomas' => \Zend\Json\Json::encode($response),
            'indexSelect' => $indexSelect);
    }

    /**
     * valida  los contries para renderizar
     * @param array $countries
     */
    public function countries(array $countries, array $countryStates) {

        $partnerNotStates = array('61e3c7b35cd1e45301aba3a796a5aa21', '5f4ee8539635b9bf69923de244a7e9ee', '4fdc7c5e48b2ffd66da9d2a33337ee67', 'ab92db5d07bc692a5d7893aaf6e9d121');
        $partnerUSAallStates = array('e9424db7cd655a46c0c19bf93b4f9051', 'ab92db5d07bc692a5d7893aaf6e9d121', 'ab96e58f02e35be9d7246837ba6fa535', '60ad84db16e9fb28b3572eb946841a75', '61e3c7b35cd1e45301aba3a796a5aa21', '5f4ee8539635b9bf69923de244a7e9ee', '4fdc7c5e48b2ffd66da9d2a33337ee67', 'ab92db5d07bc692a5d7893aaf6e9d121');
        $partnerCANCustomStates = array('0cd893b00ae9743b2957e57c27df43cc');

        $statesSupplies = array('Manitoba', 'New Brunswick', 'Newfoundland and Labrador', 'Northwest Territories', 'Nova Scotia', 'Nunavut', 'Ontario', 'Prince Edward Island', 'Quebec', 'Yukon');

        $countryBand = array(
            'AT', 'BE', 'BG', 'HR', 'CY', 'CZ', 'DK', 'EE', 'FI', 'FR', 'DE', 'GR',
            'HU', 'IE', 'IT', 'LV', 'LT', 'LU', 'MT', 'NL', 'PL', 'PT', 'RO', 'SK',
            'SI', 'ES', 'SE', 'GB');
        $arrayStates = array();
        $stateBand = array('Alaska', 'Hawaii');
        $keyPartner = $this->_partnerData['key'];        
        $info = $this->_checkoutInfo->getInfo();
        $custInfo = $info['cust'];
        $shipInfo = $info['ship'];
        $optionShip = '';
        $optionCust = '';
        $countryPhones = array();
        $arrayStatesShipping = array();
        if (!empty($countries)) {
            foreach ($countries as $country) {
                $countryPhones[$country['code']] = $country['phonecode'];
                $select = '';
                $veri = '';
                if ($country['code'] == $shipInfo['country']) {
                    $select = ' selected="selected" ';
                }
                if (array_key_exists($country['code'], $countryStates)) {
                    #recorriendo estados de country
                    $stat = array();
                    foreach ($countryStates[$country['code']] as $countryState) {
                        if ((strtolower($shipInfo['state']) == strtolower($countryState['state_code']) || strtolower($shipInfo['state']) == strtolower($countryState['state'])) && $shipInfo['state'] != '') {

                            $shipInfo['state'] = $countryState['state'];
                        }
                        if ((strtolower($custInfo['state']) == strtolower($countryState['state_code']) || strtolower($custInfo['state']) == strtolower($countryState['state'])) && $custInfo['state'] != '') {

                            $custInfo['state'] = $countryState['state'];
                        }

                        if ($country['code'] == \Bongo\Model\Entity\Country::COUNTRY_CODE_US) {
                            if (in_array($keyPartner, $partnerNotStates) && in_array($countryState['state'], $stateBand)) {
                                
                            } else {
                                $stat[] = $countryState['state'];
                            }
                        } else {
                            $stat[] = $countryState['state'];
                        }
                    }

                    $veri = ' state="YES" ';
                    $arrayStates[$country['code']] = $stat;
                    if ($country['code'] == \Bongo\Model\Entity\Country::COUNTRY_CODE_US) {
                        if (in_array($keyPartner, $partnerUSAallStates)) {
                            $arrayStatesShipping[$country['code']] = $stat;
                        } else {
                            $arrayStatesShipping[$country['code']] = $stateBand;
                        }
                    } elseif ($country['code'] == \Bongo\Model\Entity\Country::COUNTRY_CODE_CA) {
                        if (in_array($keyPartner, $partnerCANCustomStates)) {
                            $arrayStatesShipping[$country['code']] = $statesSupplies;
                        } else {
                            $arrayStatesShipping[$country['code']] = $stat;
                        }
                    } else {
                        $arrayStatesShipping[$country['code']] = $stat;
                    }
                }

                $optionShip .= '<option value="' . $country['code'] . '" ' . $veri . ' ' . $select . '>' . $country['name'] . '</option>';
                if (in_array($keyPartner, array('61e3c7b35cd1e45301aba3a796a5aa21')) && in_array($country['code'], $countryBand)) {
                    $optionCust .= '';
                } elseif (in_array($keyPartner, array('7f88caaea64d97eb512ff9e9789215f7')) && in_array($country['code'], array('AK', 'HI'))) {
                    $optionCust .= '';
                } else {
                    $optionCust .= '<option value="' . $country['code'] . '" ' . $veri . ' ' . $select . '>' . $country['name'] . '</option>';
                }
            }
        }
        return array(
            'optionShip' => $optionShip,
            'optionCust' => $optionCust,
            'countryPhones' => $countryPhones,
            'states' => $arrayStates,
            'statesShipping' => $arrayStatesShipping
        );
    }
}
