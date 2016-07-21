<?php
	App::uses('CakeEmail', 'Network/Email');

	class FawnoEmail extends CakeEmail {
		protected function _renderTemplates ($content) {
			if (!empty($this->_subject) && empty($this->_viewVars['subject'])) {
				$this->_viewVars['subject'] = $this->_subject;
			}

			$render = parent::_renderTemplates($content);

			if (!empty($render['html'])) {
				$render['html'] = str_replace(array('file:', 'file://', 'cid://'), 'cid:', $render['html']);
				if (preg_match_all('~(["\'])cid:([^\1]+)\1~iU', $render['html'], $img)) {
					$img = array_unique($img[2]);
					foreach ($img as $file) if (is_file($file)) {
						$cid = sha1($file);
						$this->addAttachments(array('cid:' . $cid => array('file' => $file, 'mimetype' => mime_content_type($file), 'contentId' => $cid)));
						$render['html'] = str_replace($file, $cid, $render['html']);
					}
				}
			}

			return $render;
		}
	}
?>
