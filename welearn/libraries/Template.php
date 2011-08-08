<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by Allan Marques
 * Date: 07/08/11
 * Time: 03:06
 *
 */

class WL_Template
{
    private $_templatePath;
    private $_template;
    private $_cssLinks = array();
    private $_jsImports = array();
    private $_jsScripts = array();
    private $_title = '';
    private $_ci;

    public function __construct()
    {
        $this->_ci =& get_instance();

        $this->_templatePath = $this->_ci->config->item('template_dir');
        $this->_template = $this->_ci->config->item('default_template');
    }

    public function setTemplate($template)
    {
        $this->_template = trim((string)$template);

        return $this;
    }

    public function getTemplate()
    {
        return $this->_template;
    }

    public function setTitle($title)
    {
        $this->_title = trim((string)$title);
        
        return $this;
    }

    public function prependCSS($css)
    {
        array_unshift($this->_cssLinks, trim((string)$css));

        return $this;
    }

    public function appendCSS($css)
    {
        $this->_cssLinks[] = trim((string)$css);

        return $this;
    }

    public function prependJSImport($js)
    {
        array_unshift($this->_jsImports, trim((string)$js));

        return $this;
    }

    public function appendJSImport($js)
    {
        $this->_jsImports[] = trim((string)$js);

        return $this;
    }

    public function prependJSScript($js)
    {
        array_unshift($this->_jsScripts, trim((string)$js));

        return $this;
    }

    public function appendJSScript($js)
    {
        $this->_jsScripts[] = trim((string)$js);

        return $this;
    }

    public function loadPartial($partial, array $data = null)
    {
        return $this->_ci->load->view('partials/' . $partial, $data, TRUE);
    }

    public function render($view = '', array $data = null)
    {
        if(!empty($view)) {
            $this->_ci->load->view($view, $data);
        }

        $templateData = array(
            'template.title' => empty($this->_title) ? '' : '| ' . $this->_title,
            'template.cssLinks' => $this->_compileCSS(),
            'template.jsImports' => $this->_compileJSImports(),
            'template.jsScripts' => $this->_compileJSScripts(),
            'content' => $this->_ci->output->get_output()
        );

        $final_output = $this->_loadTemplate($templateData);

        $this->_ci->output->set_output($final_output);
    }

    private function _compileCSS()
    {
        $cssLinks = '';

        foreach ($this->_cssLinks as $css) {
            $cssLinks .= '<link rel="stylesheet" type="text/css" href="css/' . $css . '" />';
        }

        return $cssLinks;
    }

    private function _compileJSImports()
    {
        $jsImports = '';

        foreach ($this->_jsImports as $js) {
            $jsImports .= '<script type="text/javascript" src="js/' . $js . '"></script>';
        }

        return $jsImports;
    }

    private function _compileJSScripts()
    {
        $jsScripts = '';

        foreach ($this->_jsScripts as $js) {
            $jsScripts .= '<script type="text/javascript">' . $js . '</script>';
        }

        return $jsScripts;
    }

    private function _loadTemplate(array $data)
    {
        $this->_ci->load->helper('file');

        $replaceThis = array_keys($data);

        foreach ($replaceThis as $key => $value) {
            $replaceThis[$key] = '{$' . $value . '}';
        }

        $withThis = array_values($data);
        $inThisFile = read_file($this->_templatePath . $this->_template . '/template' . EXT);

        if(!$inThisFile) {
            log_message(
                'error',
                'The template that you\'re trying to load does not exist. (' . $this->_template . ')'
            );

            show_error('Template Error, refer to log.');
        }

        return str_replace($replaceThis, $withThis, $inThisFile);
    }
}

/* End of file Template.php */
/* Location: ./application/libraries/Template.php */