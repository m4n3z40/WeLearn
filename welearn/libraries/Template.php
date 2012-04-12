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
    private $_base_url;
    private $_ci;
    private $_partialsVars = array();
    private $_urlPattern = "/^(http:\/\/|https:\/\/)/";

    public function __construct()
    {
        $this->_ci =& get_instance();
        $this->_ci->config->load('template');

        $this->_templatePath = $this->_ci->config->item('template_dir');
        $this->_template = $this->_ci->config->item('default_template');
        $this->_base_url = base_url();
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

    public function loadPartial($partial, array $data = null, $module = '')
    {
        $partialDir = 'partials';

        if ($module != '') {
            $module = trim($module, '/');
            $partialDir = $module . '/' . $partialDir;
        }

        if (preg_match('/\//', $partial)) {
            $explodedPartial = explode('/', $partial);

            $lastIndex = count($explodedPartial) - 1;
            $finalPartial = '_' . $explodedPartial[$lastIndex];

            $explodedPartial[$lastIndex] = $finalPartial;

            $partial = implode('/', $explodedPartial);
        } else {
            $partial = '_' . $partial;
        }

        return $this->_ci->load->view($partialDir . '/' . $partial, $data, TRUE);
    }

    public function render($view = '', array $data = null)
    {
        if(!empty($view)) {
            $this->_ci->load->view($view, $data);
        }

        $defaultTemplateData = array(
            'template.title' => empty($this->_title) ? '' : '| ' . $this->_title,
            'template.cssLinks' => $this->_compileCSS(),
            'template.jsNotificacoes' => ($notificacoesFlash = $this->_ci->session->flashdata('notificacoesFlash')) ?
                                          $notificacoesFlash :
                                          'false',
            'template.jsImports' => $this->_compileJSImports(),
            'template.jsScripts' => $this->_compileJSScripts(),
            'base_url' => $this->_base_url,
            'content' => $this->_ci->output->get_output()
        );

        $templateData = array_merge($defaultTemplateData, $this->_getTemplateData());

        $defaultPartials = $this->_loadDefaultPartials();
        if ($defaultPartials !== false) {
            $templateData = array_merge($templateData, $defaultPartials);
        }

        $final_output = $this->_loadTemplate($templateData);

        $this->_ci->output->set_output($final_output);
    }

    public function setDefaultPartialVar($partial, array $varAndValue)
    {
        $this->_partialsVars[(string)$partial] = $varAndValue;

        return $this;
    }

    private function _compileCSS()
    {
        $cssLinks = '';

        foreach ($this->_cssLinks as $css) {
            if ( preg_match($this->_urlPattern, $css) ) {
                $cssLinks .= '<link rel="stylesheet" type="text/css" href="' . $css . '" />';
            } else {
                $cssLinks .= '<link rel="stylesheet" type="text/css" href="' . $this->_base_url .'css/' . $css . '" />';
            }
        }

        return $cssLinks;
    }

    private function _compileJSImports()
    {
        $jsImports = '';

        foreach ($this->_jsImports as $js) {
            if ( preg_match($this->_urlPattern, $js) ) {
                $jsImports .= '<script type="text/javascript" src="' . $js . '"></script>';
            } else {
                $jsImports .= '<script type="text/javascript" src="' . $this->_base_url .'js/' . $js . '"></script>';
            }
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

    private function _loadDefaultPartials()
    {
        $templateData = $this->_ci->config->item($this->_template, 'template_data');

        if (isset($templateData['_defaultPartials']) && is_array($templateData['_defaultPartials'])) {
            $partialData = array();
            foreach ($templateData['_defaultPartials'] as $partial) {
                $templateData = (isset($this->_partialsVars[$partial])) ? $this->_partialsVars[$partial] : null;
                $partialData['partial:' . trim($partial)] = $this->loadPartial($partial, $templateData);
            }

            return $partialData;
        }

        return false;
    }

    private function _getTemplateData() {
        $templateData = $this->_ci->config->item($this->_template, 'template_data');

        $realTemplateData = array();
        foreach ($templateData as $key => $value) {
            if ( ! preg_match('/^_/', $key) ) {
                $realTemplateData[$key] = $value;
            }
        }

        return $realTemplateData;
    }
}

/* End of file Template.php */
/* Location: ./application/libraries/Template.php */