<?php 
class MinhaBiblioteca_Plugins_Barcode_Barcode extends Zend_Controller_Plugin_Abstract{
	public function create( $value, $options = array(), $barcodetype = 'code39', $type = 'image' ){
		// Somente o texto й obrigatуrio para a criaзгo
		$barcodeOptions = array( 'text' => $value );
		// Junta a configuraзгo padrгo e o $options informado, que sгo os valores de configuraзгo padrгo do Zend_Barcode
		$barcodeOptions = array_merge( $barcodeOptions, $options );
		// Nгo obrigatуrio, para retornar em JPG usa-se: 'imageType' => 'jpg'
		$rendererOptions = array();
		// Para criar uma imagem, faltando sу colocar os headers, o padrгo de imagem й PNG
		return Zend_Barcode::render( $barcodetype, $type, $barcodeOptions, $rendererOptions );
		}
}
?>