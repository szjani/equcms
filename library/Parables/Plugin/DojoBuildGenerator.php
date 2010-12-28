<?php
class Parables_Plugin_DojoBuildGenerator 
    extends Zend_Controller_Plugin_Abstract
{
    /**
     * @var Zend_Dojo_BuildLayer
     */
    protected $_buildLayer = null;

    /**
     * @var string
     */
    public $buildProfilePath = null;

    /**
     * @var string
     */
    public $layerName = null;

    /**
     * @var string
     */
    public $layerScriptPath = null;

    /**
     * Dispatch loop shutdown
     *
     * @return  void
     */
    public function dispatchLoopShutdown()
    {
        $fc = Zend_Controller_Front::getInstance();
        // get build profile path, layer name and layer script path

        /*
        if (!file_exists($this->layerScriptPath)) {
            $this->generateLayerScript();
        }

        if (!file_exists($this->buildProfile)) {
            $this->generateBuildProfile();
        }
         */
    }

    /**
     * Retrieve Zend_Dojo_BuildLayer instance
     *
     * @return  Zend_Dojo_BuildLayer
     */
    public function getBuildLayer()
    {
        if (null === $this->_buildLayer) {
            $front = Zend_Controller_Front::getInstance();
            $bootstrap = $front->getParam('bootstrap');
            $view = $bootstrap->getResource('view');

            $this->_buildLayer = new Zend_Dojo_BuildLayer(array(
                'view'      => $view,
                'layerName' => $this->layerName,
            ));
        }

        return $this->_buildLayer;
    }

    /**
     * Generate layer script
     *
     * @return  void
     */
    public function generateLayerScript()
    {
        $layerScriptDir = dirname($this->layerScriptPath);

        if (!is_dir($layerScriptDir)) {
            if (!mkdir($layerScriptDir, '0777', true)) {
                throw new Zend_Controller_Exception("Unable to create {$this->layerScriptPath}.");
            }
        }

        $layerScript = $this->getBuildLayer()->generateLayerScript();
        if (!file_put_contents($this->layerScriptPath, $layerScript)) {
            throw new Zend_Controller_Exception("Unable to write to {$this->layerScriptPath}.");
        }
    }

    /**
     * Generate build profile
     *
     * @return  void
     */
    public function generateBuildProfile()
    {
        $buildProfileDir = dirname($this->buildProfilePath);

        if (!is_dir($buildProfileDir)) {
            if (!mkdir($buildProfileDir, '0777', true)) {
                throw new Zend_Controller_Exception("Unable to create {$this->buildProfilePath}.");
            }
        }

        $buildProfile = $this->getBuildLayer()->generateBuildProfile();
        if (!file_put_contents($this->buildProfilePath, $buildProfile)) {
            throw new Zend_Controller_Exception("Unable to write to {$this->buildProfilePath}.");
        }
    }
}
